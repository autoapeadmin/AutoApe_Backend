<form action="/dealership/editDealership" method="POST" enctype="multipart/form-data">
    <div class="page-header row no-gutters py-4">
        <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
            <span class="text-uppercase page-subtitle">Settings</span>
            <h3 class="page-title">Dealership Profile</h3>
        </div>

        <div class="col d-flex">
            <div class="btn-group btn-group-sm d-inline-flex ml-auto my-auto" role="group" aria-label="Table row actions">
                <a id="editBtn" onclick="allowEdit()" style="margin-top: 19px;width: 140px;height: 40px;text-align: center;font-size: 14px;background-color:white;color:#114e92;border-color:#114e92" class="btn btn-white active">
                    Edit Profile
                </a>

                <div style="margin-top: 26px;">

                    <a onclick="cancelBtn2()" id="cancelbtn" style="display:none;margin-top: 19px;width: 140px;height: 40px;text-align: center;font-size: 14px;background-color:white;color:#114e92;border-color:#114e92;margin-right: 20px;" class="btn btn-white active">
                        Cancel
                    </a>
                </div>


                <button id="saveChange" onclick="saveChanges()" type="submit" style="display:none;margin-top: 19px;
    width: 140px;
    height: 40px;
    text-align: center;font-size: 14px;" href="list_vehicle" class="btn btn-white active">
                    Save Changes
                </button>

            </div>
        </div>
    </div>

    <?php
    $b64image = base64_encode(file_get_contents("https://maxauto.s3-ap-southeast-2.amazonaws.com/maxauto/dealership/logo/" . $dealership[0]->img_base64));
    ?>


    <div class="row">
        <div class="col-md-4">

            <div class="card edit-user-details card-small mb-4">
                <div class="card-header p-0">
                    <div style="max-height: 800px!important;" class="edit-user-details__bg">
                        <?php if ($dealership[0]->cover_base64_img == "") { ?>
                            <img style="height: 300px;width:100%" id="profilePhoto" src="https://designrevision.com/demo/shards-dashboards/images/user-profile/up-user-details-background.jpg" alt="User Details Background Image">
                        <?php } else { ?>
                            <img style="height: auto;width:100%" id="profilePhoto"
                            src="https://maxauto.s3-ap-southeast-2.amazonaws.com/maxauto/dealership/logo/<?= $dealership[0]->cover_base64_img  ?>" alt="User Details Background Image">
                        <?php } ?>

                        <label id="uploadCoverButton" class="edit-user-details__change-background">
                            <i class="material-icons mr-1"></i> Change Cover Photo <input onchange="readURLCover(this);" class="d-none" type="file">
                        </label>
                        <label style="display: none;" id="cropButtonCover" class="edit-user-details__change-background">
                            <i class="material-icons mr-1"></i> Crop Background Photo
                        </label>
                    </div>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item p-3" style="border-bottom: 0;">
                        <div class="row">
                            <div class="col-sm-12 col-md-4" style="margin-top: -95px;">

                                <?php if ($dealership[0]->img_base64 != "") { ?>
                                    <img src="https://maxauto.s3-ap-southeast-2.amazonaws.com/maxauto/dealership/logo/<?= $dealership[0]->img_base64  ?>" style="width:150px;height:150px;border-radius: 86px;" id="blah">
                                <?php } else { ?>
                                    <img src="/assets/img/male-placeholder.jpg" style="width:150px;height:150px;border-radius: 86px;" id="blah">
                                <?php } ?>




                                <style>
                                    .croppie-container {
                                        width: 350px;
                                        height: 350px;
                                    }

                                    .covercontainer {
                                        /* margin: 0 auto; */
                                        width: 100%;
                                        height: 350px;
                                    }
                                </style>


                                <input type="hidden" value="data:image/png;base64,<?= $b64image ?>" name="imageCroped" id="imageCroped">
                                <input type="hidden" value="<?= $dealership[0]->cover_base64_img ?>" name="imageCroped2" id="imageCroped2">

                                <div style="margin-top: 20px;display:none" class="form-group">
                                    <input class="btn btn-sm btn-accent ml-auto" accept="image/*" onchange="readURL(this);" type="file" class="form-control-file" id="images">
                                </div>

                            </div>

                            <div class="col-sm-12 col-md-8" style="margin-top: -30px;">
                                <label style="width: 100%;" id="updatelabel" for="images" class="btn btn-sm btn-white d-table  mt-4">
                                    <i class="material-icons"></i> Upload Profile Photo
                                </label>
                            </div>

                            <script>

                            </script>
                            <div class="col-sm-12 col-md-2">

                            </div>
                            <!--                             <div class="col-sm-12 col-md-6">
                                <strong class="text-muted d-block mb-2">Cover Photo</strong>
                                <input type="file" class="form-control-file" name="images" id="images">
                            </div> -->
                        </div>
                    </li>
                </ul>

                <ul class="list-group list-group-flush">
                    <li class="list-group-item px-4">


                        <div class="progress-wrapper">


                            <strong class="text-muted d-block mb-2">Profile Complete</strong>
                            <div class="progress progress-sm">
                                <div id="pgm" class="progress-bar bg-primary" role="progressbar" aria-valuenow="5" aria-valuemin="0" aria-valuemax="100" style="width: 10%;">
                                    <span class="progress-value">10%</span>
                                </div>
                            </div>
                            <hr>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="feFirstName">Dealership Name</label>
                                    <input disabled value="<?= $dealership[0]->dealership_name ?>" onblur="changePercent()" type="text" class="form-control" id="feFirstName" placeholder="First Name" name="name">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="feLastName">Company Name</label>
                                    <input disabled value="<?= $dealership[0]->company_name ?>" onblur="changePercent()" type="text" class="form-control" id="feLastName" placeholder="Last Name" name="lastname">
                                </div>
                            </div>
                            <div class="form-row">

                                <div class="form-group col-md-12">
                                    <label for="feEmailAddress">Region</label>
                                    <input value="<?= $dealership[0]->region_name ?>" disabled onblur="changePercent()" type="email" class="form-control" id="feEmailAddress" placeholder="Email" name="email2">
                                </div>

                                <div class="form-group col-md-12">
                                    <label for="feLastName">Business Group</label>
                                    <input value="<?= $dealership[0]->business_name ?>" disabled type="text" class="form-control" id="feTitle" placeholder="" name="title">
                                </div>


                            </div>


                    </li>

                </ul>

            </div>

        </div>

        <div class="col-sm-12 col-md-8">

            <div class="row">
                <div class="col-md-6">
                    <div class="card edit-user-details card-small mb-4">
                        <div class="card-header border-bottom">
                            <h6 class="m-0">Website & Email</h6>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item p-3">
                                <div class="row">
                                    <div class="col">
                                        <div class="form-row">
                                            <div class="form-group col-md-12">
                                                <label for="feFirstName">Homepage</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1">www.</span>
                                                    </div>
                                                    <input disabled="true" value="<?= $dealership[0]->dealership_website ?>" onblur="changePercent()" type="text" class="form-control phone" id="fePhone" name="phone">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label for="feLastName">Email/Login Email</label>
                                                <input disabled value="<?= $dealership[0]->dealership_email ?>" onblur="changePercent()" type="text" class="form-control landline" id="fetLand" placeholder="Email/Login Email" name="landline">
                                            </div>
                                        </div>


                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card edit-user-details card-small mb-4">
                        <div class="card-header border-bottom">
                            <h6 class="m-0">Location & Address</h6>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item p-3">
                                <div class="row">
                                    <div class="col">


                                        <div class="form-row">
                                            <div class="form-group col-md-12">
                                                <label for="feEmailAddress"> Physical Address</label>
                                                <input disabled value="<?= $dealership[0]->address ?>" onblur="changePercent()" type="email" class="form-control " id="feEmailAddress" placeholder="Email" name="email">
                                            </div>

                                        </div>
                                        <div class="form-row">

                                            <div class="form-group col-md-12">
                                                <label for="feEmailAddress"> Postal address</label>

                                                <input onblur="changePercent()" disabled value="<?= $dealership[0]->dealership_postal_address ?>" name="postal_address" type="text" class="form-control postal_address" aria-describedby="emailHelp" placeholder="Enter address" autocomplete="off">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>


            <div class="card edit-user-details card-small mb-4">
                <div class="card-header border-bottom">
                    <h6 class="m-0">Management & Contact</h6>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item p-3">
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <strong class="text-muted d-block mb-2">Dealer principal/Director/Manager Name</strong>
                                <div class="form-group">
                                    <div class="input-group mb-3">
                                        <input onblur="changePercent()" disabled value="<?= $dealership[0]->director_name ?>" type="text" class="form-control director_name" name="director_name" aria-label="Username" aria-describedby="basic-addon1">
                                    </div>
                                </div>

                                <strong class="text-muted d-block mb-2">Invoice Email</strong>
                                <div class="form-group">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">@</span>
                                        </div>
                                        <input onblur="changePercent()" disabled value="<?= $dealership[0]->contact_email ?>" type="text" class="form-control envoice_email" name="envoice_email" aria-label="Username" aria-describedby="basic-addon1">
                                    </div>
                                </div>


                            </div>
                            <div class="col-sm-12 col-md-6">
                                <strong class="text-muted d-block mb-2">Contact person name</strong>
                                <div class="form-group">
                                    <div class="input-group mb-3">
                                        <input onblur="changePercent()" disabled value="<?= $dealership[0]->contact_person ?>" type="text" class="form-control contact_name" name="contact_name" aria-label="Username" aria-describedby="basic-addon1">
                                    </div>
                                </div>

                                <strong class="text-muted d-block mb-2">Contact phone number</strong>
                                <div class="form-group">
                                    <div class="input-group mb-3">
                                        <input onblur="changePercent()" disabled value="<?= $dealership[0]->contact_phone ?>" type="text" class="form-control contact_phone" name="contact_phone" aria-label="Username" aria-describedby="basic-addon1">
                                    </div>
                                </div>
                            </div>
                        </div>



                    </li>
                </ul>
            </div>

            <div class="card edit-user-details card-small mb-4">
                <div class="card-header border-bottom">
                    <h6 class="m-0">About us</h6>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item p-3">
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <strong class="text-muted d-block mb-2">Description</strong>
                                <div class="form-group ">
                                    <textarea disabled onblur="changePercent()" class="form-control aboutme" id="aboutme" name="feDescription" rows="5"><?= $dealership[0]->dealership_description ?></textarea>
                                </div>

                            </div>

                        </div>



                    </li>
                </ul>
            </div>

        </div>


    </div>

    </div>
</form>

<!-- Button trigger modal -->
<button id="modalProfile" style="display:none" type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
    Launch demo modal
</button>

<!-- Button trigger modal -->
<button id="modalCover" style="display:none" type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCover">
    Launch demo modal
</button>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Profile Photo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div style="margin: 0 auto;" id="crops"></div>
            </div>
            <div class="modal-footer">
                <label data-dismiss="modal" class="btn btn-sm btn-white d-table  upload-result ">
                    <i class="material-icons">crop</i> Crop Photo
                </label>

            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModalCover" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Cover Photo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div style="    height: 600px;" class="modal-body">
                <div style="margin: 0 auto;" id="crops2"></div>
            </div>
            <div class="modal-footer">
                <label data-dismiss="modal" class="btn btn-sm btn-white d-table  upload-result2 ">
                    <i class="material-icons">crop</i> Crop Photo
                </label>
            </div>
        </div>
    </div>
</div>