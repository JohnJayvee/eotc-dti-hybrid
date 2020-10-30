@extends('web._layouts.main')


@section('content')



<!--team section start-->
<section class="px-120 pt-110 pb-80 gray-light-bg">
    <div class="container">
         
         <div class="row flex-row items-center px-4">
            <h5 class="text-title pb-3"><i class="fa fa-file"></i> E<span class="text-title-two"> SUBMISSION</span></h5>
            <a href="{{route('web.transaction.history')}}" class="custom-btn badge-primary-2 text-white " style="float: right;margin-left: auto;">Application History</a>
         </div>
          @include('web._components.notifications')
        <div class="card">
            <form method="POST" action="" enctype="multipart/form-data">
            {!!csrf_field()!!}
                <input type="hidden" name="department_name" id="input_department_name" value="{{old('department_name')}}">
                <input type="hidden" name="application_name" id="input_application_name" value="{{old('application_name')}}">
                <!-- <input type="hidden" name="regional_name" id="input_regional_name" value="{{old('regional_name')}}"> -->
                <div class="card-body px-5 py-0">
                    <h5 class="text-title text-uppercase pt-5">Application information</h5>
                    <div class="row">
                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1" class="text-form pb-2">Your Name</label>
                                <input type="text" class="form-control form-control-sm {{ $errors->first('full_name') ? 'is-invalid': NULL  }}"  placeholder="Last Name, First Name, Middle Name" name="full_name" value="{{old('full_name',Auth::guard('customer')->user()->name) }}">
                                @if($errors->first('full_name'))
                                    <small class="form-text pl-1" style="color:red;">{{$errors->first('full_name')}}</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1" class="text-form pb-2">Company Name</label>
                                <input type="text" class="form-control form-control-sm {{ $errors->first('company_name') ? 'is-invalid': NULL  }}" placeholder="Company Name" name="company_name" value="{{old('company_name')}}">
                                @if($errors->first('company_name'))
                                    <small class="form-text pl-1" style="color:red;">{{$errors->first('company_name')}}</small>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1" class="text-form pb-2">Your Email Address</label>
                                <input type="text" class="form-control form-control-sm" name="email" placeholder="Email Address" value="{{old('email',Auth::guard('customer')->user()->email)}}">
                                @if($errors->first('email'))
                                    <small class="form-text pl-1" style="color:red;">{{$errors->first('email')}}</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1" class="text-form pb-2">Contact Number</label>
                                <div class="input-group mb-3">
                                  <div class="input-group-prepend">
                                    <span class="input-group-text text-title fw-600">+63 <span class="pr-1 pl-2" style="padding-bottom: 2px"> |</span></span>
                                  </div>
                                  <input type="text" class="form-control br-left-white" placeholder="Contact Number" name="contact_number" value="{{old('contact_number',Auth::guard('customer')->user()->contact_number)}}">
                                </div>
                                @if($errors->first('contact_number'))
                                    <small class="form-text pl-1" style="color:red;">{{$errors->first('contact_number')}}</small>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                            <!-- <div class="col-sm-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1" class="text-form pb-2">Regional Offices</label>
                                {!!Form::select("regional_id", $regional_offices, old('regional_id'), ['id' => "input_regional_id", 'class' => "form-control form-control-sm classic ".($errors->first('regional_id') ? 'border-red' : NULL)])!!}
                            </div>
                            @if($errors->first('regional_id'))
                                <small class="form-text pl-1" style="color:red;">{{$errors->first('regional_id')}}</small>
                            @endif
                            </div> -->
                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1" class="text-form pb-2">Bureau/Office</label>
                                {!!Form::select("department_id", $department, old('department_id'), ['id' => "input_department_id", 'class' => "form-control form-control-sm classic ".($errors->first('department_id') ? 'border-red' : NULL)])!!}
                            </div>
                            @if($errors->first('department_id'))
                                <small class="form-text pl-1" style="color:red;">{{$errors->first('department_id')}}</small>
                            @endif
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1" class="text-form pb-2">Type of Application</label>
                                {!!Form::select('application_id',['' => "--Choose Application Type--"],old('application_id'),['id' => "input_application_id",'class' => "form-control form-control-sm classic ".($errors->first('application_id') ? 'border-red' : NULL)])!!}
                               
                            </div>
                            @if($errors->first('application_id'))
                                <small class="form-text pl-1" style="color:red;">{{$errors->first('application_id')}}</small>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1" class="text-form pb-2">Processing Fee</label>
                                <div class="input-group mb-3">
                                  <div class="input-group-prepend">
                                    <span class="input-group-text text-title fw-600">PHP <span class="pr-1 pl-2" style="padding-bottom: 2px"> |</span></span>
                                  </div>
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
                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1" class="text-form pb-2">Partial Amount</label>
                                <div class="input-group mb-3">
                                  <div class="input-group-prepend">
                                    <span class="input-group-text text-title fw-600">PHP <span class="pr-1 pl-2" style="padding-bottom: 2px"> |</span></span>
                                  </div>
                                  <input type="text" class="form-control br-left-white" placeholder="Partial Amountr" name="partial_amount" value="{{old('partial_amount')}}" id="input_partial_amount" readonly>
                                </div>
                                @if($errors->first('partial_amount'))
                                    <small class="form-text pl-1" style="color:red;">{{$errors->first('partial_amount')}}</small>
                                @endif
                            </div>
                        </div>
                       
                    </div>
                   <input type="hidden" name="requirements_id" id="requirements_id_containter">
                   
                    <div id="requirements_container">
                        
                        <table class="table table-responsive table-striped table-wrap" style="table-layout: fixed;" id="requirements">
                            <thead>
                               
                            </thead>
                            <tbody>
                               
                            </tbody>
                        </table>
                    </div>
                    <div id="old_requirements_container">
                        @if(old('requirements_id'))
                        <table class="table table-responsive table-striped table-wrap" style="table-layout: fixed;"  id="old_requirements">
                            <thead>
                                <tr>
                                    <th class="text-title fs-15 fs-500 p-3" width="15%">Requirement Name</th>
                                    <th class="text-title fs-15 fs-500 p-3" width="15%">File</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(explode(",",old('requirements_id')) as $index => $value)
                                    @foreach($all_requirements as $req)
                                         @if($value == $req->id)
                                        <tr>
                                            <td>{{$req->name}} {{$req->is_required == "yes" ? "(Required)" : "(Optional)"}}</td>
                                            <td><input type="file" name="file{{$value}}" accept="application/pdf,application/vnd.ms-excel">
                                            @if($errors->first('file'.$value))
                                                <small class="form-text pl-1" style="color:red;">{{$errors->first('file'.$value)}}</small>
                                            @endif
                                            </td>
                                        </tr>
                                        @endif
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                         @endif
                    </div>
                </div>
                <hr class="form pt-0">
                <div class=" card-body px-5 pb-5">
                    <h5 class="text-title text-uppercase ">Physical Submission</h5>
                    <div class="custom-control custom-checkbox mb-3">
                        <input type="checkbox" class="custom-control-input" id="customControlValidation1" name="is_check" value="1">
                        <label class="custom-control-label fs-14 fw-600 text-black" for="customControlValidation1">&nbsp;&nbsp; Check this option if you're going to submit physical copies of your documents. The system will generate a QR Code that you'll have to attach to the envelope of your physical copies.</label>
                        
                    </div>
                    <button class="btn badge badge-primary-2 text-white px-4 py-2 fs-14" type="submit"><i class="fa fa-paper-plane pr-2"></i>  Send Application</button>
                </div>
            </form>
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
    #input_partial_label:focus{
         outline:none;
    }
</style>
@endsection
@section('page-scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {

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
        str = '';
        var html = "";
        var file = "file1"
        $.getJSON( "{{route('web.get_requirements')}}?type_id="+application_id, function( response ) {
            $.each(response.data,function(index,value){
                    html += "<tr>";
                    html += "<td>" + value[0] + "</td>";
                    html += "<td><input type='file' accept='application/pdf,application/vnd.ms-excel' name=" + value[1] + "></td>"
                    html += "</tr>";
                str += value[2] + ",";
                resultString = str.replace(/,(?=[^,]*$)/, '')

            })
            $("#requirements").find('tbody').append(html);
             $("#requirements").find('thead').append("<tr><th class='text-title fs-15 fs-500 p-3' width='15%''>Requirement Name</th><th class='text-title fs-15 fs-500 p-3' width='15%'>File</th></tr>");
            $("#requirements_id_containter").val(resultString);
        });
        // return result;
    };
    $.fn.get_requirements_id = function(application_id){
        str = '';
        var html = "";
        var file = "file1"
        $.getJSON( "{{route('web.get_requirements')}}?type_id="+application_id, function( response ) {
            $.each(response.data,function(index,value){
                str += value[2] + ",";
                resultString = str.replace(/,(?=[^,]*$)/, '')

            })
            $("#requirements_id_containter").val(resultString);
        });
        // return result;
    };
    $.fn.get_partial_amount = function(application_id){
        $.getJSON('/amount?type_id='+application_id, function(result){
            amount = parseFloat(result.data[0])
            partial_amount = parseFloat(result.data[1])
            if (partial_amount > 0) {
                $('#input_partial_amount').prop("readonly" ,false);
            }else{
                $('#input_partial_amount').prop("readonly" ,true);
            }
            $('#input_processing_fee').val(formatNumber(amount));
        });
        // return result;
    };

    $("#input_department_id").on("change",function(){
      var department_id = $(this).val()
      var _text = $("#input_department_id option:selected").text();
      $(this).get_application_type(department_id,"#input_application_id","")
      $('#input_department_name').val(_text);
    })

    $("#input_regional_id").on("change",function(){
      var _text = $("#input_regional_id option:selected").text();
      $('#input_regional_name').val(_text);
    })

    $('#input_application_id').on("change",function(){
        var amount;
        $("#old_requirements").find('tbody').empty();
        $("#old_requirements").find('thead').empty();
        $("#requirements").find('tbody').empty();
        $("#requirements").find('thead').empty();
        var _text = $("#input_application_id option:selected").text();
        
        var application_id = $(this).val()
        $(this).get_partial_amount(application_id,"#input_application_id","")
        $(this).get_requirements(application_id,"#input_application_id","")
        
        $('#input_application_name').val(_text);

    });
    function formatNumber (num) {
        return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
    }
    @if(old('department_id'))
        $(this).get_application_type("{{old('department_id')}}","#input_application_id","")
    @endif
    @if(old('department_id') and  old('application_id'))
        $(this).get_application_type("{{old('department_id')}}","#input_application_id","{{old('application_id')}}")
        $(this).get_requirements_id("{{old('application_id')}}","#input_application_id","{{old('application_id')}}")
        $(this).get_partial_amount("{{old('application_id')}}","#input_application_id","{{old('application_id')}}")
    @endif
 });
</script>

@endsection