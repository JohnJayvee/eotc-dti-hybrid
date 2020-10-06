@extends('system._layouts.main')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{route('system.dashboard')}}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{route('system.department.index')}}">Transaction Management</a></li>
    <li class="breadcrumb-item active" aria-current="page">Add New Transaction</li>
  </ol>
</nav>
@stop

@section('content')
<div class="col-md-8 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Transaction Create Form</h4>
      <form class="create-form" method="POST" enctype="multipart/form-data">
        @include('system._components.notifications')
        {!!csrf_field()!!}
        <input type="hidden" name="file_count" id="file_count">
        <input type="hidden" name="department_name" id="input_department_name" value="{{old('department_name')}}">
        <input type="hidden" name="application_name" id="input_application_name" value="{{old('application_name')}}">
        <input type="hidden" name="regional_name" id="input_regional_name" value="{{old('regional_name')}}">
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label for="input_title">First Name</label>
              <input type="text" class="form-control {{$errors->first('firstname') ? 'is-invalid' : NULL}}" id="input_firstname" name="firstname" placeholder="First Name" value="{{old('firstname')}}">
              @if($errors->first('firstname'))
              <p class="mt-1 text-danger">{!!$errors->first('firstname')!!}</p>
              @endif
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="input_title">Middle Name</label>
              <input type="text" class="form-control {{$errors->first('middlename') ? 'is-invalid' : NULL}}" id="input_middlename" name="middlename" placeholder="Middle Name" value="{{old('middlename')}}">
              @if($errors->first('middlename'))
              <p class="mt-1 text-danger">{!!$errors->first('middlename')!!}</p>
              @endif
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="input_title">Last Name</label>
              <input type="text" class="form-control {{$errors->first('lastname') ? 'is-invalid' : NULL}}" id="input_lastname" name="lastname" placeholder="Last Name" value="{{old('lastname')}}">
              @if($errors->first('lastname'))
              <p class="mt-1 text-danger">{!!$errors->first('lastname')!!}</p>
              @endif
            </div>
          </div>
        </div>
        <div class="form-group">
          <label for="input_title">Company Name</label>
          <input type="text" class="form-control {{$errors->first('company_name') ? 'is-invalid' : NULL}}" id="input_company_name" name="company_name" placeholder="Company Name" value="{{old('company_name')}}">
          @if($errors->first('company_name'))
          <p class="mt-1 text-danger">{!!$errors->first('company_name')!!}</p>
          @endif
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="input_title">Email</label>
              <input type="text" class="form-control {{$errors->first('email') ? 'is-invalid' : NULL}}" id="input_email" name="email" placeholder="Email" value="{{old('email')}}">
              @if($errors->first('email'))
              <p class="mt-1 text-danger">{!!$errors->first('email')!!}</p>
              @endif
            </div>
          </div>
          <div class="col-sm-6 col-md-6 col-lg-6">
            <div class="form-group">
                <label for="exampleInputEmail1" class="text-form">Contact Number</label>
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text text-title fw-600">+63 <span class="pr-1 pl-2" style="padding-bottom: 2px"> |</span></span>
                  </div>
                  <input type="text" class="form-control br-left-white" placeholder="Contact Number" name="contact_number" value="{{old('contact_number')}}">
                </div>
                @if($errors->first('contact_number'))
                    <small class="form-text pl-1" style="color:red;">{{$errors->first('contact_number')}}</small>
                @endif
            </div>
          </div>

        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="input_title">Regional Offices</label>
              {!!Form::select("regional_id", $regional_offices, old('regional_id'), ['id' => "input_regional_id", 'class' => "custom-select ".($errors->first('regional_id') ? 'border-red' : NULL)])!!}
              @if($errors->first('regional_id'))
              <p class="mt-1 text-danger">{!!$errors->first('regional_id')!!}</p>
              @endif
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="input_title">Department</label>
              {!!Form::select("department_id", $department, old('department_id'), ['id' => "input_department_id", 'class' => "custom-select ".($errors->first('department_id') ? 'border-red' : NULL)])!!}
              @if($errors->first('department_id'))
              <p class="mt-1 text-danger">{!!$errors->first('department_id')!!}</p>
              @endif
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="input_title">Type of Application</label>
              {!!Form::select('application_id',['' => "--Choose Application Type--"],old('application_id'),['id' => "input_application_id",'class' => "custom-select ".($errors->first('application_id') ? 'border-red' : NULL)])!!}
              @if($errors->first('application_id'))
              <p class="mt-1 text-danger">{!!$errors->first('application_id')!!}</p>
              @endif
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="exampleInputEmail1" class="text-form">Processing Fee</label>
              <div class="input-group">
                <input type="text" class="form-control br-left-white br-right-white {{ $errors->first('processing_fee') ? 'is-invalid': NULL  }}" placeholder="Payment Amount" name="processing_fee" id="input_processing_fee" value="{{old('processing_fee')}}" readonly>
                <div class="input-group-append">
                  <span class="input-group-text text-title fw-600">| <span class="text-gray pl-2 pr-2 pt-1"> .00</span></span>
                </div>
              </div>
              @if($errors->first('processing_fee'))
                  <small class="form-text pl-1" style="color:red;">{{$errors->first('processing_fee')}}</small>
              @endif
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="input_title">Amount</label>
              <input type="text" class="form-control {{$errors->first('amount') ? 'is-invalid' : NULL}}" id="input_amount" name="amount" placeholder="Amount" value="{{old('amount')}}">
              @if($errors->first('amount'))
              <p class="mt-1 text-danger">{!!$errors->first('amount')!!}</p>
              @endif
            </div>
          </div>
        </div>
        <hr>
        <div class="row">
          <div class="col-md-12 col-lg-12 mb-4">
            <label class="text-form pb-2">Submitted Requirements</label>
            {!!Form::select("requirements_id[]", $requirements, old('requirements_id'), ['id' => "input_requirements_id", 'multiple' => 'multiple','class' => "custom-select select2 ".($errors->first('requirements_id') ? 'border-red' : NULL)])!!}
            @if($errors->first('requirements_id'))
              <p class="mt-1 text-danger">{!!$errors->first('requirements_id')!!}</p>
            @endif
          </div>
          <div class="col-lg-12 col-lg-12 mb-4">
            <input type="checkbox" name="hereby_check" value="yes">
              "I hereby agree that I have read and reviewed the requirements listed above, and the physical copies of it are under my possession."
            
          </div> 
        <button type="submit" class="btn btn-primary mr-2">Create Record</button>
        <a href="{{route('system.department.index')}}" class="btn btn-light">Return to Department list</a>
      </form>
    </div>
  </div>
</div>
@stop

@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('system/vendors/select2/select2.min.css')}}"/>
<style type="text/css">
  .is-invalid{
    border: solid 2px;
  }
  .select2-container--default .select2-selection--multiple .select2-selection__choice{
    font-size: 18px;
  }
  span.select2.select2-container{
    width: 100% !important;
  }
  span.input-group-text{
    border-bottom-right-radius: 5px;
    border-top-right-radius: 5px;
  }
</style>
@endsection
@section('page-scripts')
<script src="{{asset('system/vendors/select2/select2.min.js')}}" type="text/javascript"></script>
<script type="text/javascript">
  $('#file').change(function(e){
    $('#lblName').empty();
    $('#lblName').css("color", "black");
   var files = [];
    for (var i = 0; i < $(this)[0].files.length; i++) {
        files.push($(this)[0].files[i].name);
    }
    $('#lblName').text(files.join(', '));
    $('#file_count').val(files.length);
  });

  $.fn.get_application_type = function(department_id,input_purpose,selected){
    $(input_purpose).empty().prop('disabled',true)
    $(input_purpose).append($('<option>', {
              value: "",
              text: "Loading Content..."
          }));
    $.getJSON( "{{route('web.get_application_type')}}?department_id="+department_id, function( result ) {
      $(input_purpose).empty().prop('disabled',true)
      $.each(result.data,function(index,value){
        // console.log(index+value)
        $(input_purpose).append($('<option>', {
            value: index,
            text: value
        }));
      })

      $(input_purpose).prop('disabled',false)
      $(input_purpose).prepend($('<option>',{value : "",text : "--Choose Application Type--"}))

      if(selected.length > 0){
        $(input_purpose).val($(input_purpose+" option[value="+selected+"]").val());

      }else{
        $(input_purpose).val($(input_purpose+" option:first").val());
        //$(this).get_extra(selected)
      }
    });
        // return result;
  };
  // $.fn.get_requirements = function(application_id){
  //   $("#requirements tr").remove(); 
  //   $.getJSON( "{{route('web.get_requirements')}}?type_id="+application_id, function( response ) {
  //       $.each(response.data,function(index,value){
  //           $("#requirements").find('tbody').append("<tr><td>" + value + "</td></tr>");
  //       })
  //       $("#requirements_container").show();
  //   });
  //       // return result;
  // };

  $("#requirements_container").hide();
  $("#input_regional_id").on("change",function(){
    var _text = $("#input_regional_id option:selected").text();
    $('#input_regional_name').val(_text);
  })
  $("#input_department_id").on("change",function(){
    var department_id = $(this).val()
    var _text = $("#input_department_id option:selected").text();
    $(this).get_application_type(department_id,"#input_application_id","")
    $('#input_department_name').val(_text);
  })

  $('#input_application_id').change(function() {
    var _text = $("#input_application_id option:selected").text();
    $.getJSON('/amount?type_id='+this.value, function(result){
        amount = parseFloat(result.data)
        $('#input_processing_fee').val(formatNumber(amount));
    });
    var application_id = $(this).val()
    $('#input_application_name').val(_text);
  });

  function formatNumber (num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
  }

  @if(old('application_id'))
    $(this).get_application_type("{{old('department_id')}}","#input_application_id","{{old('application_id')}}")
  @endif
  $('#input_requirements_id').select2({placeholder: "Select Requirements"});
</script>


@endsection