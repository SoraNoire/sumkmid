mediaPath = 'https://s3-ap-southeast-1.amazonaws.com/mdirect/shbtm/media';
filePath = 'https://s3-ap-southeast-1.amazonaws.com/mdirect/shbtmdev/files';
var timeOutId;
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
                    console.log(err);
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
                    console.log(err);
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
                alert('Invalid File Extension');
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
                console.log(err);
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
    

    // categories
    if ($("#table-categories").length > 0) {
        console.log('category');
        $("#table-categories").DataTable({
            "ajax": $.fn.dataTable.pipeline( {
                url: '/admin/blog/ajaxcategories',
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
                        return '<a href="/admin/blog/category/'+row.id+'/view">Edit</a> | <a onclick="return confirm(\'Delete Category?\');" href="/admin/blog/category/'+row.id+'/remove" style="color: #d9534f;">Delete</a>';
                    }
                },
                    {
                    "targets": 0,
                    "data": 'name',
                    "render": function ( data, type, row ) {
                        return '<a href="/admin/blog/category/'+row.id+'/view">'+data+'</a>';
                    }
                }
            ],
            order: [
                [1, "desc"]
            ]
        });
    }

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
                        return '<a href="/admin/blog/edit-category/'+row.id+'">Edit</a> | <a onclick="return confirm(\'Delete Category?\');" href="/admin/blog/delete-category/'+row.id+'" style="color: #d9534f;">Delete</a>';
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
    if ($("#posts-table").length > 0) {
        $("#posts-table").DataTable({
            "ajax": $.fn.dataTable.pipeline( {
                url: '/admin/blog/ajaxposts',
                pages: 5 // number of pages to cache
            } ),
            "processing": true,
            "serverSide": true,
            "stateSave":true,
            "columns": [
                { "data": "title" },
                { "data": "author_name" },
                { "data": "published_date" },
                { "data": "id" },
            ],
            "columnDefs": [ {
                    "targets": -1,
                    "data": 'id',
                    "render": function ( data, type, row ) {
                        return '<a href="/admin/blog/post/'+row.id+'/view">Edit</a> | <a onclick="return confirm(\'Delete Post?\');" href="/admin/blog/post/'+row.id+'/remove" style="color: #d9534f;">Hapus</a>';
                    }
                },
                    {
                    "targets": 0,
                    "data": 'title',
                    "render": function ( data, type, row ) {
                        return '<a href="/admin/blog/post/'+row.id+'/view">'+data+'</a>';
                    }
                }
            ],
            order: [
                [2, "desc"]
            ]
        });
    }

    // media table
    if ($("#MediaTable").length > 0) {
        $("#MediaTable").DataTable({
            "ajax": {
                url: '/admin/blog/get-media'
            },
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
    if ($("#pages-table").length > 0) {
        $("#pages-table").DataTable({
            "ajax": $.fn.dataTable.pipeline( {
                url: '/admin/blog/ajaxpages',
                pages: 5 // number of pages to cache
            } ),
            "processing": true,
            "serverSide": true,
            "stateSave":true,
            "columns": [
                { "data": "title" },
                { "data": "author_name" },
                { "data": "published_date" },
                { "data": "id" },
            ],
            "columnDefs": [ {
                    "targets": -1,
                    "data": 'id',
                    "render": function ( data, type, row ) {
                        return '<a href="/admin/blog/page/'+row.id+'/view">Edit</a> | <a onclick="return confirm(\'Delete Page?\');" href="/admin/blog/page/'+row.id+'/remove">Hapus</a>';
                    }
                },
                    {
                    "targets": 0,
                    "data": 'title',
                    "render": function ( data, type, row ) {
                        return '<a href="/admin/blog/page/'+row.id+'/view">'+data+'</a>';
                    }
                }
            ],
            order: [
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

    // table-tags

    if ($("#table-tags").length > 0) {
        $("#table-tags").DataTable({
            "ajax": $.fn.dataTable.pipeline( {
                url: '/admin/blog/ajaxtags',
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
                        return '<a href="/admin/blog/tag/'+row.id+'/view">Edit</a> | <a onclick="return confirm(\'Delete Tag?\');" href="/admin/blog/tag/'+row.id+'/remove">Hapus</a>';
                    }
                },
                    {
                    "targets": 0,
                    "data": 'title',
                    "render": function ( data, type, row ) {
                        return '<a href="/admin/blog/tag/'+row.id+'/view">'+data+'</a>';
                    }
                }
            ],
            order: [
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
    // basic select2
    if ($(".myselect2").length > 0) {
        $(".myselect2").select2();
    }

    // tag select 
    if ($(".mytag").length > 0) {
        $(".mytag").select2({
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
            ],
            setup: function(editor) {
              editor.addButton('mybutton', {
                type: 'menubutton',
                text: 'Shortcode',
                icon: false,
                menu: [{
                  text: 'Bold',
                  onclick: function() {
                    editor.insertContent('[b="bold"]Text here[/b]');
                  }
                }, {
                  text: 'Italic',
                  onclick: function() {
                    editor.insertContent('[i="italic"]Text here[/i]');
                  }
                }]
              });
            }
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
if ($(".datetimepicker").length > 0) {
    $(function () {
        $('.datetimepicker').datetimepicker({
            format: "yyyy-mm-dd hh:ii",
            autoclose: true,
            todayBtn: true,
            pickerPosition: "bottom-left"
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
        load_post_category_parent();
        $('input[name=category_name]').val('');
        $('select[name=category_parent]').removeAttr('selected');
    } else {    
        // do nothing
    }
});

// files table
if ($("#filesTable").length > 0) {
    $("#filesTable").DataTable({
        "ajax":  {
            url: '/admin/blog/get-files'
        },
        "processing": true,
        "serverSide": true,
        "stateSave":true,
        "columns": [
            { "data": "name" },
            { "data": "label" },
            { "data": "id" },
            { "data": "created_at" },
        ],
        "columnDefs": [
            {
                "targets": 2,
                "data": 'id',
                "render": function ( data, type, row ) {
                    return '<a href="/admin/blog/edit-file/'+data+'" id="edit_file_label" style="cursor: pointer;">Edit</a>';
                }
            }
        ],
        order: [
            [3, "desc"]
        ]
    });
}

if ($("#postFile").length > 0) {
    $("#postFile").DataTable({
        "ajax":  {
            url: '/admin/blog/get-files'
        },
        "processing": true,
        "serverSide": true,
        "stateSave":true,
        "columns": [
            { "data": "name" },
            { "data": "label" },
            { "data": "id" },
            { "data": "created_at" },
        ],
        "columnDefs": [
            {
                "targets": 2,
                "data": 'id',
                "render": function ( data, type, row ) {
                    return '<div onclick="delete_file(\''+data+'\')" id="delete_file_post" class="btn btn-round btn-fill btn-danger">Delete</div>';
                }
            }
        ],
        order: [
            [3, "desc"]
        ]
    });
}

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

if ($("#posts-trash").length > 0) {
    $("#posts-trash").DataTable({
        "ajax": $.fn.dataTable.pipeline( {
            url: '/admin/blog/ajaxtrashposts',
            pages: 5 // number of pages to cache
        } ),
        "processing": true,
        "serverSide": true,
        "stateSave":true,
        "columns": [
            { "data": "title" },
            { "data": "author_name" },
            { "data": "post_type" },
            { "data": "published_date" },
            { "data": "id" },
        ],
        "columnDefs": [
            {
                "targets": 4,
                "data": 'id',
                "render": function ( data, type, row ) {
                    return '<a href="/admin/blog/trash/'+data+'/restore">Restore</a> | <a onclick="return confirm(\'Delete Post?\');" href="/admin/blog/trash/'+data+'/delete" style="color: #d9534f;">Delete Permanently</a>';
                }
            }
        ],
        order: [
            [3, "desc"]
        ]
    });
}