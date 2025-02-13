<?php

function getEvents($from, $to, $con){
    $eventsQuery = "SELECT 
                    events.id,
                    DATE_FORMAT(date, '%d%m%Y') AS arr_index,
                    events.name,
                    categories.name AS category,
                    icon,
                    DATE_FORMAT(date, '%l:%i%p') AS time
                FROM 
                    events, categories
                WHERE 
                    categories.id = cat
                AND
                    date BETWEEN '$from' AND '$to'
                ORDER BY
                    date";

    $rsEvents = $con->query($eventsQuery) or die($con->error);

    $events = array();

    while($row = $rsEvents->fetch_object()){
        $events[$row->arr_index][] = $row;
    }

    return $events;
}

function jsonEvent($id, $con){

    $id = clean($id, $con);

    $query = "SELECT 
            id,
            DATE_FORMAT(date, '%Y-%m-%d') AS date,
            DATE_FORMAT(date, '%H:%i') AS time,
            cat,
            name
        FROM events WHERE id = $id";

    $rsEvent = $con->query($query) or die($con->error);
    $event = $rsEvent->fetch_assoc();
    $rsEvent->free();

    return json_encode($event);
}

function addEvent($date, $time, $category, $name, $con){

    $date = clean($date, $con);
    $time = clean($time, $con);
    $category = clean($category, $con);
    $name = clean($name, $con);

    $dateFixed = date('Y-m-d h:i:s', strtotime($date . ' ' . $time));

    $sql = "INSERT INTO events (cat, name, date) VALUES ('$category', '$name', '$dateFixed')";

    $con->query($sql) or die($con->error);

    $url = date('m-Y', strtotime($date . ' ' . $time));

    return $url;
}

function editEvent($date, $time, $category, $name, $id, $con){

    $date = clean($date, $con);
    $time = clean($time, $con);
    $category = clean($category, $con);
    $name = clean($name, $con);
    $id = clean($id, $con);

    $dateTime = date('Y-m-d H:i', strtotime($date.' '.$time));

    $sql = "UPDATE events SET name = '$name', date = '$dateTime', cat = $category WHERE id = $id";

    $con->query($sql);

    $url = date('m-Y', strtotime($date));

    return $url;
}


function deleteEvent($id, $con){
    $sql = "SELECT DATE_FORMAT(date, '%m-%Y') AS url FROM events WHERE id = $id";

    $rsUrl = $con->query($sql) or die($con->error);

    $url = $rsUrl->fetch_object()->url;

    $deleteSQL = "DELETE FROM events WHERE id = $id";

    $con->query($deleteSQL) or die($con->error);

    $rsUrl->free();

    return $url;
}

function getCategories($con){
    $sql = "SELECT * FROM categories";

    $categories = array();

    $query = $con->query($sql);

    while($row = $query->fetch_object()){
        $categories[]= $row;
    }

    return $categories;
}

function clean($input, $con){
    return $con->escape_string(strip_tags($input));
}

?>