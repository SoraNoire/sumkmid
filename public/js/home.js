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
	$('#'+id).find('.event-meta').fadeIn();
}

function show_event_sharer(id) {
	$('#'+id).find('.share-socmed ul').fadeIn();
}

// init Infinite Scroll
$('.archive-list').infiniteScroll({
  path: '.pagination__next',
  append: '.post',
  status: '.scroller-status',
  hideNav: '.pagination',
});