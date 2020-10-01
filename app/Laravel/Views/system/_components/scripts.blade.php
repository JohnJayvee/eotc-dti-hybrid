<script src="{{asset('system/vendors/js/vendor.bundle.base.js')}}"></script>
<script src="{{asset('system/js/off-canvas.js')}}"></script>
<script src="{{asset('system/js/hoverable-collapse.js')}}"></script>
<script src="{{asset('system/js/template.js')}}"></script>
{{-- <script src="{{asset('system/js/settings.js')}}"></script> --}}
<script src="{{asset('system/js/todolist.js')}}"></script>
<script type="text/javascript">

	$(function () {
		$("form").on('submit',function(){
		  $(this).find('button').addClass("disabled").text("Processing...");
		})
		
	    var pagination_container = $(".pagination");

		if(typeof pagination_container == "object"){
			if(!pagination_container.hasClass("flex-wrap")){
				pagination_container.addClass("flex-wrap")
			}
			if(!pagination_container.hasClass("pagination-separated")){
				pagination_container.addClass("pagination-separated")
			}
			if(!pagination_container.hasClass("pagination-primary")){
				pagination_container.addClass("pagination-primary")
			}
		}

	    window.onload = date_only('current_date');
	    window.onload = time_only('current_time');
	    // $('input').iCheck({
	    //     checkboxClass: 'icheckbox_square-blue',
	    //     radioClass: 'iradio_square-blue',
	    //     increaseArea: '20%'
	    // });
	});

	function date_only(id){
	    var _t =  "AM";
	    date = new Date;
	    year = date.getFullYear();
	    month = date.getMonth();
	    months = new Array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
	    d = date.getDate();
	    day = date.getDay();
	    days = new Array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
	    h = date.getHours();
	    if(h<10)
	    {
	        h = "0"+h;
	    }else{
	        if(h>12){ h -=12;  _t = "PM"}
	    }
	    m = date.getMinutes();
	    if(m<10)
	    {
	        m = "0"+m;
	    }
	    s = date.getSeconds();
	    if(s<10)
	    {
	        s = "0"+s;
	    }
	    result = days[day]+', '+months[month]+' '+d+', '+year;
	    document.getElementById(id).innerHTML = result;
	    setTimeout('date_only("'+id+'");','1000');
	    return true;
	}

	function time_only(id){
	    var _t =  "AM";
	    date = new Date;
	    year = date.getFullYear();
	    month = date.getMonth();
	    months = new Array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
	    d = date.getDate();
	    day = date.getDay();
	    days = new Array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
	    h = date.getHours();
	    if(h<10)
	    {
	        h = "0"+h;
	    }else{
	        if(h>12){ h -=12;  _t = "PM"}
	    }
	    m = date.getMinutes();
	    if(m<10)
	    {
	        m = "0"+m;
	    }
	    s = date.getSeconds();
	    if(s<10)
	    {
	        s = "0"+s;
	    }
	    result = h+':'+m+':'+s+' '+_t;
	    document.getElementById(id).innerHTML = result;
	    setTimeout('time_only("'+id+'");','1000');
	    return true;
	}

	
	
</script>
@yield('page-scripts')