<?php
require 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

try {
    $dbh = new PDO($_ENV["DSN"], $_ENV["USER"], $_ENV["PASSWORD"]);
    echo "接続に成功しました\n";
} catch (PDOException $e) {
    echo "接続に失敗しました\n";
    echo $e->getMessage() . "\n";
}
