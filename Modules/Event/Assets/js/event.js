$(document).ready(function() {
// DATATABLES CONFIG
    // event category table 
    if ($("#EventCategoryTable").length > 0) {
        $("#EventCategoryTable").DataTable({
            "ajax": $.fn.dataTable.pipeline( {
                url: '/admin/blog/event/get-category',
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
                        return '<a href="/admin/blog/event/edit-category/'+row.id+'">Edit</a> | <a onclick="return confirm(\'Delete Category?\');" href="/admin/blog/event/delete-category/'+row.id+'">Delete</a>';
                    }
                },
                    {
                    "targets": 0,
                    "data": 'name',
                    "render": function ( data, type, row ) {
                        return '<a href="/admin/blog/event/edit-category/'+row.id+'">'+data+'</a>';
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
    if ($("#myTableEvent").length > 0) {
        $("#myTableEvent").DataTable({
            "ajax": $.fn.dataTable.pipeline( {
                url: '/admin/blog/event/get-events',
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
                        return '<a href="/admin/blog/event/edit-event/'+row.id+'">Edit</a> | <a onclick="return confirm(\'Delete Event?\');" href="/admin/blog/event/delete-event/'+row.id+'">Hapus</a>';
                    }
                },
                    {
                    "targets": 0,
                    "data": 'title',
                    "render": function ( data, type, row ) {
                        return '<a href="/admin/blog/event/edit-event/'+row.id+'">'+data+'</a>';
                    }
                }
            ],
            order: [
                [0, "desc"],
                [2, "desc"]
            ]
        });
    }
// END DATATABLES
});

// add category on post ajax function
$('#event-category .add_category_button').on('click', function add_category(){
    var n = $('input[name=category_name]').val();
    if (n != '') {
        $.ajax({
            type: "GET",
            url: "/admin/blog/event/add-category-event/"+n,
            success: function(msg){
                $('#event-category .category-wrap ul').append(msg);
            },
            error: function(err){
                console.log(err);
            }
        });

        $('input[name=category_name]').val('');
    } else {    
        // do nothing
    }
});

function select_event_type(){
    var event_type = $('#event-type').val();
    if (event_type == 'offline') {
        $('#event-setting .event-type-offline').show();
        $('#event-setting .event-type-online').hide();
    } else if (event_type == 'online') {
        $('#event-setting .event-type-offline').hide();
        $('#event-setting .event-type-online').show();
    }
}

$('#event-setting').on('load', select_event_type());