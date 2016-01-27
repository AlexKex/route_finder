<?php

namespace AlexKex\route_finder;

require __DIR__."/test_autoload.php";

$map2 = [
    ["A", "B", 3],
    ["A", "C", 4],
    ["B", "E", 6],
    ["B", "D", 5],
    ["C", "D", 2],
    ["C", "F", 10],
    ["D", "F", 1],
    ["D", "E", 1],
    ["E", "F", 7]
];

$finder = new RouteFinder();

$start = "A";
$finish = "F";
$info = $finder->findRoute($start, $finish, $map2);

echo "Route between ".$start." and ".$finish." \nRoute price : ".$info["price"]." \nTransit points : ".implode(" ", $info["route"])."\n";
