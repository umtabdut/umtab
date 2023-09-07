<?php

$r = $_GET['r'] > 0 ? $_GET['r'] : 0;
$g = $_GET['g'] > 0 ? $_GET['g'] : 0;
$b = $_GET['b'] > 0 ? $_GET['b'] : 0;

$hex_color = sprintf('#%02x%02x%02x', $r, $g, $b);

$colors['hex'] = $hex_color;
$colors['rgb'] = ['r' => $r, 'g' => $g, 'b' => $b];

// $colors['rgb']['r'] = $r;
// $colors['rgb']['g'] = $g;
// $colors['rgb']['b'] = $b;

// echo json_encode($colors);

echo $colors['hex'];