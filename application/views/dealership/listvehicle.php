<div class="page-header row no-gutters py-4">
    <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
        <span class="text-uppercase page-subtitle">vehicle</span>
        <h3 class="page-title">List a vehicle</h3>
    </div>
</div>

<button id="showLoading" style="display:none" type="button" class="btn btn-primary" data-toggle="modal" data-target="#loadingModal">
</button>
<!-- Modal -->
<div style="margin-top: 200px;" class="modal fade loadingModal" id="loadingModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div style="text-align: center;margin:0 auto" class="modal-body">
                <lottie-player src="/assets/img/loading.json" background="transparent" speed="1" style="width: 300px; height: 300px;" loop autoplay></lottie-player>
            </div>

        </div>
    </div>
</div>

<style>
    .modal-backdrop {
        opacity: 0.8 !important;
    }
</style>


<form id="carForm" style="width: 100%;" action="/dealership/addVehicle/0" method="POST" enctype="multipart/form-data">

    <input type="hidden" id="type" name="type" value="0">
    <div style="margin-top:0px" class="row">



        <div class="col-sm-12 col-md-6">
            <div class="card edit-user-details card-small mb-4">
                <div class="card-header border-bottom">
                    <h6 class="m-0">Vehicle Details 1 of 2</h6>
                    <img id="check1" style="width: 25px;float: right;margin-top: -25px;display:none" src="/assets/img/check3.png" />
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item p-3">
                        <div class="row">
                            <div class="col">
                                <label for="feFirstName">Vehicle Type</label><small style="color:#b111118a;font-size: 120%!important;"> *</small>
                                <div class="form-row">

                                    <div class="form-group col-md-3">
                                        <a onclick="selectCar();" style="width: 100%;" class="btn btn-white active">
                                            Car
                                        </a>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <a onclick="selectMotorbike();" style="width: 100%;background-color: white;color:#114e92" class="btn btn-white active">
                                            Motorbike
                                        </a>
                                    </div>



                                    <div class="form-group col-md-12">
                                        <label for="feFirstName">Rego</label>
                                        <input type="text" class="form-control" id="rego" placeholder="Rego
                                        " name="rego">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label for="feEmailAddress">Make</label><small style="color:#b111118a;font-size: 120%!important;"> *</small>
                                        <select multiple onblur="check()" required onchange="func(this.value);" name="make" class="custom-select make class2 ">

                                            <?php foreach ($makes as $make) { ?>
                                                <option data-image="<?php echo base_url("images/logosMake/" . $make->make_logo . ".png"); ?>" value="<?= $make->make_id ?>"><?= $make->make_description ?></option>
                                            <?php } ?>

                                        </select>
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label for="feEmailAddress">Model</label><small style="color:#b111118a;font-size: 120%!important;"> *</small>
                                        <select onblur="check()" required class="form-control model" id="model" placeholder="Select Model" name="model">
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="row">
                            <div style="display:none" class="col">
                                <label for="feFirstName">Vehicle Type</label><small style="color:#b111118a;font-size: 120%!important;"> *</small>
                                <div class="form-row">


                                    <div class="form-group col-md-3">
                                        <a onclick="selectCar();" style="width: 100%;background-color: white;color:#114e92" class="btn btn-white active">
                                            Car
                                        </a>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <a onclick="selectMotorbike();" style="width: 100%;" class="btn btn-white active">
                                            Motorbike
                                        </a>
                                    </div>



                                    <div class="form-group col-md-12">
                                        <label for="feFirstName">REGO</label>
                                        <input type="text" class="form-control" id="rego" placeholder="REGO" name="regomoto">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label for="feEmailAddress">Make</label><small style="color:#b111118a;font-size: 120%!important;"> *</small>
                                        <select name="makemoto" class="custom-select">
                                            <?php foreach ($makes2 as $make) { ?>
                                                <option value="<?= $make->make_id ?>"><?= $make->make_description ?></option>
                                            <?php } ?>

                                        </select>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>


        <div class="col-sm-12 col-md-6">
            <div class="card edit-user-details card-small mb-4">
                <div class="card-header border-bottom">
                    <h6 class="m-0">Vehicle Details 2 of 2</h6>
                    <img id="check2" style="width: 25px;float: right;margin-top: -25px;display:none" src="/assets/img/check3.png" />
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item p-3">
                        <div class="row">
                            <div id="carForm2" class="col">


                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="feEmailAddress">Body Type</label><small style="color:#b111118a;font-size: 120%!important;"> *</small>
                                        <select onblur="check()" required name="bodytype" class="custom-select bodytype">
                                            <option value="" selected disabled>Select</option>
                                            <?php foreach ($bodyTypeCar as $make2) { ?>
                                                <option value="<?= $make2['body_type_id'] ?>"><?= $make2['body_type_name'] ?></option>
                                            <?php } ?>

                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="feEmailAddress">Year</label><small style="color:#b111118a;font-size: 120%!important;"> *</small>
                                        <input onblur="check()" required type="number" class="form-control year" id="year" placeholder="Year" max="9999" name="year">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="feEmailAddress">Odometer</label><small style="color:#b111118a;font-size: 120%!important;"> *</small>
                                        <div class="input-group">
                                            <input onblur="check()" required name="odometer" type="number" class="form-control odometer" aria-label="Amount (to the nearest dollar)">
                                            <div class="input-group-append">
                                                <span style="color:black" class="input-group-text">Km</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="feEmailAddress">Engine size (cc)</label><small style="color:#b111118a;font-size: 120%!important;"> *</small>
                                        <div class="input-group">
                                            <input onblur="check()" required name="engine" type="number" class="form-control engine" aria-label="Amount (to the nearest dollar)">
                                            <div class="input-group-append">
                                                <span style="color:black" class="input-group-text">CC</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="feEmailAddress">Transmission</label><small style="color:#b111118a;font-size: 120%!important;"> *</small>
                                        <select onblur="check()" required id="trans" name="trans" class="custom-select trans">
                                            <option value="" selected disabled>Select</option>
                                            <option value="0">Automatic</option>
                                            <option value="1">Manual</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="feEmailAddress">Fuel Type</label><small style="color:#b111118a;font-size: 120%!important;"> *</small>
                                        <select onblur="check()" required id="fuelType" name="fuelType" class="custom-select fuelType">
                                            <option selected disabled>Select</option>
                                            <option value="0">Petrol</option>
                                            <option value="1">Diesel</option>
                                            <option value="2">Hybrid</option>
                                            <option value="3">Plug-in hybrid</opt ion>
                                            <option value="4">Electric</option>
                                            <option value="5">LPG</option>
                                            <option value="6">Alternative</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="feEmailAddress">Drive Type</label>
                                        <select id="driveType" name="driveType" class="custom-select">
                                            <option value="0">2WD</option>
                                            <option value="1">4WD</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div style="display:none" class="col">

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="feEmailAddress">Year</label><small style="color:#b111118a;font-size: 120%!important;"> *</small>
                                        <input type="number" class="form-control" id="yearmoto" placeholder="Year" name="yearmoto">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="feEmailAddress">Transmission</label><small style="color:#b111118a;font-size: 120%!important;"> *</small>
                                        <input type="number" class="form-control" id="transmoto" placeholder="Transmission" name="transmoto">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="feEmailAddress">Odometer</label><small style="color:#b111118a;font-size: 120%!important;"> *</small>
                                        <div class="input-group">
                                            <input name="odometermoto" type="number" class="form-control" aria-label="Amount (to the nearest dollar)">
                                            <div class="input-group-append">
                                                <span style="color:black" class="input-group-text">Km</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="feEmailAddress">Engine size (cc)</label><small style="color:#b111118a;font-size: 120%!important;"> *</small>
                                        <div class="input-group">
                                            <input name="enginemoto" type="number" class="form-control" aria-label="Amount (to the nearest dollar)">
                                            <div class="input-group-append">
                                                <span style="color:black" class="input-group-text">CC</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>





        <div class="col-sm-12 col-md-12">
            <div class="card edit-user-details card-small mb-4">
                <div class="card-header border-bottom">
                    <h6 class="m-0">Listing Details</h6>
                    <img id="check3" style="width: 25px;float: right;margin-top: -25px;display:none" src="/assets/img/check3.png" />
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item p-3">
                        <div class="row">
                            <div class="col">
                                <div class="form-row">
                                    <div class="form-group col-md-2">
                                        <label for="feLastName">Asking Price</label><small style="color:#b111118a;font-size: 120%!important;"> *</small>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span style="color:black" class="input-group-text">$</span>
                                            </div>
                                            <input onblur="check()" required name="price" type="text" class="form-control price" aria-label="Amount (to the nearest dollar)">

                                        </div>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label style="margin-top: 2.5px;" for="feLastName">Finance</label><small style="font-size: 80%!important;"> (optional)</small>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span style="color:black" class="input-group-text">$</span>
                                            </div>
                                            <input onblur="check()" name="financial" type="text" class="form-control financial" aria-label="Amount (to the nearest dollar)">
                                            <div class="input-group-append">
                                                <select style="border-radius: 0px;" id="inputState" class="form-control">
                                                    <option value="1" selected="">Per Week</option>
                                                    <option value="2">Per Month</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>



                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label for="feEmailAddress">Vehicle Description</label><small style="font-size: 80%!important;"> (optional)</small>
                                        <textarea class="form-control" id="desc" name="desc" rows="5"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>



                </ul>
            </div>

        </div>



        <div class="col-sm-12 col-md-12">
            <div class="card edit-user-details card-small mb-4">
                <div class="card-header border-bottom">
                    <h6 class="m-0">Upload Photos <small>(Photo </small><small id="counter">0</small><small> out of 15)</small></h6>
                    <small style="font-size: 90%;">Drag and drop to rearrange photos.</small>
                </div>
                <ul style="min-height:80px;padding: 20px;" class="list-group list-group-flush">
                    <a style="width: 300px;margin-top:20px;align-self: center;" onclick="$('#files').click()" class="btn btn-white"> Upload photos</a>
                    <input accept="image/png, image/jpeg" type="file" id="files" style="display:none" multiple>
                    <div id="photosWrap" class="photos-wrap row" style="position: relative; margin-top:10px">
                        <!-- Agregar y cambiar numeros, eliminar??? Agregar fotos para editar -->
                    </div>
                </ul>


            </div>
        </div>

        <input type="hidden" value="" id="ordenPhotos">


        <div class="col-sm-12 col-md-3">
            <div style="width: 100%;padding-bottom:100px" class="btn-group btn-group-sm d-inline-flex ml-auto my-auto" role="group" aria-label="Table row actions">
                <a style="width: 100%;background-color:white;color:#114e92;height:50px;font-size: medium;padding-top: 12px;" href="create_salesperson" class="btn btn-white active">
                    Reset
                </a>
            </div>
        </div>

        <div class="col-sm-12 col-md-3">
            <div style="width: 100%;padding-bottom:100px" class="btn-group btn-group-sm d-inline-flex ml-auto my-auto" role="group" aria-label="Table row actions">
                <button type="submit" style="width: 100%;height:50px;font-size: medium;padding-top: 8px;" href="create_salesperson" class="btn btn-white active">
                    Upload Listing
                </button>
            </div>
        </div>

        <div class="col-sm-12 col-md-6">

        </div>



    </div>
</form>



<form id="motoForm" style="width: 100%;display:none" action="/dealership/addVehicle/1" method="POST" enctype="multipart/form-data">

    <input type="hidden" id="type" name="type" value="0">
    <div style="margin-top:0px" class="row">



        <div class="col-sm-12 col-md-6">
            <div class="card edit-user-details card-small mb-4">
                <div class="card-header border-bottom">
                    <h6 class="m-0">Vehicle Details 1 of 2</h6>
                    <img id="check1" style="width: 25px;float: right;margin-top: -25px;display:none" src="/assets/img/check3.png" />
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item p-3">
                        <div class="row">
                            <div class="col">
                                <label for="feFirstName">Vehicle Type</label><small style="color:#b111118a;font-size: 120%!important;"> *</small>
                                <div class="form-row">


                                    <div class="form-group col-md-3">
                                        <a onclick="selectCar();" style="width: 100%;background-color: white;color:#114e92" class="btn btn-white active">
                                            Car
                                        </a>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <a onclick="selectMotorbike();" style="width: 100%;" class="btn btn-white active">
                                            Motorbike
                                        </a>
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label for="feFirstName">REGO</label>
                                        <input type="text" class="form-control" id="rego" placeholder="REGO" name="regomoto">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label for="feEmailAddress">Make</label><small style="color:#b111118a;font-size: 120%!important;"> *</small><br>
                                        <select style="width: 100%;" multiple onblur="check()" required name="makemoto" class="custom-select make class2 ">
                                            <?php foreach ($makes2 as $make) { ?>
                                                <option data-image="<?php echo base_url("images/logosMakeMoto/" . $make->make_logo . ".png"); ?>" value="<?= $make->make_id ?>"><?= $make->make_description ?></option>
                                            <?php } ?>

                                        </select>
                                    </div>
                                </div>




                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>


        <div class="col-sm-12 col-md-6">
            <div class="card edit-user-details card-small mb-4">
                <div class="card-header border-bottom">
                    <h6 class="m-0">Vehicle Details 2 of 2</h6>
                    <img id="check2" style="width: 25px;float: right;margin-top: -25px;display:none" src="/assets/img/check3.png" />
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item p-3">
                        <div class="row">
                            <div id="motoForm2" class="col">

                                <div class="form-row">

                                    <!--   <div class="form-group col-md-6">
                                        <label for="feEmailAddress">Body Type</label><small style="color:#b111118a;font-size: 120%!important;"> *</small>
                                        <select onblur="check()" required name="bodytypem" class="custom-select bodytype">
                                            <option value="" selected disabled>Select</option>
                                            <?php foreach ($bodyTypeMoto as $make2) { ?>
                                                <option value="<?= $make2['body_type_id'] ?>"><?= $make2['body_type_name'] ?></option>
                                            <?php } ?>

                                        </select>
                                    </div> -->

                                    <div class="form-group col-md-6">
                                        <label for="feEmailAddress">Year</label><small style="color:#b111118a;font-size: 120%!important;"> *</small>
                                        <input type="number" class="form-control" id="yearmoto" placeholder="Year" name="yearmoto">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="feEmailAddress">Transmission</label><small style="color:#b111118a;font-size: 120%!important;"> *</small>
                                        <input type="number" class="form-control" id="transmoto" placeholder="Transmission" name="transmoto">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="feEmailAddress">Odometer</label><small style="color:#b111118a;font-size: 120%!important;"> *</small>
                                        <div class="input-group">
                                            <input name="odometermoto" type="number" class="form-control" aria-label="Amount (to the nearest dollar)">
                                            <div class="input-group-append">
                                                <span style="color:black" class="input-group-text">Km</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">

                                    <div class="form-group col-md-6">
                                        <label for="feEmailAddress">Engine size (cc)</label><small style="color:#b111118a;font-size: 120%!important;"> *</small>
                                        <div class="input-group">
                                            <input name="enginemoto" type="number" class="form-control" aria-label="Amount (to the nearest dollar)">
                                            <div class="input-group-append">
                                                <span style="color:black" class="input-group-text">CC</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <div class="col-sm-12 col-md-12">
            <div class="card edit-user-details card-small mb-4">
                <div class="card-header border-bottom">
                    <h6 class="m-0">Listing Details</h6>
                    <img id="check3" style="width: 25px;float: right;margin-top: -25px;display:none" src="/assets/img/check3.png" />
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item p-3">
                        <div class="row">
                            <div class="col">
                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                        <label for="feLastName">Asking Price</label><small style="color:#b111118a;font-size: 120%!important;"> *</small>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span style="color:black" class="input-group-text">$</span>
                                            </div>
                                            <input onblur="check()" required name="price" type="text" class="form-control price" aria-label="Amount (to the nearest dollar)">
                                        </div>
                                    </div>


                                    <div class="form-group col-md-3">
                                        <label style="margin-top: 2.5px;" for="feLastName">Finance</label><small style="font-size: 80%!important;"> (optional)</small>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span style="color:black" class="input-group-text">$</span>
                                            </div>
                                            <input onblur="check()" name="financial" type="text" class="form-control financial" aria-label="Amount (to the nearest dollar)">
                                            <div class="input-group-append">
                                                <select style="border-radius: 0px;" id="financialtype" class="form-control">
                                                    <option value="0">No financial option</option>
                                                    <option value="1" >Per Week</option>
                                                    <option value="2" >Per Month</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>



                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label for="feEmailAddress">Vehicle Description</label><small style="font-size: 80%!important;"> (optional)</small>
                                        <textarea class="form-control" id="desc" name="desc" rows="5"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>



                </ul>
            </div>

        </div>



        <div class="col-sm-12 col-md-12">
            <div class="card edit-user-details card-small mb-4">
                <div class="card-header border-bottom">
                    <h6 class="m-0">Upload Photos <small>(Photo </small><small id="counter2">0</small><small> out of 15)</small></h6>
                    <small style="font-size: 90%;">Drag and drop to rearrange photos.</small>
                </div>
                <ul style="min-height:80px;padding: 20px;" class="list-group list-group-flush">
                    <a style="width: 300px;margin-top:20px;align-self: center;" onclick="$('#files2').click()" class="btn btn-white"> Upload photos</a>
                    <input accept="image/png, image/jpeg" type="file" id="files2" style="display:none" multiple>
                    <div id="photosWrap2" class="photos-wrap row" style="position: relative; margin-top:10px">
                        <!-- Agregar y cambiar numeros, eliminar??? Agregar fotos para editar -->
                    </div>
                </ul>


            </div>
        </div>

        <div class="col-sm-12 col-md-2">
            <div style="width: 100%;padding-bottom:100px" class="btn-group btn-group-sm d-inline-flex ml-auto my-auto" role="group" aria-label="Table row actions">
                <a style="width: 100%;background-color:white;color:#114e92;height:50px;font-size: medium;padding-top: 12px;" href="create_salesperson" class="btn btn-white active">
                    Reset
                </a>
            </div>
        </div>

        <div class="col-sm-12 col-md-2">
            <div style="width: 100%;padding-bottom:100px" class="btn-group btn-group-sm d-inline-flex ml-auto my-auto" role="group" aria-label="Table row actions">
                <button type="submit" style="width: 100%;height:50px;font-size: medium;padding-top: 8px;" href="create_salesperson" class="btn btn-white active">
                    Upload Listing
                </button>
            </div>
        </div>

        <div class="col-sm-12 col-md-6">

        </div>



    </div>
</form>





<style>
    /* Photos Section */

    .upload-btn-wrap {
        display: flex;
        justify-content: flex-end;
    }

    .upload-btn {
        background-color: #5BA600;
        box-shadow: 1px 1px 11px 0 rgba(91, 166, 0, 0.29);
        display: block;
        /* padding: 1em; */
        font-size: 14px;
        text-align: center;
        color: #fff;
        font-weight: 500;

        width: 150px;
        height: 50px;
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        align-items: center;
        padding-top: 8px;
        padding-bottom: 8px;
    }

    .upload-btn:hover {
        text-decoration: unset;
    }

    .upload-btn span {
        font-weight: 100;
        font-size: 10px;
    }

    .photos-wrap {
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-start;
    }

    .photos-wrap .card .property-img {
        height: 150px;
        display: flex;
        justify-content: center;
        align-items: center;
        overflow: hidden;
        padding: 1em;
    }

    .photos-wrap .card .property-img img {
        flex-shrink: 0;
        min-width: 125px;
        min-height: 125px;
    }

    .photos-wrap .card {
        flex: 0 0 100%;
        margin-right: 9px;
        margin-bottom: 12px;
    }

    @media (min-width: 768px) {
        .photos-wrap .card {
            flex: 0 0 32%;
        }
    }


    @media (min-width: 992px) {
        .photos-wrap .card {
            flex: 0 0 24%;
        }
    }


    .photos-wrap .card-body {
        padding: unset;
    }

    .photos-wrap .card-number-wrap {
        position: absolute;
        bottom: 0;
        padding: unset;
        font-weight: 200;
    }

    .photos-wrap .card-number-wrap.star {
        bottom: 5px;
        left: 5px;
        height: 30px;
        width: 30px;
    }

    .photos-wrap .card-number {
        background-color: #000;
        color: #fff;
        width: 35px;
        height: 35px;
        display: flex;
        justify-content: center;
        align-items: center;
        text-decoration: unset;
    }

    .photos-wrap .card-body .buttons {
        padding-right: 6px;
        padding-bottom: 6px;
        text-align: right;
    }

    .photos-wrap .card-body .buttons a:hover {}
</style>