<div class="page-header row no-gutters py-4">
    <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
        <span class="text-uppercase page-subtitle">Salesperson</span>
        <h3 class="page-title">Create Salesperson</h3>
    </div>
</div>

<form action="/dealership/addSalesperson" method="POST" enctype="multipart/form-data">
    <div class="row">
        <div class="col-md-4">

            <div class="card edit-user-details card-small mb-4">
                <div class="card-header p-0">
                    <div style="max-height: 300px!important;" class="edit-user-details__bg">
                        <img style="height: 300px;width:100%" id="profilePhoto" src="https://designrevision.com/demo/shards-dashboards/images/user-profile/up-user-details-background.jpg" alt="User Details Background Image">
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
                            <div class="col-sm-12 col-md-12" style="margin-top: -95px;">

                                <img src="/assets/img/male-placeholder.jpg" style="width:150px;height:150px;border-radius: 86px;" id="blah">

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


                                <input type="hidden" name="imageCroped" id="imageCroped">
                                <input type="hidden" name="imageCroped2" id="imageCroped2">

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
                                    <input onblur="changePercent()" type="text" class="form-control" id="feFirstName" placeholder="First Name" name="name">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="feLastName">Last Name</label>
                                    <input onblur="changePercent()" type="text" class="form-control" id="feLastName" placeholder="Last Name" name="lastname">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="feLastName">Job Title</label>
                                    <input onblur="changePercent()" type="text" class="form-control" id="feTitle" placeholder="Job Title" name="title">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="feLastName">Other spoken languages</label>
                                    <select name="selectLanguage[]" multiple="multiple" style="width: 100%;" class="class1">
                                        <?php foreach ($flags as $flag) { ?>
                                            <option <?php if (isset($lagent)) {
                                                        foreach ($lagent as $lagent2) {
                                                            if ($flag["id_flag"] == $lagent2["fk_id_language"]) {
                                                                echo "selected";
                                                            }
                                                        }
                                                    } ?> data-image="<?php echo base_url($flag["language_img"]); ?>" value="<?= $flag["language_id"] ?>"><?= $flag["language_desc"] ?></option>

                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">

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
                                        <input onblur="changePercent()" type="text" class="form-control" id="fePhone" placeholder="Phone" name="phone">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="feLastName">Landline</label>
                                        <input onblur="changePercent()" type="text" class="form-control" id="fetLand" placeholder="Landline" name="landline">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="feEmailAddress">Email</label>
                                        <input onblur="changePercent()" type="email" class="form-control" id="feEmailAddress" placeholder="Email" name="email">
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
                        <textarea onblur="changePercent()" class="form-control aboutme" id="aboutme" name="feDescription" rows="5"></textarea>
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
                                        <select name="visible" class="custom-select">
                                            <option value="0" selected="">Yes, display profile</option>
                                            <option value="1">No, do not display profile.</option>
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
            <div class="row">

                <div class="col-md-1">
                    <a style="background-color:white;color:#114e92;" href="list_salesperson" class="btn btn-accent">Cancel</a>
                </div>

                <div class="col-md-2">
                    <button  type="submit" class="btn btn-accent">Create Profile</button>
                </div>
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