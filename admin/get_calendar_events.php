<?php
include 'dbconnect.php';

$month = isset($_GET['month']) ? intval($_GET['month']) : date('n');
$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');

$start_date = "$year-$month-01";
$end_date = date('Y-m-t', strtotime($start_date)); // Last day of month

$events_query = "SELECT date, event_name FROM events 
                 WHERE date BETWEEN '$start_date' AND '$end_date'";
$events_result = mysqli_query($conn, $events_query);

$events = [];
if ($events_result) {
    while ($row = mysqli_fetch_assoc($events_result)) {
        $event_day = date('j', strtotime($row['date']));
        $events[$event_day] = true;
    }
}

header('Content-Type: application/json');
echo json_encode(['events' => $events]);
?>