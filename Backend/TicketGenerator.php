<?php 
session_start();
$user = $_SESSION['userName'];

require_once("../storage/sql_connect.php");


$days = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');


//query to get the usertype of the current user
$typeQuery = $mysqli->prepare("SELECT usertype FROM dorm WHERE username=?");
$typeQuery->bind_param("s",$user);

if($typeQuery->execute()){
    $typeResult = $typeQuery->get_result();
    $typeRow = $typeResult->fetch_assoc();
    $usertype = $typeRow['usertype'];
}


//query to get the number of timeslots the user has reserved for the week (assignments)
$assignmentQuery = $mysqli->prepare("SELECT assignments FROM dorm WHERE username=?");
$assignmentQuery->bind_param("s",$user);

if ($assignmentQuery->execute()){
    $result = $assignmentQuery->get_result();
    $row = $result->fetch_assoc();
    $ticketNumber = $row["assignments"];
}
//query to get the rest of ticket information for the user for each timeslot reserved
$machineQuery = $mysqli->prepare("SELECT id, machine, timeslot, day FROM reservations WHERE user_name = ?");
$machineQuery->bind_param("s",$user);

if ($machineQuery->execute()){
    $result = $machineQuery->get_result();
    $reservoir = array();
    $dogs = array();
    $three = array();
    $ticketID = array();
    $iter = 0;
    while($rows =  $result->fetch_assoc()){
        $time_24_hour = $rows['timeslot'];
          // Convert 24-hour time to 12-hour format
        $time_12_hour = date("h:i A", strtotime($time_24_hour));
        $reservoir[$iter] = $rows["machine"];
        $dogs[$iter] = $time_12_hour; 
        $three[$iter] = $days[$rows['day']];
        $ticketID[$iter] = $rows['id'];
        $iter++;
    }   
}

//query to get the ticket information for every user of the system
$adminQuery = $mysqli->prepare("SELECT id, machine, timeslot, day, user_name FROM reservations WHERE user_name is NOT NULL");


if ($adminQuery->execute()){
    $resultant = $adminQuery->get_result();
    $machines = array();
    $time = array();
    $day = array();
    $userID = array();
    $i = 0;
    while($rowing =  $resultant->fetch_assoc()){
        $time_24_hour = $rowing['timeslot'];
        // Convert 24-hour time to 12-hour format
      $time_12_hour = date("h:i A", strtotime($time_24_hour));
        $machines[$i] = $rowing["machine"];
        $time[$i] = $time_12_hour; 
        $day[$i] = $days[$rowing['day']];
        $userID[$i] = $rowing['id'];
        $users[$i] = $rowing['user_name'];
        $i++;
    }   
}

?>
<!--Choose if resident or staff what tickets will be displayed to the user.-->
<?php if ($usertype=="resident"):?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
    
        <link rel="icon" type="image/logo" href="../img/laundry logo.png">
        <link rel="stylesheet" href="../css/app.css">
        <script src="../js/app.js"></script>
    </head>
    <body>
        <h1>UniFresh Laundry Xpress</h1>
        <div class="ticketBox">
            <?php for($ticket = 0; $ticket < $iter; $ticket++):?>
                <div class="container">
                    <h2>Queue Ticket</h2>
                    <div class = "machine">
                        <div id="user"> 
                            <h3 id="id">User ID: <?=$user?></h3>
                            <h3 id = "date">Day: <?=$three[$ticket]?></h3></div>
                            <p id="ticket"><strong>Ticket ID:</strong></p>
                            <p id="dec1">********************************************</p>
                            <h3 id="ticket-val">A<?=$ticketID[$ticket]?></h3>
                            <p id="dec2">********************************************</p>
                            <h4>Description:</h4>
                            <p id="details"><strong>Time Slot:</strong> <?=$dogs[$ticket]?><br>
                            <strong>Period:</strong> 60 mins<br>
                            <strong>Service:</strong> Wash, Rinse & Dry<br>
                            <strong>Selected Equipment:</strong> <?=$reservoir[$ticket]?></p>
                        <p id="message">Tip: Please arrive 5 minutes prior to your scheduled time!</p>
                    </div>
                </div> 
            <?php endfor;?>
        </div>
    </body>
    </html>

<?php else:?>
    <!DOCTYPE html>
    <html lang="en">
    <head>

        <link rel="stylesheet" href="../css/app.css">
    </head>
    <body>
        <h1>UniFresh Laundry Xpress</h1>
        <div class="ticketBox">
            <?php for($ticket = 0; $ticket < $i; $ticket++):?>
                <div class="container">
                    <h2>Queue Ticket</h2>
                    <div class = "machine">
                        <div id="user"> 
                            <h3 id="id">User ID: <?=$users[$ticket]?></h3>
                            <h3 id = "date">Day: <?=$day[$ticket]?></h3></div>
                            <p id="ticket"><strong>Ticket ID:</strong></p>
                            <p id="dec1">********************************************</p>
                            <h3 id="ticket-val">A<?=$userID[$ticket]?></h3>
                            <p id="dec2">********************************************</p>
                            <h4>Description:</h4>
                            <p id="details"><strong>Time Slot:</strong> <?=$time[$ticket]?><br>
                            <strong>Period:</strong> 60 mins<br>
                            <strong>Service:</strong> Wash, Rinse & Dry<br>
                            <strong>Selected Equipment:</strong> <?=$machines[$ticket]?></p>
                            <p id="message">Tip: Please arrive 5 minutes prior to your scheduled time!</p>
                        </div>
                    </div> 
            <?php endfor;?>
        </div>
</body>
</html>


<?php endif;?>