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
                            <h4 class="fw-600 text-black text-center mb-4">Setup your account</h4>
                            <div class="form-group">
                                <input type="text" class="form-control login-input" id="input_reference_number" name="reference_number" value="{{old('reference_number')}}" placeholder="Reference Number">
                                @if($errors->first('reference_number'))
                                  <small class="form-text pl-1" style="color:red;">{{$errors->first('reference_number')}}</small>
                                @endif
                            </div>

                            <!-- Password -->
                            <div class="form-group">
                                <input type="password" id="password-field" class="form-control login-input" placeholder="New Password" name="password">
                                <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                @if($errors->first('password'))
                                  <small class="form-text pl-1" style="color:red;">{{$errors->first('password')}}</small>
                                @endif
                            </div>

                            <div class="form-group">
                                <input type="password" class="form-control login-input" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password">
                                <span toggle="#password_confirmation" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                            </div>
                              <button type="submit" class="btn btn-block btn-white fw-500 mt-4 mb-3">
                                Save
                              </button>
                            </form>
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
