# Generate CSV

This code is a PHP function that generates a CSV file from a SELECT query result and allows the user to download it. It utilizes the PHP `fputcsv` function to write data to the CSV file.

## Prerequisites

- PHP version 7.4.0 or higher
- PDO extension enabled

## Usage

1. Include the code file containing the `generate_csv` function in your PHP project.

2. Create a database connection object using PDO and pass it as the first parameter to the `generate_csv` function.

3. Provide a valid SELECT query as the second parameter to the `generate_csv` function. Only SELECT queries are allowed.

4. Specify an optional filename as the third parameter to the `generate_csv` function. If not provided, the default filename will be "Report.csv".

5. Call the `generate_csv` function to generate the CSV file and initiate the download.

### Example

```php
<?php

require_once 'generate_csv.php';

// Create a database connection
$conn = new PDO("mysql:host=localhost;dbname=mydatabase", "username", "password");

// Create an instance of the GenerateCsv class
$csvGenerator = new GenerateCsv();

// Generate the CSV file
$csvGenerator->generate_csv($conn, "SELECT * FROM mytable", "UserReport");

?>
```