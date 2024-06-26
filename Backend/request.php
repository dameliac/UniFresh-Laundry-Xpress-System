<?php

// Create connection
require_once("../storage/sql_connect.php");

// Fetch data from the REQUEST table
$sql = "SELECT * FROM requests ORDER BY submissionTime DESC"; 
$result = $mysqli ->query($sql);


date_default_timezone_set('America/Jamaica');
$submissionTime = date('Y-m-d H:i:s');
$image = $_FILES["evidence"];

?>

<!-- Display images with BLOB data from database -->
<?php if($result->num_rows > 0){ ?> 
        <?php while($row = $result->fetch_assoc()){ ?> 
            <img src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode($row['image']); ?>" /> 
        <?php } ?> 
    </div> 
<?php }else{ ?> 
    <p class="status error">Image not found...</p> 
<?php }

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MAINTENANCE REQUEST</title>
    <script src="request.js"></script>
    <link rel="stylesheet" href="requestview.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <h1>MAINTENANCE REPORTS</h1>
    <table id="reportTable">
        <thead>
            <tr>
                <th>Date</th>
                <th>Machine</th>
                <th>Problem Type</th>
                <th>Description</th>
                <th>Details</th>
                <th>Call</th>
            </tr>
        </thead>
        <tbody id="reportBody">
            <?php
            if ($result->num_rows > 0) {
                // Output data of each row
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["submissionTime"] . "</td>";
                    echo "<td>" . $row["machine"] . "</td>";
                    echo "<td>" . $row["problem"] . "</td>";
                    echo "<td>" . $row["description"] . "</td>";
                    echo "<td><a href='#' class='details-link'>View Details</a></td>";
                    echo "<td><button class='call-button'><i class='fas fa-phone icon fa-flip-horizontal' style='font-size:24px;color:blue'></i></button></td>";
                    echo "</tr>";
                }
            } else {
                echo "0 results";
            }
            $mysqli->close();
            ?>
        </tbody>
    </table> 
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span id="closeModal" class="close">&times;</span>
            <div id="modalContent"></div>
        </div>
    </div>
    <div id="modalBackdrop" class="modal-backdrop"></div>    
</body>
</html>
