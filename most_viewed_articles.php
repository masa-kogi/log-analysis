<?php

require 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

try {
    $dbh = new PDO($_ENV["DSN"], $_ENV["USER"], $_ENV["PASSWORD"]);
    echo '最もビュー数の多い記事を、指定した記事数だけ多い順に表示します。' . PHP_EOL;

    echo '記事数を入力してください。' . PHP_EOL;
    $articleNum = intval(trim(fgets(STDIN)));

    while (!isProperNum($articleNum)) {
        echo '記事数は0以上の整数値を入力してください。' . PHP_EOL;
        $articleNum = intval(trim(fgets(STDIN)));
    }

    echo PHP_EOL;

    $sql = 'SELECT `domain_code`, `page_title`, `count_views`
        FROM `pageviews`
        ORDER BY `count_views` DESC
        LIMIT :articleNum';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':articleNum', $articleNum, PDO::PARAM_INT);
    $stmt->execute();
    // $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($stmt as $article) {
        echo "\"{$article['domain_code']}\", \"{$article['page_title']}\", {$article['count_views']}" . PHP_EOL;
    }
} catch (PDOException $e) {
    echo "エラーが発生しました\n";
    echo $e->getMessage() . "\n";
}


function isProperNum(int $articleNum): bool
{
    return is_int($articleNum) && $articleNum > 0;
}
