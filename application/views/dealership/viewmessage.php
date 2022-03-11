<!-- Page Header -->
<div class="page-header row no-gutters py-4">
  <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
    <span class="text-uppercase page-subtitle">MESSAGES</span>
    <h3 class="page-title">Messages</h3>
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="card card-small mb-4">
      <div class="card-header border-bottom">
        <h6 class="m-0">Subject : <?= $messages[0]->subject ?></h6>
      </div>
      <div class="card-body p-0">
        <div class="user-activity__item pr-3 py-3">
          <div class="user-activity__item__icon">
            <img style="    width: 30px;" class="user-avatar rounded-circle mr-2" <?php if ($messages[0]->is_admin == 1) {
                                                                                  ?> src="/assets/img/logomsg.png" <?php
                                                                                  } else { ?> src="https://maxauto.s3-ap-southeast-2.amazonaws.com/maxauto/dealership/logo/<?= $_SESSION["DEALER_LOGO"] ?>" <?php } ?> alt="User Avatar">
          </div>
          <div class="user-activity__item__content">
            <span class="text-light"><?php echo date("D d/m/y H:i", strtotime($messages[0]->indate)); ?></span>
            <p><?= $messages[0]->message ?></p>
          </div>
        </div>
        <?php
        foreach ($replys as $reply) {
        ?>
          <?php if ($reply->is_admin_reply == 1) {
          ?>
            <div class="user-activity__item pr-3 py-3">
              <div class="user-activity__item__icon">
                <img style="width: 30px;" class="user-avatar rounded-circle mr-2" src="/assets/img/logomsg.png" alt="User Avatar">
              </div>
              <div class="user-activity__item__content">
                <span class="text-light"><?php echo date("D d/m/y H:i", strtotime($reply->indate)); ?></span>
                <p><?= $reply->reply ?></p>
              </div>
            </div>
          <?php
          } else { ?>
            <div class="user-activity__item pr-3 py-3">
              <div class="user-activity__item__icon">
                <img style="width: 30px;" class="user-avatar rounded-circle mr-2" src="<?= $_SESSION["DEALER_LOGO"] ?>" alt="User Avatar">
              </div>
              <div class="user-activity__item__content">
                <span class="text-light"><?php echo date("D d/m/y H:i", strtotime($reply->indate)); ?></span>
                <p><?= $reply->reply ?></p>
              </div>
            </div>
          <?php } ?>

        <?php
        }
        ?>
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <form style="width: 100%;" action="/dealership/addReply/<?= $messages[0]->message_id ?>" method="POST" enctype="multipart/form-data">
      <div class="card card-small mb-4">
        <div class="card-header border-bottom">
          <h6 class="m-0">Reply</h6>
        </div>
        <div style="margin:10px" class="card-body p-0">
          <textarea class="form-control" id="desc" name="message" rows="5"></textarea>
          <button type="submit" style="margin-top:10px" class="btn btn-white active">
           Send
            </a>
        </div>
      </div>
    </form>
  </div>

  <div>

  </div>


  <!-- Modal -->



  <style>
    .user-activity__item {
      display: -ms-flexbox;
      display: flex;
      margin-left: 1.875rem;
      border-left: 1px solid #e9ecef;
      border-bottom: 1px solid #e9ecef;
      font-weight: 400
    }

    .user-activity__item:last-child {
      border-bottom: 0
    }

    .user-activity__item::after {
      display: block;
      clear: both;
      content: ""
    }

    .user-activity__item__icon {
      text-align: center;
      border-radius: 50%;
      float: left;
      width: 1.875rem;
      height: 1.875rem;
      min-width: 1.875rem;
      background: #f5f6f8;
      margin-left: -.9375rem;
      margin-right: .9375rem;
      box-shadow: 0 0 0 2px #fff, inset 0 0 3px rgba(0, 0, 0, .2)
    }

    .user-activity__item__icon i {
      font-size: 1rem;
      line-height: 1.875rem;
      color: #aeb9c4
    }

    .user-activity__item__content {
      float: left
    }

    .user-activity__item__content p {
      margin: 0
    }

    .user-activity__item__content a {
      font-weight: 400
    }

    .user-activity__item__content span {
      font-size: 80%
    }

    .user-activity__item__action {
      float: right
    }

    .user-activity__item__task-list {
      list-style: none;
      margin: 0;
      padding: 0
    }

    .user-activity__item__task-list .custom-control {
      line-height: 1.5rem
    }
  </style>