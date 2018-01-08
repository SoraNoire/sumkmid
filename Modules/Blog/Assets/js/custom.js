type=["","info","success","warning","danger"],notif={showNotification:function(o,e,n,i){color=i,$.notify({message:n},{type:type[color],timer:1000,placement:{from:o,align:e}})}};
mediaPath = 'https://s3-ap-southeast-1.amazonaws.com/mdirect/shbtm/media';
filePath = 'https://s3-ap-southeast-1.amazonaws.com/mdirect/shbtm/files';
var timeOutId;

var jPlugin = $.blogPlugin();

$('#site-setting').on('click', '.program-media', function(){
    jPlugin.openModal('.custom-modal');
    tujuan = $('#'+$(this).attr('data-tujuan'));
});

$("#browse_media_post").click(function() {
    jPlugin.openModal('.media-modal');
});

$("#browse_fimg_post").click(function() {
    jPlugin.openModal('.fimg-modal');
    tujuan = $('#'+$(this).attr('data-tujuan'));
});

$("#browse_file_post").click(function() {
    jPlugin.openModal('.file-modal');
});

$(".close-modal, .overlay").click(function() {
    jPlugin.closeModal();
});

// fungsi upload image
$('#uploadmedia').on('change', function(e){
    e.preventDefault();
    var fd = new FormData($("#actuploadmedia")[0]);
    $('.dataTables_processing').show();
    timeOutId = setTimeout(jPlugin.uploadFile(fd, 'media'), 1000);
});

// fungsi upload fimg
$('#uploadfimg').on('change', function add_media(e){
    e.preventDefault();
    var fd = new FormData($("#actuploadfimg")[0]);
    $('.dataTables_processing').show();
    timeOutId = setTimeout(jPlugin.uploadFile(fd, 'media'), 1000);
});

// fungsi upload file
$('#fileUpload').on('change', function add_file(e){
    e.preventDefault();
    var fd = new FormData($("#fileupload-form")[0]);
    $('.dataTables_processing').show();
    timeOutId = setTimeout(jPlugin.uploadFile(fd, 'file'), 1000);
});

// fungsi delete media
function delete_media(id){
    timeOutId = setTimeout(jPlugin.deleteFile(id, 'media'), 2000);
};

// fungsi delete file
function delete_file(id){
    timeOutId = setTimeout(jPlugin.deleteFile(id, 'file'), 2000);
};

function select_media(e){
    var a = $("<input>");
    var b = $(e).text();
    $("body").append(a);
    a.val(b).select();
    document.execCommand("copy");
    a.remove();
    jPlugin.closeModal();
}

function select_fimg(e){
    $('.preview-fimg-wrap').show();
    var a = $('.preview-fimg');
    var c = $(e).text();
    a.css('background-image', 'url('+c+')');
    tujuan.val(c);
    jPlugin.closeModal();
}

function select_input_media(e){
    var c = $(e).text();
    tujuan.val(c);
    var name = 'data-'+tujuan.attr('name');
    tujuan.parents('li.dd-item').attr(name, c);
    jPlugin.closeModal();
}

function remove_fimg(){
    $('.preview-fimg-wrap').hide();
    var a = $('.preview-fimg');
    var b = $('#featured_img');
    a.css('background-image', 'url()');
    b.val('');
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

function cancelDelete(){
    clearTimeout(timeOutId);
    $('#canceldelete').hide();
    $('.table-overlay').hide();
};

if ($('.category-wrap').length > 0) {
    $('.category-wrap').ready(jPlugin.loadListCategory());
}

if ($('.category-parent').length > 0) {
    $('.category-parent').ready(jPlugin.loadListParentCategory());
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
                jPlugin.addCategoryLoad();
            },
            error: function(err){
                console.log(err);
                jPlugin.addCategoryLoad();
            },
            always: function(a){
                jPlugin.addCategoryLoad();
            }
        });

        $('input[name=category_name]').val('');
        $('select[name=category_parent]').removeAttr('selected');
    } else {    
        // do nothing
    }
});

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

if ($('#program-structure')) {
    $('#program-structure').nestable({ group: 1, maxDepth: 1 });
    check_program();
}

$('#program-structure').on('click', '.remove_item', function(e){
    e.preventDefault();
    $(this).parents('li').remove();
    check_program();
});

$('#program-structure').on('keyup change', 'input', function(){
    var name = 'data-'+$(this).attr('name');
    var val = $(this).val();
    $(this).parents('li.dd-item').attr(name, val);
});

$('#program-structure').on('keyup change', 'textarea', function(){
    var name = 'data-'+$(this).attr('name');
    var val = $(this).val();
    $(this).parents('li.dd-item').attr(name, val);
});

$('#setting-program').on('click', '.save-program', function(){
    var data = JSON.stringify($('#program-structure').nestable('serialize'));
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {program :data},
        type: 'POST',
        url: '/admin/blog/save-program',
        success: function(response){
            notif.showNotification("top","right",'Berhasil Disimpan','2');
        },
        error: function(err){
            notif.showNotification("top","right",'Gagal Disimpan','4');
        }
    });
});

$('#setting-program').on('click', '.add-program', function(){
    var id = get_program_id();
    id += 1;
    $('#program-structure .dd-list:first-child').append('<li class="dd-item" data-id="'+id+'" data-title="" data-description="" data-logo="" data-background=""><div class="dd-handle dd3-handle">Drag</div><div class="program-item dd3-content panel panel-default" id="program'+id+'"><div class="program-title"><span>Program '+id+'</span><a data-toggle="collapse" data-parent="#program-structure" href="#program-collapse-'+id+'"><i style="float: right;" class="fa fa-caret-down" aria-hidden="true"></i></a></div><div id="program-collapse-'+id+'" class="collapse program-collapse panel panel-default"><div class="form-group"><label>Title</label><input class="form-control" type="text" name="title" value=""><label>URl</label><input class="form-control" type="text" name="url" value=""><label>Logo</label><div class="input-group"><input class="form-control" type="text" name="logo" value="" readonly="readonly" id="program-logo'+id+'"><span class="input-group-btn"><button class="btn btn-default program-media" type="button" data-tujuan="program-logo'+id+'">Browse media</button></span></div><label>Background</label><div class="input-group"><input class="form-control" type="text" name="background" value="" readonly="readonly" id="program-bg'+id+'"><span class="input-group-btn"><button class="btn btn-default program-media" type="button" data-tujuan="program-bg'+id+'">Browse media</button></span></div><label>Description</label><textarea name="description" class="form-control"></textarea></div><a href="#" class="remove_item">Remove</a></div></div></li>');
    check_program();
});

function check_program(){
    var a = $('#program-structure').find('li.dd-item').length;
    if (a >= 5) {
        $('#setting-program .add-program').remove();
    } else if (a <= 5) {
        if ($('#setting-program .add-program').length <= 0) {
            $('#setting-program .panel-body').prepend('<button type="button" class="btn btn-info pull-left add-program">Add Program +</button>');
        }
    }
}

function get_program_id(){
    var id = 0;
    if ($("#program-structure li:last-child").length > 0) {
        id = parseInt($("#program-structure li:last-child").attr('data-id'));
    }   
    return id;
}

function check_post(){
    var a = $('#post_check').is(':checked');
    if (a) {
        $('#post_category').removeAttr('disabled');
    } else {
        $('#post_category').attr('disabled', 'disabled');
    }
}

if ($('#post_check').length > 0) {
    $('#post_check').ready(check_post());

    $('#post_check').on('change', function(){
        check_post();
    });
}
