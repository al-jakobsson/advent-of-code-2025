<?php 

// Day 5


function parse_input(string $input): array {
    list($id_ranges_str, $ingredient_ids_str) = explode("\n\n", $input);
    $fresh_id_ranges = [];
    foreach (explode("\n", trim($id_ranges_str)) as $id_range_str) {
        list($start, $end) = explode('-', trim($id_range_str));
        $fresh_id_ranges[] = [
            'start' => $start,
            'end'   => $end
        ];
    }
    $ingredient_ids = [];
    foreach (explode("\n", trim($ingredient_ids_str)) as $ingredient_id_str) {
        $ingredient_ids[] = trim($ingredient_id_str);
    }
    return [$fresh_id_ranges, $ingredient_ids];
}

function get_fresh_ingredient_count(
    array $ingredient_ids,
    array $fresh_id_ranges
):  int {
    $fresh_ingredient_count = 0;
    foreach ($ingredient_ids as $id) {
        $id = (int) $id;
        foreach ($fresh_id_ranges as $range) {
            $start  = (int) $range['start'];
            $end    = (int) $range['end'];
            if ($id >= $start && $id <= $end) {
                $fresh_ingredient_count++;
                break;
            }
        }
    }
    return $fresh_ingredient_count;
}

/** 
 * This looks cool, but we exhaust the allowed memory size
 * Whoops.
 */ 
function get_full_fresh_count(array $fresh_id_ranges): int {
    $fresh = [];
    foreach ($fresh_id_ranges as $range) {
        $start  = (int)$range['start'];
        $end    = (int)$range['end'];
        for ($i = $start; $i <= $end; $i++) {
            $fresh[$i] = true; 
        }
    }
    return count($fresh);
}

/**
 * Sweep line algorithm
 * Fast as hell, doesn't break computer.
 * Looks like absolute ass.
 * I have brain damage.
 * I can't believe this works.
 */

function get_fresh_coverage_count(array $ranges): int {
    $starts = [];
    $ends   = [];
    foreach ($ranges as $r) {
        $starts[] = (int)$r['start'];
        $ends[]   = (int)$r['end'] + 1; // make end exclusive
    }
    sort($starts);
    sort($ends);
    $start_idx      = 0;
    $end_idx        = 0;
    $open_ranges    = 0;
    $coverage       = 0;
    $last_position  = null;
    $starts_count   = count($starts);
    $ends_count     = count($ends);
    while ($start_idx < $starts_count || $end_idx < $ends_count) {
        $has_unprocessed_starts = $start_idx < $starts_count;
        $has_unprocessed_ends   = $end_idx   < $ends_count;
        $end_is_exhausted       = !$has_unprocessed_ends;
        $start_comes_before_end =
            $has_unprocessed_starts
            && $has_unprocessed_ends
            && $starts[$start_idx] <= $ends[$end_idx];
        $next_is_start =
            $has_unprocessed_starts
            && ($end_is_exhausted || $start_comes_before_end);
        if ($next_is_start) {
            $pos = $starts[$start_idx];
            if ($open_ranges > 0) {
                $coverage += $pos - $last_position;
            }
            $last_position = $pos;
            $open_ranges++;
            $start_idx++;
        } else {
            $pos = $ends[$end_idx];
            if ($open_ranges > 0) {
                $coverage += $pos - $last_position;
            }
            $last_position = $pos;
            $open_ranges--;
            $end_idx++;
        }
    }
    return $coverage;
}


// Do the thing 

list($fresh_id_ranges, $ingredient_ids) = parse_input(
    file_get_contents($argv[1])
);

// Solve part 1
$fresh_ingredient_count = get_fresh_ingredient_count($ingredient_ids, $fresh_id_ranges);
echo "Fresh ingredient count: $fresh_ingredient_count\n";

// Solve part 2
$fresh_coverage_count = get_fresh_coverage_count($fresh_id_ranges);
echo "Fresh coverage count: $fresh_coverage_count\n";
