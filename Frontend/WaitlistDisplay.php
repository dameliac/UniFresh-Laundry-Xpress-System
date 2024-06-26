<?php
session_start();
require_once("../storage/sql_connect.php");

//sets date to local time and get current hour and time
date_default_timezone_set('America/New_York');
$currentDay = date('w');
$currentHour = date('H');


//Get current users selected machines and timeslot (if any) for current day from database
$query = $mysqli->prepare("SELECT id, machine, timeslot, user_name FROM reservations WHERE user_name = ? AND day = ?");
$query->bind_param("ss", $user, $currentDay);

if ($query->execute()) {
    $result = $query->get_result();
    if ($result->num_rows > 0){
        $row = $result->fetch_assoc();
        $ticketNumber = $row["id"];
    }
    else{
        $ticketNumber = null;
    }

}
//Get names of all users in the dorm and their associated username
$nameQuery = $mysqli->query("SELECT firstname, lastname, username FROM dorm");

if ($nameQuery){
    $usernames = array();

    while ($rower = $nameQuery->fetch_assoc()) {
        $username = $rower['username'];
        $firstname = $rower['firstname'];
        $lastname = $rower['lastname'];
        $usernames[$username] = array('firstname' => $firstname, 'lastname' => $lastname);
    }
}
//Get the ticket number, user name, and machine of all users assigned to the same machine on the current day
$machineQuery = $mysqli->prepare("SELECT id, machine, HOUR(timeslot) as hour, day, user_name FROM reservations WHERE machine = ? AND day = ? AND user_name IS NOT NULL");

$machineQuery->bind_param("ss", $row["machine"], $currentDay);

if ($machineQuery->execute()) {
    $result = $machineQuery->get_result();
    $waitlist = array();
    $nowServing = array();
    while ($rows = $result->fetch_assoc()) {
        $status = (intval($rows['hour']) === intval($currentHour)) ? "Now Serving" : "In Waiting";
        $personWaiting = array(
            'ticketNumber' => "A" . $rows['id'],
            'name' => $usernames[$rows['user_name']]['firstname'] . " " . $usernames[$rows['user_name']]['lastname'],
            'machine' => $rows['machine'],
            'status' => $status,
        );

        if ($status === "Now Serving") {
            $nowServing[] = $personWaiting;
        } else {
            $waitlist[] = $personWaiting;
        }
    }
}
?>


<!--Displays list of people in order of closeness to time of available machine selected by the current user -->
<!DOCTYPE html>
<html lang="en">
<head>
      
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>

    <div id="waitlist-container">
        <h1 class="heading">UniFresh Laundry Xpress</h1>
        <h2>Queue Display</h2>
        <h3>NOW SERVING</h3>
        <table id="nowserving-table">
            <thead>
                <tr>
                    <th>Ticket #</th>
                    <th>Name</th>
                    <th>Machine </th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="serving">
                <?php foreach ($nowServing as $personWaiting):?>
                    <tr>
                        
                        <td><?=$personWaiting['ticketNumber']?></td>
                        <td><?=$personWaiting['name']?></td>
                        <td><?=$personWaiting['machine']?></td>
                        <td id="serving"><?=$personWaiting['status']?></td>
                    </tr>
                <?php endforeach;?>
            </tbody>
        </table>
       
        <h3>IN - WAITING</h3>
        <table id="waitlist-table">
            <thead>
                <tr>
                    <th>Ticket #</th>
                    <th>Name</th>
                    <th>Machine</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="Waitlist">
                <?php foreach($waitlist as $personWaiting):?>
                    <tr>
                        <td><?=$personWaiting['ticketNumber']?></td>
                        <td><?=$personWaiting['name']?></td>
                        <td><?=$personWaiting['machine']?></td>
                        <td id = "waiting"><?=$personWaiting['status']?></td>
                    </tr>
                <?php endforeach;?>
            </tbody>
        </table>
    </div>
</body>
</html>
