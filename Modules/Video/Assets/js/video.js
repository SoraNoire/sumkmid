$(document).ready(function() {
// DATATABLES CONFIG
    // video category table 
    if ($("#video #VideoCategoryTable").length > 0) {
        $("#video #VideoCategoryTable").DataTable({
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
                        return '<a href="/admin/blog/category/'+row.id+'/edit">Edit</a> | <a onclick="return confirm(\'Delete Category?\');" href="/admin/blog/category/'+row.id+'/remove">Delete</a>';
                    }
                },
                    {
                    "targets": 0,
                    "data": 'name',
                    "render": function ( data, type, row ) {
                            return '<a href="/admin/blog/category/'+row.id+'/edit">'+data+'</a>';
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
    if ($("#video #table-videos").length > 0) {
        $("#video #table-videos").DataTable({
            "ajax": $.fn.dataTable.pipeline( {
                url: '/admin/blog/video/ajaxvideos',
                pages: 5 // number of pages to cache
            } ),
            "processing": true,
            "serverSide": true,
            "stateSave":true,
            bSortable: true,
            "columns": [
                { "data": "title" },
                { "data": "author" },
                { "data": "published_date" },
                { "data": "id" },
            ],
            "columnDefs": [ {
                    "targets": -1,
                    "data": 'id',
                    "render": function ( data, type, row ) {
                        return '<a href="/admin/blog/video/'+row.id+'/edit">Edit</a> | <a onclick="return confirm(\'Delete Video?\');" href="/admin/blog/video/'+row.id+'/remove">Hapus</a>';
                    }
                },
                    {
                    "targets": 0,
                    "data": 'title',
                    "render": function ( data, type, row ) {
                        return '<a href="/admin/blog/video/'+row.id+'/edit">'+data+'</a>';
                    }
                }
            ],
            order: [
                [2, "desc"]
            ]
        });
    }

    // tag table
    if ($("#video #VideoTagTable").length > 0) {
        $("#video #VideoTagTable").DataTable({
            "ajax": $.fn.dataTable.pipeline( {
                url: '/admin/blog/ajaxtags',
                pages: 5 // number of pages to cache
            } ),
            "processing": true,
            "serverSide": true,
            "stateSave":true,
            "columns": [
                { "data": "name" },
                { "data": "created_date" },
                { "data": "id" },
            ],
            "columnDefs": [ {
                    "targets": -1,
                    "data": 'id',
                    "render": function ( data, type, row ) {
                        return '<a href="/admin/blog/tag/'+row.id+'/edit">Edit</a> | <a onclick="return confirm(\'Delete Tag?\');" href="/admin/blog/tags/'+row.id+'/delete">Hapus</a>';
                    }
                },
                    {
                    "targets": 0,
                    "data": 'title',
                    "render": function ( data, type, row ) {
                        return '<a href="/admin/blog/tag/'+row.id+'/edit">'+data+'</a>';
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
    var id = $('meta[name="item-id"]').attr('content');
    $.ajax({
        type: "GET",
        url: "/admin/blog/ajaxcategories",
        success: function(msg){
            var _el = '';
            for(i=0;i<msg.data.length;i++){
                var d = msg.data[i];
                _el += "<li><label><input name='categories[]' type='checkbox' value='"+d.id+"'>"+d.name+"</label></li>";
            }
            $('#video .category-wrap ul').html(_el);
        },
        error: function(err){
            console.log(err);
        }
    });
}

function load_video_category_parent(){
    var id = $('meta[name="category-id"]').attr('content');
    $.ajax({
        type: "GET",
        url: "/admin/blog/video/ajaxparentcat/"+id,
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
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: "/admin/blog/video/addcat",
            data: {'name':n, 'parent':p},
            success: function(msg){
                console.log(msg);
            },
            error: function(err){
                console.log(err);
            }
        });

        load_video_category();
        // load_video_category_parent();
        $('input[name=category_name]').val('');
        $('select[name=category_parent]').removeAttr('selected');
    } else {    
        // do nothing
    }
});