<!doctype html>
<html class="no-js h-100" lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Max Auto Admin</title>
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


        <aside class="main-sidebar col-12 col-md-3 col-lg-2 px-0">
          <div class="main-navbar">
            <nav class="navbar align-items-stretch navbar-light bg-white flex-md-nowrap border-bottom p-0">
              <a class="navbar-brand w-100 mr-0" href="#" style="line-height: 25px;">
                <div class="d-table m-auto">
                  <img id="main-logo" class="d-inline-block align-top mr-1" style="max-width: 125px;" src="/assets/img/logoblue.png" alt="Shards Dashboard">

                </div>
              </a>
              <a class="toggle-sidebar d-sm-inline d-md-none d-lg-none">
                <i class="material-icons">&#xE5C4;</i>
              </a>
            </nav>
          </div>
          <form action="#" class="main-sidebar__search w-100 border-right d-sm-flex d-md-none d-lg-none">

          </form>
          <div class="nav-wrapper">
            <ul class="nav flex-column">
              <li class="nav-item">
                <a class="nav-link <?php if ($page == "dashboard") echo "active"; ?>" href="/Maxautoadmin/dashboarda">
                  <i class="material-icons">dashboard</i>
                  <span>Dashboard</span>
                </a>
              </li>
            </ul>
            <h6 class="main-sidebar__nav-title">Business Group</h6>
            <ul class="nav flex-column">
              <li class="nav-item">
                <a class="nav-link <?php if ($page == "buss") echo "active"; ?>" href="/Maxautoadmin/businessgroup">
                  <i class="material-icons">business</i>
                  <span>Create Business</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?php if ($page == "busslist") echo "active"; ?>" href="/Maxautoadmin/businesslist">
                  <i class="material-icons">list</i>
                  <span>Business List</span>
                </a>
              </li>
            </ul>
            <h6 class="main-sidebar__nav-title">Dealership</h6>
            <ul class="nav flex-column">
              <li class="nav-item">
                <a class="nav-link <?php if ($page == "dealership") echo "active"; ?>" href="/Maxautoadmin/dealership">
                  <i class="material-icons">store</i>
                  <span>Create Dealership</span>
                </a>
              </li>
              <li class="nav-item">
                <a href="/Maxautoadmin/listdealership" class="nav-link <?php if ($page == "list") echo "active"; ?>">
                  <i class="material-icons">list</i>
                  <span>Dealership List</span>
                </a>
              </li>
            </ul>
            <h6 class="main-sidebar__nav-title">Subscriptions</h6>
            <ul class="nav flex-column">
              <li class="nav-item">
                <a class="nav-link <?php if ($page == "product") echo "active";  ?>" href="/Maxautoadmin/products">
                  <i class="material-icons">inventory_2</i>
                  <span>Subscription Types</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?php if ($page == "subs") echo "active";  ?>" href="/Maxautoadmin/subscription">
                  <i class="material-icons">request_quote</i>
                  <span>Create Subscription</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?php if ($page == "sublist") echo "active";  ?>" href="/Maxautoadmin/subscriptionlist">
                  <i class="material-icons">list</i>
                  <span>Subscription List</span>
                </a>
              </li>
            </ul>
            <h6 class="main-sidebar__nav-title">API</h6>
            <ul class="nav flex-column">
              <li class="nav-item">
                <a class="nav-link <?php if ($page == "api") echo "active"; ?>" href="/Maxautoadmin/apimanagement">
                  <i class="material-icons">vpn_key</i>
                  <span>APIs Management</span>
                </a>
              </li>
            </ul>

            <h6 class="main-sidebar__nav-title">APP</h6>
            <ul class="nav flex-column">
              <li class="nav-item">
                <a class="nav-link <?php if ($page == "app") echo "active"; ?>" href="/Maxautoadmin/appconfig">
                  <i class="material-icons">developer_mode</i>
                  <span>APP Settings</span>
                </a>
              </li>
            </ul>

            <h6 class="main-sidebar__nav-title">MESSAGES</h6>
            <ul class="nav flex-column">
              <li class="nav-item">
                <a class="nav-link <?php if ($page == "subs") echo "active";  ?>" href="/Maxautoadmin/messages">
                  <i class="material-icons">email</i>
                  <span>Messages</span>
                </a>
              </li>

              <li class="nav-item">
                <a class="nav-link <?php if ($page == "subs") echo "active";  ?>" href="/Maxautoadmin/contactus">
                  <i class="material-icons">email</i>
                  <span>Contact us</span>
                </a>
              </li>

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
          <main class="main-content col-lg-10 col-md-9 col-sm-12 p-0 offset-lg-1">
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
                        <i class="material-icons">&#xE7F4;</i>
                        <span class="badge badge-pill badge-danger">2</span>
                      </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-small" aria-labelledby="dropdownMenuLink">
                      <a class="dropdown-item" href="#">
                        <div class="notification__icon-wrapper">
                          <div class="notification__icon">
                            <i class="material-icons">&#xE6E1;</i>
                          </div>
                        </div>
                        <div class="notification__content">
                          <span class="notification__category">Analytics</span>
                          <p>Your website’s active users count increased by
                            <span class="text-success text-semibold">28%</span> in the last week. Great job!
                          </p>
                        </div>
                      </a>
                      <a class="dropdown-item" href="#">
                        <div class="notification__icon-wrapper">
                          <div class="notification__icon">
                            <i class="material-icons">&#xE8D1;</i>
                          </div>
                        </div>
                        <div class="notification__content">
                          <span class="notification__category">Sales</span>
                          <p>Last week your store’s sales count decreased by
                            <span class="text-danger text-semibold">5.52%</span>. It could have been worse!
                          </p>
                        </div>
                      </a>
                      <a class="dropdown-item notification__all text-center" href="#"> View all Notifications </a>
                    </div>
                  </li>
                  <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-nowrap px-3" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                      <img class="user-avatar rounded-circle mr-2" src="/assets/img/male-placeholder.jpg" alt="User Avatar">
                      <span class="d-none d-md-inline-block">Administrator</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-small">
                      <a class="dropdown-item" href="user-profile-lite.html">
                        <i class="material-icons">&#xE7FD;</i> Profile</a>
                      <a class="dropdown-item" href="components-blog-posts.html">
                        <i class="material-icons">vertical_split</i> Blog Posts</a>
                      <a class="dropdown-item" href="add-new-post.html">
                        <i class="material-icons">note_add</i> Add New Post</a>
                      <div class="dropdown-divider"></div>
                      <a class="dropdown-item text-danger" href="#">
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
                <strong>Success!</strong> Dealership has been updated!
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
          }
          ?>

          <!-- / .main-navbar -->
          <div class="main-content-container container-fluid px-4">
            <!-- Container -->