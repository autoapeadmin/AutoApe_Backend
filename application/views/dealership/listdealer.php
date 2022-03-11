<!-- Page Header -->
<div class="page-header row no-gutters py-4">
  <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
    <span class="text-uppercase page-subtitle">Dealerships</span>
    <h3 class="page-title">Dealership List</h3>
  </div>
</div>
<div class="row">
  <div class="col">
    <div class="card card-small mb-4">
      <div class="card-header border-bottom">
        <h6 class="m-0">Active dealership</h6>
      </div>
      <div style="padding-bottom:0px!important" class="card-body p-0 pb-3 text-center dataTables_wrapper no-footer">
        <table id="activeTable" class="transaction-history d-none dataTable no-footer dtr-inline">
          <thead class="bg-light">
            <tr>
              <th scope="col" class="border-0">#</th>
              <th scope="col" class="border-0">Dealership name</th>
              <th scope="col" class="border-0">Contact Name</th>
              <th scope="col" class="border-0">Website</th>
              <th scope="col" class="border-0">Contact Phone</th>
              <th scope="col" class="border-0">Contact Person</th>
              <th scope="col" class="border-0">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php
            foreach ($dealer as $objDealer) {
            ?>
              <tr>
                <td><?= $objDealer->dealership_id ?></td>
                <td><?= $objDealer->dealership_name ?></td>
                <td><?= $objDealer->contact_email ?></td>
                <td><?= $objDealer->dealership_website ?></td>
                <td><?= $objDealer->dealership_phone ?></td>
                <td><?= $objDealer->contact_person ?></td>
                <td>
                  <div class="btn-group btn-group-sm" role="group" aria-label="Table row actions">
                    <button onclick="goEdit(<?= $objDealer->dealership_id ?>)" type="button" class="btn btn-white">
                      <i class="material-icons">edit</i>
                    </button>
                    <button onclick="inactive(<?= $objDealer->dealership_id ?>)" type="button" class="btn btn-white">
                      <i class="material-icons">delete</i>
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
    </div>
  </div>
</div>

<div class="row">
  <div class="col">
    <div class="card card-small mb-4">
      <div class="card-header bg-dark">
        <h6 class="m-0 text-white">Non-active dealership</h6>
      </div>
      <div class="card-body p-0 pb-3 text-center bg-dark">
        <table class="table table-dark mb-0">
          <thead class="thead-dark">
            <tr>
              <th scope="col" class="border-0">#</th>
              <th scope="col" class="border-0">Dealership name</th>
              <th scope="col" class="border-0">Contact Name</th>
              <th scope="col" class="border-0">Website</th>
              <th scope="col" class="border-0">Contact Phone</th>
              <th scope="col" class="border-0">Contact Person</th>
            </tr>
          </thead>
          <tbody>
            <?php
            foreach ($noactivedealer as $objDealer) {
            ?>
              <tr>
                <td><?= $objDealer->dealership_id ?></td>
                <td><?= $objDealer->dealership_name ?></td>
                <td><?= $objDealer->contact_email ?></td>
                <td><?= $objDealer->dealership_website ?></td>
                <td><?= $objDealer->dealership_phone ?></td>
                <td><?= $objDealer->contact_person ?></td>
                <td onclick="goEdit(<?= $objDealer->dealership_id ?>)" style="text-align: right;"><button type="button" class="mb-2 btn btn-primary mr-2"><i class="material-icons">edit</i></button></td>
                <td onclick="activate(<?= $objDealer->dealership_id ?>)" style="text-align: left;"><button type="button" class="mb-2 btn btn-success mr-2"><i class="material-icons">backup</i></button></td>
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