<?php 

// Day 4 Solutions

function make_grid(array $grid_rows): array {
    $grid = [];
    foreach ($grid_rows as $row_idx => $row) {
        foreach (str_split(trim($row)) as $col_idx => $value) {
            $grid[$row_idx][$col_idx] = $value;
        }
    }
    return $grid;
}

function get_adjacent_roll_count(array $grid, int $row_idx, int $col_idx): int {
    $adjacent_roll_count = 0;
    foreach (range($row_idx - 1, $row_idx + 1) as $adj_row) {
        foreach (range($col_idx - 1, $col_idx + 1) as $adj_col) {
            if ($adj_row == $row_idx && $adj_col == $col_idx) {
                continue;
            }
            $adj_value = $grid[$adj_row][$adj_col] ?? null;
            if (!empty($adj_value) && $adj_value == '@') {
                $adjacent_roll_count++;
            }
        }
    }
    return $adjacent_roll_count;
}

function get_accessible_roll_count(array $grid): int {
    $accessible_roll_count = 0;
    foreach ($grid as $row_idx => $row) {
        foreach ($row as $col_idx => $value) {
            $adjacent_roll_count    = get_adjacent_roll_count($grid, $row_idx, $col_idx);
            if ($value == '@' && $adjacent_roll_count < 4) {
                $accessible_roll_count++;
            }
        }
    }
    return $accessible_roll_count;
}

function remove_all_rolls(array $grid): int {
    $total_rolls_removed        = 0;
    while (true) {
        $rolls_removed = 0;
        foreach ($grid as $row_idx => $row) {
            foreach ($row as $col_idx => $value) {
                $adjacent_roll_count = get_adjacent_roll_count($grid, $row_idx, $col_idx);            
                if ($value == '@' && $adjacent_roll_count < 4) {
                    $grid[$row_idx][$col_idx] = 'X';
                    $rolls_removed++;
                } 
            }
        }
        if ($rolls_removed < 1) {
            break;
        }
        $total_rolls_removed += $rolls_removed;
    }
    return $total_rolls_removed;
}



$input_file_path        = $argv[1];
$grid_rows              = file($input_file_path);
$grid                   = make_grid($grid_rows);

// Solve part 1
$accessible_roll_count  = get_accessible_roll_count($grid);
echo $accessible_roll_count . "\n";

// Solve part 2
$total_rolls_removed    = remove_all_rolls($grid);
echo $total_rolls_removed . "\n";
