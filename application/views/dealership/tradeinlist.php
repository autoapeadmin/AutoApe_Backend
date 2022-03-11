<!-- Page Header -->
<div class="page-header row no-gutters py-4">
  <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
    <span class="text-uppercase page-subtitle">CONTACT</span>
    <h3 class="page-title">Trade In Listings</h3>
  </div>
</div>


<div class="row">
  <div class="col">
    <div class="card card-small mb-4">
      <div class="card-header border-bottom">
        <h6 class="m-0">Listings Managament</h6>
      </div>
      <div style="padding-bottom:0px!important" class="card-body p-0 pb-3 text-center dataTables_wrapper no-footer">



        <div id="divTable1">

          <table id="activeTable" class="transaction-history d-none dataTable no-footer dtr-inline">
            <thead class="bg-light">
              <tr>

                <th scope="col" class="border-0">Photo</th>
                <th scope="col" class="border-0">Odometer (KM)</th>
                <th scope="col" class="border-0">Make</th>
                <th scope="col" class="border-0">Model</th>
                <th scope="col" class="border-0">Received on</th>
                <th scope="col" class="border-0">Actions</th>
              </tr>
            </thead>
            <tbody style="    font-weight: 800;">
              <?php
              foreach ($vehicles as $vehicle) {

              ?>
                <tr>
   
                  <td><img class="imglistveh" style="border-radius: 4px;    width: 110px;
    height: 70px;
" src="<?php if (property_exists($vehicle, "pic_url")) {
                  echo $vehicle->pic_url;
                }  ?>" /></td>
                  <td><?= $vehicle->odometer ?></td>
                  <td><?= $vehicle->make_description ?></td>
                  <td><?= $vehicle->model_desc ?></td>
                  <td><?php echo date("d/m/y", strtotime($vehicle->indate)); ?></td>
                  <td>
                    <div class="btn-group btn-group-sm" role="group" aria-label="Table row actions">
                      <!-- onclick="inactive(<?= $vehicle->id ?>)" -->
                      <button data-toggle="modal" data-target="#modal<?= $vehicle->id ?>" type="button" class="btn btn-white">
                        View
                      </button>
                      <button onclick="inactive(<?= $vehicle->id ?>)" type="button" class="btn btn-white">
                        Withdraw
                      </button>
                      <!-- data-toggle="modal" data-target="#modal<?= $vehicle->id ?>" -->

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

  <!-- Modal -->
  <div class="modal fade" id="modal<?= $vehicle->id ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Trade In <?= $vehicle->make_description ?> <?= $vehicle->model_desc ?> </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-sm-12 col-md-12">
              <div class="form-group">

                <div style="text-align: center;" class="input-group">
                  <img class="imglistveh" style="border-radius: 4px;width: 300px;margin: 0 auto;
    height: 200px;
" src="<?php if (property_exists($vehicle, "pic_url")) {
          echo $vehicle->pic_url;
        }  ?>" />
                </div>
              </div>
              <div class="form-group">
                <label for="feLastName">Customer Email</label>
                <div class="input-group">
                  <input value="<?= $vehicle->customer_email ?>" disabled class="form-control" id="desc" name="desc" ></input>
                </div>
              </div>
              <div class="form-group">
                <label for="feLastName">Customer Name</label>
                <div class="input-group">
                  <input value="<?= $vehicle->customer_name ?>" disabled class="form-control" id="desc" name="desc" ></input>
                </div>
              </div>
              <div class="form-group">
                <label for="feLastName">Description</label>
                <div class="input-group">
                  <textarea disabled class="form-control" id="desc" name="desc"> <?= $vehicle->description ?></textarea>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">

          <button data-dismiss="modal" class="btn btn-primary">Close</button>
        </div>
      </div>
    </div>
  </div>

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
      text: "Vehicle will be removed!",
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