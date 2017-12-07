// DATATABLES CONFIG
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
                    return '<a href="/admin/blog/event/'+row.id+'/view">Edit</a> | <a onclick="return confirm(\'Delete Event?\');" href="/admin/blog/event/'+row.id+'/remove">Hapus</a>';
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
// END DATATABLES

// add category on post ajax function
$('#event-category .add_category_button').on('click', function add_category(){
    var n = $('input[name=category_name]').val();
    var token = $('input[name=c_token]').val();
    if (n != '') {
        $.ajax({
            type: "POST",
            url: "/admin/blog/category/ajaxadd",
            data:{
              "_token": token,
              name: n, // Second add quotes on the value.
              catjax: true,
            },
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

function htm_check(){
    var a = $('input[name=htm_free]').is(':checked');
    if (a) {
        $('#htm-parent input').attr('disabled', 'disabled');
        $('#htm-parent button').attr('disabled', 'disabled');
    } else {
        $('#htm-parent input').removeAttr('disabled');
        $('#htm-parent button').removeAttr('disabled');
    }
}

if ($('input[name=htm_free]').length > 0) {
    $(document).ready(htm_check());
    $('input[name=htm_free]').on('change', function(){
        htm_check();        
    });
}

function add_htm(){
    var htm_id = parseInt($("#htm-parent .row:last-child").attr('data-id'));
    htm_id += 1;
    $('#htm-parent').append('<div class="row" id="htm-'+htm_id+'" data-id="'+htm_id+'"><div class="form-group col-sm-6"><label>Nominal</label><div class="input-group"><span class="input-group-addon">Rp</span><input value="" class="form-control" type="text" name="htm[]"></div></div><div class="form-group col-sm-6"><label>Label</label><div class="input-group"><input type="text" name="htm_label[]" class="form-control"><span class="input-group-btn"><button class="btn btn-info" class="add-htm" onclick="add_htm()" type="button">+</button></span><span class="input-group-btn"><button class="btn btn-warning" class="remove-htm" onclick="remove_htm(\'htm-'+htm_id+'\')" type="button">-</button></span></div></div></div>');
}

function remove_htm(id){
    $('#'+id).remove();
}
