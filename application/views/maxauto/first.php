<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="/assets/css/dropzone.min.css">
    <link rel="shortcut icon" type="image/x-icon" href="/assets/img/flaticon.png" />
    <title>Charging Stations</title>
</head>

<body>

    <nav style="background:#0e4e92!important" class="navbar navbar-light bg-light">
        <div class="container">
            <img src="/assets/img/logo.png" style="width:130px;float:left" />
        </div>
    </nav>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item active">
                        <a class="nav-link" href="#">Charging Stations <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item ">
                        <a class="nav-link" href="newcars">New Cars <span class="sr-only">(current)</span></a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- SETUP DATA -->
    <div style="margin-top:30" class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h4 style="color:#0e4e92"><i class="fas fa-plug" style="margin-right:5px"></i>Charging Station</h3>
                    </div>
                    <div class="card-body">
                        <form action="/maxauto/addCharging" method="POST">
                            <div class="form-group">

                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="exampleInputEmail1">Name</label>
                                        <select name="name" class="form-control">
                                            <option disabled selected value> Select option or empty to create --> </option>
                                            <?php
                                            foreach ($owner as $row) {
                                            ?>
                                                <option value="<?= $row->name ?>">
                                                    <?= $row->name ?>
                                                </option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="exampleInputEmail1">Add new</label>
                                        <input type="text" name="nuevo" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter name">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Address</label>
                                <input id="findaddress" name="address" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter address">
                            </div>
                            <label for="exampleInputEmail1">Lat</label>
                            <input onblur="drawMap()" class="form-control" type="text" name="lat1" id="lat1" value="" placeholder="-36.79988229999999" />
                            <label for="exampleInputEmail1">Long</label>
                            <input onblur="drawMap()" class="form-control" type="text" name="lon1" id="lon1" value="" placeholder="174.7375245" />

                            <input type="hidden" name="lat" id="lat" value="" />

                            <input type="hidden" name="lon" id="lon" value="" />
                            <div class="form-group">
                                <label for="exampleInputEmail1">Description Address</label>
                                <input name="desc" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter address description">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Connectors</label><br>

                                <input type="checkbox" id="1" name="con1" checked>
                                <label for="1">CHAdeMO</label>

                                <input type="checkbox" id="2" name="con2" checked>
                                <label for="2">CCS (type 2)</label>

                                <input type="checkbox" id="3" name="con3">
                                <label for="3">TESLA</label>
                            </div>
                            <div style="display:none" class="form-group">
                                <label for="exampleInputEmail1">Phone</label>
                                <input name="phone" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter Phone">
                            </div>
                            <button style="background-color:#0e4e92" id="sub" type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Submit</button>
                        </form>
                    </div>
                </div>


            </div>

            <div class="col-md-6">
                <div style="height:658px;" class="card shadow mb-4">

                    <iframe id="googleMap" width="100%" height="100%" frameborder="0" style="border:0" src="https://www.google.com/maps/embed/v1/place?q=newzealand&key=AIzaSyBC2goRItT_XPUke72aQtH2gr6nVvWK4xw" allowfullscreen></iframe>
                </div>
            </div>

            <div style="margin-top:10" class="col-md-12">

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h4 style="color:#0e4e92"><i class="fas fa-plug" style="margin-right:5px"></i>Uploaded</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-hover">
                            <thead>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Lat</th>
                                <th>Long</th>
                                <th>Delete</th>
                                <th>Map</th>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($data as $row) {
                                ?>
                                    <tr>
                                        <td><?= $row->id ?></td>
                                        <td><?= $row->name ?></td>
                                        <td><?= $row->address ?></td>
                                        <td><?= $row->lat ?></td>
                                        <td><?= $row->long ?></td>
                                        <td><a id="delete" href="deleteCharging/<?= $row->id ?>" class="btn btn-danger"><i class="fas fa-trash-alt"></i></a></td>
                                        <td onclick="viewMap2('<?= $row->lat ?>','<?= $row->long ?>')"><a style="color:white" id="view" class="btn btn-info"><i class="fas fa-map"></i></a></td>
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
    </div>

    <!-- Nav Step -->
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

                    document.getElementById("lat1").value = position.coords.latitude;
                    document.getElementById("lon1").value = position.coords.longitude;


                    var circle = new google.maps.Circle({
                        center: geolocation,
                        radius: position.coords.accuracy
                    });
                    autocomplete.setBounds(circle.getBounds());

                    drawMap();
                });
            }
        }

        function drawMap() {
            var lat = document.getElementById("lat1").value;
            var lon = document.getElementById("lon1").value;
            document.getElementById('googleMap').src = "https://www.google.com/maps/embed/v1/place?q=" + lat + "," + lon + "&key=AIzaSyBC2goRItT_XPUke72aQtH2gr6nVvWK4xw";
        }

        function viewMap2(lat, lon) {
            document.getElementById('googleMap').src = "https://www.google.com/maps/embed/v1/place?q=" + lat + "," + lon + "&key=AIzaSyBC2goRItT_XPUke72aQtH2gr6nVvWK4xw";
        }
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" integrity="sha512-F5QTlBqZlvuBEs9LQPqc1iZv2UMxcVXezbHzomzS6Df4MZMClge/8+gXrKw2fl5ysdk4rWjR0vKS7NNkfymaBQ==" crossorigin="anonymous"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB6iV9miWQ3t-0lrXxMl58iOX3r_gA6JTw&libraries=places&callback=initAutocomplete" async defer></script>

</body>