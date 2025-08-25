<?php

declare(strict_types=1);


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


function fetchData(string $url): array
{
    
    $context = stream_context_create([
        'http' => [
            'timeout' => 5,
        ],
    ]);

    $response = @file_get_contents($url, false, $context);
    $data = json_decode($response, true);

    return $data;
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
