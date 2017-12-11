mediaPath = 'https://s3-ap-southeast-1.amazonaws.com/mdirect/shbtm/media';
filePath = 'https://s3-ap-southeast-1.amazonaws.com/mdirect/shbtm/files';
var timeOutId;

var jPlugin = $.blogPlugin();

$("#browse_media_post").click(function() {
    jPlugin.openModal('.media-modal');
});

$("#browse_fimg_post").click(function() {
    jPlugin.openModal('.fimg-modal');
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
    timeOutId = setTimeout(jPlugin.uploadFile(fd, 'media'), 1000);
});

// fungsi upload fimg
$('#uploadfimg').on('change', function add_media(e){
    e.preventDefault();
    var fd = new FormData($("#actuploadfimg")[0]);
    timeOutId = setTimeout(jPlugin.uploadFile(fd, 'media'), 1000);
});

// fungsi upload file
$('#fileUpload').on('change', function add_file(e){
    e.preventDefault();
    var fd = new FormData($("#fileupload-form")[0]);
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
    var b = $('#featured_img');
    var c = $(e).text();
    a.css('background-image', 'url('+c+')');
    b.val(c);
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
