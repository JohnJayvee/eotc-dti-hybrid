@extends('system._layouts.main')

@section('content')
<div class="row p-3">
  <div class="col-12">
    @include('system._components.notifications')
    <div class="row ">
      <div class="col-md-6">
        <h5 class="text-title text-uppercase">{{$page_title}}</h5>
      </div>
      <div class="col-md-6 ">
        <p class="text-dim  float-right">EOR-PHP Processor Portal / Transactions</p>
      </div>
    </div>
  
  </div>

  <div class="col-12 ">
    <form>
      <div class="row">
        <div class="col-md-2 p-2">
          <select class="form-control form-control-lg classic" id="exampleFormControlSelect1">
            <option>Filter By</option>
            <option>Filter By</option>
            <option>Filter By</option>

          </select>
        </div>
        <div class="col-md-2 p-2">
          <select class="form-control form-control-lg classic" id="exampleFormControlSelect1">
            <option>Entries</option>
          </select>
        </div>
        <div class="col-md-5"></div>
        <div class="col-md-3 p-2">
          <div class="form-group has-search">
            <span class="fa fa-search form-control-feedback"></span>
            <input type="text" class="form-control form-control-lg" placeholder="Search">
          </div>
        </div>
      </div>
    </form>
  </div>
  <div class="col-md-12">
     <div class="shadow fs-15">
      <table class="table table-responsive table-striped table-wrap" style="table-layout: fixed;">
        <thead>
          <tr class="text-center ">
            <th class="text-title p-3" width="15%">Transaction Date</th>
            <th class="text-title p-3" width="15%">Submitted By</th>
            <th class="text-title p-3" width="30%">Application Type</th>
            <th class="text-title p-3" width="10%">Processing Fee</th>
            <th class="text-title p-3" width="10%">Amount</th>
            <th class="text-title p-3" width="10%">Processor/Status</th>
            <th class="text-title p-3" width="10%">Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($transactions as $transaction)
          <tr class="text-center">
            <td>{{ Helper::date_format($transaction->created_at)}}</td>
            <td>{{ $transaction->customer ? $transaction->customer->full_name : $transaction->customer_name}}</td>
            <td>{{ $transaction->type ? Strtoupper($transaction->type->name) : "N/A"}}<br> {{$transaction->code}}</td>
            <td>
              <div>{{$transaction->processing_fee ?: 0 }}</div>
              <div><small><span class="badge badge-pill badge-{{Helper::status_badge($transaction->payment_status)}} p-2">{{Str::upper($transaction->payment_status)}}</span></small></div>
              <div><small><span class="badge badge-pill badge-{{Helper::status_badge($transaction->transaction_status)}} p-2 mt-1">{{Str::upper($transaction->transaction_status)}}</span></small></div>
            </td>
            <td>
              <div>{{$transaction->amount ?: '---'}}</div>
              <div><small><span class="badge badge-pill badge-{{Helper::status_badge($transaction->application_payment_status)}} p-2">{{Str::upper($transaction->application_payment_status)}}</span></small></div>
              <div><small><span class="badge badge-pill badge-{{Helper::status_badge($transaction->application_transaction_status)}} p-2 mt-1">{{Str::upper($transaction->application_transaction_status)}}</span></small></div>
            </td>
            <td>
              <div>
                <span class="badge badge-pill badge-{{Helper::status_badge($transaction->status)}} p-2">{{Str::upper($transaction->status)}}</span>
              </div>
              @if($transaction->status == 'APPROVED')
                <div class="mt-1"><p>{{ $transaction->admin ? $transaction->admin->full_name : '---' }}</p></div>
              @endif
            </td>
            <td >
              <button type="button" class="btn btn-sm p-0" data-toggle="dropdown" style="background-color: transparent;"> <i class="mdi mdi-dots-horizontal" style="font-size: 30px"></i></button>
              <div class="dropdown-menu" aria-labelledby="dropdownMenuSplitButton2">
                <a class="dropdown-item" href="{{route('system.transaction.show',[$transaction->id])}}">View transaction</a>
               <!--  <a class="dropdown-item action-delete"  data-url="#" data-toggle="modal" data-target="#confirm-delete">Remove Record</a> -->
              </div>
            </td>
          </tr>
          @empty
          <tr>
           <td colspan="5" class="text-center"><i>No transaction Records Available.</i></td>
          </tr>
          @endforelse
          
        </tbody>
      </table>
    </div>
  </div>
</div>
@stop


@section('page-styles')
<link rel="stylesheet" href="{{asset('system/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css')}}">
<style type="text/css" >
  .input-daterange input{ background: #fff!important; }  
  .btn-sm{
    border-radius: 10px;
  }
</style>

@stop

@section('page-scripts')
<script src="{{asset('system/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
<script type="text/javascript">
  $(function(){
    $('.input-daterange').datepicker({
      format : "yyyy-mm-dd"
    });

    $(".action-delete").on("click",function(){
      var btn = $(this);
      $("#btn-confirm-delete").attr({"href" : btn.data('url')});
    });

  })
</script>
@stop