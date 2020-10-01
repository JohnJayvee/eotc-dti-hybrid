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
      <p class="card-description">
        Fill up the <strong class="text-danger">* required</strong> fields.
      </p>
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
          <div class="col-md-6">
            <div class="form-group">
              <label for="input_title">Contact Number</label>
              <input type="text" class="form-control {{$errors->first('contact_number') ? 'is-invalid' : NULL}}" id="input_contact_number" name="contact_number" placeholder="Contact Number" value="{{old('contact_number')}}">
              @if($errors->first('contact_number'))
              <p class="mt-1 text-danger">{!!$errors->first('contact_number')!!}</p>
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
              <label for="input_title">Processing Fee</label>
              <input type="text" class="form-control {{$errors->first('processing_fee') ? 'is-invalid' : NULL}}" id="input_processing_fee" name="processing_fee" placeholder="Processing Fee" value="{{old('processing_fee')}}" readonly>
              @if($errors->first('processing_fee'))
              <p class="mt-1 text-danger">{!!$errors->first('processing_fee')!!}</p>
              @endif
            </div>
          </div>
        </div>
        <div class="row mb-2">
          <div class="col-md-6">
            <div id="requirements_container">
              <label class="text-form pb-1">Required Documents:</label>
              <table id="requirements">
                <tbody>
                     
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <hr>
        <div class="row">
          <div class="col-md-12 col-lg-12">
              <label class="text-form pb-2">Application Requirements</label>
              <div class="form-group">
                  <div class="upload-btn-wrapper">
                    <input type="file" name="file[]" class="form-control" id="file" accept="application/pdf" multiple>
                  </div>
                  @forelse($errors->all() as $error)
                    @if($error == "Only PDF File are allowed.")
                        <label id="lblName" style="vertical-align: top;padding-top: 20px;color: red;" class="fw-500 pl-3">{{$error}}</label>
                    @elseif($error == "No File Uploaded.")
                        <label id="lblName" style="vertical-align: top;padding-top: 20px;color: red;" class="fw-500 pl-3">{{$error}}</label>
                    @elseif($error == "Please Submit minimum requirements.")
                        <label id="lblName" style="vertical-align: top;padding-top: 20px;color: red;" class="fw-500 pl-3">{{$error}}</label>
                    @endif
                  @empty
                    <label id="lblName" style="vertical-align: top;padding-top: 20px;" class="fw-500 pl-3"></label>
                  @endforelse
              </div>
          </div>
        </div>

        <button type="submit" class="btn btn-primary mr-2">Create Record</button>
        <a href="{{route('system.department.index')}}" class="btn btn-light">Return to Department list</a>
      </form>
    </div>
  </div>
</div>
@stop

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
  $.fn.get_requirements = function(application_id){
    $("#requirements tr").remove(); 
    $.getJSON( "{{route('web.get_requirements')}}?type_id="+application_id, function( response ) {
        $.each(response.data,function(index,value){
            $("#requirements").find('tbody').append("<tr><td>" + value + "</td></tr>");
        })
        $("#requirements_container").show();
    });
        // return result;
  };

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
        $('#input_processing_fee').val(result.data);
    });
    var application_id = $(this).val()
    $(this).get_requirements(application_id,"#input_application_id","")
    
    $('#input_application_name').val(_text);
  });

  @if(old('application_id'))
    $(this).get_requirements("{{old('application_id')}}","#input_application_id","{{old('application_id')}}")
    $(this).get_application_type("{{old('department_id')}}","#input_application_id","{{old('application_id')}}")
  @endif

</script>


@endsection