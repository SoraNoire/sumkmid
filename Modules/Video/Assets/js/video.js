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
