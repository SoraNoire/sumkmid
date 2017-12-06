// Burger Dropdown
$('.burgerBtn').click(function(){
	$('.navWrapper ul').slideToggle();
	$('.trnsOverlay').show();
});

$('.trnsOverlay').click(function(){
	$('.trnsOverlay').hide();
	$('.navWrapper ul').slideUp();
});

if ($('.topSlider').length > 0) {
	var mySwiper = new Swiper ('.topSlider', {
	    direction: 'horizontal',
	    loop: true,
	    autoplay: {
	        delay: 5000,
	    },
	});
}

$('.toggleMenu').click(function(){
    $('.navWrapper ul').slideToggle();
});
$('.agreemnt').click(function(){
    if($('.agreemnt input')[0].checked ){
        $('.agreemnt input').prop('checked', false);
    }else{
        $('.agreemnt input').prop('checked', true);
    }
});
$('.wrapSubject').click(function(){
    $('#subject').change();   
});

$('#submit_newsletter').on('click', function(e){
	e.preventDefault();
	var a = $('input[name=email_subscribe]').val();
	$.ajax({
        type: 'GET',
        url: '/subcribe/'+a,
        success: function(response){
			$('#myalert').show();
			$('#myalert span').html(response);
			$('#myalert').addClass('alert-success');
        },
        error: function(err){
			$('#myalert').show();
			$('#myalert span').html('Terjadi kesalahan. Silahkan coba beberapa menit lagi');
			$('#myalert').addClass('alert-danger');
        }
    });
    a = '';
});

$('#myalert').on('click', 'a', function(e){
	e.preventDefault();
	$('#myalert').hide();
});

function show_event_detail(id) {
	$('#'+id).find('.lihat-detail').hide();
	$('#'+id).find('.lihat-sedikit').css('display', 'inline-block');
	$('#'+id).find('.tempat').show();
	$('#'+id).find('.htm').show();
	$('#'+id).find('.event-buttons').css('display', 'inline-block');
	$('#'+id).find('.event-desc').addClass('show');
}

function show_less_event_detail(id) {
	$('#'+id).find('.lihat-detail').show();
	$('#'+id).find('.lihat-sedikit').hide();
	$('#'+id).find('.tempat').hide();
	$('#'+id).find('.htm').hide();
	$('#'+id).find('.event-buttons').hide();
	$('#'+id).find('.event-desc').removeClass('show');
}

function show_event_sharer(id) {
	if(window.outerWidth < 659){
		$('#'+id).find('.share-socmed').fadeIn().css("display","inline-block");
		$('.whiteOverlay').fadeIn();
	}else{
		$('#'+id).find('.share-socmed').fadeIn().css("display","inline-block");
	}
}

$('.whiteOverlay').click(function(){
	$('.whiteOverlay').fadeOut();
	$('.share-socmed').fadeOut();
});

$('.closeAlert').click(function(){
	$('.formAlert').slideUp();
})
// init Infinite Scroll
$('.archive-list').infiniteScroll({
  path: '.pagination__next',
  append: '.post',
  status: '.scroller-status',
  hideNav: '.pagination',
});