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
    .text-6xl{
        font-size: 5em;
    }
    .text-status{
        font-size: 1.4em;
        font-weight: bold;
    }
    .text-label{
        font-size: 15px;
        font-weight: bold;
    }

    .btn-badge-primary{
        display: inline;
        background-color: #0045A2 ;
        padding: 0.8rem 1.5rem;
    }
    .btn-badge-primary:hover{
        background-color: #27437D ;
    }
    .status-pending{
        color:#D69E2E;
    }
    .status-success{
        color: #38A169;
    }
    .status-failed{
        color: #D63231;
    }
  </style>
</head>

<body>
 <section class="bg-white ptb-100 full-screen">
        <div class="container">
            <div class="row align-items-center justify-content-center pt-5 pt-sm-5 pt-md-5 pt-lg-0">
                <div class="col-md-4 col-lg-4">
                    <div class="card login-signup-card shadow-lg mb-0">
                        <div class="card-body px-md-5 py-5">
                            <div class="text-center">
                                @if(($prefix == "APP" AND $transaction->application_transaction_status == "COMPLETED") || ($prefix == "PF" AND $transaction->transaction_status == "COMPLETED"))
                                    <span class="text-6xl">
                                        <i class="fas fa-check-circle status-success"></i>
                                    </span> 
                                    <h2 class="status-success">Success!</h2>
                                    <p class="text-status">Transaction Success</p>
                                    <p class="text-label"> Your payment has been processed. This is to confirm your Online Application was successful.</p>
                                @elseif(($prefix == "APP" AND $transaction->application_transaction_status == "PENDING") || ($prefix == "PF" AND $transaction->transaction_status == "PENDING"))
                                    <span class="text-6xl">
                                    <i class="fas fa-check-circle status-pending"></i>
                                    </span> 
                                    <h2 class="status-pending">Pending!</h2>
                                    <p class="text-status">Transaction Pending</p>
                                    <p class="text-label"> Sorry your payment was not processed. </p>
                                @else
                                    <span class="text-6xl">
                                    <i class="fas fa-times-circle status-failed"></i>
                                    </span> 
                                    <h2 class="status-failed">Failed!</h2>
                                    <p class="text-status">Transaction Pending</p>
                                    <p class="text-label"> Sorry your card transaction cannot be processed. Please try again, or you may contact your Bank. Thank you. </p>
                                @endif
                                <a href="{{route('web.main.index')}}" class="btn btn-badge-primary text-white">Go back to home</a href=""></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</body>
</html>
