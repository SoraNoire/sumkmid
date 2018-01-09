$(document).ready(function() {
// NESTABLE
    if ($('#menu-structure').length > 0) {
        $('#menu-structure').nestable({ group: 1,maxDepth: 1 });
        load_list_menu();
    }
// END NESTABLE
});

// menu function
// save menu
$('.save-menu').on('click', function(){
    var data_menu = JSON.stringify($('#menu-structure').nestable('serialize'));
    var menu_type = $(this).attr('menu-type');
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {menu :data_menu},
        type: 'POST',
        url: '/admin/blog/menu/save-menu/'+menu_type,
        success: function(response){
            $("html, body").animate({
                scrollTop: 0
            }, 200);
            if (response == 'berhasil simpan') {
                notif.showNotification("top","right",'Menu Berhasil Disimpan','2');
            } else {
                notif.showNotification("top","right",'Menu Gagal Disimpan','4');
            }
        },
        error: function(err){
             $("html, body").animate({
                scrollTop: 0
            }, 200);
            notif.showNotification("top","right",'Menu Gagal Disimpan','4');
        }
    });
    setTimeout(function(){ $('#saved').hide() }, 2000);
});

// save menu mobile
$('.save-menu-mobile').on('click', function(){
    var data_menu = JSON.stringify($('#menu-structure').nestable('serialize'));
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {menu :data_menu},
        type: 'POST',
        url: '/admin/blog/menu/save-menu-mobile',
        success: function(response){
            $("html, body").animate({
                scrollTop: 0
            }, 200);
            if (response == 'berhasil simpan') {
                notif.showNotification("top","right",'Menu Berhasil Disimpan','2');
            } else {
                notif.showNotification("top","right",'Menu Gagal Disimpan','4');
            }
        },
        error: function(err){
             $("html, body").animate({
                scrollTop: 0
            }, 200);
            notif.showNotification("top","right",'Menu Gagal Disimpan','4');
        }
    });
    setTimeout(function(){ $('#saved').hide() }, 2000);
});

$('#menu_page').on('click', '.add_menu', function(){
    $("input[type=checkbox][name=menu_page]:checked").each(function(){ 
        var menu_id = get_menu_id();
        menu_id += 1;
        var url = $(this).attr('data-link');
        var label = $(this).attr('data-label');

        $('#menu-structure .dd-list:first-child').append('<li class="dd-item" data-id="'+menu_id+'" data-link="'+url+'" data-label="'+label+'"><div class="dd-handle dd3-handle">Drag</div><div class="menu-item dd3-content panel panel-default" id="menu'+menu_id+'"><div class="menu-title"><span>'+label+'</span><a data-toggle="collapse" data-parent="#menu-structure" href="#menu-collapse-'+menu_id+'"><i style="float: right;" class="fa fa-caret-down" aria-hidden="true"></i></a></div><div id="menu-collapse-'+menu_id+'" class="collapse menu-collapse panel panel-default"><div class="form-group"><label>Label</label><input class="form-control" type="text" name="title" value="'+label+'"><label>URL</label><input class="form-control" type="url" name="url" value="'+url+'"></div><a href="#" class="remove_item">Remove</a></div></div></li>')

        $(this).prop('checked',false);
    });
});

$('#menu_category').on('click', '.add_menu', function(){
    $("input[type=checkbox][name=menu_category]:checked").each(function(){ 
        var menu_id = get_menu_id();
        menu_id += 1;
        var url = $(this).attr('data-link');
        var label = $(this).attr('data-label');

        $('#menu-structure .dd-list:first-child').append('<li class="dd-item" data-id="'+menu_id+'" data-link="'+url+'" data-label="'+label+'"><div class="dd-handle dd3-handle">Drag</div><div class="menu-item dd3-content panel panel-default" id="menu'+menu_id+'"><div class="menu-title"><span>'+label+'</span><a data-toggle="collapse" data-parent="#menu-structure" href="#menu-collapse-'+menu_id+'"><i style="float: right;" class="fa fa-caret-down" aria-hidden="true"></i></a></div><div id="menu-collapse-'+menu_id+'" class="collapse menu-collapse panel panel-default"><div class="form-group"><label>Label</label><input class="form-control" type="text" name="title" value="'+label+'"><label>URL</label><input class="form-control" type="url" name="url" value="'+url+'"></div><a href="#" class="remove_item">Remove</a></div></div></li>')

        $(this).prop('checked',false);
    });
});

$('#menu_category_mobile').on('click', '.add_menu', function(){
    $("input[type=checkbox][name=menu_category]:checked").each(function(){ 
        var kat_id = $(this).val();
        var label = $(this).attr('data-label');

        $('#menu-structure .dd-list:first-child').append('<li class="dd-item" data-id="'+kat_id+'"><div class="dd-handle dd3-handle">Drag</div><div class="menu-item dd3-content panel panel-default"><div class="menu-title"><span>'+label+'</span><a href="#" class="remove_item" style="float: right;">Remove</a></div></div></li>')

        $(this).prop('checked',false);
    });
});

$('#menu_url').on('click', '.add_menu', function(){
    var menu_id = get_menu_id();
    menu_id += 1;
    var label = $('#custom-label').val();
    var url = $('#custom-url').val();

    $('#menu-structure .dd-list:first-child').append('<li class="dd-item" data-id="'+menu_id+'" data-link="'+url+'" data-label="'+label+'"><div class="dd-handle dd3-handle">Drag</div><div class="menu-item dd3-content panel panel-default" id="menu'+menu_id+'"><div class="menu-title"><span>'+label+'</span><a data-toggle="collapse" data-parent="#menu-structure" href="#menu-collapse-'+menu_id+'"><i style="float: right;" class="fa fa-caret-down" aria-hidden="true"></i></a></div><div id="menu-collapse-'+menu_id+'" class="collapse menu-collapse panel panel-default"><div class="form-group"><label>Label</label><input class="form-control" type="text" name="title" value="'+label+'"><label>URL</label><input class="form-control" type="url" name="url" value="'+url+'"></div><a href="#" class="remove_item">Remove</a></div></div></li>')

    $('#custom-label').val('');
    $('#custom-url').val('http://');
});

$('#menu-structure').on('click', '.remove_item', function(e){
    e.preventDefault();
    $(this).parents('li').remove();
});

$('#menu-structure').on('keyup', 'input[name=title]', function(){
    var a = $(this).val();
    $(this).parents('.menu-item').find('.menu-title span').text(a);
    $(this).parents('.dd-item').attr('data-label', a);
});

$('#menu-structure').on('keyup', 'input[name=url]', function(){
    var a = $(this).val();
    $(this).parents('.dd-item').attr('data-link', a);
});

$('#menu_page').on('keyup', 'input[name=search_component]', function(){
    $('#search_page_component .add_menu').show();

    var a = $(this).val();
    if (a == '') {
        a = 'none';
    }
    $.ajax({
        type: 'GET',
        url: '/admin/blog/menu/search-page-component/'+a,
        success: function(response){
            $('#search_page_component .search-result ul').html(response);
        },
        error: function(err){
            $('#search_page_component .search-result ul').html('<li>Something went wrong</li>');
        }
    });
});

$('#menu_page').on('click', 'a', function(){
    $("input[type=checkbox][name=menu_page]:checked").each(function(){ 
        $(this).prop('checked',false);
    });
});

$('#menu_page').on('click', 'a', function(){
    $('input[name=search_component]').val('');
});

$('#menu_category, #menu_category_mobile').on('keyup', 'input[name=search_component]', function(){
    $('#search_cat_component .add_menu').show();
    var a = $(this).val();
    if (a == '') {
        a = 'none';
    }
    $.ajax({
        type: 'GET',
        url: '/admin/blog/menu/search-category-component/'+a,
        success: function(response){
            $('#search_cat_component .search-result ul').html(response);
        },
        error: function(err){
            $('#search_cat_component .search-result ul').html('<li>Something went wrong</li>');
        }
    });
});

$('#menu_category').on('click', 'a', function(){
    $("input[type=checkbox][name=menu_category]:checked").each(function(){ 
        $(this).prop('checked',false);
    });
});

$('#menu_category').on('click', 'a', function(){
    $('input[name=search_component]').val('');
});


function get_menu_id(){
    var menu_id = new Array();
    if ($("#menu-structure li:last-child").length > 0) {

        var item = $("#menu-structure li").map(function(){
            menu_id.push(parseInt($(this).attr('data-id')));
        }).get().join();
        menu_id = Math.max(...menu_id);
    }   

    return menu_id;
}

$(document).ready(get_menu_id());

function select_menu(){
    var a = $('#select-menu-option').val();
    $('.save-menu').attr('menu-type', a);
    load_list_menu();
}

function load_list_menu() {
    var menu_type = $('.save-menu').attr('menu-type');
    $.ajax({
        type: 'GET',
        url: '/admin/blog/menu/get-menu/'+menu_type,
        success: function(response){
            $('#menu-structure .dd-list').html(response);
        },
        error: function(err){
            notif.showNotification("top","right",'Terjadi Kesalahan. Silahkan coba lagi dalam beberapa menit.','4');
        }
    });
}
// end menu function