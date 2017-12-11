mediaPath = 'https://s3-ap-southeast-1.amazonaws.com/mdirect/shbtm/media';
filePath = 'https://s3-ap-southeast-1.amazonaws.com/mdirect/shbtm/files';
var timeOutId;

$("#browse_media_post").click(function() {
    $("html, body").animate({
        scrollTop: 0
    }, 500);
    $(".overlay").fadeIn(), $(".media-modal").fadeIn();
});

$("#close_media_post, .overlay").click(function() {
    $(".overlay").fadeOut(), $(".media-modal").fadeOut()
});

$("#browse_fimg_post").click(function() {
    $("html, body").animate({
        scrollTop: 0
    }, 500);
    $(".overlay").fadeIn(), $(".fimg-modal").fadeIn();
});

$("#close_fimg_post, .overlay").click(function() {
    $(".overlay").fadeOut(), $(".fimg-modal").fadeOut()
});

$("#browse_file_post").click(function() {
    $("html, body").animate({
        scrollTop: 0
    }, 500);
    $(".overlay").fadeIn(), $(".file-modal").fadeIn();
});

$("#close_file_post, .overlay").click(function() {
    $(".overlay").fadeOut(), $(".file-modal").fadeOut()
});

// fungsi upload image
$('#uploadmedia').on('change', function add_media(e){
    e.preventDefault();
    timeOutId = setTimeout(ajaxFn, 1000, e);

    $('.dataTables_processing').show();

    function ajaxFn(e){
        var fd = new FormData($("#actuploadmedia")[0]);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: "/admin/blog/store-media",
            processData: false,
            contentType: false,
            data: fd,
            success: function(msg){
                        $(".mediatable").DataTable().ajax.reload(null, false);
                        console.log('add');
            },
            error: function(err){
                    $(".mediatable").DataTable().ajax.reload(null, false);
                    var obj = err.responseJSON;
                    alert(Object.values(obj)[0].toString());
                }
        });
        $('.dataTables_processing').hide();
    };
});

$('#uploadfimg').on('change', function add_media(e){
    e.preventDefault();
    timeOutId = setTimeout(ajaxFn, 1000, e);
    $('.dataTables_processing').show();

    function ajaxFn(e){
        var fd = new FormData($("#actuploadfimg")[0]);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: "/admin/blog/store-media",
            processData: false,
            contentType: false,
            data: fd,
            success: function(msg){
                        $(".mediatable").DataTable().ajax.reload(null, false);
                        console.log(msg);
            },
            error: function(err){
                    $(".mediatable").DataTable().ajax.reload(null, false);
                    var obj = err.responseJSON;
                    alert(Object.values(obj)[0].toString());
                }
        });
        $('.dataTables_processing').hide();
    };
});

// fungsi upload file
$('#fileUpload').on('change', function add_file(e){
    e.preventDefault();
    timeOutId = setTimeout(ajaxFn, 1000, e);
    $('.dataTables_processing').show();

    function ajaxFn(e){
        var fd = new FormData($("#fileupload-form")[0]);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: "/admin/blog/store-file",
            processData: false,
            contentType: false,
            data: fd,
            success: function(msg){
                $(".filestable").DataTable().ajax.reload(null, false);
                // var obj = JSON.parse(msg);
                console.log(msg);
            },
            error: function(err){
                $(".filestable").DataTable().ajax.reload(null, false);
                var obj = err.responseJSON;
                alert(Object.values(obj)[0].toString());
            },
            always: function(a){
                $(".filestable").DataTable().ajax.reload(null, false);
                // console.log(a);
            }
        });
        $('.dataTables_processing').hide();
    }
});

function delete_media(e){
    timeOutId = setTimeout(ajaxFn, 2000, e);

    $('#canceldelete').show();
    $('.dataTables_processing').show();
    function ajaxFn(e){
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "GET",
            url: "/admin/blog/delete-media/"+e,
            processData: false,
            contentType: false,
            success: function(msg){
                $(".mediatable").DataTable().ajax.reload(null, false);
            },
            error: function(err){
                $(".mediatable").DataTable().ajax.reload(null, false);
                alert('delete error');
            }
        });
        $('#canceldelete').hide();
        $('.dataTables_processing').hide();
    };
};


function select_media(e){
    var a = $("<input>");
    var b = $(e).text();
    $("body").append(a);
    a.val(b).select();
    document.execCommand("copy");
    a.remove();
    $(".overlay").fadeOut();
    $(".media-modal").fadeOut();
}

function select_fimg(e){
    $('.preview-fimg-wrap').show();
    var a = $('.preview-fimg');
    var b = $('#featured_img');
    var c = $(e).text();
    a.css('background-image', 'url('+c+')');
    b.val(c);
    $(".overlay").fadeOut();
    $(".fimg-modal").fadeOut();
}

function remove_fimg(){
    $('.preview-fimg-wrap').hide();
    var a = $('.preview-fimg');
    var b = $('#featured_img');
    a.css('background-image', 'url()');
    b.val('');
}

$(document).ready(function() {
// DATATABLES CONFIG

    // multiselect for bulk delete
    $('.mydatatable tbody').on( 'click', 'tr', function () {
        $(this).toggleClass('selected');
        var count = $(".mydatatable").DataTable().rows('.selected').data().length;
        if (count > 0) {
            $('.bulk-delete-item').show();   
            $('.bulk-delete-count').html( $(".mydatatable").DataTable().rows('.selected').data().length ); 
        } else {
            $('.bulk-delete-item').hide(); 
        }
        var ids = $.map($(".mydatatable").DataTable().rows('.selected').data(), function (item) {
            return item.id
        });
        $('.bulk-delete-id').val(JSON.stringify(ids));
    });
    // end multiselect for bulk delete
// END DATATABLES
});

function cancelDelete(){
    clearTimeout(timeOutId);
    $('#canceldelete').hide();
    $('.table-overlay').hide();
};

// date time picker
if ($(".datetimepicker").length > 0) {
    $(function () {
        $('.datetimepicker').datetimepicker({
            format: "yyyy-mm-dd hh:ii",
            autoclose: true,
            todayBtn: true,
            pickerPosition: "top-left"
        });
    });
}

if ($(".event-datetimepicker").length > 0) {
    $(function () {
        $('.event-datetimepicker').datetimepicker({
            format: "yyyy-mm-dd",
            autoclose: true,
            todayBtn: true,
            pickerPosition: "top-left",
            minView: 2
        });
    });
}

function load_post_category(){
    var id = $('meta[name="item-id"]').attr('content');
    $.ajax({
        type: "GET",
        url: "/admin/blog/get-category-post/"+id,
        success: function(msg){
            $('.category-wrap ul').html(msg);
        },
        error: function(err){
            console.log(err);
        }
    });
}

function load_post_category_parent(){
    var id = $('meta[name="category-id"]').attr('content');
    $.ajax({
        type: "GET",
        url: "/admin/blog/get-category-parent/"+id,
        success: function(msg){
            $('.category-parent').html(msg);
        },
        error: function(err){
            console.log(err);
        }
    });
}

if ($('.category-wrap').length > 0) {
    $('.category-wrap').ready(load_post_category());
}

if ($('.category-parent').length > 0) {
    $('.category-parent').ready(load_post_category_parent());
}

// add category on post ajax function
$('.add_category_button').on('click', function add_category(){
    var n = $('input[name=category_name]').val();
    var p = $('select[name=category_parent]').val();
    if (n != '') {
        $.ajax({
            type: "GET",
            url: "/admin/blog/add-category-post/"+n+"/"+p,
            success: function(msg){
                console.log(msg);
            },
            error: function(err){
                console.log(err);
            }
        });

        load_post_category();
        console.log('ss');
        load_post_category_parent();
        $('input[name=category_name]').val('');
        $('select[name=category_parent]').removeAttr('selected');
    } else {    
        // do nothing
    }
});

// select file for post
if ($("#postFile").length > 0) {
    $('#postFile tbody').on( 'click', 'tr', function () {
        $(this).toggleClass('selected');
        $('#count-files').html( $("#postFile").DataTable().rows('.selected').data().length +' row(s) selected' );
        $('#selected-files').show();
    });
}

// select selected files for post
if ($("#select-files").length > 0) {
    $('#select-files').on('click', function(){
        var files = $.map($("#postFile").DataTable().rows('.selected').data(), function (item) {
            return item.label
        });
        var names = $.map($("#postFile").DataTable().rows('.selected').data(), function (item) {
            return item.name
        });

        files.forEach(function(file, index) {  
            $(".file-list").append('<div class="form-group input-group file-item"><span class="input-group-addon"><i class="fa fa-file-o" aria-hidden="true"></i></span><input type="text" name="file_label[]" class="form-control" placeholder="insert label for file here" value="'+file+'"><span class="input-group-btn"><button class="btn btn-danger file-delete" type="button"><i class="fa fa-times" aria-hidden="true"></i></button></span><input type="hidden" name="file_name[]" value="'+names[index]+'"></div>');
        });

        $(".overlay").fadeOut(), $(".custom-modal").fadeOut(), $('#count-files').html(''), $('#postFile').find('tr').removeClass('selected')
        
    });
}

// fungsi delete file 
if ($('.file-list').length > 0) {
    $('.file-list').on('click', '.file-delete', function(){
        $(this).parents('.file-item').remove();
    });
}

function delete_file(e){
    timeOutId = setTimeout(ajaxFn, 2000, e);

    $('.dataTables_processing').show();
    $('#canceldelete').show();
    function ajaxFn(e){
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "GET",
            url: "/admin/blog/delete-file/"+e,
            processData: false,
            contentType: false,
            success: function(msg){
                $(".filestable").DataTable().ajax.reload(null, false);
            },
            error: function(err){
                $(".filestable").DataTable().ajax.reload(null, false);
                console.log(err);
            }
        });
        $('#canceldelete').hide();
        $('.dataTables_processing').hide();
    };
};
