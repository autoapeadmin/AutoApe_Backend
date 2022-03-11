<!-- Page Header -->

<?php
foreach ($agents as $agent) {
?>

  <div style="margin-top:100px" class="modal fade" id="modal<?= $agent->id_consultant ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Remove <?= $agent->consultant_first_name ?> <?= $agent->consultant_last_name ?></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <span style="    font-weight: 300;">Are you sure you want to remove <?= $agent->consultant_first_name ?> <?= $agent->consultant_last_name ?>? </span>
        </div>
        <div style="padding-top: 0px;border-top: 0px solid #dfe1e3;" class="modal-footer">
          <a style="background-color: white;color:#0c4e91" data-dismiss="modal" class="btn btn-accent">Cancel</a>
          <a href="changeStatusSales/<?= $agent->id_consultant ?>/0 " type="button" class="btn btn-primary">Confirm</a>
        </div>
      </div>
    </div>
  </div>
<?php
}
?>

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
        + Create Consultant
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


    <div class="col-lg-3 col-md-6 col-sm-12 mb-4 pl-5 pr-5">
      <div style="white-space: nowrap;" class="card card-small card-post card-post--1">

        <div style="min-height: 80px;    text-align: right;" class="card-post__image">

          <div class="dropdown">
            <div id="dp<?= $agent->id_consultant ?>" style="font-size: large;font-weight: 300;text-align: left;" class="dropdown-content">
              <a href="edit_salesperson/<?= $agent->id_consultant ?>"><span style="transform: translateY(5px);color:#636363" class="material-icons">perm_identity</span>View Profile</a>
              <a data-toggle="modal" data-target="#modal<?= $agent->id_consultant ?>"><span style="transform: translateY(5px);color:#636363" class="material-icons">delete</span>Remove</a>
            </div>
          </div>

          <a onclick="viewMenu(<?= $agent->id_consultant ?>)" style="right: 15px;top: 15px;cursor:pointer">
            <span style="width: 40px;height: 40px;margin-top: 10px;margin-right: 15px;" class="material-icons">
              more_horiz
            </span>
          </a>

          <div class="card-post__author d-flex" style="text-align: center;margin: 0 auto;width: 100%;">

            <?php
            if ($agent->base64_img != "") {
            ?>
              <a href="#" class="card-post__author-avatar card-post__author-avatar--small" style="background-image: url('https://maxauto.s3-ap-southeast-2.amazonaws.com/maxauto/dealership/logo/<?= $agent->base64_img ?>');width: 110px;height: 110px;
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
        <div class="card-body" style="padding-top: 75px;text-align: center;text-overflow: ellipsis;overflow: hidden;">
          <span style="font-size: 21px;" class="card-title">
            <?= $agent->consultant_first_name ?> <?= $agent->consultant_last_name ?>
          </span><br>
          <span style="color:#63676bb0!important;font-size: medium;font-weight: 400;" class="text-muted">
            <?= $agent->sales_consultant_title ?></span><br>
          <a href="mailto:<?= $agent->consultant_email ?>" target="_blank" rel="noopener noreferrer" style="font-weight: 300;font-size: small;" class="text-muted">
            <!-- <span class="material-icons" style="font-size: 15px;
    transform: translateY(2px);">
                  email
                </span> --><?= $agent->consultant_email ?>
          </a>
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
            url: '/Dealership/changeStatusSales/' + id + '/1',
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