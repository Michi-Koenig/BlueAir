<?php

$array = array();
$array['room'] = "Wohnen";
$array['device'] = 1;
$array['temp'] = array(); // unnötige Deklarierung aber zur lesbarkeit
$array['temp'][] = 22;
$array['temp'][] = 22;
$array['temp'][] = 23;
$array['temp'][] = 24;
$array['temp'][] = 24;
$array['temp'][] = 25;
$array['temp'][] = 23;

echo json_encode($array, JSON_PRETTY_PRINT);



?>