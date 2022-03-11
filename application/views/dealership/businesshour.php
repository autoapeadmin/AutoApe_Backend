<div class="page-header row no-gutters py-4">
  <div class="col">
    <span class="text-uppercase page-subtitle">SETTINGS</span>
    <h3 class="page-title">Business Hours</h3>
  </div>
</div>

<div class="row">

  <div class="col">
    <div class="card card-small mb-4">
      <div class="card-header border-bottom">
        <h6 class="m-0">What is <?= $_SESSION["DEALER_NAME"] ?>'s Hours?</h6>
      </div>

      <div style="padding-bottom:0px!important" class="card-body p-0 pb-3 text-center dataTables_wrapper no-footer">

        <form action="/dealership/saveHours" method="post" id="main_contact_form" name="main_contact_form" novalidate="">

          <script>
            function selectElement(id, valueToSelect) {
              let element = document.getElementById(id);
              element.value = valueToSelect;
            }
          </script>

          <?php
          if ($times) {
            print_r('<div style="margin-top: 10px;text-align: start;" class="container-fluid">');
            foreach ($times as $time) {
              switch ($time["day"]) {
                case 1:
                  $isOpen = true;
                  if ($time["open_time"] == "close") {
                    $isOpen = false;
                  } else {
                  }
          ?>
                  <div class="row">
                    <div class="form-group col-md-2">
                      <label for="displayEmail">Monday</label>
                      <select <?php if (!$isOpen) echo "disabled" ?> id="01open" name="01open" class="custom-select">
                        <option value="06:00">06:00 AM</option>
                        <option value="06:30">06:30 AM</option>
                        <option value="07:00">07:00 AM</option>
                        <option value="07:30">07:30 AM</option>
                        <option value="08:00">08:00 AM</option>
                        <option value="08:30">08:30 AM</option>
                        <option value="09:00">09:00 AM</option>
                        <option value="09:30">09:30 AM</option>
                        <option value="10:00">10:00 AM</option>
                        <option value="10:30">10:30 AM</option>
                        <option value="11:00">11:00 AM</option>
                        <option value="11:30">11:30 AM</option>
                        <option value="12:00">12:00 PM</option>
                        <option value="12:30">12:30 PM</option>
                        <option value="13:00">1:00 PM</option>
                        <option value="13:30">1:30 PM</option>
                        <option value="14:00">2:00 PM</option>
                        <option value="14:30">2:30 PM</option>
                        <option value="15:00">3:00 PM</option>
                        <option value="15:30">3:30 PM</option>
                        <option value="16:00">4:00 PM</option>
                        <option value="16:30">4:30 PM</option>
                        <option value="17:00">5:00 PM</option>
                        <option value="17:30">5:30 PM</option>
                        <option value="18:00">6:00 PM</option>
                        <option value="18:30">6:30 PM</option>
                        <option value="19:00">7:00 PM</option>
                        <option value="19:30">7:30 PM</option>
                        <option value="20:00">8:00 PM</option>
                        <option value="20:30">8:30 PM</option>
                        <option value="21:00">9:00 PM</option>
                        <option value="21:30">9:30 PM</option>
                        <option value="22:00">10:00 PM</option>
                        <option value="22:30">10:30 PM</option>
                        <option value="23:00">11:00 PM</option>
                        <option value="23:30">11:30 PM</option>
                      </select>
                    </div>
                    <div class="form-group col-md-2">
                      <label style="color:white;font: size 1px;" for="displayEmail">'</label>
                      <select <?php if (!$isOpen) echo "disabled" ?> id="01close" name="01close" class="custom-select">
                      <option value="06:00">06:00 AM</option>
                        <option value="06:30">06:30 AM</option>
                        <option value="07:00">07:00 AM</option>
                        <option value="07:30">07:30 AM</option>
                        <option value="08:00">08:00 AM</option>
                        <option value="08:30">08:30 AM</option>
                        <option value="09:00">09:00 AM</option>
                        <option value="09:30">09:30 AM</option>
                        <option value="10:00">10:00 AM</option>
                        <option value="10:30">10:30 AM</option>
                        <option value="11:00">11:00 AM</option>
                        <option value="11:30">11:30 AM</option>
                        <option value="12:00">12:00 PM</option>
                        <option value="12:30">12:30 PM</option>
                        <option value="13:00">1:00 PM</option>
                        <option value="13:30">1:30 PM</option>
                        <option value="14:00">2:00 PM</option>
                        <option value="14:30">2:30 PM</option>
                        <option value="15:00">3:00 PM</option>
                        <option value="15:30">3:30 PM</option>
                        <option value="16:00">4:00 PM</option>
                        <option value="16:30">4:30 PM</option>
                        <option value="17:00">5:00 PM</option>
                        <option value="17:30">5:30 PM</option>
                        <option value="18:00">6:00 PM</option>
                        <option value="18:30">6:30 PM</option>
                        <option value="19:00">7:00 PM</option>
                        <option value="19:30">7:30 PM</option>
                        <option value="20:00">8:00 PM</option>
                        <option value="20:30">8:30 PM</option>
                        <option value="21:00">9:00 PM</option>
                        <option value="21:30">9:30 PM</option>
                        <option value="22:00">10:00 PM</option>
                        <option value="22:30">10:30 PM</option>
                        <option value="23:00">11:00 PM</option>
                        <option value="23:30">11:30 PM</option>
                      </select>
                    </div>

                    <div class="form-group col-md-1">
                      <label style="color:white;margin-top:4px" for="displayEmail">'</label>
                      <div class="custom-control custom-toggle ml-auto my-auto">
                        <input name="monday" type="checkbox" id="monday" class="custom-control-input" <?php if ($isOpen) echo "checked" ?>>
                        <label class="custom-control-label" for="monday"></label>
                      </div>
                    </div>
                  </div>
                  <script>
                    selectElement('01open', '<?= $time["open_time"] ?>');
                    selectElement('01close', '<?= $time["close_time"] ?>');
                  </script>

                <?php
                  break;

                case 2:
                  $isOpen = true;
                  if ($time["open_time"] == "close") {
                    $isOpen = false;
                  } else {
                  }
                ?>
                  <div class="row">
                    <div class="form-group col-md-2">
                      <label for="displayEmail">Tuesday</label>
                      <select <?php if (!$isOpen) echo "disabled" ?> id="02open" name="02open" class="custom-select">
                        <option value="06:00">06:00 AM</option>
                        <option value="06:30">06:30 AM</option>
                        <option value="07:00">07:00 AM</option>
                        <option value="07:30">07:30 AM</option>
                        <option value="08:00">08:00 AM</option>
                        <option value="08:30">08:30 AM</option>
                        <option value="09:00">09:00 AM</option>
                        <option value="09:30">09:30 AM</option>
                        <option value="10:00">10:00 AM</option>
                        <option value="10:30">10:30 AM</option>
                        <option value="11:00">11:00 AM</option>
                        <option value="11:30">11:30 AM</option>
                        <option value="12:00">12:00 PM</option>
                        <option value="12:30">12:30 PM</option>
                        <option value="13:00">1:00 PM</option>
                        <option value="13:30">1:30 PM</option>
                        <option value="14:00">2:00 PM</option>
                        <option value="14:30">2:30 PM</option>
                        <option value="15:00">3:00 PM</option>
                        <option value="15:30">3:30 PM</option>
                        <option value="16:00">4:00 PM</option>
                        <option value="16:30">4:30 PM</option>
                        <option value="17:00">5:00 PM</option>
                        <option value="17:30">5:30 PM</option>
                        <option value="18:00">6:00 PM</option>
                        <option value="18:30">6:30 PM</option>
                        <option value="19:00">7:00 PM</option>
                        <option value="19:30">7:30 PM</option>
                        <option value="20:00">8:00 PM</option>
                        <option value="20:30">8:30 PM</option>
                        <option value="21:00">9:00 PM</option>
                        <option value="21:30">9:30 PM</option>
                        <option value="22:00">10:00 PM</option>
                        <option value="22:30">10:30 PM</option>
                        <option value="23:00">11:00 PM</option>
                        <option value="23:30">11:30 PM</option>
                      </select>
                    </div>
                    <div class="form-group col-md-2">
                      <label style="color:white;font: size 1px;" for="displayEmail">'</label>
                      <select <?php if (!$isOpen) echo "disabled" ?> id="02close" name="02close" class="custom-select">
                        <option value="06:00">06:00 AM</option>
                        <option value="06:30">06:30 AM</option>
                        <option value="07:00">07:00 AM</option>
                        <option value="07:30">07:30 AM</option>
                        <option value="08:00">08:00 AM</option>
                        <option value="08:30">08:30 AM</option>
                        <option value="09:00">09:00 AM</option>
                        <option value="09:30">09:30 AM</option>
                        <option value="10:00">10:00 AM</option>
                        <option value="10:30">10:30 AM</option>
                        <option value="11:00">11:00 AM</option>
                        <option value="11:30">11:30 AM</option>
                        <option value="12:00">12:00 PM</option>
                        <option value="12:30">12:30 PM</option>
                        <option value="13:00">1:00 PM</option>
                        <option value="13:30">1:30 PM</option>
                        <option value="14:00">2:00 PM</option>
                        <option value="14:30">2:30 PM</option>
                        <option value="15:00">3:00 PM</option>
                        <option value="15:30">3:30 PM</option>
                        <option value="16:00">4:00 PM</option>
                        <option value="16:30">4:30 PM</option>
                        <option value="17:00">5:00 PM</option>
                        <option value="17:30">5:30 PM</option>
                        <option value="18:00">6:00 PM</option>
                        <option value="18:30">6:30 PM</option>
                        <option value="19:00">7:00 PM</option>
                        <option value="19:30">7:30 PM</option>
                        <option value="20:00">8:00 PM</option>
                        <option value="20:30">8:30 PM</option>
                        <option value="21:00">9:00 PM</option>
                        <option value="21:30">9:30 PM</option>
                        <option value="22:00">10:00 PM</option>
                        <option value="22:30">10:30 PM</option>
                        <option value="23:00">11:00 PM</option>
                        <option value="23:30">11:30 PM</option>
                      </select>
                    </div>

                    <div class="form-group col-md-1">
                      <label style="color:white;margin-top:4px" for="displayEmail">'</label>
                      <div class="custom-control custom-toggle ml-auto my-auto">
                        <input name="tues" type="checkbox" id="tues" class="custom-control-input" <?php if ($isOpen) echo "checked" ?>>
                        <label class="custom-control-label" for="tues"></label>
                      </div>
                    </div>
                  </div>
                  <script>
                    selectElement('02open', '<?= $time["open_time"] ?>');
                    selectElement('02close', '<?= $time["close_time"] ?>');
                  </script>

                <?php
                  break;

                case 3:
                  $isOpen = true;
                  if ($time["open_time"] == "close") {
                    $isOpen = false;
                  } else {
                  }
                ?>
                  <div class="row">
                    <div class="form-group col-md-2">
                      <label for="displayEmail">Wednesday</label>
                      <select <?php if (!$isOpen) echo "disabled" ?> id="03open" name="03open" class="custom-select">
                        <option value="06:00">06:00 AM</option>
                        <option value="06:30">06:30 AM</option>
                        <option value="07:00">07:00 AM</option>
                        <option value="07:30">07:30 AM</option>
                        <option value="08:00">08:00 AM</option>
                        <option value="08:30">08:30 AM</option>
                        <option value="09:00">09:00 AM</option>
                        <option value="09:30">09:30 AM</option>
                        <option value="10:00">10:00 AM</option>
                        <option value="10:30">10:30 AM</option>
                        <option value="11:00">11:00 AM</option>
                        <option value="11:30">11:30 AM</option>
                        <option value="12:00">12:00 PM</option>
                        <option value="12:30">12:30 PM</option>
                        <option value="13:00">1:00 PM</option>
                        <option value="13:30">1:30 PM</option>
                        <option value="14:00">2:00 PM</option>
                        <option value="14:30">2:30 PM</option>
                        <option value="15:00">3:00 PM</option>
                        <option value="15:30">3:30 PM</option>
                        <option value="16:00">4:00 PM</option>
                        <option value="16:30">4:30 PM</option>
                        <option value="17:00">5:00 PM</option>
                        <option value="17:30">5:30 PM</option>
                        <option value="18:00">6:00 PM</option>
                        <option value="18:30">6:30 PM</option>
                        <option value="19:00">7:00 PM</option>
                        <option value="19:30">7:30 PM</option>
                        <option value="20:00">8:00 PM</option>
                        <option value="20:30">8:30 PM</option>
                        <option value="21:00">9:00 PM</option>
                        <option value="21:30">9:30 PM</option>
                        <option value="22:00">10:00 PM</option>
                        <option value="22:30">10:30 PM</option>
                        <option value="23:00">11:00 PM</option>
                        <option value="23:30">11:30 PM</option>
                      </select>
                    </div>
                    <div class="form-group col-md-2">
                      <label style="color:white;font: size 1px;" for="displayEmail">'</label>
                      <select <?php if (!$isOpen) echo "disabled" ?> id="03close" name="03close" class="custom-select">
                        <option value="06:00">06:00 AM</option>
                        <option value="06:30">06:30 AM</option>
                        <option value="07:00">07:00 AM</option>
                        <option value="07:30">07:30 AM</option>
                        <option value="08:00">08:00 AM</option>
                        <option value="08:30">08:30 AM</option>
                        <option value="09:00">09:00 AM</option>
                        <option value="09:30">09:30 AM</option>
                        <option value="10:00">10:00 AM</option>
                        <option value="10:30">10:30 AM</option>
                        <option value="11:00">11:00 AM</option>
                        <option value="11:30">11:30 AM</option>
                        <option value="12:00">12:00 PM</option>
                        <option value="12:30">12:30 PM</option>
                        <option value="13:00">1:00 PM</option>
                        <option value="13:30">1:30 PM</option>
                        <option value="14:00">2:00 PM</option>
                        <option value="14:30">2:30 PM</option>
                        <option value="15:00">3:00 PM</option>
                        <option value="15:30">3:30 PM</option>
                        <option value="16:00">4:00 PM</option>
                        <option value="16:30">4:30 PM</option>
                        <option value="17:00">5:00 PM</option>
                        <option value="17:30">5:30 PM</option>
                        <option value="18:00">6:00 PM</option>
                        <option value="18:30">6:30 PM</option>
                        <option value="19:00">7:00 PM</option>
                        <option value="19:30">7:30 PM</option>
                        <option value="20:00">8:00 PM</option>
                        <option value="20:30">8:30 PM</option>
                        <option value="21:00">9:00 PM</option>
                        <option value="21:30">9:30 PM</option>
                        <option value="22:00">10:00 PM</option>
                        <option value="22:30">10:30 PM</option>
                        <option value="23:00">11:00 PM</option>
                        <option value="23:30">11:30 PM</option>
                      </select>
                    </div>

                    <div class="form-group col-md-1">
                      <label style="color:white;margin-top:4px" for="displayEmail">'</label>
                      <div class="custom-control custom-toggle ml-auto my-auto">
                        <input name="wed" type="checkbox" id="wed" class="custom-control-input" <?php if ($isOpen) echo "checked" ?>>
                        <label class="custom-control-label" for="wed"></label>
                      </div>
                    </div>
                  </div>
                  <script>
                    selectElement('03open', '<?= $time["open_time"] ?>');
                    selectElement('03close', '<?= $time["close_time"] ?>');
                  </script>

                <?php
                  break;

                case 4:
                  $isOpen = true;
                  if ($time["open_time"] == "close") {
                    $isOpen = false;
                  } else {
                  }
                ?>
                  <div class="row">
                    <div class="form-group col-md-2">
                      <label for="displayEmail">Thursday</label>
                      <select <?php if (!$isOpen) echo "disabled" ?> id="04open" name="04open" class="custom-select">
                        <option value="06:00">06:00 AM</option>
                        <option value="06:30">06:30 AM</option>
                        <option value="07:00">07:00 AM</option>
                        <option value="07:30">07:30 AM</option>
                        <option value="08:00">08:00 AM</option>
                        <option value="08:30">08:30 AM</option>
                        <option value="09:00">09:00 AM</option>
                        <option value="09:30">09:30 AM</option>
                        <option value="10:00">10:00 AM</option>
                        <option value="10:30">10:30 AM</option>
                        <option value="11:00">11:00 AM</option>
                        <option value="11:30">11:30 AM</option>
                        <option value="12:00">12:00 PM</option>
                        <option value="12:30">12:30 PM</option>
                        <option value="13:00">1:00 PM</option>
                        <option value="13:30">1:30 PM</option>
                        <option value="14:00">2:00 PM</option>
                        <option value="14:30">2:30 PM</option>
                        <option value="15:00">3:00 PM</option>
                        <option value="15:30">3:30 PM</option>
                        <option value="16:00">4:00 PM</option>
                        <option value="16:30">4:30 PM</option>
                        <option value="17:00">5:00 PM</option>
                        <option value="17:30">5:30 PM</option>
                        <option value="18:00">6:00 PM</option>
                        <option value="18:30">6:30 PM</option>
                        <option value="19:00">7:00 PM</option>
                        <option value="19:30">7:30 PM</option>
                        <option value="20:00">8:00 PM</option>
                        <option value="20:30">8:30 PM</option>
                        <option value="21:00">9:00 PM</option>
                        <option value="21:30">9:30 PM</option>
                        <option value="22:00">10:00 PM</option>
                        <option value="22:30">10:30 PM</option>
                        <option value="23:00">11:00 PM</option>
                        <option value="23:30">11:30 PM</option>
                      </select>
                    </div>
                    <div class="form-group col-md-2">
                      <label style="color:white;font: size 1px;" for="displayEmail">'</label>
                      <select <?php if (!$isOpen) echo "disabled" ?> id="04close" name="04close" class="custom-select">
                        <option value="06:00">06:00 AM</option>
                        <option value="06:30">06:30 AM</option>
                        <option value="07:00">07:00 AM</option>
                        <option value="07:30">07:30 AM</option>
                        <option value="08:00">08:00 AM</option>
                        <option value="08:30">08:30 AM</option>
                        <option value="09:00">09:00 AM</option>
                        <option value="09:30">09:30 AM</option>
                        <option value="10:00">10:00 AM</option>
                        <option value="10:30">10:30 AM</option>
                        <option value="11:00">11:00 AM</option>
                        <option value="11:30">11:30 AM</option>
                        <option value="12:00">12:00 PM</option>
                        <option value="12:30">12:30 PM</option>
                        <option value="13:00">1:00 PM</option>
                        <option value="13:30">1:30 PM</option>
                        <option value="14:00">2:00 PM</option>
                        <option value="14:30">2:30 PM</option>
                        <option value="15:00">3:00 PM</option>
                        <option value="15:30">3:30 PM</option>
                        <option value="16:00">4:00 PM</option>
                        <option value="16:30">4:30 PM</option>
                        <option value="17:00">5:00 PM</option>
                        <option value="17:30">5:30 PM</option>
                        <option value="18:00">6:00 PM</option>
                        <option value="18:30">6:30 PM</option>
                        <option value="19:00">7:00 PM</option>
                        <option value="19:30">7:30 PM</option>
                        <option value="20:00">8:00 PM</option>
                        <option value="20:30">8:30 PM</option>
                        <option value="21:00">9:00 PM</option>
                        <option value="21:30">9:30 PM</option>
                        <option value="22:00">10:00 PM</option>
                        <option value="22:30">10:30 PM</option>
                        <option value="23:00">11:00 PM</option>
                        <option value="23:30">11:30 PM</option>
                      </select>
                    </div>
                    <div class="form-group col-md-1">
                      <label style="color:white;margin-top:4px" for="displayEmail">'</label>
                      <div class="custom-control custom-toggle ml-auto my-auto">
                        <input name="thurds" type="checkbox" id="thurds" class="custom-control-input" <?php if ($isOpen) echo "checked" ?>>
                        <label class="custom-control-label" for="thurds"></label>
                      </div>
                    </div>
                  </div>
                  <script>
                    selectElement('04open', '<?= $time["open_time"] ?>');
                    selectElement('04close', '<?= $time["close_time"] ?>');
                  </script>

              <?php
                  break;

                  case 5:
                    $isOpen = true;
                    if ($time["open_time"] == "close") {
                      $isOpen = false;
                    } else {
                    }
                  ?>
                    <div class="row">
                      <div class="form-group col-md-2">
                        <label for="displayEmail">Friday</label>
                        <select <?php if (!$isOpen) echo "disabled" ?> id="05open" name="05open" class="custom-select">
                      <option value="06:00">06:00 AM</option>
                        <option value="06:30">06:30 AM</option>
                        <option value="07:00">07:00 AM</option>
                        <option value="07:30">07:30 AM</option>
                        <option value="08:00">08:00 AM</option>
                        <option value="08:30">08:30 AM</option>
                        <option value="09:00">09:00 AM</option>
                        <option value="09:30">09:30 AM</option>
                        <option value="10:00">10:00 AM</option>
                        <option value="10:30">10:30 AM</option>
                        <option value="11:00">11:00 AM</option>
                        <option value="11:30">11:30 AM</option>
                        <option value="12:00">12:00 PM</option>
                        <option value="12:30">12:30 PM</option>
                        <option value="13:00">1:00 PM</option>
                        <option value="13:30">1:30 PM</option>
                        <option value="14:00">2:00 PM</option>
                        <option value="14:30">2:30 PM</option>
                        <option value="15:00">3:00 PM</option>
                        <option value="15:30">3:30 PM</option>
                        <option value="16:00">4:00 PM</option>
                        <option value="16:30">4:30 PM</option>
                        <option value="17:00">5:00 PM</option>
                        <option value="17:30">5:30 PM</option>
                        <option value="18:00">6:00 PM</option>
                        <option value="18:30">6:30 PM</option>
                        <option value="19:00">7:00 PM</option>
                        <option value="19:30">7:30 PM</option>
                        <option value="20:00">8:00 PM</option>
                        <option value="20:30">8:30 PM</option>
                        <option value="21:00">9:00 PM</option>
                        <option value="21:30">9:30 PM</option>
                        <option value="22:00">10:00 PM</option>
                        <option value="22:30">10:30 PM</option>
                        <option value="23:00">11:00 PM</option>
                        <option value="23:30">11:30 PM</option>
                        </select>
                      </div>
                      <div class="form-group col-md-2">
                        <label style="color:white;font: size 1px;" for="displayEmail">'</label>
                        <select <?php if (!$isOpen) echo "disabled" ?> id="05close" name="05close" class="custom-select">
                      <option value="06:00">06:00 AM</option>
                        <option value="06:30">06:30 AM</option>
                        <option value="07:00">07:00 AM</option>
                        <option value="07:30">07:30 AM</option>
                        <option value="08:00">08:00 AM</option>
                        <option value="08:30">08:30 AM</option>
                        <option value="09:00">09:00 AM</option>
                        <option value="09:30">09:30 AM</option>
                        <option value="10:00">10:00 AM</option>
                        <option value="10:30">10:30 AM</option>
                        <option value="11:00">11:00 AM</option>
                        <option value="11:30">11:30 AM</option>
                        <option value="12:00">12:00 PM</option>
                        <option value="12:30">12:30 PM</option>
                        <option value="13:00">1:00 PM</option>
                        <option value="13:30">1:30 PM</option>
                        <option value="14:00">2:00 PM</option>
                        <option value="14:30">2:30 PM</option>
                        <option value="15:00">3:00 PM</option>
                        <option value="15:30">3:30 PM</option>
                        <option value="16:00">4:00 PM</option>
                        <option value="16:30">4:30 PM</option>
                        <option value="17:00">5:00 PM</option>
                        <option value="17:30">5:30 PM</option>
                        <option value="18:00">6:00 PM</option>
                        <option value="18:30">6:30 PM</option>
                        <option value="19:00">7:00 PM</option>
                        <option value="19:30">7:30 PM</option>
                        <option value="20:00">8:00 PM</option>
                        <option value="20:30">8:30 PM</option>
                        <option value="21:00">9:00 PM</option>
                        <option value="21:30">9:30 PM</option>
                        <option value="22:00">10:00 PM</option>
                        <option value="22:30">10:30 PM</option>
                        <option value="23:00">11:00 PM</option>
                        <option value="23:30">11:30 PM</option>
                        </select>
                      </div>
                      <div class="form-group col-md-1">
                        <label style="color:white;margin-top:4px" for="displayEmail">'</label>
                        <div class="custom-control custom-toggle ml-auto my-auto">
                          <input name="friday" type="checkbox" id="friday" class="custom-control-input" <?php if ($isOpen) echo "checked" ?>>
                          <label class="custom-control-label" for="friday"></label>
                        </div>
                      </div>
                    </div>
                    <script>
                      selectElement('05open', '<?= $time["open_time"] ?>');
                      selectElement('05close', '<?= $time["close_time"] ?>');
                    </script>
  
                <?php
                    break;


                  case 6:
                    $isOpen = true;
                    if ($time["open_time"] == "close") {
                      $isOpen = false;
                    } else {
                    }
                  ?>
                    <div class="row">
                      <div class="form-group col-md-2">
                        <label for="displayEmail">Saturday</label>
                        <select <?php if (!$isOpen) echo "disabled" ?> id="06open" name="06open" class="custom-select">
                      <option value="06:00">06:00 AM</option>
                        <option value="06:30">06:30 AM</option>
                        <option value="07:00">07:00 AM</option>
                        <option value="07:30">07:30 AM</option>
                        <option value="08:00">08:00 AM</option>
                        <option value="08:30">08:30 AM</option>
                        <option value="09:00">09:00 AM</option>
                        <option value="09:30">09:30 AM</option>
                        <option value="10:00">10:00 AM</option>
                        <option value="10:30">10:30 AM</option>
                        <option value="11:00">11:00 AM</option>
                        <option value="11:30">11:30 AM</option>
                        <option value="12:00">12:00 PM</option>
                        <option value="12:30">12:30 PM</option>
                        <option value="13:00">1:00 PM</option>
                        <option value="13:30">1:30 PM</option>
                        <option value="14:00">2:00 PM</option>
                        <option value="14:30">2:30 PM</option>
                        <option value="15:00">3:00 PM</option>
                        <option value="15:30">3:30 PM</option>
                        <option value="16:00">4:00 PM</option>
                        <option value="16:30">4:30 PM</option>
                        <option value="17:00">5:00 PM</option>
                        <option value="17:30">5:30 PM</option>
                        <option value="18:00">6:00 PM</option>
                        <option value="18:30">6:30 PM</option>
                        <option value="19:00">7:00 PM</option>
                        <option value="19:30">7:30 PM</option>
                        <option value="20:00">8:00 PM</option>
                        <option value="20:30">8:30 PM</option>
                        <option value="21:00">9:00 PM</option>
                        <option value="21:30">9:30 PM</option>
                        <option value="22:00">10:00 PM</option>
                        <option value="22:30">10:30 PM</option>
                        <option value="23:00">11:00 PM</option>
                        <option value="23:30">11:30 PM</option>
                        </select>
                      </div>
                      <div class="form-group col-md-2">
                        <label style="color:white;font: size 1px;" for="displayEmail">'</label>
                        <select <?php if (!$isOpen) echo "disabled" ?> id="06close" name="06close" class="custom-select">
                      <option value="06:00">06:00 AM</option>
                        <option value="06:30">06:30 AM</option>
                        <option value="07:00">07:00 AM</option>
                        <option value="07:30">07:30 AM</option>
                        <option value="08:00">08:00 AM</option>
                        <option value="08:30">08:30 AM</option>
                        <option value="09:00">09:00 AM</option>
                        <option value="09:30">09:30 AM</option>
                        <option value="10:00">10:00 AM</option>
                        <option value="10:30">10:30 AM</option>
                        <option value="11:00">11:00 AM</option>
                        <option value="11:30">11:30 AM</option>
                        <option value="12:00">12:00 PM</option>
                        <option value="12:30">12:30 PM</option>
                        <option value="13:00">1:00 PM</option>
                        <option value="13:30">1:30 PM</option>
                        <option value="14:00">2:00 PM</option>
                        <option value="14:30">2:30 PM</option>
                        <option value="15:00">3:00 PM</option>
                        <option value="15:30">3:30 PM</option>
                        <option value="16:00">4:00 PM</option>
                        <option value="16:30">4:30 PM</option>
                        <option value="17:00">5:00 PM</option>
                        <option value="17:30">5:30 PM</option>
                        <option value="18:00">6:00 PM</option>
                        <option value="18:30">6:30 PM</option>
                        <option value="19:00">7:00 PM</option>
                        <option value="19:30">7:30 PM</option>
                        <option value="20:00">8:00 PM</option>
                        <option value="20:30">8:30 PM</option>
                        <option value="21:00">9:00 PM</option>
                        <option value="21:30">9:30 PM</option>
                        <option value="22:00">10:00 PM</option>
                        <option value="22:30">10:30 PM</option>
                        <option value="23:00">11:00 PM</option>
                        <option value="23:30">11:30 PM</option>
                        </select>
                      </div>
                      <div class="form-group col-md-1">
                        <label style="color:white;margin-top:4px" for="displayEmail">'</label>
                        <div class="custom-control custom-toggle ml-auto my-auto">
                          <input name="saturday" type="checkbox" id="saturday" class="custom-control-input" <?php if ($isOpen) echo "checked" ?>>
                          <label class="custom-control-label" for="saturday"></label>
                        </div>
                      </div>
                    </div>
                    <script>
                      selectElement('06open', '<?= $time["open_time"] ?>');
                      selectElement('06close', '<?= $time["close_time"] ?>');
                    </script>
  
                <?php
                    break;

                    case 7:
                      $isOpen = true;
                      if ($time["open_time"] == "close") {
                        $isOpen = false;
                      } else {
                      }
                    ?>
                      <div class="row">
                        <div class="form-group col-md-2">
                          <label for="displayEmail">Sunday</label>
                          <select <?php if (!$isOpen) echo "disabled" ?> id="07open" name="07open" class="custom-select">
                          <option value="06:00">06:00 AM</option>
                        <option value="06:30">06:30 AM</option>
                        <option value="07:00">07:00 AM</option>
                        <option value="07:30">07:30 AM</option>
                        <option value="08:00">08:00 AM</option>
                        <option value="08:30">08:30 AM</option>
                        <option value="09:00">09:00 AM</option>
                        <option value="09:30">09:30 AM</option>
                        <option value="10:00">10:00 AM</option>
                        <option value="10:30">10:30 AM</option>
                        <option value="11:00">11:00 AM</option>
                        <option value="11:30">11:30 AM</option>
                        <option value="12:00">12:00 PM</option>
                        <option value="12:30">12:30 PM</option>
                        <option value="13:00">1:00 PM</option>
                        <option value="13:30">1:30 PM</option>
                        <option value="14:00">2:00 PM</option>
                        <option value="14:30">2:30 PM</option>
                        <option value="15:00">3:00 PM</option>
                        <option value="15:30">3:30 PM</option>
                        <option value="16:00">4:00 PM</option>
                        <option value="16:30">4:30 PM</option>
                        <option value="17:00">5:00 PM</option>
                        <option value="17:30">5:30 PM</option>
                        <option value="18:00">6:00 PM</option>
                        <option value="18:30">6:30 PM</option>
                        <option value="19:00">7:00 PM</option>
                        <option value="19:30">7:30 PM</option>
                        <option value="20:00">8:00 PM</option>
                        <option value="20:30">8:30 PM</option>
                        <option value="21:00">9:00 PM</option>
                        <option value="21:30">9:30 PM</option>
                        <option value="22:00">10:00 PM</option>
                        <option value="22:30">10:30 PM</option>
                        <option value="23:00">11:00 PM</option>
                        <option value="23:30">11:30 PM</option>
                          </select>
                        </div>
                        <div class="form-group col-md-2">
                          <label style="color:white;font: size 1px;" for="displayEmail">'</label>
                          <select <?php if (!$isOpen) echo "disabled" ?> id="07close" name="07close" class="custom-select">
                          <option value="06:00">06:00 AM</option>
                        <option value="06:30">06:30 AM</option>
                        <option value="07:00">07:00 AM</option>
                        <option value="07:30">07:30 AM</option>
                        <option value="08:00">08:00 AM</option>
                        <option value="08:30">08:30 AM</option>
                        <option value="09:00">09:00 AM</option>
                        <option value="09:30">09:30 AM</option>
                        <option value="10:00">10:00 AM</option>
                        <option value="10:30">10:30 AM</option>
                        <option value="11:00">11:00 AM</option>
                        <option value="11:30">11:30 AM</option>
                        <option value="12:00">12:00 PM</option>
                        <option value="12:30">12:30 PM</option>
                        <option value="13:00">1:00 PM</option>
                        <option value="13:30">1:30 PM</option>
                        <option value="14:00">2:00 PM</option>
                        <option value="14:30">2:30 PM</option>
                        <option value="15:00">3:00 PM</option>
                        <option value="15:30">3:30 PM</option>
                        <option value="16:00">4:00 PM</option>
                        <option value="16:30">4:30 PM</option>
                        <option value="17:00">5:00 PM</option>
                        <option value="17:30">5:30 PM</option>
                        <option value="18:00">6:00 PM</option>
                        <option value="18:30">6:30 PM</option>
                        <option value="19:00">7:00 PM</option>
                        <option value="19:30">7:30 PM</option>
                        <option value="20:00">8:00 PM</option>
                        <option value="20:30">8:30 PM</option>
                        <option value="21:00">9:00 PM</option>
                        <option value="21:30">9:30 PM</option>
                        <option value="22:00">10:00 PM</option>
                        <option value="22:30">10:30 PM</option>
                        <option value="23:00">11:00 PM</option>
                        <option value="23:30">11:30 PM</option>
                          </select>
                        </div>
                        <div class="form-group col-md-1">
                          <label style="color:white;margin-top:4px" for="displayEmail">'</label>
                          <div class="custom-control custom-toggle ml-auto my-auto">
                            <input name="sunday" type="checkbox" id="sunday" class="custom-control-input" <?php if ($isOpen) echo "checked" ?>>
                            <label class="custom-control-label" for="sunday"></label>
                          </div>
                        </div>
                      </div>
                      <script>
                        selectElement('07open', '<?= $time["open_time"] ?>');
                        selectElement('07close', '<?= $time["close_time"] ?>');
                      </script>
    
                  <?php
                      break;

                default:

                  break;
              }
              ?>

            <?php
            }
            print_r('</div>');
          } else {
            ?>
            <div style="margin-top: 10px;text-align: start;" class="row container-fluid">


              <div class="form-group col-md-2">
                <label for="displayEmail">Monday</label>
                <select id="01open" name="01open" class="custom-select">
                  <option value="06:00">06:00</option>
                  <option value="06:30">06:30</option>
                  <option value="07:00">07:00</option>
                  <option value="07:30">07:30</option>
                  <option value="08:00">08:00</option>
                  <option value="08:30">08:30</option>
                  <option value="09:00">09:00</option>
                  <option value="09:30">09:30</option>
                  <option value="10:00">10:00</option>
                  <option value="10:30">10:30</option>
                  <option value="11:00">11:00</option>
                  <option value="11:30">11:30</option>
                  <option value="12:00">12:00</option>
                  <option value="12:30">12:30</option>
                  <option value="13:00">13:00</option>
                  <option value="13:30">13:30</option>
                  <option value="14:00">14:00</option>
                  <option value="14:30">14:30</option>
                  <option value="15:00">15:00</option>
                  <option value="15:30">15:30</option>
                  <option value="16:00">16:00</option>
                  <option value="16:30">16:30</option>
                  <option value="17:00">17:00</option>
                  <option value="17:30">17:30</option>
                  <option value="18:00">18:00</option>
                  <option value="18:30">18:30</option>
                  <option value="19:00">19:00</option>
                  <option value="19:30">19:30</option>
                  <option value="20:00">20:00</option>
                  <option value="20:30">20:30</option>
                  <option value="21:00">21:00</option>
                  <option value="21:30">21:30</option>
                  <option value="22:00">22:00</option>
                  <option value="22:30">22:30</option>
                  <option value="22:30">23:00</option>
                  <option value="10">23:30</option>
                </select>
              </div>
              <div class="form-group col-md-2">
                <label style="color:white;font: size 1px;" for="displayEmail">'</label>
                <select id="01close" name="01close" class="custom-select">
                  <option value="06:00">06:00</option>
                  <option value="06:30">06:30</option>
                  <option value="07:00">07:00</option>
                  <option value="07:30">07:30</option>
                  <option value="08:00">08:00</option>
                  <option value="08:30">08:30</option>
                  <option value="09:00">09:00</option>
                  <option value="09:30">09:30</option>
                  <option value="10:00">10:00</option>
                  <option value="10:30">10:30</option>
                  <option value="11:00">11:00</option>
                  <option value="11:30">11:30</option>
                  <option value="12:00">12:00</option>
                  <option value="12:30">12:30</option>
                  <option value="13:00">13:00</option>
                  <option value="13:30">13:30</option>
                  <option value="14:00">14:00</option>
                  <option value="14:30">14:30</option>
                  <option value="15:00">15:00</option>
                  <option value="15:30">15:30</option>
                  <option value="16:00">16:00</option>
                  <option value="16:30">16:30</option>
                  <option value="17:00">17:00</option>
                  <option value="17:30">17:30</option>
                  <option value="18:00">18:00</option>
                  <option value="18:30">18:30</option>
                  <option value="19:00">19:00</option>
                  <option value="19:30">19:30</option>
                  <option value="20:00">20:00</option>
                  <option value="20:30">20:30</option>
                  <option value="21:00">21:00</option>
                  <option value="21:30">21:30</option>
                  <option value="22:00">22:00</option>
                  <option value="22:30">22:30</option>
                  <option value="22:30">23:00</option>
                  <option value="10">23:30</option>
                </select>
              </div>

              <div class="form-group col-md-1">
                <label style="color:white;margin-top:4px" for="displayEmail">'</label>
                <div class="custom-control custom-toggle ml-auto my-auto">
                  <input name="monday" type="checkbox" id="monday" class="custom-control-input" checked="">
                  <label class="custom-control-label" for="monday"></label>
                </div>
              </div>

            </div>

            <div style="margin-top: 10px;text-align: start;" class="row container-fluid">
              <div class="form-group col-md-2">
                <label for="displayEmail">Tuesday</label>
                <select id="02open" name="02open" class="custom-select">
                  <option value="06:00">06:00</option>
                  <option value="06:30">06:30</option>
                  <option value="07:00">07:00</option>
                  <option value="07:30">07:30</option>
                  <option value="08:00">08:00</option>
                  <option value="08:30">08:30</option>
                  <option value="09:00">09:00</option>
                  <option value="09:30">09:30</option>
                  <option value="10:00">10:00</option>
                  <option value="10:30">10:30</option>
                  <option value="11:00">11:00</option>
                  <option value="11:30">11:30</option>
                  <option value="12:00">12:00</option>
                  <option value="12:30">12:30</option>
                  <option value="13:00">13:00</option>
                  <option value="13:30">13:30</option>
                  <option value="14:00">14:00</option>
                  <option value="14:30">14:30</option>
                  <option value="15:00">15:00</option>
                  <option value="15:30">15:30</option>
                  <option value="16:00">16:00</option>
                  <option value="16:30">16:30</option>
                  <option value="17:00">17:00</option>
                  <option value="17:30">17:30</option>
                  <option value="18:00">18:00</option>
                  <option value="18:30">18:30</option>
                  <option value="19:00">19:00</option>
                  <option value="19:30">19:30</option>
                  <option value="20:00">20:00</option>
                  <option value="20:30">20:30</option>
                  <option value="21:00">21:00</option>
                  <option value="21:30">21:30</option>
                  <option value="22:00">22:00</option>
                  <option value="22:30">22:30</option>
                  <option value="22:30">23:00</option>
                  <option value="10">23:30</option>
                </select>
              </div>
              <div class="form-group col-md-2">
                <label style="color:white;font: size 1px;" for="displayEmail">'</label>
                <select id="02close" name="02close" class="custom-select">
                  <option value="06:00">06:00</option>
                  <option value="06:30">06:30</option>
                  <option value="07:00">07:00</option>
                  <option value="07:30">07:30</option>
                  <option value="08:00">08:00</option>
                  <option value="08:30">08:30</option>
                  <option value="09:00">09:00</option>
                  <option value="09:30">09:30</option>
                  <option value="10:00">10:00</option>
                  <option value="10:30">10:30</option>
                  <option value="11:00">11:00</option>
                  <option value="11:30">11:30</option>
                  <option value="12:00">12:00</option>
                  <option value="12:30">12:30</option>
                  <option value="13:00">13:00</option>
                  <option value="13:30">13:30</option>
                  <option value="14:00">14:00</option>
                  <option value="14:30">14:30</option>
                  <option value="15:00">15:00</option>
                  <option value="15:30">15:30</option>
                  <option value="16:00">16:00</option>
                  <option value="16:30">16:30</option>
                  <option value="17:00">17:00</option>
                  <option value="17:30">17:30</option>
                  <option value="18:00">18:00</option>
                  <option value="18:30">18:30</option>
                  <option value="19:00">19:00</option>
                  <option value="19:30">19:30</option>
                  <option value="20:00">20:00</option>
                  <option value="20:30">20:30</option>
                  <option value="21:00">21:00</option>
                  <option value="21:30">21:30</option>
                  <option value="22:00">22:00</option>
                  <option value="22:30">22:30</option>
                  <option value="22:30">23:00</option>
                  <option value="10">23:30</option>
                </select>
              </div>

              <div class="form-group col-md-1">
                <label style="color:white;margin-top:4px" for="displayEmail">'</label>
                <div class="custom-control custom-toggle ml-auto my-auto">
                  <input name="tues" type="checkbox" id="tues" class="custom-control-input" checked="">
                  <label class="custom-control-label" for="tues"></label>
                </div>
              </div>
            </div>

            <div style="margin-top: 10px;text-align: start;" class="row container-fluid">
              <div class="form-group col-md-2">
                <label for="displayEmail">Wednesday</label>
                <select id="03open" name="03open" class="custom-select">
                  <option value="06:00">06:00</option>
                  <option value="06:30">06:30</option>
                  <option value="07:00">07:00</option>
                  <option value="07:30">07:30</option>
                  <option value="08:00">08:00</option>
                  <option value="08:30">08:30</option>
                  <option value="09:00">09:00</option>
                  <option value="09:30">09:30</option>
                  <option value="10:00">10:00</option>
                  <option value="10:30">10:30</option>
                  <option value="11:00">11:00</option>
                  <option value="11:30">11:30</option>
                  <option value="12:00">12:00</option>
                  <option value="12:30">12:30</option>
                  <option value="13:00">13:00</option>
                  <option value="13:30">13:30</option>
                  <option value="14:00">14:00</option>
                  <option value="14:30">14:30</option>
                  <option value="15:00">15:00</option>
                  <option value="15:30">15:30</option>
                  <option value="16:00">16:00</option>
                  <option value="16:30">16:30</option>
                  <option value="17:00">17:00</option>
                  <option value="17:30">17:30</option>
                  <option value="18:00">18:00</option>
                  <option value="18:30">18:30</option>
                  <option value="19:00">19:00</option>
                  <option value="19:30">19:30</option>
                  <option value="20:00">20:00</option>
                  <option value="20:30">20:30</option>
                  <option value="21:00">21:00</option>
                  <option value="21:30">21:30</option>
                  <option value="22:00">22:00</option>
                  <option value="22:30">22:30</option>
                  <option value="22:30">23:00</option>
                  <option value="10">23:30</option>
                </select>
              </div>
              <div class="form-group col-md-2">
                <label style="color:white;font: size 1px;" for="displayEmail">'</label>
                <select id="03close" name="03close" class="custom-select">
                  <option value="06:00">06:00</option>
                  <option value="06:30">06:30</option>
                  <option value="07:00">07:00</option>
                  <option value="07:30">07:30</option>
                  <option value="08:00">08:00</option>
                  <option value="08:30">08:30</option>
                  <option value="09:00">09:00</option>
                  <option value="09:30">09:30</option>
                  <option value="10:00">10:00</option>
                  <option value="10:30">10:30</option>
                  <option value="11:00">11:00</option>
                  <option value="11:30">11:30</option>
                  <option value="12:00">12:00</option>
                  <option value="12:30">12:30</option>
                  <option value="13:00">13:00</option>
                  <option value="13:30">13:30</option>
                  <option value="14:00">14:00</option>
                  <option value="14:30">14:30</option>
                  <option value="15:00">15:00</option>
                  <option value="15:30">15:30</option>
                  <option value="16:00">16:00</option>
                  <option value="16:30">16:30</option>
                  <option value="17:00">17:00</option>
                  <option value="17:30">17:30</option>
                  <option value="18:00">18:00</option>
                  <option value="18:30">18:30</option>
                  <option value="19:00">19:00</option>
                  <option value="19:30">19:30</option>
                  <option value="20:00">20:00</option>
                  <option value="20:30">20:30</option>
                  <option value="21:00">21:00</option>
                  <option value="21:30">21:30</option>
                  <option value="22:00">22:00</option>
                  <option value="22:30">22:30</option>
                  <option value="22:30">23:00</option>
                  <option value="10">23:30</option>
                </select>
              </div>


              <div class="form-group col-md-1">
                <label style="color:white;margin-top:4px" for="displayEmail">'</label>
                <div class="custom-control custom-toggle ml-auto my-auto">
                  <input name="wed" type="checkbox" id="wed" class="custom-control-input" checked="">
                  <label class="custom-control-label" for="wed"></label>
                </div>
              </div>
            </div>

            <div style="margin-top: 10px;text-align: start;" class="row container-fluid">
              <div class="form-group col-md-2">
                <label for="displayEmail">Thursday</label>
                <select id="04open" name="04open" class="custom-select">
                  <option value="06:00">06:00</option>
                  <option value="06:30">06:30</option>
                  <option value="07:00">07:00</option>
                  <option value="07:30">07:30</option>
                  <option value="08:00">08:00</option>
                  <option value="08:30">08:30</option>
                  <option value="09:00">09:00</option>
                  <option value="09:30">09:30</option>
                  <option value="10:00">10:00</option>
                  <option value="10:30">10:30</option>
                  <option value="11:00">11:00</option>
                  <option value="11:30">11:30</option>
                  <option value="12:00">12:00</option>
                  <option value="12:30">12:30</option>
                  <option value="13:00">13:00</option>
                  <option value="13:30">13:30</option>
                  <option value="14:00">14:00</option>
                  <option value="14:30">14:30</option>
                  <option value="15:00">15:00</option>
                  <option value="15:30">15:30</option>
                  <option value="16:00">16:00</option>
                  <option value="16:30">16:30</option>
                  <option value="17:00">17:00</option>
                  <option value="17:30">17:30</option>
                  <option value="18:00">18:00</option>
                  <option value="18:30">18:30</option>
                  <option value="19:00">19:00</option>
                  <option value="19:30">19:30</option>
                  <option value="20:00">20:00</option>
                  <option value="20:30">20:30</option>
                  <option value="21:00">21:00</option>
                  <option value="21:30">21:30</option>
                  <option value="22:00">22:00</option>
                  <option value="22:30">22:30</option>
                  <option value="22:30">23:00</option>
                  <option value="10">23:30</option>
                </select>
              </div>
              <div class="form-group col-md-2">
                <label style="color:white;font: size 1px;" for="displayEmail">'</label>
                <select id="04close" name="04close" class="custom-select">
                  <option value="06:00">06:00</option>
                  <option value="06:30">06:30</option>
                  <option value="07:00">07:00</option>
                  <option value="07:30">07:30</option>
                  <option value="08:00">08:00</option>
                  <option value="08:30">08:30</option>
                  <option value="09:00">09:00</option>
                  <option value="09:30">09:30</option>
                  <option value="10:00">10:00</option>
                  <option value="10:30">10:30</option>
                  <option value="11:00">11:00</option>
                  <option value="11:30">11:30</option>
                  <option value="12:00">12:00</option>
                  <option value="12:30">12:30</option>
                  <option value="13:00">13:00</option>
                  <option value="13:30">13:30</option>
                  <option value="14:00">14:00</option>
                  <option value="14:30">14:30</option>
                  <option value="15:00">15:00</option>
                  <option value="15:30">15:30</option>
                  <option value="16:00">16:00</option>
                  <option value="16:30">16:30</option>
                  <option value="17:00">17:00</option>
                  <option value="17:30">17:30</option>
                  <option value="18:00">18:00</option>
                  <option value="18:30">18:30</option>
                  <option value="19:00">19:00</option>
                  <option value="19:30">19:30</option>
                  <option value="20:00">20:00</option>
                  <option value="20:30">20:30</option>
                  <option value="21:00">21:00</option>
                  <option value="21:30">21:30</option>
                  <option value="22:00">22:00</option>
                  <option value="22:30">22:30</option>
                  <option value="22:30">23:00</option>
                  <option value="10">23:30</option>
                </select>
              </div>

              <div class="form-group col-md-1">
                <label style="color:white;margin-top:4px" for="displayEmail">'</label>
                <div class="custom-control custom-toggle ml-auto my-auto">
                  <input name="thurds" type="checkbox" id="thurds" class="custom-control-input" checked="">
                  <label class="custom-control-label" for="thurds"></label>
                </div>
              </div>
            </div>

            <div style="margin-top: 10px;text-align: start;" class="row container-fluid">
              <div class="form-group col-md-2">
                <label for="displayEmail">Friday</label>
                <select id="05open" name="05open" class="custom-select">
                  <option value="06:00">06:00</option>
                  <option value="06:30">06:30</option>
                  <option value="07:00">07:00</option>
                  <option value="07:30">07:30</option>
                  <option value="08:00">08:00</option>
                  <option value="08:30">08:30</option>
                  <option value="09:00">09:00</option>
                  <option value="09:30">09:30</option>
                  <option value="10:00">10:00</option>
                  <option value="10:30">10:30</option>
                  <option value="11:00">11:00</option>
                  <option value="11:30">11:30</option>
                  <option value="12:00">12:00</option>
                  <option value="12:30">12:30</option>
                  <option value="13:00">13:00</option>
                  <option value="13:30">13:30</option>
                  <option value="14:00">14:00</option>
                  <option value="14:30">14:30</option>
                  <option value="15:00">15:00</option>
                  <option value="15:30">15:30</option>
                  <option value="16:00">16:00</option>
                  <option value="16:30">16:30</option>
                  <option value="17:00">17:00</option>
                  <option value="17:30">17:30</option>
                  <option value="18:00">18:00</option>
                  <option value="18:30">18:30</option>
                  <option value="19:00">19:00</option>
                  <option value="19:30">19:30</option>
                  <option value="20:00">20:00</option>
                  <option value="20:30">20:30</option>
                  <option value="21:00">21:00</option>
                  <option value="21:30">21:30</option>
                  <option value="22:00">22:00</option>
                  <option value="22:30">22:30</option>
                  <option value="22:30">23:00</option>
                  <option value="10">23:30</option>
                </select>
              </div>
              <div class="form-group col-md-2">
                <label style="color:white;font: size 1px;" for="displayEmail">'</label>
                <select id="05close" name="05close" class="custom-select">
                  <option value="06:00">06:00</option>
                  <option value="06:30">06:30</option>
                  <option value="07:00">07:00</option>
                  <option value="07:30">07:30</option>
                  <option value="08:00">08:00</option>
                  <option value="08:30">08:30</option>
                  <option value="09:00">09:00</option>
                  <option value="09:30">09:30</option>
                  <option value="10:00">10:00</option>
                  <option value="10:30">10:30</option>
                  <option value="11:00">11:00</option>
                  <option value="11:30">11:30</option>
                  <option value="12:00">12:00</option>
                  <option value="12:30">12:30</option>
                  <option value="13:00">13:00</option>
                  <option value="13:30">13:30</option>
                  <option value="14:00">14:00</option>
                  <option value="14:30">14:30</option>
                  <option value="15:00">15:00</option>
                  <option value="15:30">15:30</option>
                  <option value="16:00">16:00</option>
                  <option value="16:30">16:30</option>
                  <option value="17:00">17:00</option>
                  <option value="17:30">17:30</option>
                  <option value="18:00">18:00</option>
                  <option value="18:30">18:30</option>
                  <option value="19:00">19:00</option>
                  <option value="19:30">19:30</option>
                  <option value="20:00">20:00</option>
                  <option value="20:30">20:30</option>
                  <option value="21:00">21:00</option>
                  <option value="21:30">21:30</option>
                  <option value="22:00">22:00</option>
                  <option value="22:30">22:30</option>
                  <option value="22:30">23:00</option>
                  <option value="10">23:30</option>
                </select>
              </div>

              <div class="form-group col-md-1">
                <label style="color:white;margin-top:4px" for="displayEmail">'</label>
                <div class="custom-control custom-toggle ml-auto my-auto">
                  <input name="friday" type="checkbox" id="friday" class="custom-control-input" checked="">
                  <label class="custom-control-label" for="friday"></label>
                </div>
              </div>
            </div>

            <div style="margin-top: 10px;text-align: start;" class="row container-fluid">
              <div class="form-group col-md-2">
                <label for="displayEmail">Saturday</label>
                <select id="06open" name="06open" class="custom-select">
                  <option value="06:00">06:00</option>
                  <option value="06:30">06:30</option>
                  <option value="07:00">07:00</option>
                  <option value="07:30">07:30</option>
                  <option value="08:00">08:00</option>
                  <option value="08:30">08:30</option>
                  <option value="09:00">09:00</option>
                  <option value="09:30">09:30</option>
                  <option value="10:00">10:00</option>
                  <option value="10:30">10:30</option>
                  <option value="11:00">11:00</option>
                  <option value="11:30">11:30</option>
                  <option value="12:00">12:00</option>
                  <option value="12:30">12:30</option>
                  <option value="13:00">13:00</option>
                  <option value="13:30">13:30</option>
                  <option value="14:00">14:00</option>
                  <option value="14:30">14:30</option>
                  <option value="15:00">15:00</option>
                  <option value="15:30">15:30</option>
                  <option value="16:00">16:00</option>
                  <option value="16:30">16:30</option>
                  <option value="17:00">17:00</option>
                  <option value="17:30">17:30</option>
                  <option value="18:00">18:00</option>
                  <option value="18:30">18:30</option>
                  <option value="19:00">19:00</option>
                  <option value="19:30">19:30</option>
                  <option value="20:00">20:00</option>
                  <option value="20:30">20:30</option>
                  <option value="21:00">21:00</option>
                  <option value="21:30">21:30</option>
                  <option value="22:00">22:00</option>
                  <option value="22:30">22:30</option>
                  <option value="22:30">23:00</option>
                  <option value="10">23:30</option>
                </select>
              </div>
              <div class="form-group col-md-2">
                <label style="color:white;font: size 1px;" for="displayEmail">'</label>
                <select id="06close" name="06close" class="custom-select">
                  <option value="06:00">06:00</option>
                  <option value="06:30">06:30</option>
                  <option value="07:00">07:00</option>
                  <option value="07:30">07:30</option>
                  <option value="08:00">08:00</option>
                  <option value="08:30">08:30</option>
                  <option value="09:00">09:00</option>
                  <option value="09:30">09:30</option>
                  <option value="10:00">10:00</option>
                  <option value="10:30">10:30</option>
                  <option value="11:00">11:00</option>
                  <option value="11:30">11:30</option>
                  <option value="12:00">12:00</option>
                  <option value="12:30">12:30</option>
                  <option value="13:00">13:00</option>
                  <option value="13:30">13:30</option>
                  <option value="14:00">14:00</option>
                  <option value="14:30">14:30</option>
                  <option value="15:00">15:00</option>
                  <option value="15:30">15:30</option>
                  <option value="16:00">16:00</option>
                  <option value="16:30">16:30</option>
                  <option value="17:00">17:00</option>
                  <option value="17:30">17:30</option>
                  <option value="18:00">18:00</option>
                  <option value="18:30">18:30</option>
                  <option value="19:00">19:00</option>
                  <option value="19:30">19:30</option>
                  <option value="20:00">20:00</option>
                  <option value="20:30">20:30</option>
                  <option value="21:00">21:00</option>
                  <option value="21:30">21:30</option>
                  <option value="22:00">22:00</option>
                  <option value="22:30">22:30</option>
                  <option value="22:30">23:00</option>
                  <option value="10">23:30</option>
                </select>
              </div>
              <div class="form-group col-md-1">
                <label style="color:white;margin-top:4px" for="displayEmail">'</label>
                <div class="custom-control custom-toggle ml-auto my-auto">
                  <input name="saturday" type="checkbox" id="saturday" class="custom-control-input" checked="">
                  <label class="custom-control-label" for="saturday"></label>
                </div>
              </div>
            </div>

            <div style="margin-top: 10px;text-align: start;" class="row container-fluid">
              <div class="form-group col-md-2">
                <label for="displayEmail">Sunday</label>
                <select id="07open" name="07open" class="custom-select">
                  <option value="06:00">06:00</option>
                  <option value="06:30">06:30</option>
                  <option value="07:00">07:00</option>
                  <option value="07:30">07:30</option>
                  <option value="08:00">08:00</option>
                  <option value="08:30">08:30</option>
                  <option value="09:00">09:00</option>
                  <option value="09:30">09:30</option>
                  <option value="10:00">10:00</option>
                  <option value="10:30">10:30</option>
                  <option value="11:00">11:00</option>
                  <option value="11:30">11:30</option>
                  <option value="12:00">12:00</option>
                  <option value="12:30">12:30</option>
                  <option value="13:00">13:00</option>
                  <option value="13:30">13:30</option>
                  <option value="14:00">14:00</option>
                  <option value="14:30">14:30</option>
                  <option value="15:00">15:00</option>
                  <option value="15:30">15:30</option>
                  <option value="16:00">16:00</option>
                  <option value="16:30">16:30</option>
                  <option value="17:00">17:00</option>
                  <option value="17:30">17:30</option>
                  <option value="18:00">18:00</option>
                  <option value="18:30">18:30</option>
                  <option value="19:00">19:00</option>
                  <option value="19:30">19:30</option>
                  <option value="20:00">20:00</option>
                  <option value="20:30">20:30</option>
                  <option value="21:00">21:00</option>
                  <option value="21:30">21:30</option>
                  <option value="22:00">22:00</option>
                  <option value="22:30">22:30</option>
                  <option value="22:30">23:00</option>
                  <option value="10">23:30</option>
                </select>
              </div>
              <div class="form-group col-md-2">
                <label style="color:white;font: size 1px;" for="displayEmail">/</label>
                <select id="07close" name="07close" class="custom-select">
                  <option value="06:00">06:00</option>
                  <option value="06:30">06:30</option>
                  <option value="07:00">07:00</option>
                  <option value="07:30">07:30</option>
                  <option value="08:00">08:00</option>
                  <option value="08:30">08:30</option>
                  <option value="09:00">09:00</option>
                  <option value="09:30">09:30</option>
                  <option value="10:00">10:00</option>
                  <option value="10:30">10:30</option>
                  <option value="11:00">11:00</option>
                  <option value="11:30">11:30</option>
                  <option value="12:00">12:00</option>
                  <option value="12:30">12:30</option>
                  <option value="13:00">13:00</option>
                  <option value="13:30">13:30</option>
                  <option value="14:00">14:00</option>
                  <option value="14:30">14:30</option>
                  <option value="15:00">15:00</option>
                  <option value="15:30">15:30</option>
                  <option value="16:00">16:00</option>
                  <option value="16:30">16:30</option>
                  <option value="17:00">17:00</option>
                  <option value="17:30">17:30</option>
                  <option value="18:00">18:00</option>
                  <option value="18:30">18:30</option>
                  <option value="19:00">19:00</option>
                  <option value="19:30">19:30</option>
                  <option value="20:00">20:00</option>
                  <option value="20:30">20:30</option>
                  <option value="21:00">21:00</option>
                  <option value="21:30">21:30</option>
                  <option value="22:00">22:00</option>
                  <option value="22:30">22:30</option>
                  <option value="22:30">23:00</option>
                  <option value="10">23:30</option>
                </select>
              </div>

              <div class="form-group col-md-1">
                <label style="color:white;margin-top:4px" for="displayEmail">'</label>
                <div class="custom-control custom-toggle ml-auto my-auto">
                  <input name="sunday" type="checkbox" id="sunday" class="custom-control-input" checked="">
                  <label class="custom-control-label" for="sunday"></label>
                </div>
              </div>
            </div>

          <?php
          }
          ?>

          <div style="margin-top: 10px;text-align: start;" class="row container-fluid">
            <div class="form-group col-md-2">
              <button type="submit" class="btn btn-accent">Save Changes</button>
            </div>
          </div>


        </form>

      </div>

    </div>
  </div>