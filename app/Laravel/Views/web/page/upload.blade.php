@extends('web._layouts.main')


@section('content')


<!--team section start-->
<section class="px-120 pt-110 pb-80 gray-light-bg">
    <div class="container">
      <div class="row flex-row items-center px-4 pb-2">
        <a href="{{route('web.transaction.create')}}" class="custom-btn badge-primary-2 text-white " style="float: right;margin-left: auto;">E-Submission</a>
      </div>
      <div class="card card-rounded shadow-sm">
        <div class="card-body" style="border-bottom: 3px dashed #E3E3E3;">
          <div class="row">
            <div class="col-md-11 d-flex">
              <p class="text-title fw-600 pt-3">Company: <span class="text-black">{{Str::title($transaction->company_name)}}</span></p>
              <p class="text-title fw-500 pl-3" style="padding-top: 15px;">|</p>
              <p class="text-title fw-600 pt-3 pl-3">Application Sent: <span class="text-black">{{ Helper::date_format($transaction->created_at)}}</span></p>
            </div>
          </div> 
        </div>
        <div class="card-body">
          <div class="row">

            <div class="col-md-6">
              <p class="text-title fw-600 m-0">Name: <span class="text-black">{{str::title($transaction->customer ? $transaction->customer->full_name : $transaction->customer_name)}}</span></p>
              <p class="text-title fw-600 m-0">Application: <span class="text-black">{{$transaction->type ? Str::title($transaction->type->name) : "N/A"}} [{{$transaction->code}}] </span></p>
              <p class="text-title fw-600 m-0">Email Address: <span class="text-black">{{$transaction->email}}</span></p>
              <p class="text-title fw-600 m-0">Processor Status: <span class="badge badge-{{Helper::status_badge($transaction->status)}} p-2">{{Str::title($transaction->status)}}</span></p>
            </div>
            <div class="col-md-6">
              <p class="text-title fw-600 m-0">Deparatment/Agency: <span class="text-black">{{$transaction->department ? Str::title($transaction->department->name) : "N/A"}}</span></p>
              <p class="text-title fw-600 m-0">Contact Number: <span class="text-black">+63{{$transaction->contact_number}}</span></p>
              @if($transaction->status == "DECLINED")
                <p class="text-title fw-600 m-0">Remarks: <span class="text-black">{{$transaction->remarks}}</span></p>
              @endif
            </div>

            <div class="col-md-6 mt-4">
              <p class="text-title fw-600 m-0">Transaction Details:</span></p>
              <p class="text-title fw-600 m-0">Status: <span class="badge  badge-{{Helper::status_badge($transaction->transaction_status)}} p-2">{{Str::title($transaction->transaction_status)}}</span></p>
              <p class="fw-600 m-0" style="color: #DC3C3B;">Processing Fee: Php {{Helper::money_format($transaction->processing_fee)}} [{{$transaction->processing_fee_code}}]</p>
              <p class="text-title fw-600 m-0">Payment Status: <span class="badge badge-sm badge-{{Helper::status_badge($transaction->payment_status)}} p-2">{{Str::title($transaction->payment_status)}}</span></p>
              <p class="fw-600 m-0" style="color: #DC3C3B;">Partial Amount: Php {{Helper::money_format($transaction->partial_amount)}}</p>
            </div>
            <div class="col-md-6 mt-4">
              <p class="text-title fw-600 m-0">Application Details:</span></p>
              <p class="text-title fw-600 m-0">Status: <span class="badge  badge-{{Helper::status_badge($transaction->application_transaction_status)}} p-2">{{Str::title($transaction->application_transaction_status)}}</span></p>
              <p class="fw-600 m-0" style="color: #DC3C3B;">Amount: Php {{Helper::money_format($transaction->amount ? $transaction->amount : "0.00")}} [{{$transaction->amount != NULL ? $transaction->transaction_code:"N/A"}}]</p>
              <p class="text-title fw-600 m-0">Payment Status: <span class="badge  badge-{{Helper::status_badge($transaction->application_payment_status)}} p-2">{{Str::title($transaction->application_payment_status)}}</span></p>
            </div>

          </div> 
        </div>
      </div>

      <div class="card card-rounded shadow-sm mt-4">
        <div class="card-body">
          <h5 class="text-title text-uppercase pt-2">Re-Upload Requirements</h5>
          <form method="POST" action="" enctype="multipart/form-data">
            {!!csrf_field()!!}
            <input type="hidden" name="code" value="{{$transaction->code}}">
            <div class="row">
              <div class="col-md-12 col-lg-12">
                <label class="text-form pb-2">Application Declined Requirements</label>
                  <table class="table table-responsive table-striped table-wrap" style="table-layout: fixed;" id="requirements">
                    <thead>
                      <tr>
                        <th class="text-title fs-15 fs-500 p-3" width="15%">Requirement Name</th>
                        <th class="text-title fs-15 fs-500 p-3" width="15%">File</th>
                      </tr>
                      <tbody>
                        @forelse($transaction_requirements as $index => $requirement)
                          <input type="hidden" name="requirement_id[]" value="{{$requirement->requirement_name->id}}">
                          <tr>
                            <td>{{$requirement->requirement_name ? $requirement->requirement_name->name : "N/A"}}</td>
                            <td>
                              <input type="file" name="file{{$requirement->requirement_name->id}}">
                              @if($errors->first('file'.$requirement->requirement_name->id))
                                <small class="form-text pl-1" style="color:red;">{{$errors->first('file'.$requirement->requirement_name->id)}}</small>
                              @endif
                            </td>
                          </tr>
                        @empty
                        @endforelse
                      </tbody>
                    </thead>
                  </table>
              </div>
            </div>
            <button class="btn badge badge-primary-2 text-white px-4 py-2 fs-14" type="submit"></i>SUBMIT</button>
          </form>
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
    .btn-status{
        text-align: center;
        border-radius: 10px;
    }
    .table-font th{
        font-size: 16px;
        font-weight: bold;
    }
    .table-font td{
        font-size: 13px;
        font-weight: bold;
    }
</style>
@endsection
@section('page-scripts')
<script type="text/javascript">
    $('#file').change(function(e){
      $('#lblName').empty();
      $('#lblName').css("color", "black");
     var files = [];
      for (var i = 0; i < $(this)[0].files.length; i++) {
          files.push($(this)[0].files[i].name);
      }
      $('#lblName').text(files.join(', '));
    });
</script>

@endsection