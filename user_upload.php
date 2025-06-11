<?php

$options = getopt("", ["file:", "create_table", "dry_run", "help", "u:", "p:", "h:"]);

if (isset($options['help'])) {
    echo <<<EOD
EOD;
    exit;
}

