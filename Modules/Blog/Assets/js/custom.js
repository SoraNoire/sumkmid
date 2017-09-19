mediaPath = 'https://s3-ap-southeast-1.amazonaws.com/mdirect/shbtm/media';
filePath = 'https://s3-ap-southeast-1.amazonaws.com/mdirect/shbtm/files'
//
// Pipelining function for DataTables. To be used to the `ajax` option of DataTables
//
$.fn.dataTable.pipeline = function ( opts ) {
    // Configuration options
    var conf = $.extend( {
        pages: 5,     // number of pages to cache
        url: '',      // script url
        data: null,   // function or object with parameters to send to the server
                      // matching how `ajax.data` works in DataTables
        method: 'GET' // Ajax HTTP method
    }, opts );
 
    // Private variables for storing the cache
    var cacheLower = -1;
    var cacheUpper = null;
    var cacheLastRequest = null;
    var cacheLastJson = null;
 
    return function ( request, drawCallback, settings ) {
        var ajax          = false;
        var requestStart  = request.start;
        var drawStart     = request.start;
        var requestLength = request.length;
        var requestEnd    = requestStart + requestLength;
         
        if ( settings.clearCache ) {
            // API requested that the cache be cleared
            ajax = true;
            settings.clearCache = false;
        }
        else if ( cacheLower < 0 || requestStart < cacheLower || requestEnd > cacheUpper ) {
            // outside cached data - need to make a request
            ajax = true;
        }
        else if ( JSON.stringify( request.order )   !== JSON.stringify( cacheLastRequest.order ) ||
                  JSON.stringify( request.columns ) !== JSON.stringify( cacheLastRequest.columns ) ||
                  JSON.stringify( request.search )  !== JSON.stringify( cacheLastRequest.search )
        ) {
            // properties changed (ordering, columns, searching)
            ajax = true;
        }
         
        // Store the request for checking next time around
        cacheLastRequest = $.extend( true, {}, request );
 
        if ( ajax ) {
            // Need data from the server
            if ( requestStart < cacheLower ) {
                requestStart = requestStart - (requestLength*(conf.pages-1));
 
                if ( requestStart < 0 ) {
                    requestStart = 0;
                }
            }
             
            cacheLower = requestStart;
            cacheUpper = requestStart + (requestLength * conf.pages);
 
            request.start = requestStart;
            request.length = requestLength*conf.pages;
 
            // Provide the same `data` options as DataTables.
            if ( $.isFunction ( conf.data ) ) {
                // As a function it is executed with the data object as an arg
                // for manipulation. If an object is returned, it is used as the
                // data object to submit
                var d = conf.data( request );
                if ( d ) {
                    $.extend( request, d );
                }
            }
            else if ( $.isPlainObject( conf.data ) ) {
                // As an object, the data given extends the default
                $.extend( request, conf.data );
            }
 
            settings.jqXHR = $.ajax( {
                "type":     conf.method,
                "url":      conf.url,
                "data":     request,
                "dataType": "json",
                "cache":    false,
                "success":  function ( json ) {
                    cacheLastJson = $.extend(true, {}, json);
 
                    if ( cacheLower != drawStart ) {
                        json.data.splice( 0, drawStart-cacheLower );
                    }
                    if ( requestLength >= -1 ) {
                        json.data.splice( requestLength, json.data.length );
                    }
                     
                    drawCallback( json );
                }
            } );
        }
        else {
            json = $.extend( true, {}, cacheLastJson );
            json.draw = request.draw; // Update the echo for each response
            json.data.splice( 0, requestStart-cacheLower );
            json.data.splice( requestLength, json.data.length );
 
            drawCallback(json);
        }
    }
};
 
// Register an API method that will empty the pipelined data, forcing an Ajax
// fetch on the next draw (i.e. `table.clearPipeline().draw()`)
$.fn.dataTable.Api.register( 'clearPipeline()', function () {
    return this.iterator( 'table', function ( settings ) {
        settings.clearCache = true;
    } );
} );


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

// fungsi upload image
$('#uploadmedia').on('change', function add_media(e){
    setTimeout(ajaxFn, 10, e);

    $('.table-overlay').show();
    function ajaxFn(e){
        e.preventDefault();
        var fd = new FormData($("#actuploadmedia")[0]);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: "/admin/blog/store-media",
            dataType:'json',
            async:false,
            processData: false,
            contentType: false,
            data: fd,
            success: function(msg){
                $(".mediatable").DataTable().ajax.reload(null, false);
                console.log('fd');
            },
            error: function(err){
                $(".mediatable").DataTable().ajax.reload(null, false);
                console.log(err);
            }
        });
        $('.table-overlay').hide();
    };
});

var timeOutId;
function delete_media(e){
    timeOutId = setTimeout(ajaxFn, 2000, e);

    $('#canceldelete').show();
    $('.table-overlay').show();
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
                console.log(err);
            }
        });
        $('#canceldelete').hide();
        $('.table-overlay').hide();
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

// fungsi upload file
$('#fileUpload').on('change', function add_file(e){
    e.preventDefault();
    var fd = new FormData($("#post-form")[0]);
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: "/admin/blog/store-file",
        dataType:'json',
        processData: false,
        contentType: false,
        data: fd,
        success: function(msg){
            for (var i = 0; i < msg.length; i++) {
                $('.file-list').append('<div class="form-group input-group file-item"><span class="input-group-addon"><i class="fa fa-file-o" aria-hidden="true"></i></span><input type="text" name="file_label[]" class="form-control" placeholder="insert label for file here" value="'+msg[i].oriName+'"><span class="input-group-btn"><button class="btn btn-danger file-delete" type="button" data-postid = "'+postId+'" data-filename="'+msg[i].name+'"><i class="fa fa-times" aria-hidden="true"></i></button></span><input type="hidden" name="file_doc[]" value="'+msg[i].name+'"></div>');
            }
        },
        error: function(err){
             // console.log(err);
        },
        always: function(a){
            // console.log(a);
        }
    });
});

// fungsi delete file 
$('.file-list').on('click', '.file-delete', function(){
    var fileName = $(this).attr('data-filename');
    var postId = $(this).attr('data-postid');
    var parent = $(this).parents('.file-item');
    $.ajax({
        type: "GET",
        url: "/admin/blog/delete-file/"+postId+'/'+fileName,
        success: function(msg){
            $(parent).remove();
        },
        error: function(err){
             // console.log(err);
        },
        always: function(a){
            // console.log(a);
        }
    });
});

$(document).ready(function() {
// DATATABLES CONFIG
    // category table 
    if ($("#CategoryTable").length > 0) {
        console.log('category');
        $("#CategoryTable").DataTable({
            "ajax": $.fn.dataTable.pipeline( {
                url: '/admin/blog/get-category',
                pages: 5 // number of pages to cache
            } ),
            "processing": true,
            "serverSide": true,
            "stateSave":true,
            "columns": [
                { "data": "name" },
                { "data": "created_at" },
                { "data": "id" },
            ],
            "columnDefs": [ {
                    "targets": -1,
                    "data": 'id',
                    "render": function ( data, type, row ) {
                        return '<a href="/admin/blog/edit-category/'+row.id+'">Edit</a> | <a onclick="return confirm(\'Delete Category?\');" href="/admin/blog/delete-category/'+row.id+'">Delete</a>';
                    }
                },
                    {
                    "targets": 0,
                    "data": 'name',
                    "render": function ( data, type, row ) {
                        return '<a href="/admin/blog/edit-category/'+row.id+'">'+data+'</a>';
                    }
                }
            ],
            order: [
                [0, "desc"],
                [1, "desc"]
            ]
        });
    } 

    // news table
    if ($("#myTableNews").length > 0) {
        $("#myTableNews").DataTable({
            "ajax": $.fn.dataTable.pipeline( {
                url: '/admin/blog/get-posts',
                pages: 5 // number of pages to cache
            } ),
            "processing": true,
            "serverSide": true,
            "stateSave":true,
            bSortable: true,
            "columns": [
                { "data": "title" },
                { "data": "author" },
                { "data": "created_at" },
                { "data": "id" },
            ],
            "columnDefs": [ {
                    "targets": -1,
                    "data": 'id',
                    "render": function ( data, type, row ) {
                        return '<a href="/admin/blog/edit-post/'+row.id+'">Edit</a> | <a onclick="return confirm(\'Delete Post?\');" href="/admin/blog/delete-post/'+row.id+'">Hapus</a>';
                    }
                },
                    {
                    "targets": 0,
                    "data": 'title',
                    "render": function ( data, type, row ) {
                        return '<a href="/admin/blog/edit-post/'+row.id+'">'+data+'</a>';
                    }
                }
            ],
            order: [
                [0, "desc"],
                [2, "desc"]
            ]
        });
    }

    // media table
    if ($("#MediaTable").length > 0) {
        $("#MediaTable").DataTable({
            "ajax":  $.fn.dataTable.pipeline( {
                url: '/admin/blog/get-media',
                pages: 5 // number of pages to cache
            } ),
            "processing": true,
            "serverSide": true,
            "stateSave":true,
            "columns": [
                { "data": "name" },
                { "data": "name" },
                { "data": "created_at" }
            ],
            "columnDefs": [ 
                    {
                    "targets": 0,
                    "data": 'title',
                    "render": function ( data, type, row ) {
                  return '<img style="width: 100px; max-height: 100px;" src="'+mediaPath+'/'+data+'">';
                    }
                }
            ],
            order: [
                [0, "desc"],
                [2, "desc"]
            ]
        });
    }

    // media post image modal
    if ($("#MediaPost").length > 0) {
        var path = mediaPath;
        $("#MediaPost").DataTable({
            "ajax":  {
                url: '/admin/blog/get-media'
            } ,
            "processing": true,
            "serverSide": true,
            "stateSave":true,
            "columns": [
                { "data": "name" },
                { "data": "name" },
                { "data": "created_at" },
                { "data": "id" },
            ],
            "columnDefs": [ {
                    "targets": -1,
                    "data": 'id',
                    "render": function ( data, type, row ) {
                        return '<div onclick="delete_media(\''+data+'\')" id="delete_media_post" class="btn btn-round btn-fill btn-danger">Delete</div> <div onclick="select_media(\'#'+data+'\')" id="select_media" class="btn btn-round btn-fill btn-success">Copy Media</div> <p style="display:none;" id="'+data+'">'+mediaPath+'/'+row.name+'</p>';
                    }
                },
                    {
                    "targets": 0,
                    "data": 'title',
                    "render": function ( data, type, row ) {
                  return '<img style="width: 100px; max-height: 100px;" src="'+mediaPath+'/'+data+'">';
                    }
                }
            ],
            order: [
                [0, "desc"],
                [2, "desc"]
            ]
        });
    }

    // feauterd image modal
    if ($("#FeaturedImg").length > 0) {
        var path = 'public/media';
        $("#FeaturedImg").DataTable({
            "ajax":  {
                url: '/admin/blog/get-media'
            } ,
            "processing": true,
            "serverSide": true,
            "stateSave":true,
            "columns": [
                { "data": "name" },
                { "data": "name" },
                { "data": "created_at" },
                { "data": "id" },
            ],
            "columnDefs": [ {
                    "targets": -1,
                    "data": 'id',
                    "render": function ( data, type, row ) {
                        return '<div onclick="delete_media(\''+data+'\')" id="delete_media_post" class="btn btn-round btn-fill btn-danger">Delete</div> <div onclick="select_fimg(\'#'+data+'\')" id="select_media" class="btn btn-round btn-fill btn-success">Select</div> <p style="display:none;" id="'+data+'">'+mediaPath+'/'+row.name+'</p>';
                    }
                },
                    {
                    "targets": 0,
                    "data": 'title',
                    "render": function ( data, type, row ) {
                  return '<img style="width: 100px; max-height: 100px;" src="'+mediaPath+'/'+data+'">';
                    }
                }
            ],
            order: [
                [0, "desc"],
                [2, "desc"]
            ]
        });
    }

    // pages table
    if ($("#myTablePages").length > 0) {
        $("#myTablePages").DataTable({
            "ajax": $.fn.dataTable.pipeline( {
                url: '/admin/blog/get-pages',
                pages: 5 // number of pages to cache
            } ),
            "processing": true,
            "serverSide": true,
            "stateSave":true,
            "columns": [
                { "data": "title" },
                { "data": "author" },
                { "data": "created_at" },
                { "data": "id" },
            ],
            "columnDefs": [ {
                    "targets": -1,
                    "data": 'id',
                    "render": function ( data, type, row ) {
                        return '<a href="/admin/blog/edit-page/'+row.id+'">Edit</a> | <a onclick="return confirm(\'Delete Page?\');" href="/admin/blog/delete-page/'+row.id+'">Hapus</a>';
                    }
                },
                    {
                    "targets": 0,
                    "data": 'title',
                    "render": function ( data, type, row ) {
                        return '<a href="/admin/blog/edit-page/'+row.id+'">'+data+'</a>';
                    }
                }
            ],
            order: [
                [0, "desc"],
                [2, "desc"]
            ]
        });
    }

    // tag table
    if ($("#TagTable").length > 0) {
        $("#TagTable").DataTable({
            "ajax": $.fn.dataTable.pipeline( {
                url: '/admin/blog/get-tag',
                pages: 5 // number of pages to cache
            } ),
            "processing": true,
            "serverSide": true,
            "stateSave":true,
            "columns": [
                { "data": "name" },
                { "data": "created_at" },
                { "data": "id" },
            ],
            "columnDefs": [ {
                    "targets": -1,
                    "data": 'id',
                    "render": function ( data, type, row ) {
                        return '<a href="/admin/blog/edit-tag/'+row.id+'">Edit</a> | <a onclick="return confirm(\'Delete Tag?\');" href="/admin/blog/delete-tag/'+row.id+'">Hapus</a>';
                    }
                },
                    {
                    "targets": 0,
                    "data": 'title',
                    "render": function ( data, type, row ) {
                        return '<a href="/admin/blog/edit-tag/'+row.id+'">'+data+'</a>';
                    }
                }
            ],
            order: [
                [0, "desc"],
                [1, "desc"]
            ]
        });
    }

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

// SELECT2
    // category parent select
    if ($("#CategoryParent").length > 0) {
        $("#CategoryParent").select2();
    }

    // tag select 
    if ($("#mytag").length > 0) {
        $("#mytag").select2({
            tags: true
        });
    }
// END SELECT2

// TINYMCE
    if ($('textarea.mytextarea').length > 0) {
        tinymce.init({ 
            selector:'textarea.mytextarea',
            image_caption: true,
            height: 500,
            relative_urls:false,
            theme: 'modern',
            plugins: [
            'advlist autolink lists link image charmap print preview hr anchor pagebreak',
            'searchreplace wordcount visualblocks visualchars code fullscreen',
            'insertdatetime media nonbreaking save table contextmenu directionality',
            'emoticons template paste textcolor colorpicker textpattern imagetools codesample toc help',
            ],
            toolbar1: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
            toolbar2: 'print preview media | forecolor backcolor emoticons | codesample help',
            image_advtab: true,
            templates: [
            { title: 'Test template 1', content: 'Test 1' },
            { title: 'Test template 2', content: 'Test 2' }
            ],
            content_css: [
            '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
            '//www.tinymce.com/css/codepen.min.css',
            '{{ asset("css/textarea.css") }}'
            ]
        });
    }
// END TINYMCE
});

function cancelDelete(){
    clearTimeout(timeOutId);
    $('#canceldelete').hide();
    $('.table-overlay').hide();
};

// date time picker for date published
if ($(".post-datetime").length > 0) {
    $(function () {
        $('.post-datetime').datetimepicker({
            format: "yyyy-mm-dd hh:ii",
            autoclose: true,
            todayBtn: true,
            pickerPosition: "bottom-left"
        });
    });
}

function load_category(){
    $.ajax({
        type: "GET",
        url: "/admin/blog/get-category-post/",
        success: function(msg){
            $('.category-wrap ul').html(msg);
        },
        error: function(err){
            console.log(err);
        }
    });
}

function load_category_parent(){
    $.ajax({
        type: "GET",
        url: "/admin/blog/get-category-parent/",
        success: function(msg){
            $('#CategoryParent').html(msg);
        },
        error: function(err){
            console.log(err);
        }
    });
}

$('.category-wrap').ready(load_category());
$('#CategoryParent').ready(load_category_parent());

// add category on post ajax function
$('#add_category_button').on('click', function add_category(){
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

        load_category();
        load_category_parent();
        $('input[name=category_name]').val('');
        $('select[name=category_parent]').removeAttr('selected');
    } else {    
        // do nothing
    }
});