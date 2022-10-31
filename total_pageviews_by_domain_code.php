<?php

require 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

try {
    $dbh = new PDO($_ENV["DSN"], $_ENV["USER"], $_ENV["PASSWORD"]);

    echo '指定されたドメインコードに対して、人気順に合計ビュー数を表示します。' . PHP_EOL;
    echo PHP_EOL;

    echo 'ドメインコードを入力してください。' . PHP_EOL;

    $domain_codes = explode(' ', trim(fgets(STDIN)));

    echo PHP_EOL;

    $inClause = substr(str_repeat(',?', count($domain_codes)), 1);

    $sql = "SELECT domain_code, SUM(count_views) AS total_views
        FROM pageviews
        WHERE domain_code IN ({$inClause})
        GROUP BY domain_code
        ORDER BY total_views DESC";

    echo '合計ビュー数を計算しています。' . PHP_EOL;

    $stmt = $dbh->prepare($sql);
    $stmt->execute($domain_codes);
    $results = $stmt->fetchALL(PDO::FETCH_ASSOC);

    foreach ($results as $result) {
        echo "\"{$result['domain_code']}\", {$result['total_views']}" . PHP_EOL;
    }
} catch (PDOException $e) {
    echo "エラーが発生しました\n";
    echo $e->getMessage() . "\n";
}
