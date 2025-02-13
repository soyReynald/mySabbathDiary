<?php

require '../config/mysqli.php';
require '../Models/Event.php';

echo jsonEvent($_GET['id'], $con);

?>