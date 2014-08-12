<?php



for ($i=1; $i <= 9; $i++) {
    echo "tutorial_example_0{$i}:\n";
    echo "====================\n";
    include __DIR__ . "/tutorial_example_0{$i}.php";

    echo "\n----------------------------------\n\n";
}