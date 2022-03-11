<!-- Page Header -->
<div class="page-header row no-gutters py-4">
  <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
    <span class="text-uppercase page-subtitle">Subscriptions</span>
    <h3 class="page-title">Subscription List</h3>
  </div>
</div>
<div class="row">
  <div class="col">
    <div class="card card-small mb-4">
      <div class="card-header border-bottom">
        <h6 class="m-0">Active Subscriptions</h6>
      </div>
      <div style="padding-bottom:0px!important" class="card-body p-0 pb-3 text-center dataTables_wrapper no-footer">
        <table id="activeTable" class="transaction-history d-none dataTable no-footer dtr-inline">
          <thead class="bg-light">
            <tr>
              <th scope="col" class="border-0">#</th>
              <th scope="col" class="border-0">Subscription Description</th>
              <th scope="col" class="border-0">Start Date</th>
              <th scope="col" class="border-0">Dealership</th>
              <th scope="col" class="border-0">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php
            foreach ($dealer as $objDealer) {
            ?>
              <tr>
                <td><?= $objDealer->subscription_id ?></td>
                <td><?= $objDealer->product_name ?></td>
                <td><?= $objDealer->subscription_start_date ?></td>
        

                <td><?= $objDealer->dealership_name ?></td>
                <td>
                  <div class="btn-group btn-group-sm" role="group" aria-label="Table row actions">
                    <button onclick="goEdit(<?= $objDealer->subscription_id ?>)" type="button" class="btn btn-white">
                      <i class="material-icons">edit</i>
                    </button>
                    <button onclick="inactive(<?= $objDealer->subscription_id ?>)" type="button" class="btn btn-white">
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
        <h6 class="m-0 text-white">Non-active Subscription</h6>
      </div>
      <div class="card-body p-0 pb-3 text-center bg-dark">
        <table class="table table-dark mb-0">
          <thead class="thead-dark">
            <tr>
              <th scope="col" class="border-0">#</th>
              <th scope="col" class="border-0">Subscription Description</th>
              <th scope="col" class="border-0">Start Date</th>
              <th scope="col" class="border-0">End Date</th>
              <th scope="col" class="border-0">Dealership</th>
              <th scope="col" class="border-0">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php
            foreach ($noactivedealer as $objDealer) {
            ?>
              <tr>
                <td><?= $objDealer->subscription_id ?></td>
                <td><?= $objDealer->subscription_desc ?></td>
                <td><?= $objDealer->subscription_start_date ?></td>
                <td><?= $objDealer->subscription_end_date ?></td>
                <td><?= $objDealer->dealership_name ?></td>
                <td onclick="goEdit(<?= $objDealer->subscription_id ?>)" style="text-align: right;"><button type="button" class="mb-2 btn btn-primary mr-2"><i class="material-icons">edit</i></button></td>
                <td onclick="activate(<?= $objDealer->subscription_id ?>)" style="text-align: left;"><button type="button" class="mb-2 btn btn-success mr-2"><i class="material-icons">backup</i></button></td>
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

    window.location.replace("/Maxautoadmin/editsubscription/" + id);
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
            url: '/Maxautoadmin/changeStatusSubs/' + id + '/1',
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
        url: '/Maxautoadmin/changeStatusSubs/' + id + '/0',
        type: 'POST',
      })
      .done(function(response) {
        location.reload();
      })

  }
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>