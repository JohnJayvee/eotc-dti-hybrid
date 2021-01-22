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
                                <p class="text-blue float-left">Transaction Number:</p>
                            </div>
                            <div class="col-md-6">
                                <p class="float-right text-uppercase" style="text-align: right;">{{$order_detail->transaction_number}}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="text-blue float-left">Designation Number:</p>
                            </div>
                            <div class="col-md-6">
                                <p class="float-right text-uppercase" style="text-align: right;">{{$order_detail->designation_number}}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="text-blue float-left">Title:</p>
                            </div>
                            <div class="col-md-6">
                                <p class="float-right text-uppercase" style="text-align: right;">{{$order_detail->order_title}}</p>
                            </div>
                        </div>
                        <hr>

                       @empty

                       @endforelse
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <h5 class="text-blue fs-15 m-2">Request form Details</h5>
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="text-blue float-left">Distinction:</p>
                            </div>
                            <div class="col-md-6">
                                <p class="float-right text-uppercase" style="text-align: right;">{{$transaction->order->title}}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="text-blue float-left">First Name:</p>
                            </div>
                            <div class="col-md-6">
                                <p class="float-right text-uppercase" style="text-align: right;">{{$transaction->fname}}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="text-blue float-left">Transaction Number:</p>
                            </div>
                            <div class="col-md-6">
                                <p class="float-right text-uppercase" style="text-align: right;">{{$transaction->mname}}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="text-blue float-left">Middle Initial :</p>
                            </div>
                            <div class="col-md-6">
                                <p class="float-right text-uppercase" style="text-align: right;">{{$transaction->lname}}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="text-blue float-left">Company name :</p>
                            </div>
                            <div class="col-md-6">
                                <p class="float-right text-uppercase" style="text-align: right;">{{$transaction->company_name}}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="text-blue float-left">Company Address :</p>
                            </div>
                            <div class="col-md-6">
                                <p class="float-right text-uppercase" style="text-align: right;">{{$transaction->order->address}}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="text-blue float-left">Telephone Number :</p>
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
                        <div class="row">
                            <div class="col-md-6">
                                <p class="text-blue float-left">Sector :</p>
                            </div>
                            <div class="col-md-6">
                                <p class="float-right text-uppercase" style="text-align: right;">{{$transaction->order->sector}}</p>
                            </div>
                        </div>
                        <div class="row mb-5">
                            <div class="col-md-6">
                                <p class="text-blue float-left">Purpose :</p>
                            </div>
                            <div class="col-md-6">
                                <p class="float-right text-uppercase" style="text-align: right;">{{$transaction->order->purpose}}</p>
                            </div>
                        </div>
                        <img src="{{asset('web/img/PCIMS_logo.jpg')}}" alt="logo" class="img-fluid float-right" width="30%">
                    </div>
                </div>

                <a href="{{ route('web.pay', [$code]) }}" class="btn btn-badge-primary fs-14 float-right"><i class="fa fa-check pr-2"></i>  Proceed to Pay </a>
                 <a href="{{route('system.department.index')}}" class="btn btn-badge-danger float-right mr-2">Cancel</a>
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