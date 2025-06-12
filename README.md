# Catalyst PHP Technical Test

<h1>Test 1: Script Task</h1> 

<p>PHP script execute from the command line to processes a CSV file of user data, validates and formats the information, and imports it into a MySQL or MariaDB database</p>

## Functions

- Parses a CSV file with user info: `name`, `surname`, `email`
- Validates email format (e.g., skips `abc@@mail.com`)
- Capitalizes name and surname (e.g., `john` → `John`)
- Lowercases email
- Creates the database and table if they don't exist
- Handles duplicate emails gracefully (skips, doesn't crash)
- Supports a dry run mode (validates without inserting)

<h1>Test 2: Logic Test</h1> 

<p>PHP script execute from the command line to processes Output the numbers from 1 to 100
- Where the number is divisible by three (3) output the word “foo”.
- Where the number is divisible by five (5) output the word “bar”
- Where the number is divisible by three (3) and (5) output the word “foobar”</p>

## Functions

- Save as listed above.

## Requirements

- PHP 8.1+  
- MySQL 8.0+ or MariaDB 11.x. I used MySQL 8.1.
- PHP `mysqli` extension  
- A Linux environment (e.g., Ubuntu 24.04).

Install dependencies on Ubuntu:

```bash
sudo apt update
sudo apt install php php-mysqli mysql-server