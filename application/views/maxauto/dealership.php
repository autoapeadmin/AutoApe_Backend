<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="/assets/css/dropzone.min.css">
    <link rel="shortcut icon" type="image/x-icon" href="/assets/img/flaticon.png" />
    <title>AutoApe Dealership</title>
</head>

<body>

    <nav style="background:#0e4e92!important" class="navbar navbar-light bg-light">
        <div class="container">
            <img src="/about/img/screens/logow.png" style="width:130px;float:left" />
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
                        <a class="nav-link" href="#">Create Dealership <span class="sr-only">(current)</span></a>
                    </li>
                    <!--   <li class="nav-item ">
                        <a class="nav-link" href="newcars">New Cars <span class="sr-only">(current)</span></a>
                    </li> -->
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
                        <h4 style="color:#0e4e92"><i class="fas fa-building" style="margin-right:5px"></i>Dealership</h3>
                    </div>
                    <div class="card-body">
                        <form action="/Autoape/addDealership" method="POST">
                            <div class="form-group">

                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="exampleInputEmail1">Dealership Name</label>
                                        <input type="text" name="dealername" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter name">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="exampleInputEmail1">Dealership Website</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">https://</div>
                                            </div>
                                            <input type="text" class="form-control" id="dealerweb" placeholder="Website" name="dealerweb">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button style="background-color:#0e4e92" id="sub" type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Create</button>
                        </form>
                    </div>
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
                                <th>Website</th>
                                <th>Delete</th>
                            
                            </thead>
                            <tbody>
                                <?php
                                foreach ($data as $row) {
                                ?>
                                    <tr>
                                        <td><?= $row->dealership_id ?></td>
                                        <td><?= $row->dealership_name ?></td>
                                        <td><?= $row->dealership_website ?></td>
                                        <td><a id="delete" href="deleteDealership/<?= $row->dealership_id ?>" class="btn btn-danger"><i class="fas fa-trash-alt"></i></a></td>
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" integrity="sha512-F5QTlBqZlvuBEs9LQPqc1iZv2UMxcVXezbHzomzS6Df4MZMClge/8+gXrKw2fl5ysdk4rWjR0vKS7NNkfymaBQ==" crossorigin="anonymous"></script>
  
</body>