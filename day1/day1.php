<?php

// Fucking day 1

define('DIAL_MIN', 0);
define('DIAL_MAX', 99);
define('POSITIONS', 100);

function get_rotations(string $input_file_path): array {
    $lines = file($input_file_path);
    $rotations = [];
    foreach ($lines as $line) {
        $line = trim($line);
        $rotations[] = [
            "direction" => mb_substr($line, 0, 1), 
            "clicks" => (int) mb_substr($line, 1, strlen($line) - 1)
        ];
    }
    return $rotations;
}

function get_simple_password(array $rotations, int $start_position): int {
    $position           = $start_position;
    $zero_click_count   = 0;
    foreach ($rotations as $r) {
        $clicks = $r['clicks'] % POSITIONS;
        switch ($r['direction']) {
        case 'L':
            $position = ($position - $clicks) < DIAL_MIN
                ? POSITIONS - abs($position - $clicks)
                : $position - $clicks;
            break;
        case 'R':
            $position = ($position + $clicks) > DIAL_MAX
                ? ($position + $clicks) - POSITIONS
                : $position + $clicks;
        }
        if ($position == DIAL_MIN) {
            $zero_click_count++;
        }
    }
    return $zero_click_count;
}

function get_complex_password(array $rotations, int $start_position): int {
    $position           = $start_position;
    $zero_click_count   = 0;
    foreach ($rotations as $r) {
        $direction  = $r['direction'];
        $clicks     = $r['clicks'];
        while ($clicks > 0) {
            $clicks--;
            if ($direction == 'L') {
                $position--;
            } elseif ($direction == 'R') {
                $position++;
            }
            if ($position == DIAL_MAX + 1) {
                $position = DIAL_MIN;
            } elseif ($position == DIAL_MIN - 1) {
                $position = DIAL_MAX;
            }
            if ($position == DIAL_MIN) {
                $zero_click_count++;
            } 
        }
    }
    return $zero_click_count;
}

$rotations          = get_rotations($argv[1]);
$start_position     = 50;

// Solve part 1
$simple_password    = get_simple_password($rotations, $start_position);
echo $simple_password . "\n";

// Solve part 2
$complex_password   = get_complex_password($rotations, $start_position);
echo $complex_password . "\n";
