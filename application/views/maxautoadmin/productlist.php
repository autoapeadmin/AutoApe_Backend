<!-- Page Header -->
<div class="page-header row no-gutters py-4">
  <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
    <span class="text-uppercase page-subtitle">Subscriptions</span>
    <h3 class="page-title">Subscription List</h3>
  </div>
  <div class="col d-flex">
    <div class="btn-group btn-group-sm d-inline-flex ml-auto my-auto" role="group" aria-label="Table row actions">
      <a style="    margin-top: 19px;
    width: 170px;
    height: 40px;
    text-align: center;font-size: 14px;" data-toggle="modal" data-target="#modal" class="btn btn-white active">
        New Product
      </a>

    </div>
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
              <th scope="col" class="border-0">Product Name</th>
              <th scope="col" class="border-0">Product Price</th>
              <th scope="col" class="border-0">Max Listing Monthly</th>
              <th scope="col" class="border-0">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php
            foreach ($products as $objDealer) {
            ?>
              <tr>
                <td><?= $objDealer->id ?></td>
                <td><?= $objDealer->product_name ?></td>
                <td>$<?= $objDealer->product_price ?></td>
                <td><?= $objDealer->max_listing_monthly ?></td>
                <td>
                  <div class="btn-group btn-group-sm" role="group" aria-label="Table row actions">
                    <a data-toggle="modal" data-target="#modal<?= $objDealer->id ?>" type="button" class="btn btn-white">
                      <i class="material-icons">edit</i>
                    </a>
                    <button onclick="inactive(<?= $objDealer->id ?>)" type="button" class="btn btn-white">
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


<div>

  <?php
  foreach ($products as $objDealer) {
  ?>

    <!-- Modal -->
    <form style="width: 100%;" action="/Maxautoadmin/editProduct/<?= $objDealer->id ?>" method="POST" enctype="multipart/form-data">
      <div class="modal fade" id="modal<?= $objDealer->id ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Edit Product </h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-sm-12 col-md-12">
                  <div class="form-group">
                    <label for="feLastName">Product Name</label>
                    <div class="input-group">
                      <input value="<?= $objDealer->product_name ?>" name="name" type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="feLastName">Product Price</label>
                    <div class="input-group">
                      <input value="<?= $objDealer->product_price ?>" name="price" type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="feLastName">Payment Frequency</label>
                    <div class="input-group">
                    <input value="<?= $objDealer->max_listing_monthly ?>" name="max" type="text" class="form-control" aria-label="Max Listing">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="feLastName">Description</label>
                    <div class="input-group">
                      <textarea  class="form-control" id="desc" name="desc" rows="5"><?= $objDealer->description ?></textarea>
                    </div>
                  </div>

                </div>
              </div>
            </div>
            <div class="modal-footer">

              <button type="submit" class="btn btn-primary">Edit Product</button>
            </div>
          </div>
        </div>
      </div>
    </form>

  <?php
  }
  ?>

  <!-- Modal -->
  <form style="width: 100%;" action="/Maxautoadmin/createProduct" method="POST" enctype="multipart/form-data">
    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">New Product </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12 col-md-12">
                <div class="form-group">
                  <label for="feLastName">Product Name</label>
                  <div class="input-group">
                    <input name="name" type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
                  </div>
                </div>
                <div class="form-group">
                  <label for="feLastName">Product Price</label>
                  <div class="input-group">
                    <input name="price" type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
                  </div>
                </div>
                <div class="form-group">
                  <label for="feLastName">Max Listing Monthly</label>
                  <div class="input-group">
                  <input name="max" type="text" class="form-control" aria-label="Max Listing">
                  </div>
                </div>
                <div class="form-group">
                  <label for="feLastName">Description</label>
                  <div class="input-group">
                    <textarea class="form-control" id="desc" name="desc" rows="5"></textarea>
                  </div>
                </div>

              </div>
            </div>
          </div>
          <div class="modal-footer">

            <button type="submit" class="btn btn-primary">Create Product</button>
          </div>
        </div>
      </div>
    </div>
  </form>


</div>





<script>
  function goEdit(id) {
    window.location.replace("/Maxautoadmin/editproduct/" + id);
  }

  function inactive(id) {
    Swal.fire({
      title: 'Are you sure?',
      text: "Dealership will be deleted!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
      if (result.isConfirmed) {

        $.ajax({
            url: '/Maxautoadmin/changeStatusProduct/' + id + '/1',
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
        url: '/Maxautoadmin/changeStatusProduct/' + id + '/0',
        type: 'POST',
      })
      .done(function(response) {
        location.reload();
      })
  }
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>