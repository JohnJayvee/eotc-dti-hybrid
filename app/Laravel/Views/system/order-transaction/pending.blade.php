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
        <p class="text-dim  float-right">EOR-PHP Processor Portal / For Payment Transaction List</p>
      </div>
    </div>
  
  </div>

  <div class="col-12 ">
    <form>
      <div class="row pb-2">
        
      </div>
      <div class="row">
        <div class="col-md-4 p-2">
          <div class="input-group input-daterange d-flex align-items-center">
            <input type="text" class="form-control mb-2 mr-sm-2" value="{{$start_date}}" readonly="readonly" name="start_date">
            <div class="input-group-addon mx-2">to</div>
            <input type="text" class="form-control mb-2 mr-sm-2" value="{{$end_date}}" readonly="readonly" name="end_date">
          </div>
        </div>
        <div class="col-md-4 p-2">
          <div class="form-group has-search">
            <span class="fa fa-search form-control-feedback"></span>
            <input type="text" class="form-control mb-2 mr-sm-2" id="input_keyword" name="keyword" value="{{$keyword}}" placeholder="First Name . Last Name , company name , transaction number">
          </div>
        </div>
        <div class="col-md-4 p-2">
          <button class="btn btn-primary btn-sm p-2" type="submit">Filter</button>
          <a href="{{route('system.order_transaction.pending')}}" class="btn btn-primary btn-sm p-2">Clear</a>
        </div>
      </div>
    </form>
  </div>
  <div class="col-md-12">
    <h4 class="pb-4">Record Data
      <span class="float-right">
        <a href="{{route('system.order_transaction.upload')}}" class="btn btn-sm btn-primary">Upload Excel File</a>
      </span>
    </h4>
    <div class="shadow-sm fs-15 table-responsive">
      <table class="table table-striped table-wrap">
        <thead>
          <tr class="text-center">
            <th class="text-title p-3">Transaction Number</th>
            <th class="text-title p-3">Transaction Date</th>
            <th class="text-title p-3">Payment Reference Number</th>
            <th class="text-title p-3">Submitted By</th>
            <th class="text-title p-3">Company Name</th>
            <th class="text-title p-3">Amount/Status</th>
            <th class="text-title p-3">Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($order_transactions as $order_transaction)
          <tr class="text-center">
            <td> {{$order_transaction->order_transaction_number}} </td>
            <td>{{ Helper::date_format($order_transaction->created_at)}}</td>
            <td>{{ $order_transaction->transaction_code}}</td>
            <td>{{ $order_transaction->order->full_name }}</td>
            <td>{{ $order_transaction->company_name}}</td>
            <td>
              <div>{{Helper::money_format($order_transaction->total_amount) ?: 0 }}</div>
              <div><small><span class="badge badge-pill badge-{{Helper::status_badge($order_transaction->payment_status)}} p-2">{{Str::upper($order_transaction->payment_status)}}</span></small></div>
              <div><small><span class="badge badge-pill badge-{{Helper::status_badge($order_transaction->transaction_status)}} p-2 mt-1">{{Str::upper($order_transaction->transaction_status)}}</span></small></div>
            </td>
            <td >
              <button type="button" class="btn btn-sm p-0" data-toggle="dropdown" style="background-color: transparent;"> <i class="mdi mdi-dots-horizontal" style="font-size: 30px"></i></button>
              <div class="dropdown-menu" aria-labelledby="dropdownMenuSplitButton2">
                <a class="dropdown-item" href="{{route('system.order_transaction.show',[$order_transaction->id])}}">View transaction</a>
               <!--  <a class="dropdown-item action-delete"  data-url="#" data-toggle="modal" data-target="#confirm-delete">Remove Record</a> -->
              </div>
            </td>
          </tr>
          @empty
          <tr>
           <td colspan="8" class="text-center"><i>No Order transaction Records Available.</i></td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if($order_transactions->total() > 0)
      <nav class="mt-2">
        <!-- <p>Showing <strong>{{$order_transactions->firstItem()}}</strong> to <strong>{{$order_transactions->lastItem()}}</strong> of <strong>{{$order_transactions->total()}}</strong> entries</p> -->
        {!!$order_transactions->appends(request()->query())->render()!!}
        </ul>
      </nav>
    @endif
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