<!-- Page Header -->
<div class="page-header row no-gutters py-4">
  <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
    <span class="text-uppercase page-subtitle">Settings</span>
    <h3 class="page-title">Subscription List</h3>
  </div>
</div>
<div class="row">
  <div class="col">
    <div class="card card-small mb-4">
      <div class="card-header border-bottom">
        <h6 class="m-0">Active</h6>
      </div>
      <div style="padding-bottom:0px!important" class="card-body p-0 pb-3 text-center dataTables_wrapper no-footer">
        <table id="activeTable" class="transaction-history d-none dataTable no-footer dtr-inline">
          <thead class="bg-light">
            <tr>
              <th scope="col" class="border-0">Product</th>
              <th scope="col" class="border-0">Start Date</th>

              <th scope="col" class="border-0">Subscription Fees Per Month</th>
              <th scope="col" class="border-0">Maximum listing monthly
              </th>
              <th scope="col" class="border-0">Status</th>
            </tr>
          </thead>
          <tbody>
            <?php
            foreach ($subs as $objDealer) {
            ?>
              <tr>
              <td><?= $objDealer->product_name ?></td>
                <td><?php echo date("d/m/y", strtotime($objDealer->subscription_start_date)); ?></td>
                <td>$<?= $objDealer->product_price ?></td>
                <td><?= $objDealer->max_listing_monthly ?></td>
                <td style="color:green"><?= $objDealer->status_desc ?></td>
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

<div>

</div>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>