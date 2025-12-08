<?php 

// Day 7 

$levels = file($argv[1]);

// Solve part 1

$line = [];
$splits = 0;
foreach ($levels as $level) {
    for ($pos = 0; $pos < strlen($level); $pos++) {
        $c = $level[$pos];
        switch ($c) {
        case 'S':
            $line[$pos] = true;
            break;
        case '^':
            if ($line[$pos] === true) {
                $line[$pos] = false;
                $line[$pos - 1] = true;
                $line[$pos + 1] = true;
                $splits++;
            }
        }
    }
}
echo "$splits\n";

// Solve part 2

$init   = array_shift($levels);
for ($i = 0; $i < strlen($init); $i++) {
    if ($init[$i] === 'S') {
        $current = [$i => 1];
        break;
    }
}
foreach ($levels as $row) {
    $next = [];
    foreach ($current as $col => $count) {
        if ($row[$col] === '^') {            
            $next[$col-1]   = ($next[$col-1] ?? 0) + $count;
            $next[$col+1]   = ($next[$col+1] ?? 0) + $count;
        } else {
            $next[$col]     = ($next[$col] ?? 0) + $count;
        }
    }
    $current = $next;
}
echo array_sum($current);
