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
            url: '/admin/blog/get-media',
            type:   "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
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
              return '<img style="width: 100px; max-height: 100px;" src="'+mediaPath+'/'+data.split('.').join('-300.')+'">';
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
    $("#MediaPost").DataTable({
        "ajax":  {
            url: '/admin/blog/get-media',
            type:   "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
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
                    return '<div onclick="delete_media(\''+data+'\')" id="delete_media_post" class="btn btn-round btn-fill btn-danger">Delete</div> <div onclick="select_media(\'#'+data+'\')" id="select_media" class="btn btn-round btn-fill btn-success">Copy Media</div> <p style="display:none;" id="'+data+'">'+mediaPath+'/'+row.name.split('.').join('-800.')+'</p>';
                }
            },
                {
                "targets": 0,
                "data": 'title',
                "render": function ( data, type, row ) {
              return '<img style="width: 100px; max-height: 100px;" src="'+mediaPath+'/'+data.split('.').join('-300.')+'">';
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
    $("#FeaturedImg").DataTable({
        "ajax":  {
            url: '/admin/blog/get-media',
            type:   "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
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
                    return '<div onclick="delete_media(\''+data+'\')" id="delete_media_post" class="btn btn-round btn-fill btn-danger">Delete</div> <div onclick="select_fimg(\'#'+data+'\')" id="select_media" class="btn btn-round btn-fill btn-success">Select</div> <p style="display:none;" id="'+data+'">'+mediaPath+'/'+row.name.split('.').join('-800.')+'</p>';
                }
            },
                {
                "targets": 0,
                "data": 'title',
                "render": function ( data, type, row ) {
              return '<img style="width: 100px; max-height: 100px;" src="'+mediaPath+'/'+data.split('.').join('-300.')+'">';
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
                    return '<a href="/admin/blog/page/'+row.id+'/view">Edit</a> | <a onclick="return confirm(\'Delete Page?\');" href="/admin/blog/page/'+row.id+'/remove" style="color: #d9534f;">Hapus</a>';
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
                    return '<a href="/admin/blog/edit-tag/'+row.id+'">Edit</a> | <a onclick="return confirm(\'Delete Tag?\');" href="/admin/blog/delete-tag/'+row.id+'" style="color: #d9534f;">Hapus</a>';
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
                    return '<a href="/admin/blog/tag/'+row.id+'/view">Edit</a> | <a onclick="return confirm(\'Delete Tag?\');" href="/admin/blog/tag/'+row.id+'/remove" style="color: #d9534f;">Hapus</a>';
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

// post file modal table
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

// post trash
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

// event category table 
if ($("#EventCategoryTable").length > 0) {
    $("#EventCategoryTable").DataTable({
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
                    return '<a href="/admin/blog/category/'+row.id+'/view">Edit</a> | <a onclick="return confirm(\'Delete Category?\');" href="/admin/blog/category/'+row.id+'/delete">Delete</a>';
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
            [0, "desc"],
            [1, "desc"]
        ]
    });
} 

// event table
if ($("#event-table").length > 0) {
    $("#event-table").DataTable({
        "ajax": $.fn.dataTable.pipeline( {
            url: '/admin/blog/event/ajaxevents',
            pages: 5 // number of pages to cache
        } ),
        "processing": true,
        "serverSide": true,
        "stateSave":true,
        bSortable: true,
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
                    return '<a href="/admin/blog/event/'+row.id+'/view">Edit</a> | <a onclick="return confirm(\'Delete Event?\');" href="/admin/blog/event/'+row.id+'/remove" style="color: #d9534f;">Hapus</a>';
                }
            },
                {
                "targets": 0,
                "data": 'title',
                "render": function ( data, type, row ) {
                    return '<a href="/admin/blog/event/'+row.id+'/view">'+data+'</a>';
                }
            }
        ],
        order: [
            [2, "desc"]
        ]
    });
}

// gallery category table 
if ($("#gallery #GalleryCategoryTable").length > 0) {
    $("#gallery #GalleryCategoryTable").DataTable({
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
                    return '<a href="/admin/blog/'+row.id+'/view">Edit</a> | <a onclick="return confirm(\'Delete Category?\');" href="/admin/blog/'+row.id+'/remove">Delete</a>';
                }
            },
                {
                "targets": 0,
                "data": 'name',
                "render": function ( data, type, row ) {
                    return '<a href="/admin/blog/'+row.id+'/view">'+data+'</a>';
                }
            }
        ],
        order: [
            [0, "desc"],
            [1, "desc"]
        ]
    });
} 

// gallery table
if ($("#table-gallery").length > 0) {
    $("#table-gallery").DataTable({
        "ajax": $.fn.dataTable.pipeline( {
            url: '/admin/blog/gallery/ajaxgalleries',
            pages: 5 // number of pages to cache
        } ),
        "processing": true,
        "serverSide": true,
        "stateSave":true,
        "columns": [
            { "data": "title" },
            { "data": "author_name" },
            { "data": "id" },
            { "data": "published_date" },
            { "data": "id" },
        ],
        "columnDefs": [ {
                "targets": -1,
                "data": 'id',
                "render": function ( data, type, row ) {
                    return '<a href="/admin/blog/'+row.post_type+'/'+row.id+'/edit">Edit</a> | <a onclick="return confirm(\'Delete '+row.post_type+'?\');" href="/admin/blog/'+row.post_type+'/'+row.id+'/remove" style="color: #d9534f;">Hapus</a>';
                }
            },
                {
                "targets": 0,
                "data": 'title',
                "render": function ( data, type, row ) {
                    return '<a href="/admin/blog/'+row.post_type+'/'+row.id+'/edit">'+data+'</a>';
                }
            },
                {
                "targets": 2,
                "data": 'title',
                "render": function ( data, type, row ) {
                    return row.gallery_type;
                }
            }
        ],
        order: [
            [3, "desc"]
        ]
    });
}

// gallery tag table
if ($("#GalleryTagTable").length > 0) {
    $("#GalleryTagTable").DataTable({
        "ajax": $.fn.dataTable.pipeline( {
            url: '/admin/blog/tags/ajaxtags',
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
                    return '<a href="/admin/blog/tag/'+row.id+'/view">Edit</a> | <a onclick="return confirm(\'Delete Tag?\');" href="/admin/blog/tag/'+row.id+'/remove" style="color: #d9534f;">Hapus</a>';
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
            [0, "desc"],
            [1, "desc"]
        ]
    });
}

// gallery form
// gallery table
if ($("#MediaGallery").length > 0) {
    $("#MediaGallery").DataTable({
        "ajax":  {
            url: '/admin/blog/get-media',
            type:   "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        },
        "processing": true,
        "serverSide": true,
        "stateSave":true,
        "columns": [
            { "data": "id" },
            { "data": "name" },
            { "data": "name" },
            { "data": "name" },
            { "data": "created_at" },
        ],
        "columnDefs": [
            {
                "targets":  2 ,
                "data": 'name',
                "render": function ( data, type, full, meta ) {
                    return '<img style="width: 100px; max-height: 100px;" src="'+mediaPath+'/'+data.split('.').join('-300.')+'">';
                }
            },
            {
                "targets": 0,
                "visible": false,
                "searchable": false
            },
            {
                "targets": 1,
                "visible": false,
                "searchable": false,
                "data": 'name',
                "render": function ( data, type, full, meta ) {
                    return mediaPath+'/'+data.split('.').join('-800.');
                }
            },
        ],
        order: [
            [3, "desc"],
            [4, "desc"]
        ]
    });
}

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
            { "data": "author_name" },
            { "data": "published_date" },
            { "data": "id" },
        ],
        "columnDefs": [ {
                "targets": -1,
                "data": 'id',
                "render": function ( data, type, row ) {
                    return '<a href="/admin/blog/video/'+row.id+'/edit">Edit</a> | <a onclick="return confirm(\'Delete Video?\');" href="/admin/blog/video/'+row.id+'/remove" style="color: #d9534f;">Hapus</a>';
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

// video tag table
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
                    return '<a href="/admin/blog/tag/'+row.id+'/edit">Edit</a> | <a onclick="return confirm(\'Delete Tag?\');" href="/admin/blog/tags/'+row.id+'/delete" style="color: #d9534f;">Hapus</a>';
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

if ($("#myTableslider").length > 0) {
  $("#myTableslider").DataTable({
      order: [
          [0, "asc"],
          [2, "desc"]
      ]
  });
}

// slider image table
if ($("#sliderImg").length > 0) {
  $("#sliderImg").DataTable({
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
          return '<img style="width: 100px; max-height: 100px;" src="'+mediaPath+'/'+data.split('.').join('-300.')+'">';
        }
      }
    ],
    order: [
      [0, "desc"],
      [2, "desc"]
    ]
  });
}

// image table for program
if ($("#programMedia").length > 0) {
  $("#programMedia").DataTable({
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
          return '<div onclick="delete_media(\''+data+'\')" id="delete_media_post" class="btn btn-round btn-fill btn-danger">Delete</div> <div onclick="select_input_media(\'#media-'+data+'\')" id="select_media" class="btn btn-round btn-fill btn-success">Select</div> <p style="display:none;" id="media-'+data+'">'+mediaPath+'/'+row.name+'</p>';
        }
      },
      {
        "targets": 0,
        "data": 'title',
        "render": function ( data, type, row ) {
          return '<img style="width: 100px; max-height: 100px;" src="'+mediaPath+'/'+data.split('.').join('-300.')+'">';
        }
      }
    ],
    order: [
      [0, "desc"],
      [2, "desc"]
    ]
  });
}
