<!-- Page Header -->
<div class="page-header row no-gutters py-4">
  <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
    <span class="text-uppercase page-subtitle">MESSAGES</span>
    <h3 class="page-title">Contact us </h3>
  </div>

</div>


<div class="row">
  <div class="col">
    <div class="card card-small mb-4">
      <div class="card-header border-bottom">
        <h6 class="m-0">Messages</h6>
      </div>
      <div style="padding-bottom:0px!important" class="card-body p-0 pb-3 text-center dataTables_wrapper no-footer">

        <div id="divTable1">
          <table id="listMessageAdmin2" class="transaction-history d-none dataTable no-footer dtr-inline">
            <thead class="bg-light">
              <tr>
                <th scope="col" class="border-0">Email</th>
                <th scope="col" class="border-0">Phone</th>
                <th scope="col" class="border-0">Bussiness name</th>
                <th scope="col" class="border-0">Name</th>
                <th scope="col" class="border-0">Date</th>
                <th scope="col" class="border-0">Message</th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach ($messages as $message) {
              ?>
                <tr>
                  <td style="text-align: left;"> <?= $message->name ?></td>
                  <td style="text-align: left;"> <?= $message->bussines_name ?></td>
                  <td style="text-align: left;"> <?= $message->phone ?></td>
                  <td style="text-align: left;"> <?= $message->email ?></td>
                  <td style="text-align: left;"> <?php echo date("D d/m", strtotime($message->indate)); ?></td>
                  <td style="text-align: left;"> <?= $message->desc ?></td>
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
<div>
</div>

<style>
  #listMessageAdmin tbody tr.even:hover {
    transform: scale(1);
    -webkit-transform: scale(1);
    -moz-transform: scale(1);
    box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.3);
    -webkit-box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.3);
    -moz-box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.3);
  }

  #listMessageAdmin tbody tr.odd:hover {
    transform: scale(1);
    -webkit-transform: scale(1);
    -moz-transform: scale(1);
    box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.3);
    -webkit-box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.3);
    -moz-box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.3);
  }

  #listMessageAdmin thead {
    display: none;
  }
</style>


<script>
  function viewMessage(id) {
    window.location.href = "viewmessage/" + id;
  }
</script>


<!-- Modal -->
<form style="width: 100%;" action="/Maxautoadmin/sendMessage" method="POST" enctype="multipart/form-data">
  <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">New Message </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-sm-12 col-md-12">
              <div class="form-group">
                <label for="feLastName">Dealership</label>
                <div class="input-group mb-3">
                  <select name="business" class="form-control">
                    <?php
                    foreach ($dealer as $objDealer) {
                    ?>
                      <option value="<?= $objDealer->dealership_id ?>"><?= $objDealer->dealership_name ?></option>
                    <?php
                    }
                    ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label for="feLastName">Subject</label>
                <div class="input-group">
                  <input name="subject" type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
                </div>
              </div>
              <div class="form-group">
                <label for="feLastName">Message</label>
                <div class="input-group">
                  <textarea class="form-control" id="desc" name="message" rows="5"></textarea>
                </div>
              </div>




            </div>
          </div>
        </div>
        <div class="modal-footer">

          <button type="submit" class="btn btn-primary">Send message</button>
        </div>
      </div>
    </div>
  </div>
</form>