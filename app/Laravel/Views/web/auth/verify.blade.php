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
 <section class="hero-section hero-bg-1 ptb-100 full-screen">
        <div class="container">
            <div class="row align-items-center justify-content-center pt-5 pt-sm-5 pt-md-5 pt-lg-0">
                <div class="col-md-4 col-lg-4">
                    <div class="card login-signup-card shadow-lg mb-0">
                        <div class="card-body px-md-5 py-5">
                            <form action="" method="POST" class="login-signup-form">
                                {{ csrf_field() }}
                           	    <h5 class="h5 text-center mb-4">Please Verify code from your phone number to activate account</h5>
                                <div class="form-group">
                                    <input type="text" class="form-control login-input" placeholder="Verification Code" name="code" value="{{old('code')}}">
                                </div>
                               
                                <button type="submit" class="btn btn-block secondary-solid-btn fw-500 mt-4 mb-3">
                                    <i class="fa fa-sign-in-alt"></i> Verify
                                </button>
                            </form>
                           
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

</body>

</html>
