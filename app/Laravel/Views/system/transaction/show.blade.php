@extends('system._layouts.main')

@section('content')
<div class="row px-5 py-4">
  <div class="col-12">
    @include('system._components.notifications')
    <div class="row ">
      <div class="col-md-6">
        <h5 class="text-title text-uppercase">{{$page_title}}</h5>
      </div>
      <div class="col-md-6 ">
        <p class="text-dim  float-right">EOR-PHP Processor Portal / Transactions / Transaction Details</p>
      </div>
    </div>
  </div>
  <div class="col-12 pt-4">
    <div class="card card-rounded shadow-sm">
      <div class="card-body" style="border-bottom: 3px dashed #E3E3E3;">
        <div class="row">
          <div class="col-md-1 text-center">
            <img src="{{asset('system/images/default.jpg')}}" class="rounded-circle" width="100%">
          </div>
          <div class="col-md-11 d-flex">
            <p class="text-title fw-500 pt-3">Application by: <span class="text-black">{{Str::title($transaction->customer ? $transaction->customer->full_name : $transaction->customer_name)}}</span></p>
            <p class="text-title fw-500 pl-3" style="padding-top: 15px;">|</p>
            <p class="text-title fw-500 pt-3 pl-3">Application Sent: <span class="text-black">{{ Helper::date_format($transaction->created_at)}}</span></p>
          </div>
        </div> 
      </div>
      <div class="card-body" style="border-bottom: 3px dashed #E3E3E3;">
        <div class="row">
          <div class="col-md-6">
            <p class="text-title fw-500">Name: <span class="text-black">{{str::title($transaction->customer ? $transaction->customer->full_name : $transaction->customer_name)}}</span></p>
            <p class="text-title fw-500">Application: <span class="text-black">{{$transaction->type ? Str::title($transaction->type->name) : "N/A"}} [{{$transaction->code}}] </span></p>
            <p class="text-title fw-500">Email Address: <span class="text-black">{{$transaction->email}}</span></p>
            <p class="text-title fw-500">Processor Status: <span class="badge badge-{{Helper::status_badge($transaction->status)}} p-2">{{Str::title($transaction->status)}}</span></p>
          </div>
          <div class="col-md-6">
            <p class="text-title fw-500">Deparatment/Agency: <span class="text-black">{{$transaction->department ? Str::title($transaction->department->name) : "N/A"}}</span></p>
            <p class="text-title fw-500">Contact Number: <span class="text-black">+63{{$transaction->contact_number}}</span></p>
          
            @if($transaction->status == "DECLINED")
              <p class="text-title fw-500">Remarks: <span class="text-black">{{$transaction->remarks}}</span></p>
            @endif
          </div>
          <div class="col-md-6 mt-4">
            <p class="text-title fw-500">Transaction Details:</span></p>
            <p class="text-title fw-500">Status: <span class="badge  badge-{{Helper::status_badge($transaction->transaction_status)}} p-2">{{Str::title($transaction->transaction_status)}}</span></p>
            <p class="fw-500" style="color: #DC3C3B;">Processing Fee: Php {{Helper::money_format($transaction->processing_fee)}} [{{$transaction->processing_fee_code}}]</p>
            <p class="text-title fw-500">Payment Status: <span class="badge  badge-{{Helper::status_badge($transaction->payment_status)}} p-2">{{Str::title($transaction->payment_status)}}</span></p>
          </div>
          <div class="col-md-6 mt-4">
            <p class="text-title fw-500">Application Details:</span></p>
            <p class="text-title fw-500">Status: <span class="badge  badge-{{Helper::status_badge($transaction->application_transaction_status)}} p-2">{{Str::title($transaction->application_transaction_status)}}</span></p>
            <p class="fw-500" style="color: #DC3C3B;">Amount: Php {{Helper::money_format($transaction->amount ? $transaction->amount : "0.00")}} [{{ $transaction->transaction_code }}]</p>
            <p class="text-title fw-500">Payment Status: <span class="badge  badge-{{Helper::status_badge($transaction->application_payment_status)}} p-2">{{Str::title($transaction->application_payment_status)}}</span></p>
          </div>
          @if($transaction->process_by == "processor")
          <div class="col-md-6">
            <table>
              <thead>
                <tr><td class="text-title fw-500">Submitted Physical Requirements: [{{$transaction->document_reference_code}}]</td></tr>
              </thead>
              <tbody>
                @foreach($physical_requirements as $index)
                  <tr>
                    <td>{{$index->name}}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          @endif
        </div> 
      </div>
      @if($transaction->process_by == "customer")
      <div class="card-body d-flex">
        <button class="btn btn-transparent p-3" data-toggle="collapse" data-target="#collapseExample"><i class="fa fa-download" style="font-size: 1.5rem;"></i></button>
        <p class="text-title pt-4 pl-3 fw-500">Review Attached Requirements: {{$count_file}} Item / s</p>
      </div>
      @endif
    </div>
    @if($transaction->process_by == "customer")
      <div class="collapse pt-2" id="collapseExample">
        <div class="card card-body card-rounded">
          <div class="row justify-content-center">
            <table class="table table-striped">
              <thead>
                <th>FileName</th>
                <th>File Type</th>
                <th>Status</th>
                @if(Auth::user()->type == "processor")
                  @if(in_array($transaction->status, ['PENDING', 'ONGOING']) AND $transaction->transaction_status == "COMPLETED")
                    <th>Action</th>
                  @endif
                @endif
              </thead>
              <tbody>
              @forelse($attachments as $index => $attachment)
                <tr>
                  <td><a href="{{$attachment->directory}}/{{$attachment->filename}}" target="_blank">{{$attachment->original_name}}</a></td>
                  <td>{{$attachment->type}}</td>
                  <td>{{Str::title($attachment->status)}}</td>
                  @if(Auth::user()->type == "processor" )
                    @if(in_array($transaction->status, ['PENDING', 'ONGOING']) AND $transaction->transaction_status == "COMPLETED")
                    <td >
                      <button type="button" class="btn btn-sm p-0" data-toggle="dropdown" style="background-color: transparent;"> <i class="mdi mdi-dots-horizontal" style="font-size: 30px"></i></button>
                      <div class="dropdown-menu" aria-labelledby="dropdownMenuSplitButton2">
                        <a class="dropdown-item btn-approved-requirements" href="#" data-url="{{route('system.transaction.requirements',[$attachment->id])}}?status=approved">Approve</a>
                        <a class="dropdown-item btn-approved-requirements" href="#" data-url="{{route('system.transaction.requirements',[$attachment->id])}}?status=declined">Decline</a>
                      </div>
                    </td>
                    @endif
                  @endif
                </tr>
              @empty
              <h5>No Items available.</h5>
              @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>  
    @endif
    @if(Auth::user()->type == "processor")
      @if(in_array($transaction->status, ['PENDING', 'ONGOING']) AND $transaction->transaction_status == "COMPLETED")
        <a data-url="{{route('system.transaction.process',[$transaction->id])}}?status_type=approved"  class="btn btn-primary mt-4 btn-approved border-5 text-white {{$transaction->status == 'approved' ? "isDisabled" : ""}}"><i class="fa fa-check-circle"></i> Approve Transactions</a>
        <a  data-url="{{route('system.transaction.process',[$transaction->id])}}?status_type=declined" class="btn btn-danger mt-4 btn-decline border-5 text-white {{$transaction->status == 'approved' ? "isDisabled" : ""}}""><i class="fa fa-times-circle"></i> Decline Transactions</a>
      @endif
    @endif
  </div>
  
</div>
@stop



@section('page-styles')
<link rel="stylesheet" href="{{asset('system/vendors/sweet-alert2/sweetalert2.min.css')}}">
<link rel="stylesheet" href="{{asset('system/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css')}}">
<style type="text/css" >
  .input-daterange input{ background: #fff!important; }  
  .isDisabled{
    color: currentColor;
    display: inline-block;  /* For IE11/ MS Edge bug */
    pointer-events: none;
    text-decoration: none;
    cursor: not-allowed;
    opacity: 0.5;
  }
</style>
@stop

@section('page-scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script src="{{asset('system/vendors/sweet-alert2/sweetalert2.min.js')}}"></script>
<script src="{{asset('system/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
<script type="text/javascript">
  $(function(){
    $('.input-daterange').datepicker({
      format : "yyyy-mm-dd"
    });
    $(".btn-decline").on('click', function(){
      var url = $(this).data('url');
      var self = $(this)
      Swal.fire({
        title: "All the submitted requirements will be marked as declined. Are you sure you want to declined this application?",
        
        icon: 'warning',
        input: 'text',
        inputPlaceholder: "Put remarks",
        showCancelButton: true,
        confirmButtonText: 'Decline',
        cancelButtonColor: '#d33'
      }).then((result) => {
        if (result.value === "") {
          alert("You need to write something")
          return false
        }
        if (result.value) {
          window.location.href = url + "&remarks="+result.value;
        }
      });
    });
    $(".btn-approved").on('click', function(){
      var url = $(this).data('url');
      var self = $(this)
      Swal.fire({
        title: "All the submitted requirements will be marked as approved. Are you sure you want to approve this application?",
        
        icon: 'info',
        input: 'number',
        inputPlaceholder: "Put Amount",
        showCancelButton: true,
        confirmButtonText: 'Approved!',
        cancelButtonColor: '#d33'
      }).then((result) => {
        if (result.value === "") {
          alert("You need to write something")
          return false
        }
        if (result.value) {
          window.location.href = url + "&amount="+result.value;
        }
      });
    });

    $(".btn-approved-requirements").on('click', function(){
      var url = $(this).data('url');
      var self = $(this)
      Swal.fire({
        title: 'Are you sure you want to modify this requirements?',
        text: "You will not be able to undo this action, proceed?",
        showDenyButton: true,
        showCancelButton: true,
        confirmButtonText: `Proceed`,
      }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
          window.location.href = url
        }
      });
    });
  })
</script>
@stop