<!DOCTYPE html>
<html>
<head>
	<title>Enter Code</title>
</head>
    <link href="{{ asset('css/app.css') }}?v=1.0.0" rel="stylesheet">
<body>

  	<div style="margin: 0 auto;text-align: center;margin-bottom: 55px;">
    	<img src="{{ asset('/img/icon1color.png') }}">
  	</div>

	@if (isset($error))
		<pre>
			{{$error}}
		</pre>
	@endif

	<form method="post" action="{{route('OA.admin.emailChallenge')}}" style="text-align: center;width: 30%;margin: 0 auto;">
		<div class="form-group">
			<label>Masukan kode yang telah dikirim ke email anda</label>
			<input type="text" name="code" autocomplete="off" class="form-control"/>
		</div>
		<div class="form-group">
			<input type="submit" name="Login" class="btn btn-success">
		</div>

	</form>

</body>
</html>