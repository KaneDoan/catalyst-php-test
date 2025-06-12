<?php

define('DEFAULT_DB_NAME', 'test');
$options = getopt("u:p:h:", ["file:", "create_table", "dry_run", "help"]);

if (isset($options['help'])) {
    echo <<<EOD
Usage: php user_upload.php [OPTIONS]

--file [csv file name] - This is the name of the CSV to be parsed.
--create_table - This will create or rebuild the 'users' table in the database.
--dry_run - This will be used with the --file directive in case we want to run the script but not insert
into the DB. All other functions will be executed, but the database won't be altered
-u - MySQL username
-p - MySQL password
-h - MySQL host.
--help - which will output the above list of directives with details.

Examples:
php user_upload.php -help
php user_upload.php -create_table -u user -p password -h localhost --dry_run

EOD;
    exit;
}

function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function connect_db($host, $user, $pass) {
    $mysqli = new mysqli($host, $user, $pass, 'test');
    if ($mysqli->connect_error) {
        die("Database connection failed: " . $mysqli->connect_error);
    }
    return $mysqli;
}

//Ensure the database exists, create it if it doesn't
function ensure_database_exists($host, $user, $pass, $dbname) {
    $conn = new mysqli($host, $user, $pass);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Create DB if it doesn't exist
    if (!$conn->query("CREATE DATABASE IF NOT EXISTS `$dbname`")) {
        die("Database creation failed: " . $conn->error);
    }

    $conn->close();
}

function create_table($mysqli) {
    $mysqli->query("DROP TABLE IF EXISTS users");
    $create = "CREATE TABLE users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100),
        surname VARCHAR(100),
        email VARCHAR(255) UNIQUE
    )";
    if ($mysqli->query($create)) {
        echo "Table 'users' created successfully.\n";
    } else {
        echo "Error creating table: " . $mysqli->error . "\n";
    }
}

// Get DB name or default
$dbname = $options['db'] ?? DEFAULT_DB_NAME;

if (isset($options['create_table'])) {
    if (!isset($options['u'], $options['p'], $options['h'])) {
        echo "MySQL credentials missing.\n";
        exit(1);
    }
    ensure_database_exists($options['h'], $options['u'], $options['p'], $dbname);
    $mysqli = connect_db($options['h'], $options['u'], $options['p'], $dbname);
    create_table($mysqli);
    exit;
}

if (isset($options['file'])) {
    $filename = $options['file'];
    if (!file_exists($filename)) {
        echo "File '$filename' not found.\n";
        exit(1);
    }

    $dryRun = isset($options['dry_run']);
    $mysqli = null;

    if (!$dryRun && isset($options['u'], $options['p'], $options['h'])) {
        ensure_database_exists($options['h'], $options['u'], $options['p'], $dbname);
        $mysqli = connect_db($options['h'], $options['u'], $options['p'], $dbname);
    }

    $handle = fopen($filename, "r");
    if ($handle === false) {
        die("Unable to open file.\n");
    }

    $rowCount = 0;
    $invalidCount = 0;
    $duplicateCount = 0;
    fgetcsv($handle); // Skip header

    while (($data = fgetcsv($handle)) !== false) {
        [$name, $surname, $email] = $data;

        $name = ucfirst(strtolower($name));
        $surname = ucfirst(strtolower($surname));
        $email = strtolower(trim($email));

        if (!validate_email($email)) {
            echo "Invalid email: $email\n";
            $invalidCount++;
            continue;
        }

        echo "Valid row: $name $surname <$email>\n";
        $rowCount++;

        if (!$dryRun && $mysqli) {
            $stmt = $mysqli->prepare("INSERT INTO users (name, surname, email) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $surname, $email);
            try {
                $stmt->execute();
            } catch (mysqli_sql_exception $e) {
                if (str_contains($e->getMessage(), 'Duplicate entry')) {
                    echo "Skipped duplicate email: $email\n";
                    $duplicateCount++;
                } else {
                    echo "Failed to insert: {$e->getMessage()}\n";
                }
            }
        }
    }
    fclose($handle);
    echo "Processed $rowCount valid rows.\n";
    echo "Processed $invalidCount invalid emails skipped.\n";
    echo "Processed $duplicateCount duplicate emails skipped.\n";
}