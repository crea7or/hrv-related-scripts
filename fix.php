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
    $lines = file($file);
    $output = "";
    $errors = false;
    $min_possible_rr = 100;
    $max_possible_rr = 4000;
    for ($cnt = 0; $cnt < count($lines); $cnt++) {
        $rr = trim($lines[$cnt]);
        if (is_numeric($rr) && $rr > $min_possible_rr && $rr < $max_possible_rr) {
            if (strlen($output) > 0) {
                $output .= "\n";
            }
            $output .= $rr;
        } else {
            $errors = true;
        }
    }
    if ($errors) {
        file_put_contents($file, $output);
        echo ("fixed: " . $name . "\n");
    }
    return $errors;
}

// uncomment it to fix your txt files
/*
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

echo ("files fixed: " . count($files_processed) . " without errors: " . count($errors) . "\n");
*/