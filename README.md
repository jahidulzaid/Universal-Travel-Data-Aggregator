# PHP Coding Evaluation of Real world senerio

This project includes two tasks designed to test PHP and API integration skills.

---

## Task 1: User Management System (CRUD)

A PHP application to manage users (Create, Read, Update, Delete) using a MySQL database.

###  Setup Instructions

1.  **Create Database**: Create a MySQL database named `travel_aggregator`.
    *(Note: You can use a different name by editing the connection details in `db.php`)*.

2.  **Import Schema**: Run the `user-management/query.sql` file to create the `users` table.
    ```sql
    -- In your MySQL CLI or GUI
    USE travel_aggregator;
    SOURCE user-management/query.sql;
    ```

3.  **Configure Credentials**: Edit `user-management/db.php` with your MySQL database credentials.

4.  **Start Server**: Navigate to the project root and run the built-in PHP server.
    ```bash
    php -S localhost:8000
    ```

5.  **View Application**: Open your browser and go to the following URL:
    [http://localhost:8000/user-management/index.php](http://localhost:8000/user-management/index.php)

---

## Task 2: Universal Travel Data Aggregator

This script fetches, merge and normalizes data from two different external APIs:
* [JSONPlaceholder Users](https://jsonplaceholder.typicode.com/users)
* [Rick & Morty API](https://rickandmortyapi.com/api/character)

### 🔧 How to Run

1.  **Start Server**: Make sure the PHP server is running.
    ```bash
    php -S localhost:8000
    ```

2.  **Run Script**: Open your browser and navigate to the aggregator URL:
    [http://localhost:8000/aggregator.php](http://localhost:8000/aggregator.php)

### Output

The script returns a merged and normalized JSON object containing data from both APIs. If one API call fails, the script will log the error to `logs/error.log` and still return the data from the successful API.

### Requirements

* PHP 8.0 or newer
* MySQL (for Task 1)
* Composer (optional, not required)

