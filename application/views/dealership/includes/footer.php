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
          <script src="/adminstyle/plugins/select2/select2.full.min.js"></script>
          <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
          <link rel="stylesheet" href="<?php echo base_url('adminstyle/plugins/select2/select2.min.css'); ?>">
          <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
          <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
          <script>
            $("#monday").change(function() {
              if (this.checked) {
                $('#01open').prop("disabled", false);
                $('#01close').prop("disabled", false);
              }else{
                $('#01open').prop("disabled", true);
                $('#01close').prop("disabled", true);
              }
            });

            $("#tues").change(function() {
              if (this.checked) {
                $('#02open').prop("disabled", false);
                $('#02close').prop("disabled", false);
              }else{
                $('#02open').prop("disabled", true);
                $('#02close').prop("disabled", true);
              }
            });

            $("#wed").change(function() {
              if (this.checked) {
                $('#03open').prop("disabled", false);
                $('#03close').prop("disabled", false);
              }else{
                $('#03open').prop("disabled", true);
                $('#03close').prop("disabled", true);
              }
            });

            $("#thurds").change(function() {
              if (this.checked) {
                $('#04open').prop("disabled", false);
                $('#04close').prop("disabled", false);
              }else{
                $('#04open').prop("disabled", true);
                $('#04close').prop("disabled", true);
              }
            });

            $("#friday").change(function() {
              if (this.checked) {
                $('#05open').prop("disabled", false);
                $('#05close').prop("disabled", false);
              }else{
                $('#05open').prop("disabled", true);
                $('#05close').prop("disabled", true);
              }
            });

            $("#saturday").change(function() {
              if (this.checked) {
                $('#06open').prop("disabled", false);
                $('#06close').prop("disabled", false);
              }else{
                $('#06open').prop("disabled", true);
                $('#06close').prop("disabled", true);
              }
            });


            $("#sunday").change(function() {
              if (this.checked) {
                $('#07open').prop("disabled", false);
                $('#07close').prop("disabled", false);
              }else{
                $('#07open').prop("disabled", true);
                $('#07close').prop("disabled", true);
              }
            });

            


            function viewMenu(idAgent) {

              var drop = "#dp" + idAgent;


              if ($(drop).css('display') == 'block') {
                $(drop).css("display", "none");
              } else {
                $(drop).css("display", "block");
              }
            }

            function check() {
              var tick1 = 0,
                tick2 = 0,
                tick3 = 0;

              console.log($('.make').val());

              if ($('.make').val() != null) {
                tick1 = tick1 + 1
              };

              if ($('.model').val() != null) {
                tick1 = tick1 + 1
              };

              if ($('.bodytype').val() != null) {
                tick2 = tick2 + 1
              };

              if ($('.year').val() != "") {
                tick2 = tick2 + 1
              };

              if ($('.odometer').val() != "") {
                tick2 = tick2 + 1
              };

              if ($('.engine').val() != "") {
                tick2 = tick2 + 1
              };

              if ($('.trans').val() != null) {
                tick2 = tick2 + 1
              };

              if ($('.fuelType').val() != null) {
                tick2 = tick2 + 1
              };


              if ($('.price').val() != "") {
                tick3 = tick3 + 1
              };



              if (tick1 == 2) {
                $("#check1").css("display", "initial");
              } else {
                $("#check1").css("display", "none");
              }
              if (tick2 == 6) {
                $("#check2").css("display", "initial");
              } else {
                $("#check2").css("display", "none");
              }
              if (tick3 == 1) {
                $("#check3").css("display", "initial");
              } else {
                $("#check3").css("display", "none");
              }



            }

            $('#carForm').submit(function() {
              $('#showLoading').click();
              $("#photosWrap").sortable('serialize');
            });

            $('#motoForm').submit(function() {
              $('#showLoading').click();
              $("#photosWrap").sortable('serialize');
              $("#photosWrap2").sortable('serialize');
            });
          </script>


          <script>
            function allowEdit() {
              $('#saveChange').css("display", "initial");
              $('#cancelbtn').css("display", "initial");
              $('#editBtn').css("display", "none");

              $('.aboutme').prop("disabled", false);

              $('.phone').prop("disabled", false);

              $('.director_name').prop("disabled", false);
              $('.contact_name').prop("disabled", false);
              $('.envoice_email').prop("disabled", false);

              $('.contact_phone').prop("disabled", false);
              $('.postal_address').prop("disabled", false);
            }

            function allowEditSales() {
              $('#saveChange').css("display", "initial");
              $('#editBtn').css("display", "none");
              $('#cancelbtn').css("display", "initial");



              $('.name').prop("disabled", false);
              $('.lastname').prop("disabled", false);
              $('.title').prop("disabled", false);
              $('.selectLanguage').prop("disabled", false);

              $('.phone').prop("disabled", false);
              $('.landline').prop("disabled", false);
              $('.email').prop("disabled", false);
              $('.aboutme').prop("disabled", false);

              $('.visible23').prop("disabled", false);

            }

            function cancelBtn2() {
              $('#saveChange').css("display", "none");
              $('#editBtn').css("display", "initial");
              $('#cancelbtn').css("display", "none");


              $('.aboutme').prop("disabled", true);

              $('.phone').prop("disabled", true);

              $('.director_name').prop("disabled", true);
              $('.contact_name').prop("disabled", true);
              $('.envoice_email').prop("disabled", true);

              $('.contact_phone').prop("disabled", true);
              $('.postal_address').prop("disabled", true);

              $('.visible23').prop("disabled", true);

            }

            function cancelBtn() {
              $('#saveChange').css("display", "none");
              $('#editBtn').css("display", "initial");
              $('#cancelbtn').css("display", "none");



              $('.name').prop("disabled", true);
              $('.lastname').prop("disabled", true);
              $('.title').prop("disabled", true);
              $('.selectLanguage').prop("disabled", true);

              $('.phone').prop("disabled", true);
              $('.landline').prop("disabled", true);
              $('.email').prop("disabled", true);
              $('.aboutme').prop("disabled", true);

              $('.visible23').prop("disabled", true);

            }


            function selectCar() {
              $('#carForm').css("display", "initial");
              //$('#carForm2').css("display", "initial");
              $('#motoForm').css("display", "none");
              //$('#motoForm2').css("display", "none");

              $('#type').val("0");

            }


            function selectMotorbike() {
              $('#carForm').css("display", "none");
              //$('#carForm2').css("display", "none");
              $('#motoForm').css("display", "initial");
              //$('#motoForm2').css("display", "initial");

              $('#type').val("1");
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

          <script>
            $(".class1").select2({
              templateResult: formatStateNew,
              templateSelection: formatStateNew,
              minimumResultsForSearch: Infinity
            });

            function formatStateNew(opt) {
              if (!opt.id) {
                return opt.text;
              }
              var optimage = $(opt.element).data('image');
              if (!optimage) {
                return opt.text;
              } else {
                var $opt = $(
                  '<span><img src="' + optimage + '" style="width:24px;display: inline;margin: 0;" /> <span class="descSummary"> ' + opt.text + '</span></span>'
                );
                return $opt;
              }
            };
          </script>

          <script>
            $(".class2").select2({
              templateResult: formatStateNew2,
              templateSelection: formatStateNew2,
              minimumResultsForSearch: Infinity,
              matcher: matchCustom,
              maximumSelectionLength: 1
            });

            function formatStateNew2(opt) {
              if (!opt.id) {
                return opt.text;
              }
              var optimage = $(opt.element).data('image');
              if (!optimage) {
                return opt.text;
              } else {
                var $opt = $(
                  '<span><img src="' + optimage + '" style="width:24px;display: inline;margin: 0;" /> <span class="descSummary"> ' + opt.text + '</span></span>'
                );
                return $opt;
              }
            };

            function matchCustom(params, data) {
              // If there are no search terms, return all of the data
              if ($.trim(params.term) === '') {
                return data;
              }

              // Do not display the item if there is no 'text' property
              if (typeof data.text === 'undefined') {
                return null;
              }

              // `params.term` should be the term that is used for searching
              // `data.text` is the text that is displayed for the data object
              if (data.text.toLowerCase().indexOf(params.term.toLowerCase()) > -1) {
                var modifiedData = $.extend({}, data, true);


                // You can return modified objects from here
                // This includes matching the `children` how you want in nested data sets
                return modifiedData;
              }

              // Return `null` if the term should not be displayed
              return null;
            }
          </script>

          <?php
          if ($page == "listvehicle" || $page == "createvehicle" || $page == "editvehicle") {
          ?>

            <script>
              <?php
              if ($page == "editvehicle") {
              ?>
                var countImg = <?= sizeof($photos) + 1 ?>;
              <?php
              } else {
              ?>
                var countImg = 1;
              <?php
              }
              ?>


              $("#files").change(function() {
                readURL4(this);
              });

              $("#files2").change(function() {
                readURL5(this);
              });


              function readURL5(input) {
                //countImg = 1;
                //$("#photosWrap").html("");
                if (input.files && input.files[0]) {

                  for (let index = 0; index < input.files.length; index++) {
                    console.log("IMG" + index);

                    console.log(input.files[index]);

                    var reader = new FileReader();

                    reader.onload = function(e) {
                      if (countImg == 1) {
                        $("#resumeimg").attr("src", e.target.result);

                        var html = `<div id="div${countImg}" class="col-md-2 card21">
  <div  class="card">
  <div class="property-img">
      <img name="vehicleImg[]" src="${e.target.result}" class="card-img-top" alt="...">
      <input type="hidden" name="postImg[]" id="img1" value="${e.target.result}">
  </div>
  <div class="card-body">
      <div class="row" style="margin-right: unset !important; margin-left: unset !important;">
          <div class="card-number-wrap star col-md-6 divn">
              <img src="/assets/img/star-icon.svg" class="card-img-top" alt="...">
          </div>
          <div class="col-md-12 buttons">
                <input type="hidden" name="readyimg[]" id="img1" value="${input.files[index].name}">
              <a style='cursor:pointer' onclick="$('#div${countImg}').remove();countImg = countImg -1;$('#counter').text(countImg-1);" ><img src="/assets/img/bin-icon.svg" alt="" style="width: 28px; height: 28px;"></a>
          </div>
      </div>
      </div>
  </div>
</div>`;
                        countImg++;
                      } else if (countImg == 16) {
                        alert("You can only add 15 photos")
                      } else {

                        var html = `<div id="div${countImg}"  class="col-md-2 card21">
  <div class="card">
  <div class="property-img">
      <img name="vehicleImg[]" src="${e.target.result}" class="card-img-top" alt="...">
      <input type="hidden" name="postImg[]" id="img1" value="${e.target.result}">
  </div>
  <div class="card-body">
      <div class="row" style="margin-right: unset !important; margin-left: unset !important;">
          <div class="card-number-wrap col-md-6 divn">
              <div class="card-number ">${countImg}</div>
          </div>
          <div class="col-md-12 buttons">
          <input type="hidden" name="readyimg[]" id="img1" value="${input.files[index].name}">
              <a style='cursor:pointer' onclick="$('#div${countImg}').remove(); countImg = countImg -1;$('#counter').text(countImg-1);"><img src="/assets/img/bin-icon.svg" alt="" style="width: 28px; height: 28px;"></a>
          </div>
          </div>
      </div>
  </div>
</div>
`
                        countImg++;
                      }

                      $("#counter2").text(countImg - 1);
                      $("#photosWrap2").append(html);


                    }
                    reader.readAsDataURL(input.files[index]); // convert to base64 string
                  }
                }

              }

              function readURL4(input) {
                //countImg = 1;
                //$("#photosWrap").html("");
                if (input.files && input.files[0]) {

                  for (let index = 0; index < input.files.length; index++) {
                    console.log("IMG" + index);

                    console.log(input.files[index]);

                    var reader = new FileReader();

                    reader.onload = function(e) {
                      if (countImg == 1) {
                        $("#resumeimg").attr("src", e.target.result);

                        var html = `<div id="div${countImg}" class="col-md-2 card21">
  <div  class="card">
  <div class="property-img">
      <img name="vehicleImg[]" src="${e.target.result}" class="card-img-top" alt="...">
      <input type="hidden" name="postImg[]" id="img1" value="${e.target.result}">
  </div>
  <div class="card-body">
      <div class="row" style="margin-right: unset !important; margin-left: unset !important;">
          <div class="card-number-wrap star col-md-6 divn">
              <img src="/assets/img/star-icon.svg" class="card-img-top" alt="...">
          </div>
          <div class="col-md-12 buttons">
                <input type="hidden" name="readyimg[]" id="img1" value="${input.files[index].name}">
              <a style='cursor:pointer' onclick="$('#div${countImg}').remove();countImg = countImg -1;$('#counter').text(countImg-1);" ><img src="/assets/img/bin-icon.svg" alt="" style="width: 28px; height: 28px;"></a>
          </div>
      </div>
      </div>
  </div>
</div>`;
                        countImg++;
                      } else if (countImg == 16) {
                        alert("You can only add 15 photos")
                      } else {

                        var html = `<div id="div${countImg}"  class="col-md-2 card21">
  <div class="card">
  <div class="property-img">
      <img name="vehicleImg[]" src="${e.target.result}" class="card-img-top" alt="...">
      <input type="hidden" name="postImg[]" id="img1" value="${e.target.result}">
  </div>
  <div class="card-body">
      <div class="row" style="margin-right: unset !important; margin-left: unset !important;">
          <div class="card-number-wrap col-md-6 divn">
              <div class="card-number ">${countImg}</div>
          </div>
          <div class="col-md-12 buttons">
          <input type="hidden" name="readyimg[]" id="img1" value="${input.files[index].name}">
              <a style='cursor:pointer' onclick="$('#div${countImg}').remove(); countImg = countImg -1;$('#counter').text(countImg-1);"><img src="/assets/img/bin-icon.svg" alt="" style="width: 28px; height: 28px;"></a>
          </div>
          </div>
      </div>
  </div>
</div>
`
                        countImg++;
                      }

                      $("#counter").text(countImg - 1);
                      $("#photosWrap").append(html);


                    }
                    reader.readAsDataURL(input.files[index]); // convert to base64 string
                  }
                }

              }

              $("#photosWrap").sortable({
                items: '.card21',
                helper: 'clone',
                placeholder: 'sort-placeholder',
                forcePlaceholderSize: true,
                forceHelperSize: true,
                tolerance: "pointer",
                start: function(e, ui) {
                  ui.item.data('start-pos', ui.item.index() + 1);
                },
                change: function(e, ui) {
                  var seq, startPos = ui.item.data('start-pos'),
                    $index, correction;

                  // if startPos < placeholder pos, we go from top to bottom
                  // else startPos > placeholder pos, we go from bottom to top and we need to correct the index with +1
                  //
                  correction = startPos <= ui.placeholder.index() ? 0 : 1;

                  ui.item.parent().find('div.card21').each(function(idx, el) {
                    var $this = $(el),
                      $index = $this.index();

                    // correction 0 means moving top to bottom, correction 1 means bottom to top
                    //
                    if (($index + 1 >= startPos && correction === 0) || ($index + 1 <= startPos && correction === 1)) {
                      $index = $index + correction;
                      if ($index == 1) {
                        $this.find('.divn').html("<img style='height: 30px;width: 30px;' src='/assets/img/star-icon.svg' class='card-img-top' alt='...'>");
                      } else {
                        $this.find('.divn').html("<div class='card-number'>" + $index + "</div>");
                      }

                    }

                  });

                  // handle dragged item separatelly
                  seq = ui.item.parent().find('div.sort-placeholder').index() + correction;
                  ui.item.find('.divn').html("<div class='card-number'>" + seq + "</div>");

                  if (seq == 1) {
                    ui.item.find('.divn').html("<img style='height: 30px;width: 30px;' src='/assets/img/star-icon.svg' class='card-img-top' alt='...'>");
                  }



                },
              });

              $("#photosWrap2").sortable({
                items: '.card21',
                helper: 'clone',
                placeholder: 'sort-placeholder',
                forcePlaceholderSize: true,
                forceHelperSize: true,
                tolerance: "pointer",
                start: function(e, ui) {
                  ui.item.data('start-pos', ui.item.index() + 1);
                },
                change: function(e, ui) {
                  var seq, startPos = ui.item.data('start-pos'),
                    $index, correction;

                  // if startPos < placeholder pos, we go from top to bottom
                  // else startPos > placeholder pos, we go from bottom to top and we need to correct the index with +1
                  //
                  correction = startPos <= ui.placeholder.index() ? 0 : 1;

                  ui.item.parent().find('div.card21').each(function(idx, el) {
                    var $this = $(el),
                      $index = $this.index();

                    // correction 0 means moving top to bottom, correction 1 means bottom to top
                    //
                    if (($index + 1 >= startPos && correction === 0) || ($index + 1 <= startPos && correction === 1)) {
                      $index = $index + correction;
                      if ($index == 1) {
                        $this.find('.divn').html("<img style='height: 30px;width: 30px;' src='/assets/img/star-icon.svg' class='card-img-top' alt='...'>");
                      } else {
                        $this.find('.divn').html("<div class='card-number'>" + $index + "</div>");
                      }

                    }

                  });

                  // handle dragged item separatelly
                  seq = ui.item.parent().find('div.sort-placeholder').index() + correction;
                  ui.item.find('.divn').html("<div class='card-number'>" + seq + "</div>");

                  if (seq == 1) {
                    ui.item.find('.divn').html("<img style='height: 30px;width: 30px;' src='/assets/img/star-icon.svg' class='card-img-top' alt='...'>");
                  }



                },
              });
            </script>

          <?php
          }
          ?>

          <script>
            const queryString = window.location.search;
            const urlParams = new URLSearchParams(queryString);
            const product = urlParams.get('t')
            console.log(product);
            if (product == 2) {
              changeTable3();
              $('#option2').click();
            }

            function changeTable1() {
              $('#divTable1').css("display", "initial");
              $('#divTable2').css("display", "none");
              $('#divTable3').css("display", "none");
            }

            function changeTable2() {
              $('#divTable1').css("display", "none");
              $('#divTable2').css("display", "initial");
              $('#divTable3').css("display", "none");
            }

            function changeTable3() {
              $('#divTable1').css("display", "none");
              $('#divTable2').css("display", "none");
              $('#divTable3').css("display", "initial");

            }
          </script>


          <script>
            var isSubmitting = true
            <?php if ($page == "editdealer") { ?>


              changePercent();


              function changePercent() {
                var total = 0
                if ($('#phone').val() != "") {
                  total = total + 1
                };

                if ($('.feDescription').val() != "") {
                  total = total + 1
                };

                if ($('#imageCroped').val() != "") {
                  total = total + 1
                };
                if ($('#imageCroped2').val() != "") {
                  total = total + 1
                };

                if ($('.director_name').val() != "") {
                  total = total + 1
                };

                if ($('.contact_name').val() != "") {
                  total = total + 1
                };

                if ($('.envoice_email').val() != "") {
                  total = total + 1
                };

                if ($('.contact_phone').val() != "") {
                  total = total + 1
                };
                if ($('.postal_address').val() != "") {
                  total = total + 1
                };


                var percent = (total / 9) * 100;
                var per = Math.round(percent);
                console.log(total);

                $('.progress-value').html(per.toString() + "%");
                $('.progress-bar')
                  .css("width", per + "%");

              }

            <?php }  ?>

            function saveChanges() {
              isSubmitting = true;
              return true;
            }


            $(document).ready(function() {


              $('#activeTable').DataTable({
                responsive: !0,
                language: {
                  searchPlaceholder: "Keywords"
                }
              });

              $('#listMessageDealer').DataTable({
                responsive: !0,
                "ordering": false,
              });

              $('#activeTable2').DataTable({
                responsive: !0,
                language: {
                  searchPlaceholder: "Keywords"
                }
              });

              $('#activeTable3').DataTable({
                responsive: !0,
                language: {
                  searchPlaceholder: "Keywords"
                }
              });



              $("#analytics-overview-date-range").datepicker({
                format: 'yyyy-mm-dd'
              })
              $("#analytics-overview-date-range-2").datepicker({
                format: 'yyyy-mm-dd'
              })
            });

            var $uploadCrop = $('#crops').croppie({
              viewport: {
                width: 350,
                height: 350,
                type: 'circle'
              },
              enableExif: false
            });

            var $uploadCrop2 = $('#crops2').croppie({
              viewport: {
                width: 500,
                height: 365,

              },
              boundary: {
                width: 600,
                height: 400
              },
              customClass: "covercontainer",
              enableExif: true,
            });

            function readURL(input) {

              if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {

                  $('#updatelabel')
                    .css("display", "none");


                  $('#crops').addClass('ready');
                  $uploadCrop.croppie('bind', {
                    url: e.target.result
                  }).then(function() {
                    console.log('jQuery bind complete');
                    $('.cr-slider').attr({
                      'min': 0.4000,
                      'max': 1.5000
                    });
                  });

                  $('#modalProfile').click();
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
                    .width(150)
                    .height(150)
                    .css("display", "initial");

                  $('#crops')
                    .css("display", "none");




                  $('#imageCroped').val(resp);
                });
                changePercent();
              });
            }

            function readURLCover(input) {

              if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {

                  $('#crops2').addClass('ready');
                  $uploadCrop2.croppie('bind', {
                    url: e.target.result
                  }).then(function() {
                    console.log('jQuery bind complete');
                    $('.cr-slider').attr({
                      'min': 0.1000,
                      'max': 2.5000
                    });
                    //$('.croppie-container').width("100%");
                    //$('.croppie-container').height("100%");
                  });

                  $('#modalCover').click();
                };
                reader.readAsDataURL(input.files[0]);
              }

              $('.upload-result2').on('click', function(ev) {
                $uploadCrop2.croppie('result', {
                  type: 'canvas',
                  size: 'original'
                }).then(function(resp) {
                  $('#profilePhoto')
                    .attr('src', resp)

                    .height(365)
                    .css("display", "initial");
                  $('#imageCroped2').val(resp);
                });
                changePercent();
              });

            }

            <?php if ($page != "editdealer") { ?>

              function changePercent() {
                var total = 0
                if ($('#feFirstName').val() != "") {
                  total = total + 1
                };
                if ($('#feLastName').val() != "") {
                  total = total + 1
                };
                if ($('#feTitle').val() != "") {
                  total = total + 1
                };
                if ($('.aboutme').val() != "") {
                  total = total + 1
                };
                if ($('#fePhone').val() != "") {
                  total = total + 1
                };
                if ($('#fetLand').val() != "") {
                  total = total + 1
                };
                if ($('#feEmailAddress').val() != "") {
                  total = total + 1
                };
                if ($('#imageCroped').val() != "") {
                  total = total + 1
                };
                if ($('#imageCroped2').val() != "") {
                  total = total + 1
                };

                isSubmitting = false;


                var percent = (total / 9) * 100;
                var per = Math.round(percent);
                console.log(per);

                $('.progress-value').html(per.toString() + "%");
                $('.progress-bar')
                  .css("width", per + "%");

              }

            <?php   }  ?>
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
            </script>

            <script>
              var ctx = document.getElementById('myChart').getContext('2d');

              var labels1 = [<?php
                              foreach ($graph as $val) {
                                echo '"' . $val["date"] . '",';
                              }
                              ?>];

              var solds = [<?php
                            foreach ($graph as $val) {
                              echo '"' . $val["total"] . '",';
                            }
                            ?>];

              var listed = [<?php
                            foreach ($graph2 as $val) {
                              echo '"' . $val["total"] . '",';
                            }
                            ?>];



              var colorA = "";
              var colorB = "";
              var myChart = new Chart(ctx, {
                type: 'bar',
                responsive: true,
                data: {
                  labels: labels1,
                  datasets: [{
                      label: 'New Listings',
                      data: listed,
                      backgroundColor: [
                        '#5dd9b4',
                        '#5dd9b4',
                        '#5dd9b4',
                        '#5dd9b4',
                        '#5dd9b4',
                        '#5dd9b4',
                      ],
                      borderColor: [
                        '#5dd9b4',
                        '#5dd9b4',
                        '#5dd9b4',
                        '#5dd9b4',
                        '#5dd9b4',
                        '#5dd9b4',
                      ],
                      borderWidth: 1
                    },
                    {
                      label: 'Sold',
                      data: solds,
                      backgroundColor: [
                        "#A2A2A1FF",
                        "#A2A2A1FF",
                        "#A2A2A1FF",
                        "#A2A2A1FF",
                        "#A2A2A1FF",
                        "#A2A2A1FF",
                      ],
                      borderColor: [
                        "#A2A2A1FF",
                        "#A2A2A1FF",
                        "#A2A2A1FF",
                        "#A2A2A1FF",
                        "#A2A2A1FF",
                        "#A2A2A1FF",
                      ],
                      borderWidth: 1
                    }
                  ]
                },
                options: {
                  scales: {
                    yAxes: [{
                      ticks: {
                        beginAtZero: true
                      },
                      gridLines: {
                        borderDash: [8, 4],
                        drawBorder: false,
                      }
                    }],
                    xAxes: [{
                      gridLines: {
                        color: "rgba(0, 0, 0, 0)",
                      },
                    }]
                  },
                  scaleLabel: function(label) {
                    return '$' + label.value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                  }
                }
              });
            </script>


          <?php
          }
          ?>



          </body>

          </html>