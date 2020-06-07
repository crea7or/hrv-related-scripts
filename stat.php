<?php

date_default_timezone_set('UTC');

function endsWith($haystack, $needle)
{
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }
    return (substr($haystack, -$length) === $needle);
}

function checkfile($file, $name)
{
    // user data defaults
    $error = true;
    $min_possible_rr = 100;
    $max_possible_rr = 4000;
    $max_dx = 700;
    $min_dx = 1;
    // user data defaults

    $lines = file($file);
    $min = $max_possible_rr;
    $max = $min_possible_rr;
    $prev_rr = 0;
    $rmssds = 0;
    $meanrrs = 0;
    for ($cnt = 0; $cnt < count($lines); $cnt++) {
        $rr = trim($lines[$cnt]);
        if (is_numeric($rr) && $rr > $min_possible_rr && $rr < $max_possible_rr) {
            if ($max < $rr) {
                $max = $rr;
            }
            if ($min > $rr) {
                $min = $rr;
            }

            $meanrrs += $rr;
            if ($prev_rr == 0) {
                $prev_rr = $rr;
            } else {
                $rmssds += pow(abs($rr - $prev_rr), 2);
                $prev_rr = $rr;
            }
        } else {
            // echo( "bad value\n" );
        }
    }

    $dx = $max - $min;
    if ($min == $max_possible_rr || $max == $min_possible_rr || $dx > $max_dx || $dx < $min_dx) {
        echo ("bad data in the file: " . $name . " $min=" . $min . " $max=" . $max . " $dx=" . $dx . "\n");
    } else {
        $rmssd = sqrt($rmssds / (count($lines) - 1));
        $meanrr = $meanrrs / count($lines);
        echo ("dx: " . $dx . "\trmssd: " . round($rmssd, 1) . "\tln(rmssd): " . round(log($rmssd), 2) . "\tmean rr: " . round($meanrr, 0) . "\tmax hr: " . round(60000 / $min, 1) . "\tmin hr: " . round(60000 / $max, 1) . "  file:" . $name . "\n");
        $error = false;
    }

    return $error;
}

$root = getcwd();

$files_array = scandir($root);
echo ("files found: " . count($files_array) . "\n");
$errors = array();
$files_processed = array();
foreach ($files_array as $file) {
    $path = $root . DIRECTORY_SEPARATOR . $file;
    if (is_dir($path) == false && endsWith($file, ".txt")) {
        if (checkfile($path, $file)) {
            $files_processed[] = $path;
        } else {
            $errors[] = $path;
        }
    }
}

echo ("files processed: " . count($files_processed) . " with errors: " . count($errors) . "\n");
