<!-- Page Header -->
<div class="page-header row no-gutters py-4">
    <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
        <span class="text-uppercase page-subtitle">Business Group</span>
        <h3 class="page-title">Create Business Group</h3>
    </div>
</div>
<!-- End Page Header -->
<!-- Small Stats Blocks -->
<div class="row">
    <div class="col-lg-12 mb-12">
        <form action="/Maxautoadmin/create_business_group" method="POST" enctype="multipart/form-data">

            <div class="row">
                <div class="col-md-6">
                    <div class="card card-small mb-4">
                        <div class="card-header border-bottom">
                            <h6 class="m-0">Business Group Info</h6>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item p-3" style="border-bottom: 0;">
                                <div class="row">
                                    <div class="col-sm-12 col-md-12">
                                        <strong class="text-muted d-block mb-2">Name of Group</strong>
                                        <div class="form-group">
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control" name="business_name" aria-label="Username" aria-describedby="basic-addon1">
                                            </div>
                                        </div>

                                        <strong class="text-muted d-block mb-2">Email of Group</strong>
                                        <div class="form-group">
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control" name="business_email" aria-label="Username" aria-describedby="basic-addon1">
                                            </div>
                                        </div>

                                        <strong class="text-muted d-block mb-2">Contact number</strong>
                                        <div class="form-group">
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control" name="contact_number" aria-label="Username" aria-describedby="basic-addon1">
                                            </div>
                                        </div>

                                        <strong class="text-muted d-block mb-2">Contact Person</strong>
                                        <div class="form-group">
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control" name="contact_person" aria-label="Username" aria-describedby="basic-addon1">
                                            </div>
                                        </div>

                                        <strong class="text-muted d-block mb-2">Address</strong>
                                        <div class="form-group">
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control" name="address" aria-label="Username" aria-describedby="basic-addon1">
                                            </div>
                                        </div>

                                        <button class="btn btn-sm btn-accent ml-auto"><i class="material-icons">file_copy</i> Create Business Group</button>
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