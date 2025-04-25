<?php
include 'dbconnect.php';

$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

$events_query = "SELECT event_name, time FROM events WHERE date = '$date' ORDER BY time";
$events_result = mysqli_query($conn, $events_query);

$events = [];
if ($events_result) {
    while ($row = mysqli_fetch_assoc($events_result)) {
        $events[] = [
            'event_name' => $row['event_name'],
            'time' => $row['time']
        ];
    }
}

header('Content-Type: application/json');
echo json_encode(['events' => $events]);
?>