<?php

declare(strict_types=1);

// Enable error reporting
ini_set('display_errors', '1');
error_reporting(E_ALL);

define('ERROR_LOG_FILE', __DIR__ . '/logs/error.log');

/**
 * Normalize API data to a common schema.
 *
 * @param array  $data   The original API data.
 * @param string $origin The source of the data ("Travel Guides" or "Adventure Tourists").
 *
 * @return array The normalized array.
 */
function normalizeData(array $data, string $origin): array
{
    $normalized = [];

    foreach ($data as $item) {
        if ($origin === 'Travel Guides') {
            $normalized[] = [
                'id'       => $item['id'],
                'name'     => $item['name'],
                'type'     => 'User',
                'status'   => 'Active', // No status in API; assumed
                'gender'   => 'Unknown',
                'location' => $item['address']['city'] ?? 'Unknown',
                'contact'  => $item['email'],
                'company'  => $item['company']['name'] ?? 'N/A',
                'image'    => 'https://via.placeholder.com/150?text=' . urlencode($item['name']),
                'url'      => 'https://jsonplaceholder.typicode.com/users/' . $item['id'],
                'origin'   => $origin,
            ];
        }

        if ($origin === 'Adventure Tourists') {
            $normalized[] = [
                'id'       => $item['id'],
                'name'     => $item['name'],
                'type'     => $item['species'] ?? 'Unknown',
                'status'   => $item['status'] ?? 'Unknown',
                'gender'   => $item['gender'] ?? 'Unknown',
                'location' => $item['location']['name'] ?? 'Unknown',
                'contact'  => 'N/A', // No contact field
                'company'  => $item['origin']['name'] ?? 'N/A',
                'image'    => $item['image'] ?? '',
                'url'      => $item['url'] ?? '',
                'origin'   => $origin,
            ];
        }
    }

    return $normalized;
}

/**
 * Fetch data from an external API with error handling.
 *
 * @param string $url The API endpoint.
 *
 * @return array The decoded JSON data or an empty array on failure.
 */
function fetchData(string $url): array
{
    try {
        $context = stream_context_create([
            'http' => [
                'timeout' => 5,
            ],
        ]);

        $response = @file_get_contents($url, false, $context);

        if ($response === false) {
            throw new Exception("HTTP request failed for {$url}");
        }

        $data = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("JSON decode error: " . json_last_error_msg());
        }

        return $data;
    } catch (Exception $e) {
        error_log(
            '[' . date('Y-m-d H:i:s') . '] ERROR: ' . $e->getMessage() . PHP_EOL,
            3,
            ERROR_LOG_FILE
        );

        return [];
    }
}

// Fetch data
$travelGuidesRaw      = fetchData('https://jsonplaceholder.typicode.com/users');
$adventureTouristsRaw = fetchData('https://rickandmortyapi.com/api/character');

// Normalize
$travelGuides      = normalizeData($travelGuidesRaw, 'Travel Guides');
$adventureTourists = normalizeData($adventureTouristsRaw['results'] ?? [], 'Adventure Tourists');

// Merge
$unifiedData = array_merge($travelGuides, $adventureTourists);

// Output
header('Content-Type: application/json');
echo json_encode($unifiedData, JSON_PRETTY_PRINT);
