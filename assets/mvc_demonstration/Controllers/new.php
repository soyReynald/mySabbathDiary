<?php
/* 
Array ( 
    [date] => 2020-12-1 
    [time] => 16:05 
    [category] => 3 
    [name] => To learn PHP 
)
*/
require('../config/mysqli.php');
require('../Models/Event.php');

$url = addEvent($_POST['date'], $_POST['time'], $_POST['category'], $_POST['name'], $con);

header('Location: ../?month='.$url);

?>