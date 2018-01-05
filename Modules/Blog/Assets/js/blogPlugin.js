(function($) {
    $.blogPlugin = function(options) {
        if ( typeof(options) == "undefined" || options == null ) { options = {}; };

        var jp = {
            options: $.extend({
                adminUrl: '/admin/blog/'
            }, options),
            openModal: function( modalId ) {
                $("html, body").animate({
                    scrollTop: 0
                }, 500);
                $(".overlay").fadeIn(), $(modalId).fadeIn();
            },
            closeModal: function( ) {
                $(".overlay").fadeOut(), $(".custom-modal").fadeOut()
            },
            uploadFile: function(formdata, type) {
                $('.dataTables_processing').show();
                
                var act_url = '/';
                if (type == 'file') {
                    var act_url = jp.options.adminUrl+'store-file';
                } else if (type == 'media') {
                    var act_url = jp.options.adminUrl+'store-media';
                }

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: act_url,
                    processData: false,
                    contentType: false,
                    data: formdata,
                    success: function(msg){
                        $(".mediatable").DataTable().ajax.reload(null, false);
                        console.log(msg);
                    },
                    error: function(err){
                        $(".mediatable").DataTable().ajax.reload(null, false);
                        var obj = err.responseJSON;
                        alert(Object.values(obj)[0].toString());
                    },
                    always: function(a){
                        $(".filestable").DataTable().ajax.reload(null, false);
                    }
                });

                $('.dataTables_processing').hide();
            },
            deleteFile: function(id, type) {
                $('#canceldelete').show();
                $('.dataTables_processing').show();

                var act_url = '/';
                if (type == 'file') {
                    act_url = jp.options.adminUrl+'delete-file/';
                } else if (type == 'media') {
                    act_url = jp.options.adminUrl+'delete-media/';
                } 

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "GET",
                    url: act_url+id,
                    processData: false,
                    contentType: false,
                    success: function(msg){
                        $(".mediatable").DataTable().ajax.reload(null, false);
                    },
                    error: function(err){
                        $(".mediatable").DataTable().ajax.reload(null, false);
                        alert('delete error');
                    }
                });

                $('#canceldelete').hide();
                $('.dataTables_processing').hide();

            },
            loadListCategory: function() {
                var id = $('meta[name="item-id"]').attr('content');
                $.ajax({
                    type: "GET",
                    url: jp.options.adminUrl+"get-category-post/"+id,
                    success: function(msg){
                        $('.category-wrap ul').html(msg);
                    },
                    error: function(err){
                        console.log(err);
                    }
                });
            },
            loadListParentCategory: function() {
                var id = $('meta[name="category-id"]').attr('content');
                $.ajax({
                    type: "GET",
                    url: jp.options.adminUrl+"get-category-parent/"+id,
                    success: function(msg){
                        $('.category-parent').html(msg);
                    },
                    error: function(err){
                        console.log(err);
                    }
                });
            },
            addCategoryLoad: function() {
                jp.loadListParentCategory();
                jp.loadListCategory();
            }
        };

        return {
            openModal: jp.openModal,
            closeModal: jp.closeModal,
            uploadFile: jp.uploadFile,
            deleteFile: jp.deleteFile,
            loadListCategory: jp.loadListCategory,
            loadListParentCategory: jp.loadListParentCategory,
            addCategoryLoad: jp.addCategoryLoad
        };
    };
})(jQuery);