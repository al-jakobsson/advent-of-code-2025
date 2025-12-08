<?php

// Day 6

$input_rows = file($argv[1]);
$valid_ops  = ['+', '*'];

// Solve part 1

$grid       = [];
foreach ($input_rows as $row_idx => $input_row) {
    $col_idx        = 0;
    $token          = '';
    for ($i = 0; $i < strlen($input_row); $i++) {
        $c = $input_row[$i];
        if (!ctype_space($c) && $c !== PHP_EOL) {
            $token .= $c;
            continue;
        }
        if (empty($token)) {
            continue;
        }
        $grid[$col_idx][$row_idx] = 
            in_array($token, $valid_ops)
                ? $token
                : (int) $token;
        $token  = '';
        $col_idx++;
    }
}
$sum = 0;
foreach ($grid as $col_idx => $col) {
    $op = array_pop($col);
    switch ($op) {
    case '+':
        $sum += array_sum($col);
        break;
    case '*':
        $sum += array_product($col);
        break;
    default:
        echo "invalid op: $op";
        exit();
    }
}
echo "Part 1: $sum\n";

// Solve part 2

function is_column_break(int $ch_idx, array $input_rows) {
    $col_whitespace_count = 0;
    foreach ($input_rows as $input_row) {
        $c = $input_row[$ch_idx];
        if (ctype_space($c) || $c === PHP_EOL) {
            $col_whitespace_count++;
        }
    }
    return ($col_whitespace_count == count($input_rows));
}

$ops_row    = array_pop($input_rows);
$grid       = [];
foreach ($input_rows as $row_idx => $input_row){
    $token = '';
    $col_idx = 0;
    for ($i = 0; $i < strlen($input_row); $i++) {
        $c = $input_row[$i];
        if (is_column_break($i, $input_rows)) {
            $grid[$col_idx][$row_idx] = $token;
            $token = '';
            $col_idx++;
            continue;
        }
        if (ctype_space($c)) {
            $token .= '_'; // dummy space
            continue;
        }
        $token .= $c;
    }
}
$cols = [];
foreach ($grid as $col_idx => $col) {
    foreach ($col as $row_idx => $row) {
        for ($i = 0; $i < strlen($row); $i++) {
            $d = $row[$i];
            if ($d === '_') continue;
            if (ctype_digit($d)) {
                $cols[$col_idx][$i] = 
                    ($cols[$col_idx][$i] ?? '') . $d;
            }
        }
    }
}
$ops = [];
for ($i = 0; $i < strlen($ops_row); $i++) {
    $c = $ops_row[$i];
    if (in_array($c, $valid_ops)) {
        $ops[] = $c;
    }
}
$sum = 0;
for ($i = 0; $i < count($ops); $i++) {
    switch ($ops[$i]) {
    case '+':
        $sum += array_sum($cols[$i]);
        break;
    case '*':
        $sum += array_product($cols[$i]);
        break;
    default:
        echo "invalid op";
        exit();
    }
}
echo "Part 2: $sum\n";
