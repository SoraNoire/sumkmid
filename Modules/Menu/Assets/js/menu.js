$(document).ready(function() {
// NESTABLE
    if ($('#menu-structure').length > 0) {
        $('#menu-structure').nestable({ group: 1 });
    }
// END NESTABLE
});

// menu function
$('#save-menu').on('click', function(){
    var data_menu = JSON.stringify($('#menu-structure').nestable('serialize'));
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {menu :data_menu},
        type: 'POST',
        url: '/blog/menu/save-menu',
        success: function(response){
            $('#saved').show();
        },
        error: function(err){
            $('#saved').text('Error');
            $('#saved').show();
        }
    });
    setTimeout(function(){ $('#saved').hide() }, 2000);
});

var menu_id = $("#menu-structure li").length;

$('#add_page_menu').on('click', function(){
    $("input[type=checkbox][name=menu_page]:checked").each(function(){ 
        menu_id += 1;
        var url = $(this).attr('data-link');
        var label = $(this).attr('data-label');

        $('#menu-structure .dd-list').append('<li class="dd-item" data-id="'+menu_id+'" data-link="'+url+'" data-label="'+label+'"><div class="dd-handle dd3-handle">Drag</div><div class="menu-item dd3-content panel panel-default" id="menu'+menu_id+'"><div class="menu-title"><span>'+label+'</span><a data-toggle="collapse" data-parent="#menu-structure" href="#menu-collapse-'+menu_id+'"><i style="float: right;" class="fa fa-caret-down" aria-hidden="true"></i></a></div><div id="menu-collapse-'+menu_id+'" class="collapse menu-collapse panel panel-default"><div class="form-group"><label>Label</label><input class="form-control" type="text" name="title" value="'+label+'"><label>URL</label><input class="form-control" type="url" name="url" value="'+url+'"></div><a href="#" class="remove_item">Remove</a></div></div></li>')

        $(this).prop('checked',false);
    });
});

$('#add_post_menu').on('click', function(){
    $("input[type=checkbox][name=menu_post]:checked").each(function(){ 
        menu_id += 1;
        var url = $(this).attr('data-link');
        var label = $(this).attr('data-label');
        
        $('#menu-structure .dd-list').append('<li class="dd-item" data-id="'+menu_id+'" data-link="'+url+'" data-label="'+label+'"><div class="dd-handle dd3-handle">Drag</div><div class="menu-item dd3-content panel panel-default" id="menu'+menu_id+'"><div class="menu-title"><span>'+label+'</span><a data-toggle="collapse" data-parent="#menu-structure" href="#menu-collapse-'+menu_id+'"><i style="float: right;" class="fa fa-caret-down" aria-hidden="true"></i></a></div><div id="menu-collapse-'+menu_id+'" class="collapse menu-collapse panel panel-default"><div class="form-group"><label>Label</label><input class="form-control" type="text" name="title" value="'+label+'"><label>URL</label><input class="form-control" type="url" name="url" value="'+url+'"></div><a href="#" class="remove_item">Remove</a></div></div></li>')

        $(this).prop('checked',false);
    });
});

$('#add_category_menu').on('click', function(){
    $("input[type=checkbox][name=menu_category]:checked").each(function(){ 
        menu_id += 1;
        var url = $(this).attr('data-link');
        var label = $(this).attr('data-label');

        $('#menu-structure .dd-list').append('<li class="dd-item" data-id="'+menu_id+'" data-link="'+url+'" data-label="'+label+'"><div class="dd-handle dd3-handle">Drag</div><div class="menu-item dd3-content panel panel-default" id="menu'+menu_id+'"><div class="menu-title"><span>'+label+'</span><a data-toggle="collapse" data-parent="#menu-structure" href="#menu-collapse-'+menu_id+'"><i style="float: right;" class="fa fa-caret-down" aria-hidden="true"></i></a></div><div id="menu-collapse-'+menu_id+'" class="collapse menu-collapse panel panel-default"><div class="form-group"><label>Label</label><input class="form-control" type="text" name="title" value="'+label+'"><label>URL</label><input class="form-control" type="url" name="url" value="'+url+'"></div><a href="#" class="remove_item">Remove</a></div></div></li>')

        $(this).prop('checked',false);
    });
});

$('#add_link_menu').on('click', function(){
    menu_id += 1;
    var label = $('#custom-label').val();
    var url = $('#custom-url').val();

    $('#menu-structure .dd-list').append('<li class="dd-item" data-id="'+menu_id+'" data-link="'+url+'" data-label="'+label+'"><div class="dd-handle dd3-handle">Drag</div><div class="menu-item dd3-content panel panel-default" id="menu'+menu_id+'"><div class="menu-title"><span>'+label+'</span><a data-toggle="collapse" data-parent="#menu-structure" href="#menu-collapse-'+menu_id+'"><i style="float: right;" class="fa fa-caret-down" aria-hidden="true"></i></a></div><div id="menu-collapse-'+menu_id+'" class="collapse menu-collapse panel panel-default"><div class="form-group"><label>Label</label><input class="form-control" type="text" name="title" value="'+label+'"><label>URL</label><input class="form-control" type="url" name="url" value="'+url+'"></div><a href="#" class="remove_item">Remove</a></div></div></li>')

    $('#custom-label').val('');
    $('#custom-url').val('http://');
});

$('.remove_item').on('click', function(){
    $(this).parents('li').remove();
});

$('.menu-item').on('change', 'input[type=text][name=title]', function(){
    var a = $(this).val();
    console.log($(this).parent('.menu-item #menu_title'));
    $(this).parent().parent().parent().find('.menu-title span').text(a);
});
// end menu function