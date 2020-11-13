@extends('system._layouts.main')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{route('system.dashboard')}}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{route('system.application.index')}}">Application Type Management</a></li>
    <li class="breadcrumb-item active" aria-current="page">Add New Application Type</li>
  </ol>
</nav>
@stop

@section('content')
<div class="col-md-8 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Application Type Create Form</h4>
      <form class="create-form" method="POST" enctype="multipart/form-data">
        @include('system._components.notifications')
        {!!csrf_field()!!}
        <div class="form-group">
          <label for="input_name">Application Name</label>
          <input type="text" class="form-control {{$errors->first('name') ? 'is-invalid' : NULL}}" id="input_name" name="name" placeholder="Application Name" value="{{old('name')}}">
          @if($errors->first('name'))
          <p class="mt-1 text-danger">{!!$errors->first('name')!!}</p>
          @endif
        </div>
       
        <div class="form-group">
          <label for="input_suffix">Department</label>
          {!!Form::select("department_id", $department, old('department_id'), ['id' => "input_department_id", 'class' => "custom-select mb-2 mr-sm-2 ".($errors->first('department_id') ? 'is-invalid' : NULL)])!!}
          @if($errors->first('department_id'))
          <p class="mt-1 text-danger">{!!$errors->first('department_id')!!}</p>
          @endif
        </div>
        <div class="form-group">
          <label for="input_suffix">Account Title</label>
            {!!Form::select("account_title_id", ['' => "--Choose Account Title--"], old('account_title_id'), ['id' => "input_account_title_id", 'class' => "custom-select mb-2 mr-sm-2 ".($errors->first('account_title_id') ? 'is-invalid' : NULL)])!!}
            @if($errors->first('account_title_id'))
              <p class="mt-1 text-danger">{!!$errors->first('account_title_id')!!}</p>
            @endif
        </div>
        <div class="form-group">
          <label for="input_suffix">Collection Type</label>
          {!!Form::select("collection_type", $collections, old('collection_type'), ['id' => "input_collection_type", 'class' => "custom-select mb-2 mr-sm-2 ".($errors->first('collection_type') ? 'is-invalid' : NULL)])!!}
          @if($errors->first('collection_type'))
          <p class="mt-1 text-danger">{!!$errors->first('collection_type')!!}</p>
          @endif
        </div>
        <div class="form-group">
          <label for="input_processing_fee">Processing Fee <code style="font-size: 12px;"><i>Note: Input 0 If there is no processing Fee</i></code></label>
          <input type="text" class="form-control {{$errors->first('processing_fee') ? 'is-invalid' : NULL}}" id="input_processing_fee" name="processing_fee" placeholder="Payment Fee" value="{{old('processing_fee')}}">
          @if($errors->first('processing_fee'))
          <p class="mt-1 text-danger">{!!$errors->first('processing_fee')!!}</p>
          @endif
        </div>
        <div class="form-group">
          <label for="input_partial_amount">Partial Amount<code style="font-size: 12px;"><i>Note: Input 0 If there is no partial amount</i></code></label>
          <input type="text" class="form-control {{$errors->first('partial_amount') ? 'is-invalid' : NULL}}" id="input_partial_amount" name="partial_amount" placeholder="Partial Amount" value="{{old('partial_amount')}}">
          @if($errors->first('partial_amount'))
          <p class="mt-1 text-danger">{!!$errors->first('partial_amount')!!}</p>
          @endif
        </div>
        <!-- <div class="form-group">
          <label for="input_title">Processing Days</label>
          <input type="text" class="form-control {{$errors->first('processing_days') ? 'is-invalid' : NULL}}" id="input_processing_days" name="processing_days" placeholder="Processing Days" value="{{old('processing_days')}}">
          @if($errors->first('processing_days'))
          <p class="mt-1 text-danger">{!!$errors->first('processing_days')!!}</p>
          @endif
        </div> -->
        <div class="form-group">
          <label for="input_suffix">Application Requirements</label>
          {!!Form::select("requirements_id[]", $requirements, old('requirements_id'), ['id' => "input_requirements_id", 'multiple' => 'multiple','class' => "custom-select select2 mb-2 mr-sm-2 ".($errors->first('requirements_id') ? 'is-invalid' : NULL)])!!}
          @if($errors->first('requirements_id'))
          <p class="mt-1 text-danger">{!!$errors->first('requirements_id')!!}</p>
          @endif
        </div>
        <button type="submit" class="btn btn-primary mr-2">Create Record</button>
        <a href="{{route('system.application.index')}}" class="btn btn-light">Return to Application Type list</a>
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
</style>
@endsection

@section('page-scripts')
<script src="{{asset('system/vendors/select2/select2.min.js')}}" type="text/javascript"></script>
<script type="text/javascript">

    $.fn.get_account_title = function(department_id,input_account_title,selected){
      $(input_account_title).empty().prop('disabled',true)
      $(input_account_title).append($('<option>', {
        value: "",
        text: "Loading Content..."
      }));
      $.getJSON( "{{route('web.get_account_title')}}?department_id="+department_id, function( result ) {
          $(input_account_title).empty().prop('disabled',true)
          $.each(result.data,function(index,value){
            // console.log(index+value)
            $(input_account_title).append($('<option>', {
                value: index,
                text: value
            }));
          })

          $(input_account_title).prop('disabled',false)
          $(input_account_title).prepend($('<option>',{value : "",text : "--Choose Application Type--"}))

          if(selected.length > 0){
            $(input_account_title).val($(input_account_title+" option[value="+selected+"]").val());

          }else{
            $(input_account_title).val($(input_account_title+" option:first").val());
            //$(this).get_extra(selected)
          }
      });
        // return result;
    };

    $(document).ready(function(){
      $("#input_department_id").on("change",function(){
        var department_id = $(this).val()
        var _text = $("#input_department_id option:selected").text();
        $(this).get_account_title(department_id,"#input_account_title_id","")
        $('#input_department_name').val(_text);
      })

      @if(old('department_id'))
       $(this).get_account_title("{{old('department_id')}}","#input_account_title_id","{{old('account_title_id')}}")
      @endif

      $('#input_requirements_id').select2({placeholder: "Select Requirements"});
    });//document ready
</script>
@endsection