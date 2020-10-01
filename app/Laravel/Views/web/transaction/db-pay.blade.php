@extends('web._layouts.main')


@section('content')



<!--team section start-->
<section class="px-120 pt-110 pb-80 gray-light-bg">
    <div class="container">
        @include('web._components.notifications')
        <div class="row">
            <div class="col-md-4 equal"> 
                <div class="card"> 
                    <div class="card-body text-center">
                        <h5 class="text-blue fs-14 m-0">SCAN 2 PAY</h5>
                        {!! QrCode::size(200)->generate($transaction->processing_fee_code) !!}
                       <p class="fw-bolder text-gray">Scan This QR code.</p>
                       <hr class="hr-full-dashed pt-0">
                        <h5 class="text-blue fs-14 m-0">INPUT 2 PAY</h5>
                        <h3 class="text-yellow fw-bolder pt-3">{{ strtoupper($code) }}</h3>
                       
                        <p class="text-italic fs-14 text-gray">Keep a copy/screenshot of this reference code.</p>
                        <a href="{{ route('web.transaction.pay', [$code]) }}" class="btn btn-badge-primary text-white fs-14"><i class="fa fa-check pr-2"></i>  Proceed to Pay </a>
                    </div>
                </div>
            </div>
            <div class="col-md-8 equal">
                <div class="card">
                    <div class="card-body">
                        <h5 class="text-blue fs-14 m-0">SEND QR CODE/REFERENCE CODE</h5>
                        <p class="text-yellow text-uppercase fs-14 fw-bolder pt-4">Contact Information</p>
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" name="email" class="form-control" placeholder="Email Address">
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="input-group mb-3">
                                      <div class="input-group-prepend">
                                        <span class="input-group-text text-title fw-600">+63 <span class="pr-1 pl-2" style="padding-bottom: 2px"> |</span></span>
                                      </div>
                                      <input type="text" class="form-control br-left-white" placeholder="Contact Number" name="contact_number" value="{{old('contact_number')}}">
                                    </div>
                                    
                                </div>
                            </div>
                            <button class="btn btn-badge-primary text-white fs-14 ml-3" type="submit"><i class="fa fa-paper-plane pr-2"></i>  Send </button><code class="pt-3 ml-3 text-italic">Pay Fee upon arrival on store/payment center</code>
                        </div>
                    </div>
                </div>
                <div class="card mt-4 w-100">
                    <div class="card-body">
                        <h5 class="text-blue fs-14 m-0 text-uppercase mb-4">Pay at mobile pos/wallet</h5>
                        <div class="row">
                             <div class="col-md-3">
                                 <button class="btn btn-badge-primary text-white fs-14 " type="submit"><i class="fa fa-list-alt pr-2"></i>  View list </button>
                             </div>
                             <div class="col-md-9 custom-margin">
                                 <p class="m-0 text-black fs-14" style="line-height: 1.5em">View different mobile POS/Wallet payment methods</p>
                                 <p class="m-0 text-gray fs-14 text-italic" style="line-height: 1.5em">Redirect to the selection page</p>
                             </div>
                        </div>
                        <hr class="hr-full-dashed pt-0">
                        <h5 class="text-blue fs-14 m-0 text-uppercase mb-4">Wallet App</h5>
                        <div class="row">
                             <div class="col-md-4">
                                 <button class="btn btn-badge-primary text-white fs-14 " type="submit"><i class="fa fa-download  pr-2"></i>  Download App </button>
                             </div>
                             <div class="col-md-8 custom-margin-two">
                                 <p class="m-0 text-black fs-14" style="line-height: 1.5em">Install Wallet App to your phone.</p>
                                 <p class="m-0 text-gray fs-14 text-italic" style="line-height: 1.5em">Redirect to the download page</p>
                             </div>
                        </div>
                    </div>
                </div>
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