$(document).ready(function() {
// DATATABLES CONFIG
    // video category table 
    if ($("#VideoCategoryTable").length > 0) {
        $("#VideoCategoryTable").DataTable({
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
    if ($("#myTableVideos").length > 0) {
        $("#myTableVideos").DataTable({
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
    if ($("#VideoTagTable").length > 0) {
        $("#VideoTagTable").DataTable({
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