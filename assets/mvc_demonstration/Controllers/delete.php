<?php

require '../config/mysqli.php';
require '../Models/Event.php';


$url = deleteEvent($_GET['id'], $con);

$con->close();

header('Location: ../?month=' . $url);

?>