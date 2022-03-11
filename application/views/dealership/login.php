<div class="row no-gutters h-100">
    <div style="margin-top: 100px!important;" class="col-lg-3 col-md-5 auth-form mx-auto my-auto">
        <div class="card">
            <div class="card-body">
                <img style="max-width: 227px!important;" class="auth-form__logo d-table mx-auto mb-3" src="/assets/img/logoAzul.png" alt="Shards Dashboards - Register Template">
                <h5 class="auth-form__title text-center mb-4">Access Your Account</h5>
                <form action="/dealership/signInDealer" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Email address</label>
                        <input name="user" type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Password</label>
                        <input name="password" type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
                    </div>

                    <button type="submit" class="btn btn-pill btn-accent d-table mx-auto">Access Account</button>
                </form>
            </div>
            <div class="card-footer border-top">
                <ul class="auth-form__social-icons d-table mx-auto">
                    <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                    <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                    <li><a href="#"><i class="fab fa-github"></i></a></li>
                    <li><a href="#"><i class="fab fa-google-plus-g"></i></a></li>
                </ul>
            </div>
        </div>

    </div>
</div>