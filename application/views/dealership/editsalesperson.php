<form action="/dealership/editSalesperson/<?= $agent[0]->id_consultant
                                            ?>" method="POST" enctype="multipart/form-data">

    <div class="page-header row no-gutters py-4">
        <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
            <span class="text-uppercase page-subtitle">Personnel</span>
            <h3 class="page-title"><?= $agent[0]->consultant_first_name ?> <?= $agent[0]->consultant_last_name ?></h3>
        </div>
        <div class="col d-flex">
            <div class="btn-group btn-group-sm d-inline-flex ml-auto my-auto" role="group" aria-label="Table row actions">


                <a id="editBtn" onclick="allowEditSales()" style="margin-top: 19px;width: 140px;height: 40px;text-align: center;font-size: 14px;background-color:white;color:#114e92;border-color:#114e92" class="btn btn-white active">
                    Edit Profile
                </a>


                <div style="margin-top: 26px;">

                    <a onclick="cancelBtn()" id="cancelbtn" style="display:none;margin-top: 19px;width: 140px;height: 40px;text-align: center;font-size: 14px;background-color:white;color:#114e92;border-color:#114e92;margin-right: 20px;" class="btn btn-white active">
                        Cancel
                    </a>
                </div>

                <button id="saveChange" onclick="saveChangesSales()" type="submit" style="display:none;margin-top: 19px;
    width: 140px;
    height: 40px;
    text-align: center;font-size: 14px;" href="list_vehicle" class="btn btn-white active">
                    Save Changes
                </button>






            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">

            <div class="card edit-user-details card-small mb-4">
                <div class="card-header p-0">
                    <div style="max-height: 800px!important;" class="edit-user-details__bg">
                        <?php if ($agent[0]->cover_base64_img == "") { ?>
                            <img style="height: 300px;width:100%" id="profilePhoto" src="https://designrevision.com/demo/shards-dashboards/images/user-profile/up-user-details-background.jpg" alt="User Details Background Image">
                        <?php } else { ?>
                            <img style="height: auto;width:100%" id="profilePhoto" src="<?= $agent[0]->cover_base64_img ?>" alt="User Details Background Image">
                        <?php } ?>

                        <label id="uploadCoverButton" class="edit-user-details__change-background">
                            <i class="material-icons mr-1"></i> Change Background Photo <input onchange="readURLCover(this);" class="d-none" type="file">
                        </label>
                        <label style="display: none;" id="cropButtonCover" class="edit-user-details__change-background">
                            <i class="material-icons mr-1"></i> Crop Background Photo
                        </label>
                    </div>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item p-3" style="border-bottom: 0;">
                        <div class="row">

                            <?php
                            $b64image = base64_encode(file_get_contents("https://maxauto.s3-ap-southeast-2.amazonaws.com/maxauto/dealership/logo/" . $agent[0]->base64_img));
                            ?>

                            <div class="col-sm-12 col-md-12" style="margin-top: -95px;">
                                <?php if ($agent[0]->base64_img != "") { ?>
                                    <img src="https://maxauto.s3-ap-southeast-2.amazonaws.com/maxauto/dealership/logo/<?= $agent[0]->base64_img ?>" style="width:150px;height:150px;border-radius: 86px;border: 2px white solid;" id="blah">
                                <?php } else { ?>
                                    <img src="/assets/img/male-placeholder.jpg" style="width:150px;height:150px;border-radius: 86px;border: 2px white solid;" id="blah">
                                <?php } ?>


                                <label style="width: 100%;" id="updatelabel" for="images" class="btn btn-sm btn-white d-table  mt-4">
                                    <i class="material-icons"></i> Upload Profile Photo
                                </label>

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
                                <input type="hidden" value="<?= $agent[0]->cover_base64_img ?>" name="imageCroped2" id="imageCroped2">

                                <div style="margin-top: 20px;display:none" class="form-group">
                                    <input class="btn btn-sm btn-accent ml-auto" accept="image/*" onchange="readURL(this);" type="file" class="form-control-file" id="images">
                                </div>

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
                                    <span style="font-size: medium;" class="progress-value">10%</span>
                                </div>
                            </div>
                            <hr>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="feFirstName">First Name</label>
                                    <input disabled value="<?= $agent[0]->consultant_first_name ?>" onblur="changePercent()" type="text" class="form-control name" id="feFirstName" placeholder="First Name" name="name">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="feLastName">Last Name</label>
                                    <input disabled value="<?= $agent[0]->consultant_last_name ?>" onblur="changePercent()" type="text" class="form-control lastname" id="feLastName" placeholder="Last Name" name="lastname">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="feLastName">Job Title</label>
                                    <input disabled onblur="changePercent()" type="text" class="form-control title" id="feTitle" placeholder="Last Name" name="title" value="<?= $agent[0]->sales_consultant_title ?>">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="feLastName">Other spoken languages</label>
                                    <select disabled name="selectLanguage[]" multiple="multiple" style="width: 100%;" class="class1 selectLanguage">
                                        <?php foreach ($flags as $flag) { ?>
                                            <option <?php if (isset($lagent)) {
                                                        foreach ($lagent as $lagent2) {
                                                            if ($flag["language_id"] == $lagent2["fk_language_id"]) {
                                                                echo "selected";
                                                            }
                                                        }
                                                    } ?> data-image="<?php echo base_url($flag["language_img"]); ?>" value="<?= $flag["language_id"] ?>"><?= $flag["language_desc"] ?></option>

                                        <?php } ?>
                                    </select>
                                </div>
                            </div>



                    </li>

                </ul>

            </div>

        </div>

        <div class="col-sm-12 col-md-8">
            <div class="card edit-user-details card-small mb-4">
                <div class="card-header border-bottom">
                    <h6 class="m-0">Contact Details</h6>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item p-3">
                        <div class="row">
                            <div class="col">

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="feFirstName">Mobile</label>
                                        <input disabled value="<?= $agent[0]->consultant_mobile ?>" onblur="changePercent()" type="text" class="form-control phone" id="fePhone" placeholder="Phone" name="phone">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="feLastName">Landline</label>
                                        <input disabled value="<?= $agent[0]->consultan_landline ?>" onblur="changePercent()" type="text" class="form-control landline" id="fetLand" placeholder="Landline" name="landline">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="feEmailAddress">Email</label>
                                        <input disabled value="<?= $agent[0]->consultant_email ?>" onblur="changePercent()" type="email" class="form-control email" id="feEmailAddress" placeholder="Email" name="email">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="card edit-user-details card-small mb-4">
                <div class="card-header border-bottom">
                    <h6 class="m-0">About me</h6>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item p-3">
                        <label for="feDescription">Description</label>
                        <textarea disabled onblur="changePercent()" class="form-control aboutme" id="aboutme" name="feDescription" rows="5"><?= $agent[0]->consultant_description ?></textarea>
                    </li>
                </ul>
            </div>

            <div class="card edit-user-details card-small mb-4">
                <div class="card-header border-bottom">
                    <h6 class="m-0">Profile Privacy</h6>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item p-3">
                        <div class="row">
                            <div class="col">

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="displayEmail">Display Profile Publicly</label>
                                        <select disabled name="visible" class="custom-select visible23">
                                            <option <?php if ($agent[0]->is_visible == "0") {
                                                        echo "selected";
                                                    } ?> value="0">Yes, display profile</option>
                                            <option <?php if ($agent[0]->is_visible == "1") {
                                                        echo "selected";
                                                    } ?> value="1">No, do not display profile.</option>
                                        </select>
                                    </div>
                                    <!--  <div class="col-6 col-sm-6  pb-3 pt-2 ">
                                        <div class="progress-wrapper">
                                            <label for="displayEmail">Display Profile Publicly</label>
                                            <div class="progress progress-sm" style="margin-top: 6px;">
                                                <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%;">
                                                    <span class="progress-value">80%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div> -->
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