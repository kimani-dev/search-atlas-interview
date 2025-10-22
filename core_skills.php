<?php

# 1.
$random_numbers = [];
for ($x = 1; $x <= 10; $x++) {
    array_push($random_numbers, rand(1, 20));
}

# 2.
$numbers_below_10 = array_filter($random_numbers, fn($num) => $num < 10);

# 3.
print_r($random_numbers);
print_r($numbers_below_10);
