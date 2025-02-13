<?php

/* 
Array
(
    [date] => 2021-01-18
    [time] => 10:00
    [category] => 2
    [name] => To work on Sales Sistem
)
*/

require '../config/mysqli.php';
require '../Models/Event.php';

$url = editEvent($_POST['date'], $_POST['time'], $_POST['category'], $_POST['name'], $_POST['id'], $con);

header('Location: ../?month='. $url);

?>