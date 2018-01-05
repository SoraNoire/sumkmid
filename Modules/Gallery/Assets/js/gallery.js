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
