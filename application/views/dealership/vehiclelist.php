<!-- Page Header -->
<div class="page-header row no-gutters py-4">
  <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
    <span class="text-uppercase page-subtitle">Vehicles</span>
    <h3 class="page-title">Vehicle Listings</h3>
  </div>
  <div class="col d-flex">
    <div class="btn-group btn-group-sm d-inline-flex ml-auto my-auto" role="group" aria-label="Table row actions">

      <?php if (isset($subscriptiondata)) {
        if ($subscriptiondata[0]->max_listing_monthly <= $total_month) {
      ?>
          <a onclick="alert('Maximun per month reached')" style="margin-top: 19px;
    width: 140px;
    background-color:#114e92d1;
    height: 40px;
    text-align: center;font-size: 14px;"  class="btn btn-white active">
            + Create Listing
          </a>
        <?php
        } else {
        ?>
          <a style="    margin-top: 19px;
    width: 140px;
    height: 40px;
    text-align: center;font-size: 14px;" href="list_vehicle" class="btn btn-white active">
            + Create Listing
          </a>
      <?php
        }
      } else {
        print_r("No subscription");
      }  ?>



    </div>

  </div>
</div>



<div class="row">
  <div class="col">
    <div class="card card-small mb-4">
      <div class="card-header border-bottom">
        <h6 class="m-0">Listings Managament</h6>
        <small>Total listed this month: <?= $total_month ?></small>


      </div>
      <div style="padding-bottom:0px!important" class="card-body p-0 pb-3 text-center dataTables_wrapper no-footer">

        <div class="btn-group btn-group-sm btn-group-toggle d-flex my-auto mx-auto mx-sm-0" style="background-color: white;padding: 14px;" data-toggle="buttons">
          <label onclick="changeTable1();" class="btn btn-white active">
            <input type="radio" name="options" id="option1" autocomplete="off"> Active Listings </label>
          <label onclick="changeTable3();" class="btn btn-white">
            <input type="radio" name="options" id="option2" autocomplete="off"> Sold Listings </label>
          <label onclick="changeTable2();" class="btn btn-white">
            <input type="radio" name="options" id="option3" autocomplete="off"> Withdrawn </label>



        </div>

        <div id="divTable1">

          <table id="activeTable" class="transaction-history d-none dataTable no-footer dtr-inline">
            <thead class="bg-light">
              <tr>
                <!-- <th scope="col" class="border-0">#</th> -->
                <th scope="col" class="border-0">Listed on</th>
                <th scope="col" class="border-0">Photo</th>
                <th scope="col" class="border-0">Rego</th>
            
                <th scope="col" class="border-0">Make</th>
                <th scope="col" class="border-0">Model</th>
                <th scope="col" class="border-0">Year</th>
                <th scope="col" class="border-0">Price</th>
               
                <th scope="col" class="border-0">Actions</th>
              </tr>
            </thead>
            <tbody style="    font-weight: 800;">
              <?php
              foreach ($vehicles as $vehicle) {
              ?>
                <tr>
                  <!-- <td><?= $vehicle->vehicule_id ?></td> -->
                  <td><?php echo date("d/m/y", strtotime($vehicle->indate)); ?></td>
                  <td><img class="imglistveh" style="border-radius: 4px;    width: 110px;
    height: 70px;
" src="<?php if (property_exists($vehicle, "pic_url")) {
                  echo $vehicle->pic_url;
                }  ?>" /></td>
                  <td style="color:#104e92"><?= $vehicle->vehicule_rego ?></td>
                  
                  <td><?= $vehicle->make_description ?></td>
                  <td><?= $vehicle->model_desc ?></td>
                  <td><?= $vehicle->vehicule_year ?></td>
                  <td>$<?= $vehicle->vehicule_price ?></td>
                
                  <td>
                    <div class="btn-group btn-group-sm" role="group" aria-label="Table row actions">
                      <button onclick="goEdit(<?= $vehicle->vehicule_id ?>)" type="button" class="btn btn-white">
                        Edit
                      </button>
                      <!-- onclick="inactive(<?= $vehicle->vehicule_id ?>)" -->
                      <button onclick="inactive(<?= $vehicle->vehicule_id ?>)" type="button" class="btn btn-white">
                        Withdraw
                      </button>
                      <!-- data-toggle="modal" data-target="#modal<?= $vehicle->vehicule_id ?>" -->
                      <button data-toggle="modal" data-target="#modal<?= $vehicle->vehicule_id ?>" type="button" class="btn btn-white">
                        Sold
                      </button>
                    </div>
                  </td>
                </tr>

              <?php
              }
              ?>
            </tbody>
          </table>
        </div>

        <div style="display: none
        ;" id="divTable2">
          <table id="activeTable2" class="transaction-history d-none dataTable no-footer dtr-inline">
            <thead class="bg-light">
              <tr>
                <th scope="col" class="border-0">#</th>
                <th scope="col" class="border-0">Photo</th>
                <th scope="col" class="border-0">Rego</th>
                <th scope="col" class="border-0">Year</th>
                <th scope="col" class="border-0">Make</th>
                <th scope="col" class="border-0">Model</th>
                <th scope="col" class="border-0">Price</th>
                <th scope="col" class="border-0">Withdrawn on</th>

                <th scope="col" class="border-0">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach ($vehicles_delete as $vehicle) {
              ?>
                <tr>
                  <td><?= $vehicle->vehicule_id ?></td>
                  <td><img class="imglistveh" style="border-radius: 4px;    width: 70px;
    height: 50px;
" src="<?php if (property_exists($vehicle, "pic_url")) {
                  echo $vehicle->pic_url;
                }  ?>" /></td>
                  <td style="color:#104e92"><?= $vehicle->vehicule_rego ?></td>
                  <td><?= $vehicle->vehicule_year ?></td>
                  <td><?= $vehicle->make_description ?></td>
                  <td><?= $vehicle->model_desc ?></td>
                  <td>$<?= $vehicle->vehicule_price ?></td>

                  <td><?php echo date("d/m/y", strtotime($vehicle->indate)); ?></td>
                  <td>
                    <div class="btn-group btn-group-sm" role="group" aria-label="Table row actions">
                      <button onclick="goEdit(<?= $vehicle->vehicule_id ?>)" type="button" class="btn btn-white">
                        Edit
                      </button>
                      <button onclick="activate(<?= $vehicle->vehicule_id ?>)" type="button" class="btn btn-white">
                        Relist
                      </button>
                    </div>
                  </td>
                </tr>

              <?php
              }
              ?>
            </tbody>
          </table>
        </div>

        <div style="display: none
        ;" id="divTable3">
          <table id="activeTable3" class="transaction-history d-none dataTable no-footer dtr-inline">
            <thead class="bg-light">
              <tr>
                <th scope="col" class="border-0">#</th>
                <th scope="col" class="border-0">Photo</th>
                <th scope="col" class="border-0">Rego</th>

                <th scope="col" class="border-0">Make</th>
                <th scope="col" class="border-0">Model</th>
                <th scope="col" class="border-0">Price</th>
                <th scope="col" class="border-0">Listed on</th>
                <th scope="col" class="border-0">Sold on</th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach ($vehicles_sold as $vehicle) {
              ?>
                <tr>
                  <td><?= $vehicle->vehicule_id ?></td>
                  <td><img class="imglistveh" style="border-radius: 4px;    width: 70px;
    height: 50px;
" src="<?php if (property_exists($vehicle, "pic_url")) {
                  echo $vehicle->pic_url;
                }  ?>" /></td>
                  <td style="color:#104e92"><?= $vehicle->vehicule_rego ?></td>

                  <td><?= $vehicle->make_description ?></td>
                  <td><?= $vehicle->model_desc ?></td>
                  <td>$<?= $vehicle->vehicule_price ?></td>
                  <td><?php echo date("d/m/y", strtotime($vehicle->indate)); ?></td>
                  <td><?php echo date("d/m/y", strtotime($vehicle->indate)); ?></td>
                </tr>

              <?php
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>


<div>

</div>


<?php
foreach ($vehicles as $vehicle) {
?>
  <form style="width: 100%;" action="/dealership/soldVehicle/<?= $vehicle->vehicule_id ?>" method="POST" enctype="multipart/form-data">
    <!-- Modal -->
    <div class="modal fade" id="modal<?= $vehicle->vehicule_id ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Sold <?= $vehicle->make_description ?> <?= $vehicle->model_desc ?> </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12 col-md-12">
                <div class="form-group">
                  <label for="feLastName">Price</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">$</span>
                    </div>
                    <input name="price" type="text" class="form-control" aria-label="Amount (to the nearest dollar)" value="<?= $vehicle->vehicule_price ?>">
                  </div>
                </div>
                <div class="form-group">
                  <label for="feLastName">Reference number</label>
                  <div class="input-group">
                    <input name="refnumber" type="text" class="form-control">
                  </div>
                </div>
                <div class="form-group">
                  <label for="feLastName">Salesperson</label>
                  <div class="input-group">
                    <select name="salesperson" class="custom-select">
                      <?php foreach ($agents as $agent) { ?>
                        <option value="<?= $agent->id_consultant ?>"><?= $agent->consultant_first_name ?> <?= $agent->consultant_last_name ?></option>
                      <?php } ?>

                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="feLastName">Notes</label>
                  <div class="input-group">
                    <textarea class="form-control" id="desc" name="desc" rows="5"></textarea>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">

            <button type="submit" class="btn btn-primary">Sold Vehicle</button>
          </div>
        </div>
      </div>
    </div>
  </form>
<?php
}
?>

<script>
  function goEdit(id) {
    window.location.replace("/dealership/edit_vehicle/" + id);
  }

  function inactive(id) {
    Swal.fire({
      title: 'Are you sure?',
      text: "Vehicle listing will be removed!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, remove !'
    }).then((result) => {
      if (result.isConfirmed) {

        $.ajax({
            url: '/dealership/changeStatusVehicle/' + id + '/1',
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
        url: '/dealership/changeStatusVehicle/' + id + '/0',
        type: 'POST',
      })
      .done(function(response) {
        location.reload();
      })

  }
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>