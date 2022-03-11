<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="/assets/css/dropzone.min.css">

    <link rel="shortcut icon" type="image/x-icon" href="/assets/img/flaticon.png" />
    <title>New Cars</title>
</head>

<body>

    <nav style="background:#0e4e92!important" class="navbar navbar-light bg-light">
        <div class="container">
            <img src="/assets/img/logo.png" style="width:130px;float:left" />
            <li class="nav-item dropdown no-arrow">
                <a style="margin-top:-20px" class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span style="color:white" class="mr-2 d-none d-lg-inline text-white-600 small">Admin</span>
                    <img style="width:20;color:white" class="img-profile rounded-circle" src="/assets/img/unnamed.png">
                </a>
            </li>
        </div>
    </nav>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item ">
                        <a class="nav-link" href="chargingStation">Charging Stations <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="#">New Cars <span class="sr-only">(current)</span></a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- SETUP DATA -->
    <div style="margin-top:30" class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h4 style="color:#0e4e92"><i style="margin-right:5px" class="fas fa-car"></i>Add New Car</h3>
                    </div>
                    <div class="card-body">
                        <form action="/maxauto/addNewCar" method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="exampleInputEmail1">Make</label>
                                        <select onchange="func(this.value)" class="form-control" name="make" id="make">
                                            <?php
                                            foreach ($allMake as $make) {
                                            ?>
                                                <tr>
                                                    <option value="<?= $make->make_id ?>"><?= $make->make_description ?></option>
                                                </tr>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="exampleInputEmail1">Model</label>
                                        <input class="form-control" name="model" id="model">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="exampleInputEmail1">Submodel</label>
                                        <input type="text" name="submodel" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter submodel">
                                    </div>
                                    <div class="col-md-3">
                                        <input style="margin-top:40" type="checkbox" id="mainmodel" name="mainmodel">
                                        <label for="1">Main Model</label>
                                    </div>
                                </div>
                                <div style="margin-top:15" class="row">


                                    <div class="col-md-4">
                                        <label for="exampleInputEmail1">Body Type</label>
                                        <select class="form-control" name="body" id="body">
                                            <?php
                                            foreach ($body as $body1) {
                                            ?>

                                                <option value="<?= $body1['body_type_id'] ?>"><?= $body1["body_type_name"] ?></option>

                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="exampleInputEmail1">Fuel Type</label>
                                        <select class="form-control" name="fueltype" id="fueltype">
                                            <option value="Petrol">Petrol</option>
                                            <option value="Diesel">Diesel</option>
                                            <option value="Hybrid">Hybrid</option>
                                            <option value="Plug-in hybrid">Plug-in hybrid</option>
                                            <option value="Electric">Electric</option>
                                            <option value="LPG">LPG</option>
                                            <option value="Alternative">Alternative</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="exampleInputEmail1">Fuel consumption combined in l/100 km</label>
                                        <input type="text" name="fuel_consu" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="4.8l/100km">
                                    </div>

                                </div>

                                <div style="margin-top:15" class="row">
                                    <div class="col-md-4">
                                        <label for="exampleInputEmail1">Transmission</label>
                                        <select class="form-control" name="trans" id="trans">
                                            <option value="Manual">Manual</option>
                                            <option value="Automatic">Automatic</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="exampleInputEmail1">Drive Type </label>
                                        <select class="form-control" name="drive" id="drive">
                                            <option value="2WD">2WD</option>
                                            <option value="4WD">4WD</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="exampleInputEmail1">Engine Size</label>
                                        <input type="text" name="engine" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="3.3CC or 3L">
                                    </div>
                                </div>

                                <div style="margin-top:15" class="row">
                                    <div class="col-md-4">
                                        <label for="exampleInputEmail1">URL</label>
                                        <input type="text" name="url" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="URL">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="exampleInputEmail1">Safety Rating</label>
                                        <input max="5" type="safety" name="safety" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="1">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="exampleInputEmail1">Fuel Economy</label>
                                        <input max="6" type="fuel" name="fuel" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="1">
                                    </div>
                                </div>

                                <div style="margin-top:20px" class="row">
                                    <div class="col-md-4">
                                        <input type="file" class="form-control-file" name="images" id="images">
                                    </div>
                                </div>

                                <label for="exampleInputEmail1">Description</label>
                                <textarea name="desc" class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>

                            </div>
                            <button style="background-color:#0e4e92" id="sub" type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>



            <div class="col-md-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h4 style="color:#0e4e92"><i class="fas fa-car" style="margin-right:5px"></i>Uploaded</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-hover">
                            <thead>
                                <th>ID</th>
                                <th>Make</th>
                                <th>Model</th>
                                <th>Submodel</th>

                                <th>URL</th>
                                <th>Delete</th>
                                <th>Img</th>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($data as $row) {
                                ?>
                                    <tr>
                                        <td><?= $row->id ?></td>
                                        <td><?= $row->make_description ?></td>
                                        <td><?= $row->model ?></td>
                                        <td><?= $row->submodel ?></td>
                                        <td><?= $row->url ?></td>
                                        <td><a id="delete" href="deleteCar/<?= $row->id ?>" class="btn btn-danger"><i class="fas fa-trash-alt"></i></a></td>
                                        <td><img style="width:100px" src="https://maxauto.s3-ap-southeast-2.amazonaws.com/maxauto/<?= $row->url_image ?>" /></td>
                                        <td>
                                            <form action="/maxauto/editPhoto/<?= $row->id ?>/<?= $row->url_image ?>" method="POST" enctype="multipart/form-data">
                                                <input type="file" class="form-control-file" name="images" id="images">
                                                <button style="background-color:#0e4e92" type="submit" class="btn btn-primary">Submit</button>
                                            </form>
                                        </td>

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
                });
            }
        }

        function func(id) {

            $.ajax({
                    url: 'getModels/' + id,
                    type: 'POST',
                })
                .done(function(response) {  
                    $('#model').empty();
                    response.data.forEach(element => {
                        console.log(element)
                        $('#model').append($('<option>', {
                            value: element.model_id,
                            text: element.model_desc
                        }));
                    });
                })

        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB6iV9miWQ3t-0lrXxMl58iOX3r_gA6JTw&libraries=places&callback=initAutocomplete" async defer></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" integrity="sha512-F5QTlBqZlvuBEs9LQPqc1iZv2UMxcVXezbHzomzS6Df4MZMClge/8+gXrKw2fl5ysdk4rWjR0vKS7NNkfymaBQ==" crossorigin="anonymous"></script>

</body>