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

if($('#instagram-feed').length > 0){
	var spp = 6;

	if($('body').outerWidth() < 725) {
      spp = 4;
    }
    if($('body').outerWidth() < 525) {
    	spp = 3;
    }
    if($('body').outerWidth() < 425) {
    	spp = 2;
    }

	var igSlider = new Swiper('.insta-slider', {
	slidesPerView: spp,
	spaceBetween: 10,
	loop: true,
	autoplay: {
	    delay: 3000,
		},
	pagination: {
	el: '.insta-pagination',
	},
});
}

if($('#mainSlider').length > 0){
	var mySwiper = new Swiper ('.main-slider', {
	// Optional parameters
	direction: 'horizontal',
	loop: true,
	autoplay: {
	    delay: 5000,
		},

	// If we need pagination
	pagination: {
	  el: '.swiper-pagination',
	},
	});
}

if($('#main-gallery').length > 0){

	var spp = 4;

	if($('body').outerWidth() < 830) {
      spp = 3;
    }
    if($('body').outerWidth() < 640) {
    	spp = 2;
    }
    if($('body').outerWidth() < 458) {
    	spp = 1;
    }

	var gallSlider = new Swiper('.gallery-slider', {
	slidesPerView: spp,
	spaceBetween: 0,
	loop: true,
	pagination: {
	el: '.gallery-pagination',
	},
	navigation: {
      nextEl: '.galleryGoRight',
      prevEl: '.galleryGoLeft',
    },
});
}

$('#pilihProvinsi').on('change',function(){
	$('#pilihKota').val('pilihkota');
	getprov = $('option:selected', this).attr('id');
	$('.defkota').hide();
	$('.'+getprov).show();
});

$(document).ready('.info_usaha__select', function(){
    resyncSelected();
});


// function resyncSelected()
// {
//     var selected = $('.info_usaha__select');
//     $('.info_usaha__select').find('option').removeAttr('disabled');
//         for(i=0;i<selected.length;i++)
//     {
//         var el = $(selected[i]);
//         var val = el.val();
//         alert(val);
//         $('.u-'+val).css('display', 'none');
//         $('.info_usaha__select').find('option[value='+val+']').attr('disabled','disabled');
//     }
//     //var allEl = $('.slc').find('option[value='+val+']');
// }

$(document).mouseup(function(e) 
{
    var container = $(".infoOption");
    // if the target of the click isn't the container nor a descendant of the container
    if (!container.is(e.target) && container.has(e.target).length === 0){
        container.hide();
    }
});

$('.addInfoTrigger').on('click', function(){
	$('.infoOption').show();
});

$('.addInfo').on('click', function(){
	numonly = ``;
	content = $(this).text();
	if($(this).hasClass('infoLink')){
		type = 'url';
	}else if($(this).hasClass('infoEmail')){
		type = 'email';
	}else{
		type =  'tel';
		numonly = `onkeypress='return event.charCode >= 48 && event.charCode <= 57'`;
	}
	$(".addedInfo").append(`
			<div class="formGroup">
				<div class="inputTitle">
					`+content+` :
				</div>
				<div class="inputText">
					<input type="`+type+`" `+numonly+` name="info_usaha[`+content.replace(" ", "")+`]"  value="" placeholder="`+content+` . . .">
				</div>
				<div id="close`+content+`" class="close"><i class="fa fa-times" aria-hidden="true"></i></div>
			</div>
		`);
	$('.infoOption').hide();
	$(this).hide();
});

$(document).on('click', '.close', function(){
	// alert($(this).attr('id'));
	content = $(this).attr('id').substring(5);
	$(this).parent().hide();
	$('#addInfo'+content).show();
});

function readURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function(e) {
      $('#PREVIEW').attr('src', e.target.result);
    }

    reader.readAsDataURL(input.files[0]);
  }
}

$("#KTPTRIGGER").change(function() {
  readURL(this);
});

if($('#mentor-archive').length > 0){
	var height = new Array();
    if ($("#mentor-archive .item").length > 0) {
        $("#mentor-archive .item").map(function(){
            height.push(parseInt($(this).height()));
        }).get().join();
        height = Math.max(...height);
    }   
    var window_width = $( window ).width();
    if (window_width > 375) {
    	$("#mentor-archive .item").css('height', height);
    }
}