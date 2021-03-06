/*!
 * jScroll - jQuery Plugin for Infinite Scrolling / Auto-Paging
 * @see @link{http://jscroll.com}
 *
 * @copyright 2011-2017, Philip Klauzinski
 * @license Dual licensed under the MIT and GPL Version 2 licenses.
 * @author Philip Klauzinski (http://webtopian.com)
 * @version 2.3.6
 * @requires jQuery v1.4.3+
 * @preserve
 */
!function(a){"use strict";a.jscroll={defaults:{debug:!1,autoTrigger:!0,autoTriggerUntil:!1,loadingHtml:"<small>Loading...</small>",loadingFunction:!1,padding:0,nextSelector:"a:last",contentSelector:"",pagingSelector:"",callback:!1}};var b=function(b,c){var d=b.data("jscroll"),e="function"==typeof c?{callback:c}:c,f=a.extend({},a.jscroll.defaults,e,d||{}),g="visible"===b.css("overflow-y"),h=b.find(f.nextSelector).first(),i=a(window),j=a("body"),k=g?i:b,l=a.trim(h.attr("href")+" "+f.contentSelector),m=function(){var b=a(f.loadingHtml).filter("img").attr("src");if(b){var c=new Image;c.src=b}},n=function(){b.find(".jscroll-inner").length||b.contents().wrapAll('<div class="jscroll-inner" />')},o=function(a){var b;f.pagingSelector?a.closest(f.pagingSelector).hide():(b=a.parent().not(".jscroll-inner,.jscroll-added").addClass("jscroll-next-parent").hide(),b.length||a.wrap('<div class="jscroll-next-parent" />').parent().hide())},p=function(){return k.unbind(".jscroll").removeData("jscroll").find(".jscroll-inner").children().unwrap().filter(".jscroll-added").children().unwrap()},q=function(){if(b.is(":visible")){n();var a=b.find("div.jscroll-inner").first(),c=b.data("jscroll"),d=parseInt(b.css("borderTopWidth"),10),e=isNaN(d)?0:d,h=parseInt(b.css("paddingTop"),10)+e,i=g?k.scrollTop():b.offset().top,j=a.length?a.offset().top:0,l=Math.ceil(i-j+k.height()+h);if(!c.waiting&&l+f.padding>=a.outerHeight())return u("info","jScroll:",a.outerHeight()-l,"from bottom. Loading next request..."),t()}},r=function(a){return a=a||b.data("jscroll"),a&&a.nextHref?(s(),!0):(u("warn","jScroll: nextSelector not found - destroying"),p(),!1)},s=function(){var c=b.find(f.nextSelector).first();if(c.length)if(f.autoTrigger&&(f.autoTriggerUntil===!1||f.autoTriggerUntil>0)){o(c);var d=j.height()-b.offset().top,e=b.height()<d?b.height():d,g=b.offset().top-i.scrollTop()>0?i.height()-(b.offset().top-a(window).scrollTop()):i.height();g>=e&&q(),k.unbind(".jscroll").bind("scroll.jscroll",function(){return q()}),f.autoTriggerUntil>0&&f.autoTriggerUntil--}else k.unbind(".jscroll"),c.bind("click.jscroll",function(){return o(c),t(),!1})},t=function(){var c=b.find("div.jscroll-inner").first(),d=b.data("jscroll");return d.waiting=!0,c.append('<div class="jscroll-added" />').children(".jscroll-added").last().html('<div class="jscroll-loading" id="jscroll-loading">'+f.loadingHtml+"</div>").promise().done(function(){f.loadingFunction&&f.loadingFunction()}),b.animate({scrollTop:c.outerHeight()},0,function(){var e=d.nextHref;c.find("div.jscroll-added").last().load(e,function(c,g){if("error"===g)return p();var h=a(this).find(f.nextSelector).first();d.waiting=!1,d.nextHref=h.attr("href")?a.trim(h.attr("href")+" "+f.contentSelector):!1,a(".jscroll-next-parent",b).remove(),r(),f.callback&&f.callback.call(this,e),u("dir",d)})})},u=function(a){if(f.debug&&"object"==typeof console&&("object"==typeof a||"function"==typeof console[a]))if("object"==typeof a){var b=[];for(var c in a)"function"==typeof console[c]?(b=a[c].length?a[c]:[a[c]],console[c].apply(console,b)):console.log.apply(console,b)}else console[a].apply(console,Array.prototype.slice.call(arguments,1))};return b.data("jscroll",a.extend({},d,{initialized:!0,waiting:!1,nextHref:l})),n(),m(),s(),a.extend(b.jscroll,{destroy:p}),b};a.fn.jscroll=function(c){return this.each(function(){var d,e=a(this),f=e.data("jscroll");f&&f.initialized||(d=new b(e,c))})}}(jQuery);
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

$(document).ready(function(){
    if($('#pilihKota').val() != 'pilihkota'){
    	getprov = $('#pilihProvinsi').find(":selected").attr('id');
		$('.defkota').hide();
		$('.'+getprov).show();
	}
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
	$('.info_usaha_validate').val('done');
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
				<div class="inputText" style="float: left;">
					<input type="`+type+`" `+numonly+` name="info_usaha[`+content.replace(" ", "")+`]"  value="" placeholder="`+content+` . . .">
				</div>
				<div id="close`+content+`" class="close"><i class="fa fa-times" aria-hidden="true"></i></div>
				<div class="clear"></div>
			</div>
		`);
	$('.infoOption').hide();
	$(this).hide();
});

$(document).on('click', '.close', function(){
	// alert($(this).attr('id'));
	content = $(this).attr('id').substring(5);
	$(this).parent().remove();
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