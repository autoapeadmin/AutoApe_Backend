<div class="page-header row no-gutters py-4">
    <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
        <span class="text-uppercase page-subtitle">vehicle</span>
        <h3 class="page-title">List a vehicle</h3>
    </div>
</div>

<form style="width: 100%;" action="/dealership/editVehicle/<?= $details[0]->vehicule_id ?>" method="POST" enctype="multipart/form-data">
    <div style="margin-top:0px" class="row">
   

        <div class="col-sm-12 col-md-6">
            <div class="card edit-user-details card-small mb-4">
                <div class="card-header border-bottom">
                    <h6 class="m-0">Vehicle Details</h6>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item p-3">
                        <div class="row">
                            <div id="carForm" class="col">
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label for="feFirstName">Vehicle Type</label>
                                        <select id="typVeh" onchange="selectType(this);" name="type" class="custom-select">
                                            <option <?php if ($details[0]->fk_vehicule_type_id == 0) {
                                                        echo "selected";
                                                    } ?> value="0">Car</option>
                                            <option <?php if ($details[0]->fk_vehicule_type_id == 1) {
                                                        echo "selected";
                                                    } ?> value="1">Motorbike</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label for="feFirstName">REGO</label>
                                        <input value="<?= $details[0]->vehicule_rego ?>" type="text" class="form-control" id="rego" placeholder="REGO" name="rego">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="feEmailAddress">Make</label>
                                        <input disabled value="<?= $details[0]->make_description ?>" type="text" class="form-control" id="rego" placeholder="REGO" name="rego">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="feEmailAddress">Model</label>
                                        <input disabled value="<?= $details[0]->model_desc ?>" type="text" class="form-control" id="rego" placeholder="REGO" name="rego">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="feEmailAddress">Body Type</label>
                                        <select name="bodytype" class="custom-select">
                                            <?php foreach ($bodyTypeCar as $make2) { ?>
                                                <option <?php if ($details[0]->body_type_id == $make2['body_type_id'] ) {
                                                        echo "selected";
                                                    } ?>  value="<?= $make2['body_type_id'] ?>"><?= $make2['body_type_name'] ?></option>
                                            <?php } ?>

                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="feEmailAddress">Year</label>
                                        <input value="<?= $details[0]->vehicule_year ?>" type="number" class="form-control" id="year" placeholder="Year" max="9999" name="year">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="feEmailAddress">Odometer</label>
                                        <div class="input-group">
                                            <input value="<?= $details[0]->vehicule_odometer ?>"  name="odometer" type="number" class="form-control" aria-label="Amount (to the nearest dollar)">
                                            <div class="input-group-append">
                                                <span class="input-group-text">Km</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="feEmailAddress">Engine size (cc)</label>
                                        <div class="input-group">
                                            <input value="<?= $details[0]->vehicule_engine ?>"  name="engine" type="number" class="form-control" aria-label="Amount (to the nearest dollar)">
                                            <div class="input-group-append">
                                                <span class="input-group-text">CC</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="feEmailAddress">Transmission</label>
                                        <select id="trans" name="trans" class="custom-select">
                                            <option <?php if ($details[0]->vehicule_transmission == "0") {
                                                        echo "selected";
                                                    } ?> value="0">Automatic</option>
                                            <option <?php if ($details[0]->vehicule_transmission == "1") {
                                                        echo "selected";
                                                    } ?>  value="1">Manual</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="feEmailAddress">Fuel Type</label>
                                        <select id="fuelType" name="fuelType" class="custom-select">
                                            <option <?php if ($details[0]->fk_vehicule_fuel == "0") {
                                                        echo "selected";
                                                    } ?>  value="0">Petrol</option>
                                            <option <?php if ($details[0]->fk_vehicule_fuel == "1") {
                                                        echo "selected";
                                                    } ?>  value="1">Diesel</option>
                                            <option <?php if ($details[0]->fk_vehicule_fuel == "2") {
                                                        echo "selected";
                                                    } ?>  value="2">Hybrid</option>
                                            <option <?php if ($details[0]->fk_vehicule_fuel == "3") {
                                                        echo "selected";
                                                    } ?>  value="3">Plug-in hybrid</option>
                                            <option <?php if ($details[0]->fk_vehicule_fuel == "4") {
                                                        echo "selected";
                                                    } ?>  value="4">Electric</option>
                                            <option <?php if ($details[0]->fk_vehicule_fuel == "5") {
                                                        echo "selected";
                                                    } ?>  value="5">LPG</option>
                                            <option <?php if ($details[0]->fk_vehicule_fuel == "6") {
                                                        echo "selected";
                                                    } ?>  value="6">Alternative</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="feEmailAddress">Drive Type</label>
                                        <select id="driveType" name="driveType" class="custom-select">
                                            <option <?php if ($details[0]->vehicule_4x4 == "0") {
                                                        echo "selected";
                                                    } ?>  value="0">2WD</option>
                                            <option <?php if ($details[0]->vehicule_4x4 == "1") {
                                                        echo "selected";
                                                    } ?>  value="1">4WD</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div style="display:none" id="motoForm" class="col">
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label for="feFirstName">Vehicle Type</label>
                                        <select id="typVeh1" onchange="selectType(this);" name="type" class="custom-select">
                                            <option value="0">Car</option>
                                            <option value="1">Motorbike</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label for="feFirstName">REGO</label>
                                        <input type="text" class="form-control" id="rego" placeholder="REGO" name="regomoto">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label for="feEmailAddress">Make</label>
                                        <select name="makemoto" class="custom-select">
                                            <?php foreach ($makes2 as $make) { ?>
                                                <option value="<?= $make->make_id ?>"><?= $make->make_description ?></option>
                                            <?php } ?>

                                        </select>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="feEmailAddress">Year</label>
                                        <input type="number" class="form-control" id="yearmoto" placeholder="Year" name="yearmoto">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="feEmailAddress">Transmission</label>
                                        <input type="number" class="form-control" id="transmoto" placeholder="Transmission" name="transmoto">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="feEmailAddress">Odometer</label>
                                        <div class="input-group">
                                            <input name="odometermoto" type="number" class="form-control" aria-label="Amount (to the nearest dollar)">
                                            <div class="input-group-append">
                                                <span class="input-group-text">Km</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="feEmailAddress">Engine size (cc)</label>
                                        <div class="input-group">
                                            <input name="enginemoto" type="number" class="form-control" aria-label="Amount (to the nearest dollar)">
                                            <div class="input-group-append">
                                                <span class="input-group-text">CC</span>
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

        <div class="col-sm-12 col-md-6">
            <div class="card edit-user-details card-small mb-4">
                <div class="card-header border-bottom">
                    <h6 class="m-0">Listing Info</h6>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item p-3">
                            <div class="row">
                                <div class="col">
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="feLastName">Price</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">$</span>
                                                </div>
                                                <input  name="price" type="text" class="form-control" aria-label="Amount (to the nearest dollar)" value="<?= $details[0]->vehicule_price ?>" >
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="feLastName">New Price</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">$</span>
                                                </div>
                                                <input  name="price" type="text" class="form-control" aria-label="Amount (to the nearest dollar)" value="<?= $details[0]->vehicule_price ?>" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label for="feEmailAddress">Description</label>
                                            <textarea class="form-control" id="desc" name="desc" rows="5"><?= $details[0]->vehicule_desc ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div style="width: 100%;" class="btn-group btn-group-sm d-inline-flex ml-auto my-auto" role="group" aria-label="Table row actions">
                                <button type="submit" style="width: 100%;" href="create_salesperson" class="btn btn-white active">
                                    Confirm Changes
                                </button>

                            </div>
                        </li>



                    </ul>
                </div>

            </div>

        </div>

        <div class="col-sm-12 col-md-12">
            <div class="card edit-user-details card-small mb-4">
                <div class="card-header border-bottom">
                    <h6 class="m-0">Photos</h6>
                </div>
                <ul style="min-height:80px;" class="list-group list-group-flush">
                    <a style="width: 300px;margin-top:20px;align-self: center;" onclick="$('#files').click()" class="btn btn-white"> Upload Photos<span>(Max 15 Photos)</span></a>
                    <input accept="image/png, image/jpeg" type="file" id="files" style="display:none" multiple>
                    <div id="photosWrap" class="photos-wrap row" style="position: relative; margin-top:10px;margin-left: 5px;margin-right: 5px;">
                        <!-- Agregar y cambiar numeros, eliminar⁄ Agregar fotos para editar -->
                        <?php
                        foreach ($photos as $key => $photo) {
                            $b64image = base64_encode(file_get_contents("https://maxauto.s3-ap-southeast-2.amazonaws.com/maxauto/listingCar/" . $photo->pic_url));
                        ?>
                            <div id="div<?= $key + 1 ?>" class="col-md-2 card21">
                                <div class="card">
                                    <div class="property-img">
                                        <img name="vehicleImg[]" src="<?= "data:image/png;base64," .  $b64image ?>" class="card-img-top" alt="...">
                                        <input type="hidden" name="postImg[]" id="img1" value="<?= "data:image/png;base64," .  $b64image ?>">
                                    </div>
                                    <div class="card-body">
                                        <div class="row" style="margin-right: unset !important; margin-left: unset !important;">
                                            <div class="card-number-wrap col-md-6 divn">
                                                <div class="card-number "><?= $key + 1 ?></div>
                                            </div>
                                            <div class="col-md-12 buttons">
                                                <input type="hidden" name="readyimg[]" id="img1" value="${input.files[index].name}">
                                                <a style='cursor:pointer' onclick="$('#div<?= $key + 1 ?>').remove(); countImg = countImg -1;$('#counter').text(countImg-1);"><img src="/assets/img/bin-icon.svg" alt="" style="width: 28px; height: 28px;"></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <?php
                        }
                        ?>


                    </div>
                </ul>
            </div>
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