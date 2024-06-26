<?php

  session_start();
  include "../Backend/RoleManagementData.php";
  require_once ("../storage/sql_connect.php");

  $username = $_SESSION['userName']; //get the usertype as well as firstname and lastname of current user

  $userInfo = getTyped($mysqli,$username);
 
  $_SESSION['id'] = $userInfo['id'];
 
 

  $user = 'SELECT *FROM dorm';

  
  // $userInfo = getTyped($mysqli, $username);//using the sql query from role management data file to get the user's username.


  if($result = mysqli_query($mysqli, $user)){
      if(mysqli_num_rows($result) > 0){
      while($row = mysqli_fetch_array($result)){
        if ($userInfo['id']== $row['username']){
          $id =$row['username'];
          $fname = $row['firstname'];
          $mname = $row['middlename'];
          $lname = $row['lastname'];
          $dob = $row['dateofbirth'];
          $gender = $row['gender'];
          $email = $row['email'];
          $primary = $row['primarynum'];
          $secondary = $row['secondarynum'];
          $hall = $row['hall'];
          $block = $row['block'];
          $aptnum = $row['aptnum'];
          $about = $row['about'];
        }
      }
      mysqli_free_result($result);
    } else{
      echo " ";
  }
  } else{
  echo "ERROR: Could not able to execute $sql. " . mysqli_error($mysqli);
  }



?>


<!DOCTYPE html>
<html lang="en">
<head>
     <link rel="stylesheet" href="../css/viewprofile.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    
</head>
<main>
<body>
  <div class="main-content">
    <!-- Top navbar -->
    <nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
      <div class="container-fluid">
    
        <!-- User -->
        <!-- <ul class="navbar-nav align-items-center d-none d-md-flex">
          <li class="nav-item dropdown">
            <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <div class="media align-items-center">
                
                <div class="media-body ml-2 d-none d-lg-block">
                  <span class="mb-0 text-sm  font-weight-bold"></span>
                </div>
              </div>
            </a>
             <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
              <div class=" dropdown-header noti-title">
                <h6 class="text-overflow m-0">Welcome!</h6>
              </div>
              <a href="../examples/profile.html" class="dropdown-item">
                <i class="ni ni-single-02"></i>
                <span>My profile</span>
              </a>
              <a href="../examples/profile.html" class="dropdown-item">
                <i class="ni ni-settings-gear-65"></i>
                <span>Settings</span>
              </a>
              <a href="../examples/profile.html" class="dropdown-item">
                <i class="ni ni-calendar-grid-58"></i>
                <span>Activity</span>
              </a>
              <a href="../examples/profile.html" class="dropdown-item">
                <i class="ni ni-support-16"></i>
                <span>Support</span>
              </a>
              <div class="dropdown-divider"></div>
              <a href="#!" class="dropdown-item">
                <i class="ni ni-user-run"></i>
                <span>Logout</span>
              </a>
            </div> 
          </li>
        </ul> -->
      </div>
    </nav>
    <!-- Header -->
    <div class="header pb-8 pt-5 pt-lg-8 d-flex align-items-center" style="min-height: 600px; background-image: url(../img/background-profile.jpeg); background-size: cover; background-position: center top;">
      <!-- Mask -->
      <span class="mask bg-gradient-default opacity-8"></span>
      <!-- Header container -->
      <div class="container-fluid d-flex align-items-center">
        <div class="row">
          <div class="col-lg-7 col-md-10" style="width: 800px;">
          <?php
            

              if($userInfo['type']=="resident"):
                echo "<h1 class='display-2 text-white';>Hello ". $userInfo['first'] . " " . $userInfo['last']."</h1>";
              elseif ($userInfo['type']=="staff"):
                echo "<h1 class='display-2 text-white'>Hello ". $userInfo['first'] . " " . $userInfo['last']."</h1>";
              endif;?>
            
            <p class="text-white mt-0 mb-5">This is your profile. You can view your personal information below</p>
            <a href="javascript:void(0)" onclick="Update()" class="btn btn-info">Edit profile</a>

          </div>
        </div>
      </div>
    </div>
    <!-- Page content -->
    <div class="container-fluid mt--7">
      <div class="row">
        <div class="col-xl-4 order-xl-2 mb-5 mb-xl-0">
          <!--<div class="card card-profile shadow">
            <div class="row justify-content-center">
              <div class="col-lg-3 order-lg-2">
                <div class="card-profile-image">
                  <a href="#">
                    <img src="/img/profile.png" class="rounded-circle">
                  </a>
                </div>
              </div>
            </div>
            <div class="card-header text-center border-0 pt-8 pt-md-4 pb-0 pb-md-4">
              <div class="d-flex justify-content-between">
                <a href="Account.html" class="btn btn-sm btn-info mr-4">My Account</a>
                <a href="login.html" class="btn btn-sm btn-default float-right">Log Out</a>
              </div>
            </div>
            <div class="card-body pt-0 pt-md-4">
              <div class="row">
                <div class="col">
                  <div class="card-profile-stats d-flex justify-content-center mt-md-5">
                    <div>
                      <span class="heading"></span>
                      <span class="description"> </span>
                      <span class="heading">
                        NOTIFICATIONS
                        <button type="button" class="icon-button">
                            <span class="icon-button__badge">2</span>
                        </button>
                    </span>
                      <hr class="my-4">
                      <span class="description"> [Check your messages/No new messages]</span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="text-center">
                <h3>
                  Notification: <span class="font-weight-light">[insert notif details]</span>
                </h3>
                <div class="h5 font-weight-300">
                  <i class="ni location_pin mr-2"></i>[DATE/TIME]
                </div>
                <hr class="my-4">
                <a href="#">Show more notifications</a>
                <a href="#">Go to Forum</a>
              </div>
            </div>
          </div>-->
        </div>
        <div class="col-xl-8 order-xl-1" style="justify-content: center;">
          <div class="card bg-secondary shadow">
            <div class="card-header bg-white border-0">
              <div class="row align-items-center">
                <div class="col-8">
                  <h3 class="mb-0">My account</h3>
                </div>
              </div>
            </div>
            <div class="card-body">
              <form>
                <h6 class="heading-small text-muted mb-4">User information</h6>
                <div class="pl-lg-4">
                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group focused">
                        <label class="form-control-label" for="input-username">School ID Number:</label>
                        <input type="number" id="input-username" class="form-control form-control-alternative" minlength="9" maxlength="9" pattern = "[0-9]{9}"value="<?php echo $id; ?>" readonly>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-control-label" for="input-email">Email address</label>
                        <input type="email" id="input-email" class="form-control form-control-alternative" value="<?php echo $email; ?>" readonly>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-6">
                    </div>
                    <div class="row">
                      <div class="col-lg-4">
                          <div class="form-group focused">
                              <label class="form-control-label" for="input-first-name">First name</label>
                              <input type="text" id="input-first-name" class="form-control form-control-alternative"readonly value= "<?php echo $fname; ?>">
                          </div>
                      </div>
                      <div class="col-lg-4">
                          <div class="form-group focused">
                              <label class="form-control-label" for="input-middle-name">Middle name</label>
                              <input type="text" id="input-middle-name" class="form-control form-control-alternative" value="<?php echo $mname;?>" readonly>
                          </div>
                      </div>
                      <div class="col-lg-4">
                          <div class="form-group focused">
                              <label class="form-control-label" for="input-last-name">Last name</label>
                              <input type="text" id="input-last-name" class="form-control form-control-alternative" value="<?php echo $lname;?>" readonly>
                          </div>
                      </div>
                  </div>
                  
                    <div class="col-lg-6">
                        <div class="form-group focused">
                          <label class="form-control-label" for="input-last-name">Date of Birth</label>
                          <input type="date" id="input-date-birth" class="form-control form-control-alternative" min ="2006-01-01" max= "1924-12-31" value="<?php echo $dob; ?>" readonly>
                        </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group focused">
                          <label class="form-control-label" for="input-gender">Gender</label>
                          <select id="input-gender" class="form-control form-control-alternative" readonly>
                
                              <option value=""><?php echo $gender; ?></option>
              
                          </select>
                      </div>
                  </div>
                      
                  </div>
                </div>
                <hr class="my-4">
                <!-- Address -->
                <h6 class="heading-small text-muted mb-4">Contact information</h6>
                <div class="pl-lg-4">
                   <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-control-label" for="input-country">Phone Number (Primary)</label>
                        <input type="tel" id="input-postal-code" class="form-control form-control-alternative" pattern="[0-9]{1}-[0-9]{3}-[0-9]{3}-[0-9]{4}" value ="<?php echo $primary?>" readonly>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-control-label" for="input-country">Phone Number (Secondary)</label>
                        <input type="tel" id="input-postal-code" class="form-control form-control-alternative" pattern="[0-9]{1}-[0-9]{3}-[0-9]{3}-[0-9]{4}" value="<?php echo $secondary?>" readonly>
                      </div>
                    </div>
                  </div>
                </div>
                <br>
                  <hr class="my-4">
                  <br>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group focused">
                        <h6 class="heading-small text-muted mb-4">Address</h6>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-4">
                      <div class="form-group focused">
                        <label class="form-control-label" for="input-city">Hall of Residence</label>
                        <input type="text" id="input-city" class="form-control form-control-alternative" value="<?php echo $hall?>" readonly>
                      </div>
                    </div>
                    <div class="col-lg-4">
                      <div class="form-group focused">
                        <label class="form-control-label" for="input-country">Block Name</label>
                        <input type="text" id="input-country" class="form-control form-control-alternative" value="<?php echo $block?>" readonly>
                      </div>
                    </div>
                    <div class="col-lg-4">
                      <div class="form-group">
                        <label class="form-control-label" for="input-country">Apartment Number</label>
                        <input type="text" id="input-postal-code" class="form-control form-control-alternative" value="<?php echo $aptnum ?>" readonly>
                      </div>
                    </div>
                  </div>
                </div>
                <hr class="my-4">
                <!-- Description -->
                <h6 class="heading-small text-muted mb-4">About me</h6>
                <div class="pl-lg-4">
                  <div class="form-group focused">
                    <label>About Me</label>
                    <textarea rows="4" class="form-control form-control-alternative" value="<?php echo $about?>" readonly></textarea>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div id="overview">
  
  </div>
  <footer class="footer">
    <div class="row align-items-center justify-content-xl-between">
      <div class="col-xl-6 m-auto text-center">
        <div class="copyright">
          <p>&copy; UniFresh Laundry Xpress 2024. All rights reserved.</p>
        </div>
      </div>
    </div>
  </footer>
</body>
<script src="../js/profile.js"></script>
</main>