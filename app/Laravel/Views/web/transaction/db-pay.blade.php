@extends('web._layouts.main')


@section('content')



<!--team section start-->
<section class="pt-110 pb-80 gray-light-bg">
    <div class="container-fluid" style="padding: 0 6em;">
        @include('web._components.notifications')
        <div class="row">
            <div class="col-md-7"> 
                <h5 class="text-blue fs-15 m-2">Order Details</h5>
                <div class="card"> 
                    <div class="card-body text-center">
                       @forelse($order_details as $order_detail)
                        <div class="row">
                            <div class="col-md-6">
                                <p class="text-blue float-left">Reference/Transaction/Serial Number:</p>
                            </div>
                            <div class="col-md-6">
                                <p class="float-right text-uppercase" style="text-align: right;">{{$order_detail->transaction_number}}</p>
                            </div>
                        </div>
                         <div class="row">
                            <div class="col-md-6">
                                <p class="text-blue float-left">Particulars:</p>
                            </div>
                            <div class="col-md-6">
                                <p class="float-right text-uppercase" style="text-align: right;">{{$order_detail->particulars}}</p>
                            </div>
                        </div>
                        <hr>
                       @empty

                       @endforelse
                       <h5 class="float-right">Total Amount : PHP {{  Helper::money_format($total_price)}}</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <h5 class="text-blue fs-15 m-2">Request form Details</h5>
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="text-blue float-left">Payor:</p>
                            </div>
                            <div class="col-md-6">
                                <p class="float-right text-uppercase" style="text-align: right;">{{$transaction->payor}}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="text-blue float-left">Address :</p>
                            </div>
                            <div class="col-md-6">
                                <p class="float-right text-uppercase" style="text-align: right;">{{$transaction->address}}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="text-blue float-left">Telephone/Mobile Number :</p>
                            </div>
                            <div class="col-md-6">
                                <p class="float-right text-uppercase" style="text-align: right;">{{$transaction->contact_number}}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="text-blue float-left">Email :</p>
                            </div>
                            <div class="col-md-6">
                                <p class="float-right text-uppercase" style="text-align: right;">{{$transaction->email}}</p>
                            </div>
                        </div>
                      
                        <div class="row mb-5">
                            <div class="col-md-6">
                                <p class="text-blue float-left">Department :</p>
                            </div>
                            <div class="col-md-6">
                                <p class="float-right text-uppercase" style="text-align: right;">{{Helper::order_department($transaction->department)}}</p>
                            </div>
                        </div>
                        <img src="{{asset('web/img/dti-logo.png')}}" alt="logo" class="img-fluid float-right" width="30%">
                    </div>
                </div>

                <a href="{{ route('web.pay', [$code]) }}" class="btn btn-badge-primary fs-14 float-right"><i class="fa fa-check pr-2"></i>  Proceed to Pay </a>
                 <a href="{{route('web.main.index')}}" class="btn btn-badge-danger float-right mr-2">Cancel</a>
            </div>
        </div>
        
    </div>

</section>
<!--team section end-->


@stop
@section('page-styles')
<style type="text/css">
    .custom-btn{
        padding: 5px 10px;
        border-radius: 10px;
        height: 37px;
    }
    .custom-btn:hover{
        background-color: #7093DC !important;
        color: #fff !important;
    }
</style>
@endsection
@section('page-scripts')


@endsection