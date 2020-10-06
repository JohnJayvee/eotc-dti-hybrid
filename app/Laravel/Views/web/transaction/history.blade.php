@extends('web._layouts.main')


@section('content')

<!--team section start-->
<section class="px-120 pt-110 pb-80 gray-light-bg">
    <div class="container">
         
         <div class="row flex-row items-center px-4">
            <h5 class="text-title pb-3"><i class="fa fa-file"></i> E<span class="text-title-two"> APPLICATION HISTORY</span></h5>
            <a href="{{route('web.transaction.create')}}" class="custom-btn badge-primary-2 text-white " style="float: right;margin-left: auto;">E-Submission</a>
         </div>

        <div class="card">
          <table class="table table-responsive table-striped table-wrap" style="table-layout: fixed;">
              <thead>
                <tr class="text-center">
                  <th class="text-title fs-15 fs-500 p-3" width="15%">Company Name</th>
                  <th class="text-title fs-15 fs-500 p-3" width="15%">Application</th>
                  <th class="text-title fs-15 fs-500 p-3" width="15%">Processing Fee</th>
                  <th class="text-title fs-15 fs-500 p-3" width="15%">Amount</th>
                  <th class="text-title fs-15 fs-500 p-3" width="15%">Status</th>
                  <th class="text-title fs-15 fs-500 p-3" width="15%">Date</th>
                </tr>
              </thead>
              <tbody>
                @forelse($transactions as $transaction)
                <tr class="text-center table-font">
                  <td>{{$transaction->company_name}}</th>
                  <td>{{$transaction->type->name}}<br><a href="{{route('web.transaction.show',[$transaction->id])}}">{{$transaction->code}}</a></th>
                  <td>
                    <div>{{$transaction->processing_fee ?: 0 }}</div>
                    <div>
                      <small><span class="badge badge-pill badge-{{Helper::status_badge($transaction->payment_status)}} p-2">{{Str::upper($transaction->payment_status)}}</span></small>
                    </div>
                    <div class="mt-3">
                      <small><span class="badge badge-pill badge-{{Helper::status_badge($transaction->transaction_status)}} p-2 pt-2">{{Str::upper($transaction->transaction_status)}}</span></small>
                    </div>
                  </td>
                  <td>
                    <div>{{$transaction->amount ?: '- - -'}}</div>
                    <div>
                      <small><span class="badge badge-pill badge-{{Helper::status_badge($transaction->application_payment_status)}} p-2">{{Str::upper($transaction->application_payment_status)}}</span></small>
                    </div>
                    <div class="mt-3">
                      <small><span class="badge badge-pill badge-{{Helper::status_badge($transaction->application_transaction_status)}} p-2">{{Str::upper($transaction->application_transaction_status)}}</span></small>
                    </div>
                  </td>
                  <td style="vertical-align: middle;">
                    <div>
                      <span class="badge badge-pill badge-{{Helper::status_badge($transaction->status)}} p-2">{{Str::upper($transaction->status)}}</span>
                    </div>
                  </td>
                  <td>{{Helper::date_format($transaction->created_at)}}</td>
                </tr>
                @empty
                <tr>
                 <td colspan="6" class="text-center"><i>No transaction Records Available.</i></td>
                </tr>
                @endforelse
                
              </tbody>
          </table>
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
    .btn-status{
        text-align: center;
        border-radius: 10px;
    }
    .table th{
        font-size: 16px;
        font-weight: bold;
    }
    .table td{
        font-size: 14px;
        font-weight: bold;
        vertical-align: middle;
    }
</style>
@endsection
