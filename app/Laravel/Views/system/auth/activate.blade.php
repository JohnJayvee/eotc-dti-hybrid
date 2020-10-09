<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>{{$page_title}}</title>
  @include('web._components.styles')
  <style type="text/css">
    .auth .brand-logo img {width: 250px; }
    .text-style {
      line-height: 1em;
      font-size: 10px;
      font-style: italic;
      font-weight: bold;
      color: #000;
    }
  </style>
</head>

<body>
 <section class="hero-section hero-bg-2 ptb-50 full-screen">
        <div class="container">
            <div class="row align-items-center justify-content-center pt-5 pt-sm-5 pt-md-5 pt-lg-0">
                <div class="col-md-4 col-lg-4">
                    <div class="card login-signup-card shadow-lg mb-0">
                        <div class="card-body px-md-5 py-5">
                            <form action="" method="POST" class="login-signup-form">
                                {{ csrf_field() }}
                                @include('system._components.notifications')
                              <div class="brand-logo text-center">
                                <img src="{{asset('web/img/dti-logo-web.png')}}" alt="logo" class="img-fluid" />
                             </div>
                              <h4 class="fw-600 text-black text-center mb-4">Activate your Account</h4>
                              <p class="text-style">Take Note: Please use the given reference number and the password by your Admin to activate your account</p>
                          
                            <div class="form-group">
                                <input type="text" class="form-control login-input" id="input_reference_id" name="reference_id" value="{{old('reference_id')}}" placeholder="Reference Number">
                                @if($errors->first('reference_id'))
                                  <p class="mt-1 text-danger">{!!$errors->first('reference_id')!!}</p>
                                @endif
                            </div>
                            <!-- Password -->
                            <div class="form-group">
                                <input type="password" id="input_otp" class="form-control login-input" placeholder="Password" name="otp">
                                <span toggle="#input_otp" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                @if($errors->first('otp'))
                                  <p class="mt-1 text-danger">{!!$errors->first('otp')!!}</p>
                                @endif
                            </div>
                            <!-- Submit -->
                            <button type="submit" class="btn btn-block btn-white fw-500 mt-4 mb-3 text-black">
                               Activate Account
                            </button>
                            <a href="{{route('system.auth.login')}}" class="btn btn-block secondary-solid-btn fw-500 mt-4 mb-3 text-black">Back To login</a>
                            </form>
<!--                             <div class="text-center">
                              <p class="fw-600 text-black mb-0">Don't have an account?</p>
                              <a href="{{route('system.auth.register')}}" class="fw-600 sign-up">Sign Up</a>
                            </div> -->
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

</body>
<script type="text/javascript">
$(".toggle-password").click(function() {
  $(this).toggleClass("fa-eye fa-eye-slash");
    var input = $($(this).attr("toggle"));
    if (input.attr("type") == "password") {
    input.attr("type", "text");
    } else {
    input.attr("type", "password");
    }
  });
</script>
</html>
