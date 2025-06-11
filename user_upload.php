<?php

$options = getopt("", ["file:", "create_table", "dry_run", "help", "u:", "p:", "h:"]);

if (isset($options['help'])) {
    echo <<<EOD
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

if (isset($options['create_table'])) {
    if (!isset($options['u'], $options['p'], $options['h'])) {
        echo "MySQL credentials missing.\n";
        exit(1);
    }
    $mysqli = connect_db($options['h'], $options['u'], $options['p']);
    create_table($mysqli);
    exit;
}