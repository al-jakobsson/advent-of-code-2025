<?php

// Day 2

// This took me way too fucking long
// I didn't read part 1 correctly, so I solved for part 2 first
// It's ugly, but it works.


function get_sum_of_ids_with_patterns_repeated_n_times(array $id_ranges): int {
    $sum_of_invalid_ids = 0;
    foreach ($id_ranges as $id_range) {
        list($start_id, $end_id) = explode('-', $id_range);
        foreach (range($start_id, $end_id) as $id) {
            $id                 = (string) $id;
            if ($id[0] == '0')  { continue; }
            $id_len             = strlen($id);
            if ($id_len < 2)    { continue; }
            $pattern            = '';
            $pattern_max_len    = intdiv($id_len, 2); 
            foreach (str_split($id) as $d) {
                $pattern        .= $d;
                $pattern_len    = strlen($pattern);
                if ($pattern_len > $pattern_max_len) { 
                    break; 
                }
                if ($id_len % $pattern_len != 0) {
                    continue; 
                }
                $test_id        = str_repeat($pattern, $id_len / $pattern_len);
                if ($test_id === $id) {
                    $sum_of_invalid_ids += (int) $id;
                    break;
                }
            }
        }
    }
    return $sum_of_invalid_ids;
}

function get_sum_of_ids_with_patterns_repeated_twice(array $id_ranges): int {
    $sum_of_invalid_ids = 0;
    foreach ($id_ranges as $id_range) {
        list($start_id, $end_id) = explode('-', $id_range);
        foreach (range($start_id, $end_id) as $id) {
            $id = (string) $id;
            $len = strlen($id);
            if ($len % 2 === 0) {
                $half = $len / 2;
                if (substr($id, 0, $half) === substr($id, $half)) {
                    $sum_of_invalid_ids += (int) $id;
                }
            }
        }
    }
    return $sum_of_invalid_ids;
}

$id_ranges = explode(',', trim(file_get_contents($argv[1])));

// Solve part 1
$sum = get_sum_of_ids_with_patterns_repeated_twice($id_ranges);
echo $sum . "\n";

// Solve part 2
$sum = get_sum_of_ids_with_patterns_repeated_n_times($id_ranges);
echo $sum . "\n";
