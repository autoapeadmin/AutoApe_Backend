          </div>
          <!--  <footer class="main-footer d-flex p-2 px-3 bg-white border-top">
            <ul class="nav">
              <li class="nav-item">
                <a class="nav-link" href="#">Home</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">Services</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">About</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">Products</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">Blog</a>
              </li>
            </ul>
            <span class="copyright ml-auto my-auto mr-2">Copyright © 2018
              <a href="https://designrevision.com" rel="nofollow">DesignRevision</a>
            </span>
          </footer> -->
          </main>
          </div>
          </div>
          <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
          <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
          <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
          <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>
          <script src="https://unpkg.com/shards-ui@latest/dist/js/shards.min.js"></script>
          <script src="https://cdnjs.cloudflare.com/ajax/libs/Sharrre/2.0.1/jquery.sharrre.min.js"></script>
          <script src="/maxautoAdmin/scripts/extras.1.1.0.min.js"></script>
          <script src="/maxautoAdmin/scripts/shards-dashboards.1.1.0.min.js"></script>
          <script src="/maxautoAdmin/scripts/app/app-blog-overview.1.1.0.js"></script>
          <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.js"></script>
          <script type="text/javascript" charset="utf8" src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
          <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>


          <script>
            $(document).ready(function() {
              $('#activeTable').DataTable({

                responsive: !0
              });

              $('#listMessageAdmin').DataTable({
                responsive: !0,
                "ordering": false,
              });

              $('#listMessageAdmin2').DataTable({

              });




              $("#analytics-overview-date-range").datepicker({
                format: 'yyyy-mm-dd'
              })
              $("#analytics-overview-date-range-2").datepicker({
                format: 'yyyy-mm-dd'
              })
            });


            function readURL(input) {

              var $uploadCrop = $('#crops').croppie({
                viewport: {
                  width: 100,
                  height: 100,
                  type: 'circle'
                },
                enableExif: true
              });
              if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                  $('#blah')
                    .attr('src', e.target.result)
                    .width(100)
                    .height(100);

                  $('#crops').addClass('ready');
                  $uploadCrop.croppie('bind', {
                    url: e.target.result
                  }).then(function() {
                    console.log('jQuery bind complete');
                  });
                };
                reader.readAsDataURL(input.files[0]);


              }



              $('.upload-result').on('click', function(ev) {
                $uploadCrop.croppie('result', {
                  type: 'canvas',
                  size: 'original'
                }).then(function(resp) {
                  $('#blah')
                    .attr('src', resp)
                    .width(100)
                    .height(100);

                  $('#imageCroped').val(resp);
                });
              });
            }



            function readURL2(input) {

              var $uploadCrop = $('#crops2').croppie({
                viewport: {
                  width: 220,
                  height: 60,
                },
                enableExif: true
              });
              if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                  $('#blah2')
                    .attr('src', e.target.result)
                    .width(220)
                    .height(60);

                  $('#crops2').addClass('ready');
                  $uploadCrop.croppie('bind', {
                    url: e.target.result
                  }).then(function() {
                    console.log('jQuery bind complete');
                  });
                };
                reader.readAsDataURL(input.files[0]);


              }



              $('.upload-result').on('click', function(ev) {
                $uploadCrop.croppie('result', {
                  type: 'canvas',
                  size: 'original'
                }).then(function(resp) {
                  $('#blah')
                    .attr('src', resp)
                    .width(100)
                    .height(100);

                  $('#imageCroped').val(resp);
                });
              });

              $('.upload-result2').on('click', function(ev) {
                $uploadCrop.croppie('result', {
                  type: 'canvas',
                  size: 'original'
                }).then(function(resp) {
                  $('#blah2')
                    .attr('src', resp)
                    .width(220)
                    .height(60);

                  $('#imageCroped2').val(resp);
                });
              });
            }
          </script>

          <?php
          if ($page == "dashboard") {
          ?>
            <script>
              var e = {
                responsive: !0,
                legend: {
                  display: !1
                },
                tooltips: {
                  enabled: !1
                },
                elements: {
                  point: {
                    radius: 0
                  }
                },
                scales: {
                  xAxes: [{
                    gridLines: !1,
                    ticks: {
                      display: !1
                    }
                  }],
                  yAxes: [{
                    gridLines: !1,
                    ticks: {
                      display: !1
                    }
                  }]
                }
              };

              var ctx = document.getElementById('cars').getContext('2d');
              var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                  labels: ["Label 1", "Label 2", "Label 3", "Label 4", "Label 5"],
                  datasets: [{
                    label: "Today",
                    fill: "start",
                    data: [4, 4, 4, 9, 20],
                    backgroundColor: "#e8f1fd",
                    borderColor: "#1d7bfd",
                    borderWidth: 1.5
                  }]
                },
                options: e
              });

              var ctx2 = document.getElementById('customers').getContext('2d');
              var myChart = new Chart(ctx2, {
                type: 'line',
                data: {
                  labels: ["Label 1", "Label 2", "Label 3", "Label 4", "Label 5"],
                  datasets: [{
                    label: "Today",
                    fill: "start",
                    data: [1, 9, 1, 9, 9],
                    backgroundColor: "#e9f8f1",
                    borderColor: "#46c671",
                    borderWidth: 1.5
                  }]
                },
                options: e
              });

              var ctx3 = document.getElementById('dealerships').getContext('2d');
              var myChart = new Chart(ctx3, {
                type: 'line',
                data: {
                  labels: ["Label 1", "Label 2", "Label 3", "Label 4", "Label 5"],
                  datasets: [{
                    label: "Today",
                    fill: "start",
                    data: [9, 9, 3, 9, 9],
                    backgroundColor: "#fdf6eb",
                    borderColor: "#f8b302",
                    borderWidth: 1.5
                  }]
                },
                options: e
              });

              var ctx4 = document.getElementById('subscriptions').getContext('2d');
              var myChart = new Chart(ctx4, {
                type: 'line',
                data: {
                  labels: ["Label 1", "Label 2", "Label 3", "Label 4", "Label 5"],
                  datasets: [{
                    label: "Today",
                    fill: "start",
                    data: [3, 3, 4, 9, 4],
                    backgroundColor: "#fcedf0",
                    borderColor: "#f24169",
                    borderWidth: 1.5
                  }]
                },
                options: e
              });
            </script>
          <?php
          }
          ?>


          </body>

          </html>