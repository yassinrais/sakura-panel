<div class="row auth-row">
    <div class="auth-lside d-none d-lg-block col-lg-7">
      <div class="auth-back"></div>
    </div>
    <div class="auth-rside  vcenter-item col-12 col-lg-5">
      <div class="p-5 ">
        <div class="text-center">
          <h1 class="pb-3 text-gray-900 mb-4">{{locale._("Login to continue")}}</h1>
        </div>
        <form class="user auth-form" action="auth/ajaxLogin" method="post">
          <div class="auth-msgs form-group">
            {{ flashSession.output() }}
            {{ flash.output() }}
          </div>
          <div class="wrap-input validate-input">
            {{ 
              form.render('email' , [ "class":"input", "id":"","aria-describedby":"emailHelp" ,"placeholder":"" ])
            }}
            <span class="focus-input"></span>
            <span class="label-input">{{locale._("Email")}}</span>
          </div>
          <div class="wrap-input validate-input">
            <span class="focus-input"></span>
            {{ 
              form.render('password' , [ "class":"input", "id":"","aria-describedby":"emailHelp" ,"placeholder":"" ])
            }}
            <span class="focus-input"></span>
            <span class="label-input">{{locale._("Password")}}</span>
          </div>
          {{
            form.render('csrf' , ['value': security.getToken()])
          }}
          <div class="form-group">
            <div class="custom-control custom-checkbox small">
              {{
                form.render('remember', ["class":"custom-control-input","id":"checkbox_rmb"])
              }}
              <label class="custom-control-label" for="checkbox_rmb">{{locale._("Remember Me")}}</label>
            </div>
          </div>
          <button type="submit" name="action" value="login" class="btn btn-primary btn-user btn-block">
            <i class="fas fa-key"></i> {{locale._("Login")}}
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