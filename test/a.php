<?php

$startTime = microtime(true);
// $r = file_get_contents('https://api.huobi.pro/v1/common/symbols');
sleep(5);
$endTime = microtime(true);

echo "start: " . round($startTime, 3) . PHP_EOL;
echo "end: " . round($endTime, 3) . PHP_EOL;
echo "cost: ". round($endTime - $startTime, 3) . PHP_EOL;
