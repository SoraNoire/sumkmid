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
			<span onclick="resend_code()" class="btn btn-primary" title="Resend login code to your email">Resend code</span>
			<input type="submit" name="Login" class="btn btn-success"> 
		</div>

	</form>

</body>

	<script type="text/javascript">
		var resend_endpoint = "{{route('OA.admin.resendEmailChallenge')}}";
		resend_clicked = 0;
		function resend_code()
		{
			if(resend_clicked > 0)
			{
				return;
			}
			var http = new XMLHttpRequest();
			var params = "token={{$token??''}}";
			http.open("POST", resend_endpoint, true);

			//Send the proper header information along with the request
			http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

			http.onreadystatechange = function() {//Call a function when the state changes.
			    if(http.readyState == 4 && http.status == 200) {
			        alert(http.responseText);
			    }
			}
			http.send(params);
			resend_clicked++;
		}

	</script>

</html>