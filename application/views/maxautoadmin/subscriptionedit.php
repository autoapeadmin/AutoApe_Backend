<!-- Page Header -->
<div class="page-header row no-gutters py-4">
    <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
        <span class="text-uppercase page-subtitle">Subscriptions</span>
        <h3 class="page-title">Edit Subscription</h3>
    </div>
</div>
<!-- End Page Header -->
<!-- Small Stats Blocks -->
<div class="row">
    <div class="col-lg-12 mb-12">
        <form action="/Maxautoadmin/edit_subscription/<?= $subscription[0]->subscription_id ?>" method="POST" enctype="multipart/form-data">


            <div class="row">
                <div class="col-md-6">
                    <div class="card card-small mb-4">
                        <div class="card-header border-bottom">
                            <h6 class="m-0">Subscription Info</h6>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item p-3" style="border-bottom: 0;">
                                <div class="row">
                                    <div class="col-sm-12 col-md-12">

                                        <strong style="margin-top:15px" class="text-muted d-block mb-2">Select dealership</strong>
                                        <div class="form-group">
                                            <div class="input-group mb-3">
                                                <select name="dealership" class="form-control">
                                                    <option selected="">Choose dealership</option>
                                                    <?php
                                                    foreach ($dealer as $key) {
                                                    ?>
                                                        <option <?php if ($key->dealership_id == $subscription[0]->fk_branch_id) { ?>selected<?php } ?> value="<?= $key->dealership_id ?>"><?= $key->dealership_name ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <strong class="text-muted d-block mb-2">Product name</strong>
                                        <div class="form-group">
                                            <div class="input-group mb-3">
                                                <select name="productn" class="form-control">
                                                    <option selected="">Choose Product</option>
                                                    <?php
                                                    foreach ($products as $key) {
                                                    ?>
                                                        <option <?php if ($key->id == $subscription[0]->fk_product) { ?>selected<?php } ?> value="<?= $key->id ?>"><?= $key->product_name ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>

                                        <strong class="text-muted d-block mb-2">Subscription dates</strong>
                                        <div class="row">

                                            <div class="col-12 col-sm-4 d-flex align-items-center">
                                                <div id="analytics-overview-date-range" class="input-daterange input-group input-group-sm ml-auto">
                                                    <input value="<?= str_replace(" 00:00:00", "", $subscription[0]->subscription_start_date) ?>" type="text" class="input-sm form-control" name="start" placeholder="Start Date" id="analytics-overview-date-range-1">
                                                    <span class="input-group-append">
                                                        <span class="input-group-text">
                                                            <i class="material-icons"></i>
                                                        </span>
                                                    </span>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="col-12 col-sm-12 d-flex align-items-center">
                                                <button class="btn btn-sm btn-accent ml-auto"><i class="material-icons">file_copy</i> Edit Subscription</button>
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