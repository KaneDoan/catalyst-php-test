# Catalyst PHP Technical Test

<h1>Test 1:</h1> 

<p>This is a command-line PHP script developed for Catalyst's technical test. It processes a CSV file of user data, validates and formats the information, and imports it into a MySQL or MariaDB database</p>

## Functions

- Parses a CSV file with user info: `name`, `surname`, `email`
- Validates email format (e.g., skips `abc@@mail.com`)
- Capitalizes name and surname (e.g., `john` → `John`)
- Lowercases email
- Creates the database and table if they don't exist
- Handles duplicate emails gracefully (skips, doesn't crash)
- Supports a dry run mode (validates without inserting)

## 📦 Requirements

- PHP 8.1+  
- MySQL 8.0+
- PHP `mysqli` extension  
- I ran this on Linux environment (e.g., Ubuntu 24.04)

Install dependencies on Ubuntu:

```bash
sudo apt update
sudo apt install php php-mysqli mysql-server