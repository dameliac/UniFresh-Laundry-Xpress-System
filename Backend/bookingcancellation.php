<?php 
    //check if post has been sent
    if (isset($_POST['time'])){
        session_start();
        $user = $_SESSION['userName'];
        require_once ("../storage/sql_connect.php");
        //set days of the week to associated numbers to convert numbers from database to days
        $days = ["Sunday"=> 0, "Monday"=> 1, "Tuesday"=> 2, "Wednesday"=> 3, "Thursday"=> 4, "Friday"=> 5, "Saturday"=> 6];
        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }
        //get the total number of reservations the user has made for the week(assignments) 
        $assignmentQuery = $mysqli->prepare("SELECT assignments FROM dorm WHERE username=?");
        $assignmentQuery->bind_param("s", $user);
        
        if ($assignmentQuery->execute()) {
            $result = $assignmentQuery->get_result();
            $row = $result->fetch_assoc();
            $number = $row["assignments"];
        }
        
        $time = $_POST['time'];
        $machine = $_POST['machine'];
        $day = $_POST['day'];
        //Remove the user from the assoicated timeslot
        $updateQuery = $mysqli->prepare("UPDATE reservations SET user_name=null WHERE machine=? AND day= ? AND timeslot=?");
        $updateQuery->bind_param("sss", $machine, $days[$day], $time);
        
        if ($updateQuery->execute()) {
            if ($number > 0){
                //Reduce the users total number of reservations made for the week allowing them to reassign to another timeslot for the week.
                $mysqli->query("UPDATE dorm SET assignments = assignments - 1 WHERE username = '$user'");
                echo "success";
            }
        }
        else{
            echo "failure";
        }
    }

?>

<?php
//Get session variables assigned at login to be used to find associated user information.
session_start();
$user = $_SESSION['userName'];
require_once("../storage/sql_connect.php");
$weekDays = [0 => "Sunday", 1 => "Monday", 2 => "Tuesday", 3 => "Wednesday", 4 => "Thursday", 5 => "Friday", 6 => "Saturday"];

if ($mysqli->connect_error){
    die("Connection failed: " . $mysqli->connect_error);
}

//get the number of timeslots the user is assigned to
$assignmentQuery = $mysqli->prepare("SELECT assignments FROM dorm WHERE username=?");
$assignmentQuery->bind_param("s",$user);

//assign the number of timeslot user is assigned to to number variable
if ($assignmentQuery->execute()){
    $result = $assignmentQuery->get_result();
    $row = $result->fetch_assoc();
    $number = $row["assignments"];
}

//get the information of the specified user timeslots that are still available to be cancelled
$infoQuery = $mysqli->prepare("SELECT machine, timeslot, day FROM reservations WHERE user_name = ?");
$infoQuery->bind_param("s",$user);

//assign these timeslots to array variables
if ($infoQuery->execute()){
    $result = $infoQuery->get_result();
    $mech = array();
    $clock = array();
    $week = array();
    $iter = 0;
    while($rows =  $result->fetch_assoc()){
        $mech[$iter] = $rows["machine"];
        $clock[$iter] = $rows['timeslot']; 
        $week[$iter] = $rows['day'];
        $iter++;
    }   
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Cancellation</title>
    <link rel="stylesheet" href="../css/cancel.css"/>
</head>
<body>
    <h1>UniFresh Laundry Xpress</h1>
    <h2>Booking Cancellation</h2>
    <div id="cancellationForm">
        <!--Display the available cancellation timeslots using iter instead of number variable due to assignments not being renewed till next week for expired timeslots-->
        <?php for($picket = 0; $picket < $iter; $picket++):?>
            <div onclick="canceller(event)" class="timeSlotted selected"><?=$clock[$picket] . " " . $mech[$picket] . " " . $weekDays[$week[$picket]]?></div>
        <?php endfor;?>
    </div>
</body>
</html>
<script src="../js/bookingcancellation.js"></script>