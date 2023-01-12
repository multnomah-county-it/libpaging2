<?php

// Set the current directory so we can run this from a cron job more easily
$install_path = $argv[1];
chdir($install_path);

require_once 'vendor/autoload.php';
use Symfony\Component\Yaml\Yaml;

$config = Yaml::parseFile('config/config.yaml');
if ( empty($config['BRANCHES']) ) {
    die("Could not load configuration!");
}
$config['data_path'] = "$install_path/data";
$config['template_path'] = "$install_path/public/templates";

$today = date("Y-m-d");

// Determine last month's log file
list($year, $mon, $day) = explode("-", $today);
$mon = ltrim($mon, "0");
if ( $mon == 1 ) {
    $last_mon = 12;
    $year = $year - 1;
} else {
    $last_mon = $mon - 1;
    $last_mon = sprintf("%02d", $last_mon);
}

// Read the log file and sum the values for each day
$sum = [];
$log_file = $config['log_path'] . "/$year" . '-' . "$last_mon.log";
$f_log = fopen($log_file, 'r') or die("Could not open file: $log_file");
while( ! feof($f_log) ) {
    $json = chop(fgets($f_log));
    $data = json_decode($json, true);
    if ( ! empty($data) ) {
        foreach ($data as $day) {
            foreach ($day as $code => $value) {
                if ( empty($sum[$code]) ) {
                    $sum[$code] = 0;
                }
                $sum[$code] = $sum[$code] + $value;
            }
        }
    }
}
fclose($f_log);

// Extract and sort the codes
$codes = [];
foreach ($sum as $code => $value) {
    array_push($codes, $code);
}
sort($codes);

// Open the email body file
$temp_file = 'body.csv';
$f_body = fopen($temp_file, 'w') or die("Could not open file: $temp_file");

// Write the branch codes and sums
for ($i = 0; $i < count($codes); ++$i) {
    fwrite($f_body, $codes[$i] . ',' . $sum[$codes[$i]] . "\n");
}
fclose($f_body);

// Send email
$to = $config['stats_email'];
$from = $config['email_from'];
$subject = "Paging Statistics for $last_mon/$year";
$header = 'Content-type:text/csv;charset=UTF-8';
$command_line = "/usr/bin/mailx -r $from -a \"$header\" -s \"$subject\" $to < $temp_file";
system($command_line, $retval);

unlink($temp_file);

// EOF
