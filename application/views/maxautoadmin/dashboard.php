<!-- Page Header -->
<div class="page-header row no-gutters py-4">
    <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
        <span class="text-uppercase page-subtitle">Dashboard</span>
        <h3 class="page-title">Analytics</h3>
    </div>
</div>
<!-- End Page Header -->
<!-- Small Stats Blocks -->
<div class="row">
    <div class="col-lg-12 mb-12">
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
                <div class="stats-small card card-small">
                    <div class="card-body px-0 pb-0">
                        <div class="chartjs-size-monitor" style="position: absolute; inset: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;">
                            <div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                <div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div>
                            </div>
                            <div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                <div style="position:absolute;width:200%;height:200%;left:0; top:0"></div>
                            </div>
                        </div>
                        <div class="d-flex px-3">
                            <div class="stats-small__data">
                                <span style="font-size:9px!important" class="stats-small__label mb-1 text-uppercase">Total Vehicle</span>
                                <h6 class="stats-small__value count m-0"><?= $nVehicle ?></h6>
                            </div>
                            <div class="stats-small__data text-right">
                                <span class="stats-small__percentage stats-small__percentage--increase">3,71%</span>
                            </div>
                        </div>
                        <canvas height="46" id="cars"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
                <div class="stats-small card card-small">
                    <div class="card-body px-0 pb-0">
                        <div class="chartjs-size-monitor" style="position: absolute; inset: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;">
                            <div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                <div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div>
                            </div>
                            <div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                <div style="position:absolute;width:200%;height:200%;left:0; top:0"></div>
                            </div>
                        </div>
                        <div class="d-flex px-3">
                            <div class="stats-small__data">
                                <span style="font-size:9px!important" class="stats-small__label mb-1 text-uppercase">Total Customers</span>
                                <h6 class="stats-small__value count m-0"><?= $nCustomer ?></h6>
                            </div>
                            <div class="stats-small__data text-right">
                                <span class="stats-small__percentage stats-small__percentage--increase">3,71%</span>
                            </div>
                        </div>
                        <canvas height="46" id="customers"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
                <div class="stats-small card card-small">
                    <div class="card-body px-0 pb-0">
                        <div class="chartjs-size-monitor" style="position: absolute; inset: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;">
                            <div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                <div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div>
                            </div>
                            <div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                <div style="position:absolute;width:200%;height:200%;left:0; top:0"></div>
                            </div>
                        </div>
                        <div class="d-flex px-3">
                            <div class="stats-small__data">
                                <span style="font-size:9px!important" class="stats-small__label mb-1 text-uppercase">Total Active Dealership</span>
                                <h6 class="stats-small__value count m-0"><?= $nDealer ?></h6>
                            </div>
                            <div class="stats-small__data text-right">
                                <span class="stats-small__percentage stats-small__percentage--increase">3,71%</span>
                            </div>
                        </div>
                        <canvas height="46" id="dealerships"></canvas>
                    </div>
                </div>
            </div>


            <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
                <div class="stats-small card card-small">
                    <div class="card-body px-0 pb-0">
                        <div class="chartjs-size-monitor" style="position: absolute; inset: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;">
                            <div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                <div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div>
                            </div>
                            <div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                <div style="position:absolute;width:200%;height:200%;left:0; top:0"></div>
                            </div>
                        </div>
                        <div class="d-flex px-3">
                            <div class="stats-small__data">
                                <span style="font-size:9px!important" class="stats-small__label mb-1 text-uppercase">Total Subscription</span>
                                <h6 class="stats-small__value count m-0"><?= $nSubs ?></h6>
                            </div>
                            <div class="stats-small__data text-right">
                                <span class="stats-small__percentage stats-small__percentage--increase">3,71%</span>
                            </div>
                        </div>
                        <canvas height="46" id="subscriptions"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>