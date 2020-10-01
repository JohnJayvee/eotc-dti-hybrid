@if(session()->has('notification-status'))
<div class="alert alert-{{in_array(session()->get('notification-status'),['failed','error','danger']) ? 'danger' : session()->get('notification-status')}}" role="alert">
{{session()->get('notification-msg')}}
</div>
@endif