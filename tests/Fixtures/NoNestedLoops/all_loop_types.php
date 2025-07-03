<?php

declare(strict_types=1);

namespace TestFixtures\NoNestedLoops;

// Test all combinations of nested loops

// For inside For
for ($i = 0; $i < 5; $i++) {
    for ($j = 0; $j < 5; $j++) { // error
        echo $i * $j;
    }
}

// For inside Foreach
foreach ($items as $item) {
    for ($i = 0; $i < 5; $i++) { // error
        echo $item . $i;
    }
}

// For inside While
$i = 0;
while ($i < 5) {
    for ($j = 0; $j < 5; $j++) { // error
        echo $i * $j;
    }
    $i++;
}

// For inside Do-While
$i = 0;
do {
    for ($j = 0; $j < 5; $j++) { // error
        echo $i * $j;
    }
    $i++;
} while ($i < 5);

// Foreach inside For
for ($i = 0; $i < 5; $i++) {
    foreach ($items as $item) { // error
        echo $i . $item;
    }
}

// Foreach inside Foreach (already tested)

// Foreach inside While
$i = 0;
while ($i < 5) {
    foreach ($items as $item) { // error
        echo $i . $item;
    }
    $i++;
}

// Foreach inside Do-While
$i = 0;
do {
    foreach ($items as $item) { // error
        echo $i . $item;
    }
    $i++;
} while ($i < 5);

// While inside For
for ($i = 0; $i < 5; $i++) {
    $j = 0;
    while ($j < 5) { // error
        echo $i * $j;
        $j++;
    }
}

// While inside Foreach
foreach ($items as $item) {
    $i = 0;
    while ($i < 5) { // error
        echo $item . $i;
        $i++;
    }
}

// While inside While (already tested)

// While inside Do-While
$i = 0;
do {
    $j = 0;
    while ($j < 5) { // error
        echo $i * $j;
        $j++;
    }
    $i++;
} while ($i < 5);

// Do-While inside For
for ($i = 0; $i < 5; $i++) {
    $j = 0;
    do { // error
        echo $i * $j;
        $j++;
    } while ($j < 5);
}

// Do-While inside Foreach
foreach ($items as $item) {
    $i = 0;
    do { // error
        echo $item . $i;
        $i++;
    } while ($i < 5);
}

// Do-While inside While
$i = 0;
while ($i < 5) {
    $j = 0;
    do { // error
        echo $i * $j;
        $j++;
    } while ($j < 5);
    $i++;
}

// Do-While inside Do-While (already tested)
