<?php
$pdo = new PDO('mysql:host=db;port=3306;dbname=laravel', 'laravel', 'laravel');
$stmt = $pdo->query('SHOW COLUMNS FROM reading_sessions');
foreach ($stmt as $row) {
    echo $row['Field'] . ' (' . $row['Type'] . ')' . PHP_EOL;
}