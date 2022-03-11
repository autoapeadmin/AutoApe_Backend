<!-- Page Header -->
<div class="page-header row no-gutters py-4">
    <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
        <span class="text-uppercase page-subtitle">Dashboard</span>
        <h3 class="page-title">ANALYTICS</h3>
    </div>
</div>
<!-- End Page Header -->
<!-- Small Stats Blocks -->
<div class="row">
    <div class="col-lg-9 mb-8">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-12 mb-4">
                <div class="stats-small card card-small">
                    <div onclick="goTo('vehicle_list')" style="cursor:pointer" class="card-body px-0 pb-0">
                        <div class="row">
                            <div class="col-md-4 col-sm-12">
                                <div style="text-align:center">
                                    <img style="width: 60px;" class="user-avatar rounded-circle ml-2" src="/assets/img/d1.png" alt="User Avatar">
                                </div>
                            </div>
                            <div class="col-md-8 col-sm-12">
                                <div class="stats-small__data">
                                    <span style="font-size:9px!important" class="stats-small__label mb-1 text-uppercase">Monthly Listing</span>
                                    <h6 class="stats-small__value count m-0"><?= $total_month ?> / <?= $subscriptiondata[0]->max_listing_monthly ?></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                function goTo(url) {
                    window.location.href = url;
                }
            </script>

            <div class="col-lg-3 col-md-3 col-sm-12 mb-4">
                <div class="stats-small card card-small">
                    <div onclick="goTo('list_salesperson')" style="cursor:pointer" class="card-body px-0 pb-0">
                        <div class="row">
                            <div class="col-md-4 col-sm-12">
                                <div style="text-align:center">
                                    <img style="width: 60px;" class="user-avatar rounded-circle ml-2" src="/assets/img/d2.png" alt="User Avatar">
                                </div>
                            </div>
                            <div class="col-md-8 col-sm-12">
                                <div class="stats-small__data">
                                    <span style="font-size:9px!important" class="stats-small__label mb-1 text-uppercase">Salespersons</span>
                                    <h6 class="stats-small__value count m-0"><?= $nCustomer ?></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-3 col-sm-12 mb-4">
                <div class="stats-small card card-small">
                    <div class="card-body px-0 pb-0">
                        <div class="row">
                            <div class="col-md-4 col-sm-12">
                                <div style="text-align:center">
                                    <img style="width: 60px;" class="user-avatar rounded-circle ml-2" src="/assets/img/d3.png" alt="User Avatar">
                                </div>
                            </div>
                            <div class="col-md-8 col-sm-12">
                                <div class="stats-small__data">
                                    <span style="font-size:9px!important" class="stats-small__label mb-1 text-uppercase">Sold this month</span>
                                    
                                    <small class="text-muted count m-0">Vehicles: <?= $soldmo[0]->number ?></small>
                                    <small class="text-muted count m-0">Total: $<?= number_format($soldmo[0]->total) ?></small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-3 col-sm-12 mb-4">
                <div class="stats-small card card-small">
                    <div class="card-body px-0 pb-0">
                        <div class="row">
                            <div class="col-md-4 col-sm-12">
                                <div style="text-align:center">
                                    <img style="width: 60px;" class="user-avatar rounded-circle ml-2" src="/assets/img/d4.png" alt="User Avatar">
                                </div>
                            </div>
                            <div class="col-md-8 col-sm-12">
                                <div class="stats-small__data">
                                    <span style="font-size:9px!important" class="stats-small__label mb-1 text-uppercase">Sold last month</span>
                                    <h6 class="stats-small__value count m-0">$0.00</h6>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>

            <div class="col col-lg-12 col-md-12
             col-sm-12 mb-4">
                <div class="card card-small h-100">

                    <div class="card-body pt-0">
                        <h6 style="font-weight: 600;" class="m-0 pt-4">Monthly vehicle tracking</h6>
                        <canvas id="myChart"></canvas>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div  class="col col-lg-3 col-md-3 col-sm-12 mb-4">
        <div class="card card-small h-100">
            <div class="card-header border-bottom">

                <h6 style="font-weight: 600;" class="m-0">Recent Sales</h6>
                <div class="block-handle"></div>
            </div>
            <div class="card-body pt-3">
                <?php
                foreach ($lastSold as $vehicle) {
                ?>
                    <div class="row">
                        <div style="text-align: center;" class="col-md-4">
                            <img class="imglistveh" style="border-radius: 4px;    width: 100%;
    height: 70px;
" src="<?php if (property_exists($vehicle, "pic_url")) {
                        echo $vehicle->pic_url;
                    } else {
                        echo "https://toitoi.nz/wp-content/uploads/2020/04/placeholder.png";
                    }  ?>" />
                        </div>
                        <div class="col-md-8">
                            <div>
                                <span style="color:#5a626a; font-size:initial;" class="card-post__author-name pt-1"><?= $vehicle->make_description ?> <?= $vehicle->model_desc ?></span><br>
                                <small style="font-size:small;" class="text-muted pt-2"><?= $vehicle->vehicule_rego ?> </small>
                                <br>
                                <small style="font-size:small;" class="text-muted pt-2"><?= $vehicle->vehicule_year ?> </small>
                            </div>
                        </div>
                    </div>
                    <hr>
                <?php
                }
                ?>
            </div>
            <div class="card-footer ">
                <a style="width: 100%;height:50px;     font-size: medium;
    padding-top: 13px;" href="vehicle_list?t=2" class="btn btn-white active">
                    View More
                </a>
            </div>

        </div>
    </div>

    <div style="display:none" class="col-lg-3 col-md-3 col-sm-12 mb-4 myprofiledash">
        <div class="stats-small card card-small">
            <div class="card-body px-3 pb-0">
                <div class="row">
                    <div class="col-md-3">
                        <div style="text-align:center">
                            <img style="    width: 60px;" class="user-avatar rounded-circle mr-2" src="https://maxauto.s3-ap-southeast-2.amazonaws.com/maxauto/dealership/logo/<?= $_SESSION["DEALER_LOGO"] ?>" alt="User Avatar">
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="stats-small__data">
                            <span class="card-post__author-name pt-1"><?= $_SESSION["DEALER_NAME"] ?></span>
                            <a href="messages"><small class="text-muted pt-2"><?= $nMessage ?> New Messages</span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>