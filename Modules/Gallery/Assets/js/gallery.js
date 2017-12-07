// DATATABLES CONFIG
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
            { "data": "published_date" },
            { "data": "id" },
        ],
        "columnDefs": [ {
                "targets": -1,
                "data": 'id',
                "render": function ( data, type, row ) {
                    return '<a href="/admin/blog/gallery/'+row.id+'/view">Edit</a> | <a onclick="return confirm(\'Delete gallery?\');" href="/admin/blog/gallery/'+row.id+'/remove">Hapus</a>';
                }
            },
                {
                "targets": 0,
                "data": 'title',
                "render": function ( data, type, row ) {
                    return '<a href="/admin/blog/gallery/'+row.id+'/view">'+data+'</a>';
                }
            }
        ],
        order: [
            [2, "desc"]
        ]
    });
}

// tag table
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
            [0, "desc"],
            [1, "desc"]
        ]
    });
}
// END DATATABLES

// gallery form
// gallery table
if ($("#MediaGallery").length > 0) {
    $("#MediaGallery").DataTable({
        "ajax":  {
            url: '/admin/blog/get-media'
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

// select image for galery
if ($("#MediaGallery").length > 0) {
    $('#MediaGallery tbody').on( 'click', 'tr', function () {
        $(this).toggleClass('selected');
        $('#count-galeri').html( $("#MediaGallery").DataTable().rows('.selected').data().length +' row(s) selected' );
        $('#selected-image-galeri').show();
    });
}

// select selected image for galery
if ($("#select-image-galeri").length > 0) {
    $('#select-image-galeri').on('click', function(){
        var images = $.map($("#MediaGallery").DataTable().rows('.selected').data(), function (item) {
            return item.name
        });
        var ids = $.map($("#MediaGallery").DataTable().rows('.selected').data(), function (item) {
            return item.id
        });

        images.forEach(function(image, index) {  
            $("#selected-images").append("<div id='img-"+ids[index]+"' class='image'> <input id='input-"+ids[index]+"' type='hidden' name='gallery_images[]' class='form-control' value='"+ids[index]+"'> <a class='close'> <i class='fa fa-times' aria-hidden='true'></i> </a> <image src='"+mediaPath+"/"+image.split('.').join('-800.')+"'> </div>");
        });

        $(".overlay").fadeOut(), $(".custom-modal").fadeOut(), $('#count-galeri').html(''), $('#MediaGallery').find('tr').removeClass('selected')
        
    });
}

// remove selected image galery
if ($('#selected-images').length > 0) {
    $('#selected-images').on('click', '.close', function(){
        $(this).parents('.image').remove();
    }); 
}
