@if (session()->has('flash_notification.message'))
<div class="alert alert-{{ session()->get('flash_notification.level') }} fade in">
  <span class="close" data-dismiss="alert">×</span>
  <i class="fa fa-exclamation-circle pull-left"></i>
  <p>{!! session()->get('flash_notification.message') !!}</p>
</div>
@endif

