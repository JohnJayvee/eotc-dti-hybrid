<script src="{{asset('web/js/jquery.magnific-popup.min.js')}}"></script>
<script src="{{asset('web/js/jquery.easing.min.js')}}"></script>
<script src="{{asset('web/js/jquery.mb.YTPlayer.min.js')}}"></script>
<script src="{{asset('web/js/mixitup.min.js')}}"></script>
<script src="{{asset('web/js/wow.min.js')}}"></script>
<script src="{{asset('web/js/owl.carousel.min.js')}}"></script>
<script src="{{asset('web/js/jquery.countdown.min.js')}}"></script>
<script src="{{asset('web/js/scripts.js')}}"></script>
<script type="text/javascript">
    $(function () {
    	window.onload = date_only('current_date');
    	window.onload = time_only('current_time');
        (function(d, s, id) {
            var js, gjs = d.getElementById('gwt-standard-footer');
            js = d.createElement(s); js.id = id;
            js.src = "//gwhs.i.gov.ph/gwt-footer/footer.js";
            gjs.parentNode.insertBefore(js, gjs);
        }
        (document, 'script', 'gwt-footer-jsdk'));

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