<!-- Page Header -->
<div class="page-header row no-gutters py-4">
  <div class="col">
    <span class="text-uppercase page-subtitle">Personnel</span>
    <h3 class="page-title">Sales Consultants</h3>
  </div>

  <div class="col d-flex">
    <div class="btn-group btn-group-sm d-inline-flex ml-auto my-auto" role="group" aria-label="Table row actions">

      <a style="    margin-top: 19px;
    width: 170px;
    height: 40px;
    text-align: center;font-size: 14px;" href="create_salesperson" class="btn btn-white active">
        + Create Consultant 1
      </a>

    </div>
  </div>
</div>

<form style="width: 100%;" action="/dealership/list_salesperson" method="POST" enctype="multipart/form-data">
  <div class="input-group input-group-seamless mb-4">
    <div class="input-group-prepend">
      <div class="input-group-text">
        <i class="fas fa-search"></i>
      </div>
    </div>
    <input name="keyword" onkeyup="findAgent(this)" class="navbar-search form-control" type="text" placeholder="First or last name" aria-label="Search">
  </div>
</form>

<script>
  function findAgent(e) {
    if (e.value.length > 3) {
      console.log(e.value);
    }
  }
</script>


<div class="row">
  <?php
  foreach ($agents as $agent) {
  ?>


    <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
      <div class="card card-small card-post card-post--1">

        <div style="min-height: 80px;    text-align: right;" class="card-post__image">

          <div class="dropdown">
            <button class="dropbtn">Dropdown</button>
            <div class="dropdown-content">
              <a href="#">Link 1</a>
              <a href="#">Link 2</a>
              <a href="#">Link 3</a>
            </div>
          </div>

          <a style="right: 15px;top: 15px;cursor:pointer">
            <span style="width: 40px;height: 40px;margin-top: 10px;margin-right: 15px;" class="material-icons">
              more_horiz
            </span>
          </a>




          <div class="card-post__author d-flex" style="text-align: center;
    margin: 0 auto;
    width: 100%;">

            <?php
            if ($agent->base64_img != "") {

            ?>
              <a href="#" class="card-post__author-avatar card-post__author-avatar--small" style="background-image: url('<?= $agent->base64_img ?>');width: 110px;height: 110px;
    text-align: center;
    margin: 0 auto;">Written by Anna Kunis</a>
            <?php
            } else {
            ?>
              <a href="#" class="card-post__author-avatar card-post__author-avatar--small" style="background-image: url('/assets/img/male-placeholder.jpg');    width: 110px;
    height: 110px;
    text-align: center;
    margin: 0 auto;">Written by Anna Kunis</a>


            <?php
            }
            ?>
          </div>
        </div>
        <div class="card-body" style="    padding-top: 75px;text-align: center;">
          <h5 class="card-title">
            <a class="text-fiord-blue" href="#"> <?= $agent->consultant_first_name ?> <?= $agent->consultant_last_name ?></a>
          </h5>
          <span style="color:#63676b!important;font-size: large;" class="text-muted">
            <?= $agent->sales_consultant_title ?></span><br>
          <span style="font-weight: 300;font-size: small;" class="text-muted">
            <!-- <span class="material-icons" style="font-size: 15px;
    transform: translateY(2px);">
                  email
                </span> --><?= $agent->consultant_email ?>
          </span>
        </div>
        <div style="display:none!important" class="card-footer border-top d-flex">
          <div style="    width: 100%;" class="my-auto ml-auto">
            <!--  href="edit_salesperson/<?= $agent->id_consultant ?>" -->
            <a href="edit_salesperson/<?= $agent->id_consultant ?>" style="width: 100%;" class="btn btn-sm btn-white">
              <i class="material-icons">visibility</i> View </a>
          </div>
        </div>
      </div>
    </div>

  <?php
  }
  ?>

</div>
<div>
</div>

<script>
  function goEdit(id) {
    window.location.replace("/Maxautoadmin/editdealership/" + id);
  }

  function inactive(id) {
    Swal.fire({
      title: 'Are you sure?',
      text: "Dealership will be desactivated!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, desactivated!'
    }).then((result) => {
      if (result.isConfirmed) {

        $.ajax({
            url: '/Maxautoadmin/changeStatusDealership/' + id + '/1',
            type: 'POST',
          })
          .done(function(response) {
            location.reload();
          })


      }
    })
  }

  function activate(id) {
    $.ajax({
        url: '/Maxautoadmin/changeStatusDealership/' + id + '/0',
        type: 'POST',
      })
      .done(function(response) {
        location.reload();
      })

  }
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<style>
  /* Dropdown Button */
  .dropbtn {
    background-color: #4CAF50;
    color: white;
    padding: 16px;
    font-size: 16px;
    border: none;
  }

  /* The container <div> - needed to position the dropdown content */
  .dropdown {
    position: relative;
    display: inline-block;
  }

  /* Dropdown Content (Hidden by Default) */
  .dropdown-content {
    display: none;
    position: absolute;
    background-color: #f1f1f1;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
    z-index: 1;
  }

  /* Links inside the dropdown */
  .dropdown-content a {
    color: black;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
  }

  /* Change color of dropdown links on hover */
  .dropdown-content a:hover {
    background-color: #ddd;
  }

  /* Show the dropdown menu on hover */
  .dropdown:hover .dropdown-content {
    display: block;
  }

  /* Change the background color of the dropdown button when the dropdown content is shown */
  .dropdown:hover .dropbtn {
    background-color: #3e8e41;
  }
</style>