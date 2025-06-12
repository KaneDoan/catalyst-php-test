<?php

$output = [];

for ($i = 1; $i <= 100; $i++) {
    if ($i % 15 === 0) {
        $output[] = 'foobar';
    } elseif ($i % 3 === 0) {
        $output[] = 'foo';
    } elseif ($i % 5 === 0) {
        $output[] = 'bar';
    } else {
        $output[] = $i;
    }
}

foreach ($output as $value) {
    echo $value . ", ";
}
?>