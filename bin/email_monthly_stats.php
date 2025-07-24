<?php

declare(strict_types=1);

require_once 'vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;

// Set the current directory so we can run this from a cron job more easily
$installPath = $argv[1];
chdir($installPath);

$config = Yaml::parseFile('config/config.yaml');
if (empty($config['BRANCHES'])) {
    die('Could not load configuration!');
}
$config['data_path'] = "{$installPath}/data";
$config['template_path'] = "{$installPath}/public/templates";

$today = date('Y-m-d');

// Determine last month's log file
[$year, $mon, $day] = explode('-', $today);
$mon = ltrim($mon, '0');
if ((int) $mon === 1) {
    $lastMon = 12;
    $year = (string) ((int) $year - 1);
} else {
    $lastMon = (int) $mon - 1;
    $lastMon = sprintf('%02d', $lastMon);
}

// Read the log file and sum the values for each day
$sum = [];
$logFile = $config['log_path'] . "/{$year}-{$lastMon}.log";
$fLog = fopen($logFile, 'r');
if ($fLog === false) {
    die("Could not open file: {$logFile}");
}

while (!feof($fLog)) {
    $json = trim((string) fgets($fLog)); // Cast to string and trim whitespace
    $data = json_decode($json, true);
    if (!empty($data)) {
        foreach ($data as $dayData) { // Renamed $day to $dayData to avoid conflict
            foreach ($dayData as $code => $value) {
                if (!isset($sum[$code])) { // Use isset for checking array key existence
                    $sum[$code] = 0;
                }
                $sum[$code] += $value; // Use += for addition assignment
            }
        }
    }
}
fclose($fLog);

// Extract and sort the codes
$codes = [];
foreach ($sum as $code => $value) {
    $codes[] = $code; // Use short array push syntax
}
sort($codes);

// Open the email body file
$tempFile = 'body.csv';
$fBody = fopen($tempFile, 'w');
if ($fBody === false) {
    die("Could not open file: {$tempFile}");
}

// Write the branch codes and sums
for ($i = 0; $i < count($codes); ++$i) {
    fwrite($fBody, "{$codes[$i]},{$sum[$codes[$i]]}\n");
}
fclose($fBody);

// Send email
$to = $config['stats_email'];
$from = $config['email_from'];
$subject = "Paging Statistics for {$lastMon}/{$year}";
$header = 'Content-type: text/csv; charset=UTF-8';
$commandLine = "/usr/bin/mailx -r {$from} -a \"{$header}\" -s \"{$subject}\" {$to} < {$tempFile}";
system($commandLine, $retval);

unlink($tempFile);
