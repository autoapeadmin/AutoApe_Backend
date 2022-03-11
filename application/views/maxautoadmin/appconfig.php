<!-- Page Header -->
<div class="page-header row no-gutters py-4">
    <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
        <span class="text-uppercase page-subtitle">CONFIGURATIONS</span>
        <h3 class="page-title">APP Configuration</h3>
    </div>
</div>
<!-- End Page Header -->
<!-- Small Stats Blocks -->
<div class="row">
    <div class="col-lg-12 mb-12">
        <form action="/Maxautoadmin/edit_app_conf" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6">
                    <div class="card card-small mb-4">
                        <div class="card-header border-bottom">
                            <h6 class="m-0">Prices Info</h6>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item p-3" style="border-bottom: 0;">
                                <div class="row">
                                    <div class="col-sm-12 col-md-6">
                                        <strong class="text-muted d-block mb-2">Listing Prices Cars</strong>
                                        <div class="form-group">
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">$</span>
                                                </div>
                                                <input id="car_price" name="car_price"  value="<?= $config[0]->price_car ?>" disabled type="text" class="form-control" aria-label="Amount (to the nearest dollar)" placeholder="1000">

                                            </div>
                                        </div>

                                        <strong class="text-muted d-block mb-2">Listing Price Cars Discount</strong>
                                        <div class="form-group">
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">$</span>
                                                </div>
                                                <input id="car_d_price" name="car_d_price" value="<?= $config[0]->price_discount_car ?>" disabled type="text" class="form-control" aria-label="Amount (to the nearest dollar)" placeholder="1000">

                                            </div>
                                        </div>

                                        <strong style="margin-top:15px" class="text-muted d-block mb-2">Wanted List Price</strong>
                                        <div class="form-group">
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">$</span>
                                                </div>
                                                <input id="wanted" name="wanted" value="<?= $config[0]->price_wanted_list ?>" disabled type="text" class="form-control" aria-label="Amount (to the nearest dollar)" placeholder="1000">

                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="input-group mb-3">
                                                <a onclick="enableEdit()" href="#" class="mb-2 btn btn-primary mr-2">Edit</a>
                                                <button type="submit" class="mb-2 btn btn-success mr-2">Save</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <strong class="text-muted d-block mb-2">Listing Prices Motorbikes</strong>
                                        <div class="form-group">
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">$</span>
                                                </div>
                                                <input id="price_moto" name="price_moto"  value="<?= $config[0]->price_moto ?>" disabled type="text" class="form-control" aria-label="Amount (to the nearest dollar)" placeholder="1000">

                                            </div>
                                        </div>

                                        <strong class="text-muted d-block mb-2">Listing Price Motorbikes Discount</strong>
                                        <div class="form-group">
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">$</span>
                                                </div>
                                                <input id="price_moto_d" name="price_moto_d" value="<?= $config[0]->price_discount_moto ?>" disabled type="text" class="form-control" aria-label="Amount (to the nearest dollar)" placeholder="1000">

                                            </div>
                                        </div>


                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>

<!-- Page Header -->
<div class="page-header row no-gutters py-4">
    <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
        <span class="text-uppercase page-subtitle">CONFIGURATIONS</span>
        <h3 class="page-title">Banner</h3>
    </div>

    <div class="col d-flex">
    <div class="btn-group btn-group-sm d-inline-flex ml-auto my-auto" role="group" aria-label="Table row actions">
      <a style="    margin-top: 19px;
    width: 170px;
    height: 40px;
    text-align: center;font-size: 14px;" data-toggle="modal" data-target="#modal" class="btn btn-white active">
        Create Banner
      </a>

    </div>
  </div>
</div>



<div class="row">
    <div class="col-lg-12 mb-12">
        <form action="/Maxautoadmin/edit_app_conf" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-small mb-4">
                        <div class="card-header border-bottom">
                            <h6 class="m-0">APP Banners</h6>
                        </div>
                        <ul class="list-group list-group-flush">
                           
                            <li class="list-group-item p-3" style="border-bottom: 0;">
                            <div style="padding-bottom:0px!important" class="card-body p-0 pb-3 text-center dataTables_wrapper no-footer">
                            <table id="activeTable" class="transaction-history d-none dataTable no-footer dtr-inline">
                            <thead class="bg-light">
                                <tr>
                                <th scope="col" class="border-0">#</th>
                                <th scope="col" class="border-0">Title</th>
                                <th scope="col" class="border-0">Region</th>
                              
                                <th scope="col" class="border-0">Background Image</th>
                                <th scope="col" class="border-0">Status</th>
                                <th scope="col" class="border-0">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($banners as $banner) {
                            ?>
                            <tr>
  <td><?=$banner["number"] ?></td>
  <td><h6 style="text-align:left" class="stats-small__value count m-0"><?=$banner["title"] ?></h6><h6  style="text-align:left"  class="stats-small__value count m-0" class="stats-small__label mb-1 text-uppercase"><?=$banner["subtitle"] ?></h6></td>
  <td><?=$banner["region_name"] ?></td>
  <td><img style="width: 50%"  src="https://maxauto.s3-ap-southeast-2.amazonaws.com/maxauto/<?=$banner["bg_image"] ?>" alt="User Avatar"></td>
  <td><?php if($banner["delete_flag"]==="0"){echo "Active";} else{ echo "Desactivated";} ?></td>     
  <td>
                  <div class="btn-group btn-group-sm" role="group" aria-label="Table row actions">
                    <a data-toggle="modal" data-target="#modal<?=$banner["banner_id"]?>" type="button" class="btn btn-white">
                      <i class="material-icons">edit</i>
                    </a>
                  </div>
                </td>         
  </tr>

                            <?php
                              }
                           ?>
                           
                            </tbody>
</table>
</div>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>


<?php
       foreach ($banners as $banner) {
  ?>

<form style="width: 100%;" action="/Maxautoadmin/edit_banner/<?= $banner["banner_id"] ?>" method="POST" enctype="multipart/form-data">
      <div class="modal fade" id="modal<?= $banner["banner_id"] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Edit Banner </h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
            <div class="row">
              <div class="col-sm-6 col-md-6">
                <div class="form-group">
                  <label for="feLastName">Title</label>
                  <div class="input-group">
                    <input value="<?=$banner["title"] ?>" name="title" type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
                  </div>
                </div>
                <div class="form-group">
                  <label for="feLastName">Subtitle</label>
                  <div class="input-group">
                    <input value="<?=$banner["subtitle"] ?>" name="subtitle" type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
                  </div>
                </div>
                <div class="form-group">
                  <label for="feLastName">Button Text</label>
                  <div class="input-group">
                  <input value="<?=$banner["button_text"] ?>" name="buttonText" type="text" class="form-control" aria-label="Max Listing">
                  </div>
                </div>
              </div>
              <div class="col-sm-6 col-md-6">
                <div class="form-group">
                  <label for="feLastName">Title Colour </label>
                  <div class="input-group">
                    <input  value="<?=$banner["title_color"] ?>" name="titleColour" type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
                  </div>
                </div>
                <div class="form-group">
                  <label for="feLastName">Subtitle Colour </label>
                  <div class="input-group">
                    <input value="<?=$banner["subtitle_color"] ?>" name="subtitleColour" type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
                  </div>
                </div>
                <div class="form-group">
                  <label for="feLastName">Button Text Colour</label>
                  <div class="input-group">
                  <input value="<?=$banner["button_text_color"] ?>" name="buttonTextColour" type="text" class="form-control" aria-label="Max Listing">
                  </div>
                </div>
              </div>

              <div class="col-sm-6 col-md-6">
           
              <div class="form-group">
                  <label for="feLastName">Order Number</label>
                  <div class="input-group">
                    <input value="<?=$banner["number"] ?>"  name="number" type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
                  </div>
                </div>
              </div>
            

              <div class="col-sm-6 col-md-6">
              <div class="form-group">
                  <label for="feLastName">Region</label>
                  <div class="input-group">
                                                <select name="region" class="form-control">
                                                    <option selected="">Choose region</option>
                                                    <?php
                                                    foreach ($region as $key) {
                                                    ?>
                                                        <option <?php if($key["region_id"]===$banner["region_id"]) {echo "selected";}   ?> value="<?= $key["region_id"] ?>"><?= $key["region_name"] ?></option>
                                                    <?php } ?>
                                                </select>
                  </div>
                </div>
              </div>
    

              <div class="col-sm-6 col-md-6">
           
           <div class="form-group">
               <label for="feLastName">Status</label>
               <div class="input-group">
               <select name="delete" class="form-control">
                                                    <option <?php if($banner["delete_flag"]==="0") {echo "selected";}   ?> value="0" selected="">Activate</option>
                                                    <option <?php if($banner["region_id"]==="1") {echo "selected";}   ?> value="1">Desactived</option>
                </select>
               </div>
             </div>
           </div>
            </div>
            </div>
            <div class="modal-footer">
              <a style="color:white" onclick="inactive(<?=$banner['banner_id'] ?>)" class="btn btn-danger">Delete</a>
              <button type="submit" class="btn btn-primary">Edit</button>
            </div>
          </div>
        </div>
      </div>
    </form>

<?php
  }
  ?>

  <script>
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
                url: '/Maxautoadmin/delete_banner/' + id ,
                type: 'POST',
              })
              .done(function(response) {
                location.reload();
              })


          }
        })
      }
  </script>

<form style="width: 100%;" action="/Maxautoadmin/create_banner" method="POST" enctype="multipart/form-data">
    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">New Banner </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-6 col-md-6">
                <div class="form-group">
                  <label for="feLastName">First Line Title</label>
                  <div class="input-group">
                    <input required name="title" type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
                  </div>
                </div>
                <div class="form-group">
                  <label for="feLastName">Second Line Title</label>
                  <div class="input-group">
                    <input required name="subtitle" type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
                  </div>
                </div>
                <div class="form-group">
                  <label for="feLastName">Button Text</label>
                  <div class="input-group">
                  <input required name="buttonText" type="text" class="form-control" aria-label="Max Listing">
                  </div>
                </div>
              </div>
              <div class="col-sm-6 col-md-6">
                <div class="form-group">
                  <label for="feLastName">Title Colour </label>
                  <div class="input-group">
                    <input required name="titleColour" type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
                  </div>
                </div>
                <div class="form-group">
                  <label for="feLastName">-</label>
                  <div class="input-group">
                    <input disabled required name="subtitleColour" type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
                  </div>
                </div>
                <div class="form-group">
                  <label for="feLastName">Button Text Colour</label>
                  <div class="input-group">
                  <input required name="buttonTextColour" type="text" class="form-control" aria-label="Max Listing">
                  </div>
                </div>
              </div>
              <div class="col-sm-6 col-md-6">
                <div class="form-group">
                  <label for="feLastName">Image Background </label>
                  <div class="input-group">
                    <input name="imgBg" type="file" class="form-control" aria-label="Amount (to the nearest dollar)"  accept="image/png, image/jpeg" >
                  </div>
                </div>
              </div>
            
              <div class="col-sm-6 col-md-6">
                <div class="form-group">
                  <label for="feLastName">Region</label>
                  <div class="input-group">
                                                <select required name="region" class="form-control">
                                                    
                                                    <?php
                                                    foreach ($region as $key) {
                                                    ?>
                                                        <option value="<?= $key["region_id"] ?>"><?= $key["region_name"] ?></option>
                                                    <?php } ?>
                                                </select>
                  </div>
                </div>
              </div>
              <div class="col-sm-6 col-md-6">
                <div class="form-group">
                  <label for="feLastName">Order Number</label>
                  <div class="input-group">
                    <input required name="number" type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
                  </div>
                </div>
              </div>
              <div class="col-sm-6 col-md-6">
                <div class="form-group">
                  <label for="feLastName">Action Type</label>
                  <div class="input-group">
                                                <select required name="actiontype" class="form-control">
                                                        <option value="0">Screen</option>
                                                        <option selected value="1">URL</option>
                                                </select>
                  </div>
                </div>
              </div>
              <div class="col-sm-6 col-md-6">
                <div class="form-group">
                  <label for="feLastName">Action URL (https://www.google.com)</label>
                  <div class="input-group">
                    <input required name="action" type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
                  </div>
                </div>
              </div>
              <div class="col-sm-6 col-md-6">
                <div class="form-group">
                  <label for="feLastName">Action Screen</label>
                  <div class="input-group">
                  <select required name="actionScreen" class="form-control">
                                                        <option value="Login">Login</option>
                                                        <option value="Search">Search</option>
                                                        <option value="WantedList">WantedList</option>
                                                        <option value="EvCharger">EvCharger</option>
                                                        <option value="TrafficCamera2">TrafficCamera</option>
                                                        <option value="SalesAgreement2">SalesAgreement</option>
                                                        <option value="MyVehicles">MyVehicles</option>
                                                        <option value="Police">Police</option>
                                                        <option value="Owner">Owner</option>
                                                        <option value="NZTAScreen">NZTAScreen</option>
                                                        <option value="List">Create listing</option>                                                       
                                                </select>
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
    function enableEdit() {
        document.getElementById("car_price").disabled = false;
        document.getElementById("car_d_price").disabled = false;
        document.getElementById("wanted").disabled = false;
        document.getElementById("price_moto").disabled = false;
        document.getElementById("price_moto_d").disabled = false;
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>      