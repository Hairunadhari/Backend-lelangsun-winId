@extends('app.layouts')
@section('content')
<script>
  Pusher.logToConsole = true;

var pusher = new Pusher('3e3b048d0a545da6a3a7', {
  cluster: 'ap1'
});

var channel = pusher.subscribe('my-channel');
channel.bind('my-event', function(data) {
  alert(JSON.stringify(data));
});
</script>
<div class="section-body">
  <div class="card">
    <div class="card-header">
      <h4>Super Admin Win</h4>
    </div>
    <div class="card-body">
      <p>Selamat Datang di aplikasi super admin Win</p>
    </div>
  </div>
</div>
@endsection