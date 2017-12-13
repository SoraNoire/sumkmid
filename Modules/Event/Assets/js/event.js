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
    $('#htm-parent').append('<div class="row" id="htm-'+htm_id+'" data-id="'+htm_id+'"><div class="form-group col-sm-6"><label>Nominal</label><div class="input-group"><span class="input-group-addon">Rp</span><input value="" class="form-control" type="text" name="htm_nominal[]"></div></div><div class="form-group col-sm-6"><label>Label</label><div class="input-group"><input type="text" name="htm_label[]" class="form-control"><span class="input-group-btn"><button class="btn btn-info" class="add-htm" onclick="add_htm()" type="button">+</button></span><span class="input-group-btn"><button class="btn btn-warning" class="remove-htm" onclick="remove_htm(\'htm-'+htm_id+'\')" type="button">-</button></span></div></div></div>');
}

function remove_htm(id){
    $('#'+id).remove();
}
