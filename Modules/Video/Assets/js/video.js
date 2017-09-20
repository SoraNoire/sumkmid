$(document).ready(function() {
// DATATABLES CONFIG
    // video category table 
    if ($("#video #VideoCategoryTable").length > 0) {
        $("#video #VideoCategoryTable").DataTable({
            "ajax": $.fn.dataTable.pipeline( {
                url: '/admin/blog/video/get-category',
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
                        return '<a href="/admin/blog/video/edit-category/'+row.id+'">Edit</a> | <a onclick="return confirm(\'Delete Category?\');" href="/admin/blog/video/delete-category/'+row.id+'">Delete</a>';
                    }
                },
                    {
                    "targets": 0,
                    "data": 'name',
                    "render": function ( data, type, row ) {
                        return '<a href="/admin/blog/video/edit-category/'+row.id+'">'+data+'</a>';
                    }
                }
            ],
            order: [
                [0, "desc"],
                [1, "desc"]
            ]
        });
    } 

    // videos table
    if ($("#video #myTableVideos").length > 0) {
        $("#video #myTableVideos").DataTable({
            "ajax": $.fn.dataTable.pipeline( {
                url: '/admin/blog/video/get-videos',
                pages: 5 // number of pages to cache
            } ),
            "processing": true,
            "serverSide": true,
            "stateSave":true,
            bSortable: true,
            "columns": [
                { "data": "title" },
                { "data": "author" },
                { "data": "published_at" },
                { "data": "id" },
            ],
            "columnDefs": [ {
                    "targets": -1,
                    "data": 'id',
                    "render": function ( data, type, row ) {
                        return '<a href="/admin/blog/video/edit-video/'+row.id+'">Edit</a> | <a onclick="return confirm(\'Delete Video?\');" href="/admin/blog/video/delete-video/'+row.id+'">Hapus</a>';
                    }
                },
                    {
                    "targets": 0,
                    "data": 'title',
                    "render": function ( data, type, row ) {
                        return '<a href="/admin/blog/video/edit-video/'+row.id+'">'+data+'</a>';
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
    if ($("#video #VideoTagTable").length > 0) {
        $("#video #VideoTagTable").DataTable({
            "ajax": $.fn.dataTable.pipeline( {
                url: '/admin/blog/video/get-tag',
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
                        return '<a href="/admin/blog/video/edit-tag/'+row.id+'">Edit</a> | <a onclick="return confirm(\'Delete Tag?\');" href="/admin/blog/video/delete-tag/'+row.id+'">Hapus</a>';
                    }
                },
                    {
                    "targets": 0,
                    "data": 'title',
                    "render": function ( data, type, row ) {
                        return '<a href="/admin/blog/video/edit-tag/'+row.id+'">'+data+'</a>';
                    }
                }
            ],
            order: [
                [0, "desc"],
                [1, "desc"]
            ]
        });
    }
// END DATATABLES
});

function load_video_category(){
    $.ajax({
        type: "GET",
        url: "/admin/blog/video/get-category-video/",
        success: function(msg){
            $('#video .category-wrap ul').html(msg);
        },
        error: function(err){
            console.log(err);
        }
    });
}

function load_video_category_parent(){
    $.ajax({
        type: "GET",
        url: "/admin/blog/video/get-category-parent/",
        success: function(msg){
            $('#video .category-parent').html(msg);
        },
        error: function(err){
            console.log(err);
        }
    });
}

if ($('#video .category-wrap').length > 0) {
    $('#video .category-wrap').ready(load_video_category());
}

if ($('#video .category-parent').length > 0) {
    $('#video .category-parent').ready(load_video_category_parent());
}

// add category on post ajax function
$('#video .add_category_button').on('click', function add_category(){
    var n = $('input[name=category_name]').val();
    var p = $('select[name=category_parent]').val();
    if (n != '') {
        $.ajax({
            type: "GET",
            url: "/admin/blog/video/add-category-post/"+n+"/"+p,
            success: function(msg){
                console.log(msg);
            },
            error: function(err){
                console.log(err);
            }
        });

        load_video_category();
        load_video_category_parent();
        $('input[name=category_name]').val('');
        $('select[name=category_parent]').removeAttr('selected');
    } else {    
        // do nothing
    }
});