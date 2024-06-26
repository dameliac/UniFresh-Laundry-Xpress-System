<?php
        session_start();
       require_once("../storage/sql_connect.php");
        //gets the machine name and status of all machines in the database and stores it in an array.
        $query = $mysqli->prepare("SELECT machineName, machineStatus FROM `machine status`");
        if ($query->execute()) {
            $result = $query->get_result();
            $reservations = array();
            while ($row = $result->fetch_assoc()) {
                $reservations[$row['machineName']] = $row['machineStatus'];
            }
        }
?>
<!--The statuses of each machine is displayed in the machine status page to show the maintenance staff the changes as they make it-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Machine Status</title>
    <!-- <link rel="stylesheet" href="../css/laundry.css"> -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
    <script src="../js/status.js"></script>
</head>
<body>
    <div class="machineDisplayer">
        <form class="machineDisplay" method="post" onsubmit="machineStatusChange(event)">
            <select name="machine" id="machineSelect">
                <Option value="Machine 1">
                    Machine 1
                </Option>
                <option value="Machine 2">
                    Machine 2
                </option>
                <option value="Machine 3">
                    Machine 3
                </option>
                <option value="Machine 4">
                    Machine 4
                </option>
                <option value="Machine 5">
                    Machine 5
                </option>
            </select>
            <?php for($machine = 1; $machine <= 5; $machine++):
                    $isAvailable = ($reservations["Machine $machine"] == 1)         
                ?>
                <div class="Machine<?=$isAvailable ? " Available" : ""?>">
                    <img src="<?=$isAvailable ? "../img/washing.png" : "../img/washingred.png"?>" alt="Laundry washing" id="machine">
                    <span>Machine <?=$machine;?></span>
                </div>
            <?php endfor;?>
            <button type="submit" class="machineButtons">Toggle Availability</button>
        </form>
        <form class="machineDisplay" method="post" onsubmit="machineStatusChange(event)">
            <select name="machine" id="machineSelect">
                <Option value="Machine 6">
                    Machine 6
                </Option>
                <option value="Machine 7">
                    Machine 7
                </option>
                <option value="Machine 8">
                    Machine 8
                </option>
                <option value="Machine 9">
                    Machine 9
                </option>
                <option value="Machine 10">
                    Machine 10
                </option>
            </select>
            <?php for($machine = 6; $machine <= 10; $machine++):
                    $isAvailable = ($reservations["Machine $machine"] == 1)         
            ?>
                <div class="Machine">
                    <img src="<?=$isAvailable ? "../img/washing.png" : "../img/washingred.png"?>" alt="Laundry washing" id="machine">
                    <span>Machine <?=$machine;?></span>
                </div>
            <?php endfor;?>
            <button type="submit"  class="machineButtons">Toggle Availability</button>
        </form>
    </div>
    <footer class="footer">
    <div style="text-align: center;">
      <div class="col-xl-6 m-auto text-center">
        <div class="copyright">
          <p>&copy; UniFresh Laundry Xpress 2024. All rights reserved.</p>
        </div>
      </div>
    </div>
  </footer>
    <script src="../js/status.js"></script>
</body>
</html>