<!-- Page Header -->
<div class="page-header row no-gutters py-4">
  <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
    <span class="text-uppercase page-subtitle">API</span>
    <h3 class="page-title">API List</h3>
  </div>
</div>
<div class="row">
  <div class="col">
    <div class="card card-small mb-4">
      <div class="card-header border-bottom">
        <h6 class="m-0">APIs</h6>
      </div>
      <div style="padding-bottom:0px!important" class="card-body p-0 pb-3 text-center dataTables_wrapper no-footer">
        <table id="activeTable" class="transaction-history d-none dataTable no-footer dtr-inline">
          <thead class="bg-light">
            <tr>
              <th scope="col" class="border-0">#</th>
              <th scope="col" class="border-0">Logo</th>
              <th scope="col" class="border-0">Dealership name</th>
              <th scope="col" class="border-0">API Key</th>
            </tr>
          </thead>
          <tbody>
            <?php
            foreach ($dealer as $objDealer) {
            ?>
              <tr>
                <td><?= $objDealer->dealership_id ?></td>
                <td>  <img  src="<?= $objDealer->img_base64 ?>" style="width:40px;height:40px;" id="blah" /></td>
                <td><?= $objDealer->dealership_name ?></td>
                <td><?= $objDealer->dealership_api_key ?></td>
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