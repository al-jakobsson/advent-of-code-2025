<?php
$input_file_path    = $argv[1];
$battery_count      = $argv[2];
$banks              = file($input_file_path);

echo get_total_output_joltage($banks, $battery_count);

function get_total_output_joltage(array $banks, int $battery_count): int {
    $total_output_joltage = 0;
    foreach ($banks as $bank_str) {
        $total_output_joltage += get_max_output_joltage_sliding_window_greedy(
            str_split(trim($bank_str)), 
            $battery_count
        );
    }
    return $total_output_joltage;
}
function get_max_output_joltage_tael_style(array $bank, int $battery_count): int {
    $selected   = array_fill(0, $battery_count, 0); 
    $i          = 0;
    for ($depth = 0; $depth < $battery_count; $depth++) {
        for ($n = $i; $n <= count($bank) - ($battery_count - $depth); $n++) {
            if ($selected[$depth] < $bank[$n]) {
                $selected[$depth] = $bank[$n];
                $i = $n + 1;
            }
        }
    }
    return (int) implode("", $selected);
}

function get_max_output_joltage_sliding_window_greedy(
    array $bank, 
    int $battery_count
): int {
    $selected   = [];
    $start      = 0;
    for ($i = 0; $i < $battery_count; $i++) {
        $remaining              = $battery_count - $i;
        $search_window_end      = count($bank) - $remaining;
        $max_digit_in_window    = -1;
        $max_pos                = $start;
        for ($pos = $start; $pos <= $search_window_end; $pos++) {
            if ($bank[$pos] > $max_digit_in_window) {
                $max_digit_in_window    = $bank[$pos];
                $max_pos                = $pos;
            }
        }
        $selected[]     = $max_digit_in_window;
        $start          = $max_pos + 1;
    }
    return (int) implode('', $selected);
}

