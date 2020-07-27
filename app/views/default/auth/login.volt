  <div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

      <div class="col-xl-10 col-lg-12 col-md-9">

        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
              <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
              <div class="col-lg-6">
                <div class="p-5">
                  <div class="text-center">
                    <img src="assets/img/sakura-icon.png" class="img-responsive rounded-circle mb-3" width="80px">
                    <h1 class="h4 text-gray-900 mb-4"><b>Authentification</b></h1>
                    <h1 class="h4 text-gray-900 mb-4">Sign In</h1>
                  </div>
                  <form class="user" action="" method="post">
                    {{ flashSession.output() }}
                    {{ flash.output() }}
                    <div class="form-group">
                      {{ 
                        form.render('email' , [ "class":"form-control form-control-user", "aria-describedby":"emailHelp" ,"placeholder":"Enter Email Address..." ])
                      }}
                    </div>
                    <div class="form-group">
                      {{ 
                        form.render('password' , [ "class":"form-control form-control-user", "aria-describedby":"emailHelp" ,"placeholder":"Enter Password..." ])
                      }}
                    </div>
                    {{
                      form.render('csrf' , ['value': security.getToken()])
                    }}
                    <div class="form-group">
                      <div class="custom-control custom-checkbox small">
                        {{
                          form.render('remember', ["class":"custom-control-input","id":"checkbox_rmb"])
                        }}
                        <label class="custom-control-label" for="checkbox_rmb">Remember Me</label>
                      </div>
                    </div>
                    <button type="submit" name="action" value="login" class="btn btn-primary btn-user btn-block">
                      Login
                    </button>
                  </form>
                  <!-- <hr> -->
                  <!-- <div class="text-center">
                    <a class="small" href="forgot-password.html">Forgot Password?</a>
                  </div>
                  <div class="text-center">
                    <a class="small" href="register.html">Create an Account!</a>
                  </div> -->
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>
