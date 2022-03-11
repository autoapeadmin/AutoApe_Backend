<!-- Page Header -->
<div class="page-header row no-gutters py-4">
    <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
        <span class="text-uppercase page-subtitle">Dealerships</span>
        <h3 class="page-title">Create Dealership</h3>
    </div>
</div>
<!-- End Page Header -->
<!-- Small Stats Blocks -->
<div class="row">
    <div class="col-lg-12 mb-12">
        <form action="/Maxautoadmin/create_dealership" method="POST" enctype="multipart/form-data">

            <div class="row">
                <div class="col-md-6">
                    <div class="card card-small mb-4">
                        <div class="card-header border-bottom">
                            <h6 class="m-0">Dealership Info</h6>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item p-3" style="border-bottom: 0;">
                                <div class="row">
                                    <div class="col-sm-12 col-md-12">
                                        <strong class="text-muted d-block mb-2">Dealership name</strong>
                                        <div class="form-group">
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control" name="dealership_name" aria-label="Username" aria-describedby="basic-addon1">
                                            </div>
                                        </div>

                                        <strong class="text-muted d-block mb-2">Company name</strong>
                                        <div class="form-group">
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control" name="company_name" aria-label="Username" aria-describedby="basic-addon1">
                                            </div>
                                        </div>

                                        <strong style="margin-top:15px" class="text-muted d-block mb-2">Business group</strong>
                                        <div class="form-group">
                                            <div class="input-group mb-3">
                                                <select name="region" class="form-control">
                                                    <option selected="">Choose region</option>
                                                    <?php
                                                    foreach ($region as $key) {
                                                    ?>
                                                        <option value="<?= $key["region_id"] ?>"><?= $key["region_name"] ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>


                                        <strong class="text-muted d-block mb-2">Dealership Notes</strong>
                                        <div class="form-group">
                                            <div class="input-group mb-3">
                                                <textarea style="height:85px" type="text" class="form-control" name="dealership_desc" aria-label="Username" aria-describedby="basic-addon1"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card card-small mb-4">
                        <div class="card-header border-bottom">
                            <h6 class="m-0">Dealership Info</h6>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item p-3" style="border-bottom: 0;">
                                <div class="row">
                                    <div class="col-sm-12 col-md-12">


                                        <strong class="text-muted d-block mb-2">Dealership website</strong>
                                        <div class="form-group">
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control" name="dealership_web" aria-label="Username" aria-describedby="basic-addon1">
                                            </div>
                                        </div>

                                        <strong class="text-muted d-block mb-2">Dealership Email/Login Email</strong>
                                        <div class="form-group">
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1">@</span>
                                                </div>
                                                <input type="text" class="form-control" name="dealership_email" aria-label="Username" aria-describedby="basic-addon1">
                                            </div>
                                        </div>


                                        <strong class="text-muted d-block mb-2">Dealership address</strong>
                                        <div class="form-group">
                                            <div class="input-group mb-3">
                                                <input id="findaddress" name="address" type="text" class="form-control" aria-describedby="emailHelp" placeholder="Enter address" autocomplete="off">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="input-group col-md-6">
                                                <input class="form-control" type="text" name="lat" id="lat" placeholder="lat" value="" />
                                            </div>
                                            <div class="input-group col-md-6">
                                                <input class="form-control" type="text" name="lon" id="lon" placeholder="long" value="" />
                                            </div>
                                        </div>

                                        <strong style="margin-top:15px" class="text-muted d-block mb-2">Dealership region</strong>
                                        <div class="form-group">
                                            <div class="input-group mb-3">
                                                <select name="region" class="form-control">
                                                    <option selected="">Choose region</option>
                                                    <?php
                                                    foreach ($region as $key) {
                                                    ?>
                                                        <option value="<?= $key["region_id"] ?>"><?= $key["region_name"] ?></option>
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
            </div>


            <div class="card card-small mb-4">

                <div class="card-header border-bottom">
                    <h6 class="m-0">Contact & Invoice Info</h6>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item p-3" style="border-bottom: 0;">
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <strong class="text-muted d-block mb-2">Director name</strong>
                                <div class="form-group">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" name="director_name" aria-label="Username" aria-describedby="basic-addon1">
                                    </div>
                                </div>

                                <strong class="text-muted d-block mb-2">Envoice Email</strong>
                                <div class="form-group">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">@</span>
                                        </div>
                                        <input type="text" class="form-control" name="envoice_email" aria-label="Username" aria-describedby="basic-addon1">
                                    </div>
                                </div>

                                <strong class="text-muted d-block mb-2">Postal address</strong>
                                <div class="form-group">
                                    <div class="input-group mb-3">
                                        <input name="postal_address" type="text" class="form-control" aria-describedby="emailHelp" placeholder="Enter address" autocomplete="off">
                                    </div>
                                </div>

                            </div>
                            <div class="col-sm-12 col-md-6">
                                <strong class="text-muted d-block mb-2">Contact person name</strong>
                                <div class="form-group">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" name="contact_name" aria-label="Username" aria-describedby="basic-addon1">
                                    </div>
                                </div>

                                <strong class="text-muted d-block mb-2">Contact phone number</strong>
                                <div class="form-group">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" name="contact_phone" aria-label="Username" aria-describedby="basic-addon1">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="card card-small mb-4">
                <div class="card-header border-bottom">
                    <h6 class="m-0">Images</h6>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item p-3" style="border-bottom: 0;">
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <strong class="text-muted d-block mb-2">Logo Image</strong>
                                <div class="form-group">
                                    <input accept="image/*" onchange="readURL(this);" type="file" class="form-control-file" name="images" id="images">
                                </div>

                                <input type='hidden' name="imageCroped" id="imageCroped">

                                <img style="width:100px;height:100px;margin-top: 15px;" id="blah" />

                                <a class="upload-result btn btn-sm btn-success ml-auto">Crop</a>
                            </div>
                            <script>

                            </script>
                            <div class="col-sm-12 col-md-6">


                                <div id="crops"></div>
                            </div>
                            <!--                             <div class="col-sm-12 col-md-6">
                                <strong class="text-muted d-block mb-2">Cover Photo</strong>
                                <input type="file" class="form-control-file" name="images" id="images">
                            </div> -->
                        </div>
                    </li>
                </ul>

                <ul class="list-group list-group-flush">
                    <li class="list-group-item p-3" style="border-bottom: 0;">
                        <button class="btn btn-sm btn-accent ml-auto"><i class="material-icons">file_copy</i> Create Dealership</button>
                    </li>
                </ul>
        </form>
    </div>
</div>
</div>

<script>
    //address auto complate
    //address auto complate
    var placeSearch, autocomplete;


    var placeSearch, autocomplete, autocomplete1;

    function initAutocomplete() {

        autocomplete = new google.maps.places.Autocomplete(
            /** @type {!HTMLInputElement} */
            (document.getElementById('findaddress')), {
                types: ['geocode'],
                componentRestrictions: {
                    country: "nz"
                }
            });

        autocomplete.addListener('place_changed', fillInAddress);

    }

    function fillInAddress() {
        // Get the place details from the autocomplete object.
        var place = autocomplete.getPlace();

        console.log(place);

        document.getElementById("findaddress").value = place.address_components[0].long_name + " " + place.address_components[1].long_name + ", " + place.address_components[2].long_name + ", " + place.address_components[3].long_name + ", " + place.address_components[5].long_name;
        document.getElementById("lat").value = place.geometry.location.lat();
        document.getElementById("lon").value = place.geometry.location.lng();

        document.getElementById("lat1").value = place.geometry.location.lat();
        document.getElementById("lon1").value = place.geometry.location.lng();

        document.getElementById("sub").disabled = false;

        drawMap();

        // Get each component of the address from the place details
        // and fill the corresponding field on the form.


        ////////
    }

    function geolocate() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var geolocation = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };
                document.getElementById("lat").value = position.coords.latitude;
                document.getElementById("lon").value = position.coords.longitude;

                var circle = new google.maps.Circle({
                    center: geolocation,
                    radius: position.coords.accuracy
                });
                autocomplete.setBounds(circle.getBounds());
            });
        }
    }
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB6iV9miWQ3t-0lrXxMl58iOX3r_gA6JTw&libraries=places&callback=initAutocomplete" async defer></script>