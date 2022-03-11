<!doctype html>
<html class="no-js h-100" lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Dealership Admin</title>
  <meta name="description" content="A high-quality &amp; free Bootstrap admin dashboard template pack that comes with lots of templates and components.">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link rel="stylesheet" id="main-stylesheet" data-version="1.1.0" href="/maxautoAdmin/styles/shards-dashboards.1.1.0.min.css">
  <link rel="stylesheet" href="/maxautoAdmin/styles/extras.1.1.0.min.css">
  <link rel="shortcut icon" type="image/x-icon" href="/assets/img/flaticon.png">
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.css" rel="stylesheet">


</head>

<body class="h-100">
  <div class="container-fluid">
    <div class="row">
      <!-- Main Sidebar -->

      <?php
      if ($page != "login") {
      ?>

        <form style="width: 100%;" action="/Dealership/changePassword/<?= $_SESSION["DEALERSHIP"] ?>" method="POST" enctype="multipart/form-data">
          <div class="modal fade" id="changePassword" tabindex="-1" role="dialog" aria-labelledby="changePassword" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Change Password</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">

                  <div class="form-group">
                    <label for="feLastName">New Password</label>
                    <div class="input-group">
                      <input onblur="checkRepeat()" id="npass" name="npass" type="password" class="form-control" aria-label="Amount (to the nearest dollar)">
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="feLastName">Confirm Password</label>
                    <div class="input-group">
                      <input onblur="checkRepeat()" id="rpass" name="rpass" type="password" class="form-control" aria-label="Amount (to the nearest dollar)">
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button id="saveaps" disabled type="submit" class="btn btn-primary">Save changes</button>
                </div>
              </div>
            </div>
          </div>
        </form>

        <aside class="main-sidebar col-12 col-md-3 col-lg-2 px-0">
          <div class="main-navbar">
            <nav class="navbar align-items-stretch navbar-light bg-white flex-md-nowrap  p-0">
              <a class="navbar-brand w-100 mr-0" href="#" style="line-height: 25px;">
                <div class="d-table m-auto">
                  <img id="main-logo" class="d-inline-block align-top mr-1 main-logo " style="max-width: 318px;margin-top:-5px;    width: 100%;
    height: auto;  position: absolute;left: -28px;
" src="/assets/img/logoAzul.png" alt="Shards Dashboard">

                </div>
              </a>
              <a class="toggle-sidebar d-sm-inline d-md-none d-lg-none">
                <i class="material-icons">&#xE5C4;</i>
              </a>
            </nav>
          </div>

          <div style="    margin-top: 40px;" class="nav-wrapper">
            <h6 style=" font-size: 13px;   color: #0e4e92;
   " class="main-sidebar__nav-title pb-5 pt-2">Dealership Hub</h6>
            <ul class="nav flex-column">
              <li class="nav-item">
                <a class="nav-link <?php if ($page == "dashboard") echo "active"; ?>" href="/dealership/dashboard">
                  <i style="color: #00000091;" class="material-icons">dashboard</i>
                  <span>Home</span>
                </a>
              </li>
            </ul>
            <h6 class="main-sidebar__nav-title">Vehicles</h6>
            <ul class="nav flex-column">
              <li class="nav-item">
                <a class="nav-link <?php if ($page == "dealership") echo "active"; ?>" href="/dealership/vehicle_list">
                  <i style="color: #00000091;" class="material-icons">directions_car</i>
                  <span>Vehicle Listings</span>
                </a>
              </li>

            </ul>
            <h6 class="main-sidebar__nav-title">Personnel</h6>
            <ul class="nav flex-column">
              <li class="nav-item">
              </li>
              <li class="nav-item">
                <a href="/dealership/list_salesperson" class="nav-link <?php if ($page == "listsales") echo "active"; ?>">
                  <i style="color: #00000091;" class="material-icons">person</i>
                  <span>Sales Consultants</span>
                </a>
              </li>
            </ul>

            <h6 class="main-sidebar__nav-title">SETTINGS</h6>
            <ul class="nav flex-column">
              <li class="nav-item">
                <a class="nav-link <?php if ($page == "editdealer") echo "active";  ?>" href="/dealership/edit_dealership">
                  <i style="color: #00000091;" class="material-icons">manage_accounts</i>
                  <span>Dealership Profile</span>
                </a>
              </li>
             <li class="nav-item">
                <a class="nav-link <?php if ($page == "businesshour") echo "active";  ?>" href="/dealership/business_hours">
                  <i style="color: #00000091;" class="material-icons">date_range</i>
                  <span>Business Hours</span>
                </a>
              </li> 
              <li class="nav-item">
                <a class="nav-link <?php if ($page == "sublist") echo "active";  ?>" href="/dealership/subscriptions">
                  <i style="color: #00000091;" class="material-icons">list</i>
                  <span>Subscription List</span>
                </a>
              </li>
            </ul>
            <h6 class="main-sidebar__nav-title">CONTACT</h6>
            <ul class="nav flex-column">
              <li class="nav-item">
                <a class="nav-link <?php if ($page == "subs") echo "active";  ?>" href="/dealership/tradein_list">
                  <i style="color: #00000091;" class="material-icons">request_page</i>
                  <span>Trade In</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?php if ($page == "subs") echo "active";  ?>" href="/dealership/messages">
                  <i style="color: #00000091;" class="material-icons">email</i>
                  <span>Messages</span>
                </a>
              </li>
              <!-- 
              <li class="nav-item">
                <a class="nav-link <?php if ($page == "subs") echo "active";  ?>" href="/dealership/messages">
                  
                  <span>Email us: info@autoape.co.nz</span>
                </a>
              </li>
 -->
            </ul>

            <!--
              href="listdealership"
              <li class="nav-item">
                <a class="nav-link " href="add-new-post.html">
                  <i class="material-icons">note_add</i>
                  <span>Add New Post</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link " href="form-components.html">
                  <i class="material-icons">view_module</i>
                  <span>Forms &amp; Components</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link " href="tables.html">
                  <i class="material-icons">table_chart</i>
                  <span>Tables</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link " href="user-profile-lite.html">
                  <i class="material-icons">person</i>
                  <span>User Profile</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link " href="errors.html">
                  <i class="material-icons">error</i>
                  <span>Errors</span>
                </a>
              </li> -->

          </div>
        </aside>
        <main class="main-content col-lg-10 col-md-9 col-sm-12 p-0 offset-lg-2 offset-md-3">
        <?php
      } else {
        ?>
          <main class="col-lg-12 col-md-9 col-sm-12 p-0 ">
          <?php
        }
          ?>
          <!-- End Main Sidebar -->


          <?php
          if ($page != "login") {
          ?>

            <div class="main-navbar sticky-top bg-white">
              <!-- Main Navbar -->
              <nav class="navbar align-items-stretch navbar-light flex-md-nowrap p-0">
                <form action="#" class="main-navbar__search w-100 d-none d-md-flex d-lg-flex">

                </form>
                <ul class="navbar-nav border-left flex-row ">
                  <li class="nav-item border-right dropdown notifications">
                    <a class="nav-link nav-link-icon text-center" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <div class="nav-link-icon__wrapper">
                        <i style="color: #00000091;" class="material-icons">&#xE7F4;</i>
                        <?php
                        if ($_SESSION["newMessages"] > 0) {
                        ?>
                          <span class="badge badge-pill badge-danger"><?= $_SESSION["newMessages"] ?></span>
                        <?php
                        } else {
                        }
                        ?>

                      </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-small" aria-labelledby="dropdownMenuLink">


                      <?php
                      $messages = $_SESSION["lastTwoMessages"];
                      ?>

                      <?php
                      foreach ($messages as $message) {
                      ?>


                        <div style="cursor: pointer;" onclick="viewMessage('<?= $message->message_id ?>')" class="dropdown-item">
                          <div class="notification__icon-wrapper">
                            <div class="notification__icon">

                              <?php if ($message->is_admin == 1) {
                              ?>
                                <img class="user-avatar rounded-circle mr-2" src="/assets/img/logomsg.png" alt="User Avatar">
                              <?php
                              } else { ?>
                                <img class="user-avatar rounded-circle mr-2" src="https://maxauto.s3-ap-southeast-2.amazonaws.com/maxauto/dealership/logo/<?= $message->img_base64 ?>" alt="User Avatar">
                              <?php } ?>
                            </div>
                          </div>
                          <div class="notification__content">
                            <span class="notification__category">Message</span>
                            <p><?= $message->subject ?> <span style="font-size: small;font-weight: 300;"><?= $message->message ?></span></p>
                            <small><?php echo date("D d/m", strtotime($message->indate)); ?></small>
                          </div>
                        </div>
                      <?php
                      }
                      ?>


                      <a class="dropdown-item notification__all text-center" href="/dealership/messages"> View all Messages </a>
                    </div>
                  </li>
                  <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-nowrap px-3" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                      <img class="user-avatar rounded-circle mr-2" src="https://maxauto.s3-ap-southeast-2.amazonaws.com/maxauto/dealership/logo/<?= $_SESSION["DEALER_LOGO"] ?>" alt="User Avatar">
                      <span class="d-none d-md-inline-block"><?= $_SESSION["DEALER_NAME"] ?></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-small">
                      <a class="dropdown-item" href="edit_dealership">
                        <i class="material-icons">&#xE7FD;</i> Profile</a>
                      <a href="#" class="dropdown-item" data-toggle="modal" data-target="#changePassword">
                        <i class="material-icons">&#xE7FD;</i> Change Password</a>
                      <a class="dropdown-item" href="list_vehicle">
                        <i class="material-icons">add_circle</i> Create Listing</a>

                      <div class="dropdown-divider"></div>
                      <a href="/dealership/logout" class="dropdown-item text-danger">
                        <i class="material-icons text-danger">&#xE879;</i> Logout </a>
                    </div>
                  </li>
                </ul>
                <nav class="nav">
                  <a href="#" class="nav-link nav-link-icon toggle-sidebar d-md-inline d-lg-none text-center border-left" data-toggle="collapse" data-target=".header-navbar" aria-expanded="false" aria-controls="header-navbar">
                    <i class="material-icons">&#xE5D2;</i>
                  </a>
                </nav>
              </nav>
            </div>
          <?php
          }
          ?>

          <?php

          if (strpos($_SERVER['REQUEST_URI'], "updatec=ok") !== false) {
          ?>
            <div class="alert alert-success alert-dismissible fade show mb-0" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
              <i class="fa fa-check mx-2"></i>
              <strong>Success!</strong> Configuration has been updated!
            </div>
          <?php
          }
          ?>


          <?php

          if (strpos($_SERVER['REQUEST_URI'], "update=ok") !== false) {
          ?>
            <div class="alert alert-success alert-dismissible fade show mb-0" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
              <i class="fa fa-check mx-2"></i>
              <strong>Success!</strong> Details have been updated!
            </div>
          <?php
          }
          ?>

          <?php
          if (strpos($_SERVER['REQUEST_URI'], "log=error") !== false) {
          ?>
            <div class="alert alert-danger alert-dismissible fade show mb-0" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
              <i class="fa fa-exclamation-triangle mx-2"></i>
              <strong>Error!</strong> Something is wrong with yours credentials!
            </div>
          <?php
          }
          ?>

          <?php
          if (strpos($_SERVER['REQUEST_URI'], "log=nomember") !== false) {
          ?>
            <div class="alert alert-danger alert-dismissible fade show mb-0" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
              <i class="fa fa-exclamation-triangle mx-2"></i>
              <strong>Error!</strong> This account is no longer available.
            </div>
          <?php
          }
          ?>

          <?php
          if (strpos($_SERVER['REQUEST_URI'], "create=ok") !== false) {
          ?>
            <div class="alert alert-success alert-dismissible fade show mb-0" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
              <i class="fa fa-check mx-2"></i>
              <strong>Success!</strong> Salesperson has been created!
            </div>
          <?php
          }
          ?>

          <?php
          if (strpos($_SERVER['REQUEST_URI'], "pass=oks") !== false) {
          ?>
            <div class="alert alert-success alert-dismissible fade show mb-0" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
              <i class="fa fa-check mx-2"></i>
              <strong>Success!</strong> Password has been changed!
            </div>
          <?php
          }
          ?>

          <script>
            function viewMessage(id) {
              window.location.href = "/dealership/viewmessage/" + id;
            }
          </script>

          <!-- / .main-navbar -->
          <div class="main-content-container container-fluid px-4">
            <!-- Container -->




            <script>
              function checkRepeat() {
                var npass = document.getElementById('npass');
                var rpass = document.getElementById('rpass');

                if (rpass.value != npass.value) {
                  document.getElementById("saveaps").disabled = true;
                } else {
                  document.getElementById("saveaps").disabled = false;
                }

              }
            </script>