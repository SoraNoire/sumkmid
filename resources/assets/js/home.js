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

// $('#submit_newsletter').on('click', function(e){
// 	e.preventDefault();
// 	var a = $('input[name=email_subscribe]').val();
// 	$.ajax({
//         type: 'GET',
//         url: '/subcribe/'+a,
//         success: function(response){
// 			$('#myalert').show();
// 			$('#myalert span').html(response);
// 			$('#myalert').addClass('alert-success');
//         },
//         error: function(err){
// 			$('#myalert').show();
// 			$('#myalert span').html('Terjadi kesalahan. Silahkan coba beberapa menit lagi');
// 			$('#myalert').addClass('alert-danger');
//         }
//     });
//     a = '';
// });

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
	$('#'+id).find('.event-desc').removeClass('hidden');
}

function show_less_event_detail(id) {
	$('#'+id).find('.lihat-detail').show();
	$('#'+id).find('.lihat-sedikit').hide();
	$('#'+id).find('.tempat').hide();
	$('#'+id).find('.htm').hide();
	$('#'+id).find('.event-buttons').hide();
	$('#'+id).find('.event-desc').removeClass('show');
	$('#'+id).find('.event-desc').addClass('hidden');
}

function show_event_sharer(id) {
	if(window.outerWidth < 659){
		$('#'+id).find('.share-socmed').fadeIn().css("display","inline-block");
		$('.whiteOverlay').fadeIn();
	}else{
		$('#'+id).find('.share-socmed').fadeIn().css("display","inline-block");
	}
}

$(document).ready(function(){
	$('#upldimageuser').on('submit',(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
			headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type:'POST',
            url: $(this).attr('action'),
            data:formData,
            cache:false,
            contentType: false,
            processData: false,
            success:function(data){
				// console.log(data);
				$('.photoPreview').css('background-image', "url("+data+"?random="+new Date().getTime()+")");
            },
            error: function(data){
                console.log("error");
                console.log(data);
            }
        });
    }));
    $("#inputUserImage").on('change',function(e){
		$( "#upldimageuser" ).submit();
    });
});

window.onresize = function(){
    
    if (window.innerWidth > 830)
    {
		$('.navWrapper ul').show();
		$('.userNavSetting ul').hide();
		$('.trnsOverlay').hide();
    }else{
		$('.userNavSetting ul').show();
		$('.navWrapper ul').hide();
		
	}
} 

$('#profileTrigger').click(function(){
	$('.userNavSetting ul').slideToggle();
});

$('.whiteOverlay').click(function(){
	$('.whiteOverlay').fadeOut();
	$('.share-socmed').fadeOut();
});

$('.closeAlert').click(function(){
	$('.formAlert').slideUp();
})
// init Infinite Scroll
// $('.archive-list').infiniteScroll({
//   path: '.pagination__next',
//   append: '.post',
//   status: '.scroller-status',
//   hideNav: '.pagination',
// });

$('ul.pagination').hide();

$(function(){
    $('.infinite-scroll').jscroll({
        autoTrigger: true,
        loadingHtml: function() {
        	$('.loading').show();
        }
        ,
        padding: 0,
        nextSelector: '.pagination li.active + li a',
        contentSelector: 'div.infinite-scroll',
        callback: function() {
            $('ul.pagination').remove();
            $('.atEnd').show();
            $('.loading').hide();
            $('.endOfEvent').show();
        }
    });
});

