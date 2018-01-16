@extends('layouts.publicbase')

@section('content')

<div class="breadcrumb">
	<div class="container">
		<ol itemscope itemtype="http://schema.org/BreadcrumbList">
            <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                <a itemprop="item" href="{{ route('public_home') }}"><span itemprop="name">Beranda</span></a> <i class="fa fa-angle-right" aria-hidden="true"></i>
                <meta itemprop="position" content="1" />
            </li>
            <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                <a itemprop="item" href="{{ route('public_newsletter') }}"><span itemprop="name">Newsletter</span></a> <i class="fa fa-angle-right" aria-hidden="true"></i>
                <meta itemprop="position" content="2" />
            </li>
        </ol>
	</div>
</div>

<div id="mentor">
	<div class="container">
		<!-- Begin MailChimp Signup Form -->
		<div id="mc_embed_signup">
			<form action="https://sahabatumkm.us17.list-manage.com/subscribe/post?u=dd58ff00500b48aaaf7ad5317&amp;id=4484f1b91d" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" novalidate>
			    <div id="mc_embed_signup_scroll">
				<h2>Subscribe to our mailing list</h2>
			<div class="indicates-required"><span class="asterisk">*</span> indicates required</div>
			<div class="mc-field-group">
				<label for="mce-EMAIL">Email Address  <span class="asterisk">*</span>
			</label>
				<input type="email" value="{{ $email }}" name="EMAIL" class="required email" id="mce-EMAIL" autofocus="autofocus">
			</div>
			<div class="mc-field-group">
				<label for="mce-FNAME">First Name </label>
				<input type="text" value="" name="FNAME" class="" id="mce-FNAME">
			</div>
			<div class="mc-field-group">
				<label for="mce-LNAME">Last Name </label>
				<input type="text" value="" name="LNAME" class="" id="mce-LNAME">
			</div>
			<div class="mc-field-group size1of2">
				<label for="mce-BIRTHDAY-month">Birthday </label>
				<div class="datefield">
					<span class="subfield monthfield"><input class="birthday " type="text" pattern="[0-9]*" value="" placeholder="MM" size="2" maxlength="2" name="BIRTHDAY[month]" id="mce-BIRTHDAY-month"></span> / 
					<span class="subfield dayfield"><input class="birthday " type="text" pattern="[0-9]*" value="" placeholder="DD" size="2" maxlength="2" name="BIRTHDAY[day]" id="mce-BIRTHDAY-day"></span> 
					<span class="small-meta nowrap">( mm / dd )</span>
				</div>
			</div>	<div id="mce-responses" class="clear">
					<div class="response" id="mce-error-response" style="display:none"></div>
					<div class="response" id="mce-success-response" style="display:none"></div>
				</div>    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
			    <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_dd58ff00500b48aaaf7ad5317_4484f1b91d" tabindex="-1" value=""></div>
			    <div class="clear"><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button"></div>
			    </div>
			</form>
		</div>

		<!--End mc_embed_signup-->
	</div>
</div>
@endsection