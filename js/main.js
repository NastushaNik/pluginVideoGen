var all_title_selected = '';

var p = 1;

var ui = jQuery('.um-profile-photo').data('user_id') || mup_config.ui;

jQuery(function(){

    jQuery('#my-secs').justifiedGallery({

        rowHeight : 300,

        lastRow : 'justify',

        margins : 20

    });

    jQuery('#tag-secs').justifiedGallery({

        rowHeight : 300,

        lastRow : 'justify',

        margins : 20

    });    

    jQuery('#all-secs').justifiedGallery({

        rowHeight : 300,

        lastRow : 'nojustify',

        margins : 20,

    });

    jQuery('#my-latest-secs').justifiedGallery({

        rowHeight : 300,

        lastRow : 'nojustify',

        margins : 20,

    });
    jQuery('#my-best_raiting-secs').justifiedGallery({

        rowHeight : 300,

        lastRow : 'nojustify',

        margins : 20,

    });

    jQuery('#my-best_raiting-home').justifiedGallery({

        rowHeight : 300,

        lastRow : 'hide',

        margins : 0,

    });


    jQuery(window).scroll(function() {

        var asecs = jQuery('#my-best_raiting-secs');

        var bottomOffset = 50;

        if(jQuery(window).scrollTop() + jQuery(window).height() == jQuery(document).height() && !jQuery('body').hasClass('loading') && asecs.length != 0) {

            p++;

            var data = {

                action: 'vg_loading_best_raiting_masonry',

                page: p,

                nonce_code : mup_config.nonce

            };

        

            jQuery.ajax({

                url: mup_config.ajax_url,

                data: data,

                dataType: 'json',

                type: 'POST',

                beforeSend: function( xhr){

                    jQuery('body').addClass('loading');

                },

                success: function(resp) {  

                    console.log(resp);

                    if (resp.all_images != '') {   

                        asecs.append(resp.all_images);                           

                        asecs.justifiedGallery('norewind');

                        jQuery('body').removeClass('loading');

                    }       

                },

                error: function( jqXHR, textStatus, errorThrown ){

                         console.log('ОШИБКИ AJAX запроса: ' + textStatus );

                         console.log(jqXHR);

                         console.log(errorThrown);

                }

            });     

        }

    });

    jQuery(window).scroll(function() {

        var asecs = jQuery('#my-latest-secs');

        var bottomOffset = 50;

        if(jQuery(window).scrollTop() + jQuery(window).height() == jQuery(document).height() && !jQuery('body').hasClass('loading') && asecs.length != 0) {

            p++;

            var data = {

                action: 'vg_loading_latest_masonry',

                page: p,

                nonce_code : mup_config.nonce

            };

        

            jQuery.ajax({

                url: mup_config.ajax_url,

                data: data,

                dataType: 'json',

                type: 'POST',

                beforeSend: function( xhr){

                    jQuery('body').addClass('loading');

                },

                success: function(resp) {  

                    console.log(resp);

                    if (resp.all_images != '') {   

                        asecs.append(resp.all_images);                           

                        asecs.justifiedGallery('norewind');

                        jQuery('body').removeClass('loading');

                    }       

                },

                error: function( jqXHR, textStatus, errorThrown ){

                         console.log('ОШИБКИ AJAX запроса: ' + textStatus );

                         console.log(jqXHR);

                         console.log(errorThrown);

                }

            });     

        }

    });



    jQuery(window).scroll(function() {

        var asecs = jQuery('#all-secs');

        var bottomOffset = 50;

        if(jQuery(window).scrollTop() + jQuery(window).height() == jQuery(document).height() && !jQuery('body').hasClass('loading') && asecs.length != 0) {

            p++;

            var data = {

                action: 'vg_loading_next_masonry',

                page: p,

                ui: mup_config.ui,

                nonce_code : mup_config.nonce

            };

        

            jQuery.ajax({

                url: mup_config.ajax_url,

                data: data,

                dataType: 'json',

                type: 'POST',

                beforeSend: function( xhr){

                    jQuery('body').addClass('loading');

                },

                success: function(resp) {  

                    //console.log(resp);

                    if (resp.all_images != '') {   

                        asecs.append(resp.all_images);                           

                        asecs.justifiedGallery('norewind');

                        jQuery('body').removeClass('loading');

                    }       

                },

                error: function( jqXHR, textStatus, errorThrown ){

                         console.log('ОШИБКИ AJAX запроса: ' + textStatus );

                         console.log(jqXHR);

                         console.log(errorThrown);

                }

            });     

        }

    });

    jQuery(document).on('click', '.modal-header .close', function(){
        
       jQuery('.vg-page-video').get(0).pause();

    });

    // jQuery(document).on('click', '#vg-button-video-set', function(e){

    //     var vg_set = jQuery(e.target).parent().find('.vg-video-settings');

    //     jQuery('.vg-secs-block').addClass('vg-redy-for-secs');

    //     jQuery('html, body').animate({

    //     scrollTop: jQuery("#my-secs").offset().top

    // }, 1500, function() {

    //     vg_set.css('top', jQuery('#Top_bar').height());

    //     vg_set.css('position', 'fixed').slideDown();

    //     });

    // });



    jQuery(document).on('click', '#vg-button-video-upload', function(e){

        jQuery(e.target).parent().find('.vg-video-settings').slideDown();

    });



    jQuery(document).on('click', '#vg-video-settings-close', function(e){ 

        jQuery(e.target).parent().slideUp();

        jQuery('.vg-redy-for-secs').find('img.image_for_video').css({"filter": "contrast(1)",

                    "-webkit-filter": "contrast(1)",

                    "-moz-filter": "contrast(1)",

                    "-o-filter": "contrast(1)",

                    "-ms-filter": "contrast(1)"}).removeClass('selected');

        jQuery('.vg-redy-for-secs').find(".icon-check").fadeOut();

        all_title_selected = '';

        jQuery('.vg-redy-for-secs').find('.vg-image-add-text-container').slideUp( "slow", function() {

            //jQuery('.vg-redy-for-secs').find('.vg-image-add-text-container > input')[0].value = '';  

        });

        jQuery('.vg-secs-block').removeClass('vg-redy-for-secs');



    });



    jQuery(document).on('click', '#vg-uploads-close', function(e){ 

        jQuery(e.target).parent().slideUp();

    });



    jQuery(document).on('click', '.vg-image-tag-delete', function(e){        

       var data = {

            action: 'vg_delete_tag',

            img_id: jQuery(e.target).data('img-name'),

            term_id : jQuery(e.target).data('term-name')

        };  



        jQuery.ajax({

            url: mup_config.ajax_url,

            data: data,

            dataType: 'json',

            type: 'POST',

            beforeSend: function(){
                    
                    jQuery(e.target).parent().find('.vg-image-add-tag-container').hide( "slow", function() {  });

                    uploadDiv = jQuery('<li>', {

                        id: 'vg-before-load',

                        style: 'z-index:999;'

                    });

                    uploadDiv.append('<a><svg class="circle-chart-tag" viewBox="0 0 100 100" width="20" height="20" xmlns="http://www.w3.org/2000/svg"><circle class="circle-chart__circle" stroke="rgba(26, 165, 208,1)" stroke-width="8" stroke-linecap="round" fill="none" cx="50" cy="50" r="40"></circle></svg></a>');

                    jQuery(jQuery(e.target).parents(".images-attr").find(".attachmentTag")[0]).prepend(uploadDiv);

                },

            success: function(resp) {

                jQuery(e.target).parent('li').remove();

                uploadDiv.slideUp().remove();

                

            },

            error: function( jqXHR, textStatus, errorThrown ){

                     console.log('ОШИБКИ AJAX запроса: ' + textStatus );

                     console.log(jqXHR);

                     console.log(errorThrown);

            }

        });

    });



    jQuery('#drop a').click(function(){

        // имитация нажатия на поле выбора файла

        jQuery(this).parent().find('input').click();

    });

    function searchInArr(arr, keys) {
        let len = arr.length - 1;
        for (let i = 0; i <= len; i++) {
            // switch(arr[i].types[0]) {
            //     case keys[0]:  
            //         return(arr[i].formatted_address);
            //     case keys[1]:   
            //         return(arr[i].formatted_address);
            //     case keys[2]:   
            //         return(arr[i].formatted_address);
            //     default:
            //         return('');
            // }
            if (arr[i].types[0] === keys[0] || arr[i].types[0] === keys[1] || arr[i].types[0] === keys[2]) {
                if (arr[i].address_components.length != 0) {
                    let out = arr[i].address_components[0].long_name;
                    if (arr[i].address_components[arr[i].address_components.length-1].long_name != out) {
                        out = out+', '+arr[i].address_components[arr[i].address_components.length-1].long_name;
                    }
                    return(out);                    
                }else{
                    return(arr[i].formatted_address);
                }
                //console.log(arr[i].formatted_address);
            }
        }
    }



    var form = jQuery('#upload');



    function DmsToDecimal(degree_num, minute_num, second_num, second_den){

        var degree = degree_num;

        var minute = minute_num/60

        var second = (second_num)/(second_den)/3600

        return degree + minute + second

    }



    var ul = jQuery('#upload ul');

    // инициализация плагина jQuery File Upload

    jQuery('#upload').fileupload({

        url: mup_config.upload_url,

        // этот элемент будет принимать перетаскиваемые на него файлы

        dropZone: jQuery('#drop'),

        // Функция будет вызвана при помещении файла в очередь

        add: function (e, data) {

            data.formData = {'action': 'upload-attachment','_wpnonce': mup_config.nonce, 'async-upload':data.files[0], 'name':data.files[0].name};

             

            var tpl = jQuery('<li class="uploader"><input style="display: none !important;" type="text" value="0" data-width="48" data-height="48"'+

                ' data-fgColor="#1aa5d0" data-readOnly="1" data-bgColor="#3e4043" /><p></p></li>');



            tpl.find('p').text(data.files[0].name)

                         .append('<i>' + formatFileSize(data.files[0].size) + '</i>'); 

            //jQuery('.setting-upload').slideUp();

            var newDiv = jQuery('<div>');

            newDiv.append('<div class="vg-img-data-load"><span>Reading image data </span><i class="fa fa-spinner fa-pulse"></i></div>');

            data.context = tpl.appendTo(ul); 

            // инициализация плагина jQuery Knob

            tpl.find('input').knob(); 

            // отслеживание нажатия на иконку отмены

            // tpl.find('span').click(function(){

            //     if(tpl.hasClass('working')){

            //         jqXHR.abort();

            //     }

 

            //     tpl.fadeOut(function(){

            //         tpl.remove();

            //     });

            // });

            // Автоматически загружаем файл при добавлении в очередь

            var jqXHR = data.submit()

        .success(function (result, textStatus, jqXHR) {

            jQuery('#my-secs').prepend(newDiv); 

            console.log(jQuery.parseJSON(result));

            var url = jQuery.parseJSON(result);

            var img = jQuery('<img>', {

                src: url.data.url,

                id: 'img-'+url.data.id,

                class: 'image_for_video',

                'data-img-id': url.data.id,

                'data-img-url': url.data.url

            });

            newDiv.prepend(img);        

            jQuery('#my-secs').justifiedGallery({

                rowHeight : 300,

                lastRow : 'justify',

                margins : 20

            });

            ul.find('li:first-child').slideUp('slow').remove(); 

            

            var GPSLatitudeRef = url.data.GPSLatitudeRef;

            var GPSLatitude = url.data.GPSLatitude;

            var GPSLongitudeRef = url.data.GPSLongitudeRef;

            var GPSLongitude = url.data.GPSLongitude;

            console.log(GPSLatitudeRef);

            console.log(GPSLatitude);

            console.log(GPSLongitudeRef);

            console.log(GPSLongitude);

            // console.log(Number(GPSLatitude[0]));

            // console.log(Number(GPSLatitude[1]));

            // console.log(Number(GPSLongitude[0]));

            // console.log(Number(GPSLongitude[1]));

            



            if( GPSLatitudeRef != undefined && GPSLatitude.length != 0 && GPSLongitudeRef != undefined && GPSLongitude.length != 0){               

                var latitude = DmsToDecimal(Number(GPSLatitude[0]), Number(GPSLatitude[1]),  Math.floor(GPSLatitude[2]), Math.trunc(GPSLatitude[2]));

                var longitude = DmsToDecimal(Number(GPSLongitude[0]), Number(GPSLongitude[1]),  Math.floor(GPSLongitude[2]), Math.trunc(GPSLongitude[2]));

                if (GPSLatitudeRef == 'S'){latitude *= -1};

                if (GPSLongitudeRef == 'W'){longitude *= -1};



                jQuery.ajax({

                url: 'https://maps.googleapis.com/maps/api/geocode/json?latlng='+latitude+','+longitude+'&key=AIzaSyD63hlbCj6QmK-RkQuwiifS-mku2TM45h4&language=en',

                //data: data,

                dataType: 'json',

                type: 'GET',

                success: function(resp) {

                    var arrToSearch = ["locality", "postal_code", "country"];

                    console.log(resp);

                    setTimeout(function() {

                        console.log(searchInArr(resp.results, arrToSearch));

                            // var loc = searchInArr(resp.results, arrToSearch).split(", ");

                            // var l = loc.length;

                            // if ( l > 1 ) {

                            //     var term_name = loc[0]+', '+loc[l];

                            // }else{

                            //     var term_name = 

                            // }



                            var data = {

                                action: 'my_raiting',

                                img_id: url.data.id,

                                img_url: url.data.url,

                                term_name: searchInArr(resp.results, arrToSearch),

                            };



                            jQuery.ajax({

                                url: mup_config.ajax_url,

                                data: data,

                                dataType: 'json',

                                type: 'POST',

                                success: function(resp) {               

                                    console.log(resp);  



                                    var add_tag = jQuery('<div class="vg-image-add-tag-wraper"><div style="display:none;" class="vg-image-add-tag-container" role="add tag"><button type="button" class="vg-image-add-tag-close close" data-dismiss="modal">&times;</button><input type="text" class="text" id="attachments-'+url.data.id+'-post_tag" name="attachments['+url.data.id+'][post_tag]" value=""></div><button class="btn btn-xs vg-image-add-tag" data-img-id="'+url.data.id+'">Add tag</button></div>');



                                    var img_title = jQuery('<div style="display:none;" class="vg-image-add-text-container" role="add text"><input type="text" class="text" placeholder="Image title" value=""></div>');



                                    var images_attr = jQuery('<div>');

                                    images_attr.addClass('images-attr');

                                    newDiv.append(img);

                                    newDiv.append('<div class="vg-select-icons"><i class="icon-check"></i></div>');

                                    newDiv.append(img_title); 

                                    images_attr.append(resp.raiting);                                               

                                    images_attr.append(resp.tagUl);

                                    images_attr.append(resp.postFace);   

                                    images_attr.append(resp.likes);

                                    images_attr.append(add_tag);

                                    newDiv.append(resp.menu);

                                    newDiv.append(resp.author_name);

                                    newDiv.append(images_attr);



                                    jQuery('#Header .chanse-to-win h3').text(resp.user_chanse_win);                                                



                                    newDiv.find('.vg-img-data-load').slideUp().remove();      

                                        jQuery('#my-secs').justifiedGallery({

                                            rowHeight : 300,

                                            lastRow : 'justify',

                                            margins : 20

                                        });

                                         

                                },

                                error: function( jqXHR, textStatus, errorThrown ){

                                         console.log('ОШИБКИ AJAX запроса: ' + textStatus );

                                         console.log(jqXHR);

                                         console.log(errorThrown);

                                }

                            });



                    }, 1500);



                },

                error: function( jqXHR, textStatus, errorThrown ){

                    console.log('ОШИБКИ AJAX запроса: ' + textStatus );

                    console.log(jqXHR);

                    console.log(errorThrown);

                }

            });

            } else {

                var data = {

                action: 'my_raiting',

                img_id: url.data.id

                };



                jQuery.ajax({

                    url: mup_config.ajax_url,

                    data: data,

                    dataType: 'json',

                    type: 'POST',

                    success: function(resp) {    

                        var add_tag = jQuery('<div class="vg-image-add-tag-wraper"><div style="display:none;" class="vg-image-add-tag-container" role="add tag"><button type="button" class="vg-image-add-tag-close close" data-dismiss="modal">&times;</button><input type="text" class="text" id="attachments-'+url.data.id+'-post_tag" name="attachments['+url.data.id+'][post_tag]" value=""></div><button class="btn btn-xs vg-image-add-tag" data-img-id="'+url.data.id+'">Add tag</button></div>')



                        var check = jQuery('<i class="fa fa-check-circle-o" aria-hidden="true"></i>');



                        var img_title = jQuery('<div style="display:none;" class="vg-image-add-text-container" role="add text"><input type="text" class="text" placeholder="Image title" value=""></div>');



                        var images_attr = jQuery('<div>');

                        images_attr.addClass('images-attr');

                        newDiv.append(img);

                        newDiv.append('<div class="vg-select-icons"><i class="icon-check"></i></div>');

                        newDiv.append(img_title); 

                        images_attr.append(resp.raiting);                                               

                        images_attr.append(resp.tagUl);

                        images_attr.append(resp.postFace);   

                        images_attr.append(resp.likes);

                        images_attr.append(add_tag);

                        newDiv.append(resp.menu);

                        newDiv.append(resp.author_name);

                        newDiv.append(images_attr);

                        jQuery('#Header .chanse-to-win h3').text(resp.user_chanse_win);

                        newDiv.find('.vg-img-data-load').slideUp( 'slow', function(e) {

                            newDiv.find('.vg-img-data-load').remove();// Animation complete.

                        });      

                        jQuery('#my-secs').justifiedGallery({

                            rowHeight : 300,

                            lastRow : 'justify',

                            margins : 20

                        });

                        console.log(resp);

                    },

                    error: function( jqXHR, textStatus, errorThrown ){

                             console.log('ОШИБКИ AJAX запроса: ' + textStatus );

                             console.log(jqXHR);

                             console.log(errorThrown);

                    }

                });



            }

        /*console.log(textStatus);console.log(jqXHR);*/

        })

        .error(function (jqXHR, textStatus, errorThrown) {/* ... */})

        .complete(function (result, textStatus, jqXHR) {/* ... */});

        },

        progress: function(e, data){

            var progress = parseInt(data.loaded / data.total * 100, 10);

            // обновляем шкалу

            data.context.find('input').val(progress).change();

 

            if(progress == 100){

                data.context.removeClass('working');

            }

        },

 

        fail:function(e, data){

            // что-то пошло не так

            data.context.addClass('error');

        }

 

    }); 

 

    jQuery(document).on('drop dragover', function (e) {

        e.preventDefault();

    });

 

    // вспомогательная функция, которая форматирует размер файла

    function formatFileSize(bytes) {

        if (typeof bytes !== 'number') {

            return '';

        }

 

        if (bytes >= 1000000000) {

            return (bytes / 1000000000).toFixed(2) + ' GB';

        }

 

        if (bytes >= 1000000) {

            return (bytes / 1000000).toFixed(2) + ' MB';

        }

 

        return (bytes / 1000).toFixed(2) + ' KB';

    }

 

    // });



    jQuery(document).on('click', '.vg-watch-video', function(e){

        e.preventDefault();

        var modal = jQuery( '#' + jQuery(this).data('modal-id') );

        if ( modal.find('source').attr('src') !=  jQuery(this).data('video-src')  ) {

                modal.find('video').remove();

                var new_v = jQuery('<video class="vg-page-video" controls autoplay>');

                new_v.append('<source src="'+jQuery(this).data('video-src')+'">')

                modal.find('.embed-responsive').append(new_v);

        } else {

            //modal.modal();        

        }

        modal.modal(); 

        //modal.find('source').src;

        //modal.find('source').attr('src', jQuery(this).data('video-src' ));

        // console.log(modal.find('source').attr('src'));

        // console.log(jQuery(this).data('video-src'));

    });



    jQuery(document).on('click', '#vg-modal-video .close', function(e){

        var video = jQuery(e.target).parent().find('video');

        console.log(video);

    });



    jQuery(document).on('click', '.entry-visible button.vg-image-add-tag', function(e) {

        var ntd = jQuery(e.target).parent().find('.vg-image-add-tag-container');

        var new_tag = ntd.find('input')[0].value;

        console.log(ntd.find('input')[0].value);

        //console.log(jQuery(e.target).parent().find('.vg-image-add-tag-container > input')[0].value);

        //console.log(new_tag);

                   // console.log(jQuery(e.target).parents(".slick-slide").find(".attachmentTag")[0]);

        if ('' == new_tag || undefined == new_tag ) {

            jQuery(e.target).parent().find('.vg-image-add-tag-container').show( "slow", function() {

            ntd.find('input').focus();

            jQuery('input').keydown(function(e) {

                if(e.keyCode === 13) {

                    ntd.parent().find('.vg-image-add-tag').trigger('click');

                    ntd.find('input')[0].value = '';

                }

            });

          });       



        }else{

            var data = {

                action: 'vg_add_new_tag',

                img_id: jQuery(e.target).data('img-id'),

                term_name : new_tag.replace("#", "")

            };



            jQuery.ajax({

                url: mup_config.ajax_url,

                data: data,

                dataType: 'json',

                type: 'POST',

                beforeSend: function(){

                    jQuery(e.target).parent().find('.vg-image-add-tag-container').hide( "slow", function() {  });

                    uploadDiv = jQuery('<li>', {

                        id: 'vg-before-load',

                        style: 'z-index:999;'

                    });

                    uploadDiv.append('<a><svg class="circle-chart-tag" viewBox="0 0 100 100" width="20" height="20" xmlns="http://www.w3.org/2000/svg"><circle class="circle-chart__circle" stroke="rgba(26, 165, 208,1)" stroke-width="8" stroke-linecap="round" fill="none" cx="50" cy="50" r="40"></circle></svg></a>');

                    jQuery(jQuery(e.target).parents(".images-attr").find(".attachmentTag")[0]).prepend(uploadDiv);

                },

                success: function(resp) {

                    jQuery(e.target).parent().find('.vg-image-add-tag-container > input')[0].value = '';

                    jQuery(jQuery(e.target).parents(".images-attr").find(".attachmentTag li:nth-child(4)")).remove();

                    jQuery(jQuery(e.target).parents(".images-attr").find(".attachmentTag")[0]).prepend(resp.tagLi);

                        // Animation complete.

                    uploadDiv.slideUp().remove(); 

                },

                error: function( jqXHR, textStatus, errorThrown ){

                    console.log('ОШИБКИ AJAX запроса: ' + textStatus );

                    console.log(jqXHR);

                    console.log(errorThrown);

                }

            });

        }

    });





    jQuery( "button.my-button-video" ).click(function(e) {

        

        $images = jQuery('img.image_for_video.selected');

        

        if ($images.length > 7) {

            alert('You can select only 7 images for 1 video!');

        }else if ($images.length == 0) {

            alert('Select at least one image for video!');

        }else{

            jQuery(e.target).parent().find('.vg-title-video').val('');

            jQuery(e.target).parent().find('.download-video').slideUp();

            jQuery(e.target).parent().find('.delete-video').slideUp();

            jQuery(e.target).parent().find('.post-ratings').slideUp();

            jQuery(e.target).parent().find('.attachmentTag').slideUp();

            jQuery(e.target).parent().slideUp();

            jQuery(e.target).parent().find('.vg-video-generator-loader').slideDown();

            jQuery('.vg-redy-for-secs').find('img.image_for_video').css({"filter": "contrast(1)",

                "-webkit-filter": "contrast(1)",

                "-moz-filter": "contrast(1)",

                "-o-filter": "contrast(1)",

                "-ms-filter": "contrast(1)"}).removeClass('selected');

            jQuery('.vg-redy-for-secs').find(".icon-check").fadeOut();

            all_title_selected = '';

            jQuery('.vg-redy-for-secs').find('.vg-image-add-text-container').slideUp( "slow", function(){

            });

            jQuery('.vg-secs-block').removeClass('vg-redy-for-secs');

            var image = {};

            var title_for_all = jQuery('.vg-image-add-text-container input:checkbox:checked');     

            $images.each(function(key, value){

                console.log(jQuery(value));

                image[key] = {

                    img_url: jQuery(value).data('img-url'),

                    img_title: jQuery(value).parent().find('input.text').val(),

                    img_id : jQuery(value).data('img-id'),



                };       

            })      

            var data = {

                action: 'vg_generate_new_video',

                video_title: jQuery(e.target).parent().find('.vg-title-video').val(),

                images : image,

                sound : jQuery(e.target).parent().find('.vg-sound-video-select').val()

            };



            console.log(data);



            jQuery.ajax({

                url: mup_config.ajax_url,

                data: data,

                dataType: 'json',

                type: 'POST',

                beforeSend: function(){

                    uploadDiv = jQuery('<div>', {

                        id: 'vg-before-load',

                        style: 'position:fixed;top:0;bottom:0;right:0;left:0;background:rgba(0,0,0,0.5);z-index:999;'

                    });

                    uploadDiv.append('<h2 class="text-wait">Video is generating, it may take up to 1 min.</h2><svg class="circle-chart" viewBox="0 0 100 100" width="100" height="100" xmlns="http://www.w3.org/2000/svg"><circle class="circle-chart__circle" stroke="rgba(26, 165, 208,1)" stroke-width="8" stroke-linecap="round" fill="none" cx="50" cy="50" r="40"></circle></svg>');

                    jQuery('#Content').prepend(uploadDiv);

                },

                success: function(resp) {

                    if (resp.out != '' || resp.out != undefined) {

                        jQuery('#my-secs').prepend(resp.out);           

                        jQuery('#my-secs').justifiedGallery({

                            rowHeight : 300,

                            lastRow : 'justify',

                            margins : 20

                        });

                        jQuery(e.target).parents('.col-lg-6').css('display', 'none');

                        uploadDiv.slideUp().remove(); 

                        jQuery('#Header .chanse-to-win h3').text(resp.user_chanse_win);

                    }

                },

                error: function( jqXHR, textStatus, errorThrown ){

                         console.log('ОШИБКИ AJAX запроса: ' + textStatus );

                         console.log(jqXHR);

                         console.log(errorThrown);

                }

            });

        }    

    });





    jQuery(document).on('click', '.um-own-profile .justified-gallery .entry-visible img.image_for_video+.vg-select-icons',

        function change_Select_Images(e){

            var $this   = jQuery(this).parent().find('img');

            $this.toggleClass('selected');            

            if (jQuery('.image_for_video.selected').length >= 8) {

                $this.removeClass('selected');

                alert('You can select only 7 images for 1 video!');        

            }else{

                if ($this.hasClass('selected')) {

                    $this.css({"filter": "contrast(0.2)",

                                "-webkit-filter": "contrast(0.2)",

                                "-moz-filter": "contrast(0.2)",

                                "-o-filter": "contrast(0.2)",

                                "-ms-filter": "contrast(0.2)"});

                    $this.find(".icon-check").fadeIn();

                    if (all_title_selected != undefined && all_title_selected == '') {

                        $this.parent().find('.vg-image-add-text-container').slideDown( "slow", function() {

                            $this.parent().find('.vg-image-add-text-container > input')[0].value = '';

                        });

                    }   

                }else{

                    $this.css({"filter": "contrast(1)",

                                "-webkit-filter": "contrast(1)",

                                "-moz-filter": "contrast(1)",

                                "-o-filter": "contrast(1)",

                                "-ms-filter": "contrast(1)"});

                    $this.find(".icon-check").fadeOut();

                    all_title_selected = '';

                    $this.parent().find('.vg-image-add-text-container').slideUp( "slow", function() {

                        $this.parent().find('.vg-image-add-text-container > input')[0].value = '';  

                    });

                }

            }                  

            return false;

        }

    );





    jQuery(document).on('click', 'button.vg-image-add-tag-close',

        function(e) {

            jQuery(e.target).parent().slideUp( "slow", function() {

        });

    });





    jQuery(document).on('click', '.delete-video', function(e){

        if (confirm("Delete?")) {

            var data = {

                    action: 'vg_delete_video',

                    video_id: jQuery(e.target).data('delete-video')

            };

            jQuery.ajax({

                url: mup_config.ajax_url,

                data: data,

                dataType: 'json',

                type: 'POST',

                success: function(resp) {

                    //console.log(resp);

                    jQuery(e.target).parents('.entry-visible').remove();

                    jQuery('#my-secs, #all-secs').justifiedGallery({

                        rowHeight : 300,

                        lastRow : 'justify',

                        margins : 20

                    });

                    

                },

                error: function( jqXHR, textStatus, errorThrown ){

                         console.log('ОШИБКИ AJAX запроса: ' + textStatus );

                         console.log(jqXHR);

                         console.log(errorThrown);

                }

            });

        }

    });



    jQuery(document).on('click', '.vg-all-user-videos .delete-video', function(e){

        var data = {

            action: 'vg_delete_video',

            video_id: jQuery(e.target).data('delete-video')

        };

        jQuery.ajax({

            url: mup_config.ajax_url,

            data: data,

            dataType: 'json',

            type: 'POST',

            success: function(resp) {

                //console.log(resp);

                jQuery(e.target).parents('.entry-visible').remove();

                  

                

            },

            error: function( jqXHR, textStatus, errorThrown ){

                     console.log('ОШИБКИ AJAX запроса: ' + textStatus );

                     console.log(jqXHR);

                     console.log(errorThrown);

            }

        });

    });



    // Переменная куда будут располагаться данные файлов 

    var file, i, sound_file;

    var inp_numb = 1

    var image_files = [];

    var $imgForm    = jQuery('.vg-upload-form');

    if ($imgForm != undefined){

        var files = [];

        var $formNotice = jQuery('.vg-form-notice');

        var $imgNotice  = $imgForm.find('.image-notice');

        var $imgPreview = $imgForm.find('.uploaded-imgs');

        var $imgFile    = $imgForm.find('.image-upload input[type=file]');

        var $imgId      = $imgForm.find('[name="image_id"]');    

    }

 

    // Вешаем функцию на событие

    // Получим данные файлов и добавим их в переменную

    function vg_add_imput(event){

        $imgFile = $imgForm.find('.image-upload input[type=file]');

        //console.log($imgFile.length);

        var newLabel = document.createElement('label');

        var newInput = document.createElement('input');

        newInput.type='file';

        newInput.multiple='multiple';

        newInput.accept='image/*';

        newInput.className='image-imput-'+inp_numb+' generated-image-input';

        newInput.setAttribute('data-input-number', inp_numb);

        var deleteButton = document.createElement('button');

        deleteButton.append('Delete');

        deleteButton.className='btn btn-outline-danger btn-xs delete-image-imput-'+inp_numb+' generated-delete-button';

        deleteButton.setAttribute('data-delete-input-number', inp_numb);

        //jQuery('.image-upload').append(newInput);

        jQuery(newLabel).append(newInput).append('Upload images');

        //event.before(newInput);

        //event.after(deleteButton);

        //console.log(newLabel);

        jQuery(event).parents('.image-upload').append(newLabel);

    }

    // $imgFile.change(function(){

    // files = this.files;

    //     console.log(this);

    //     console.log(files);

    //     if (files.length < 7) {

    //     vg_add_imput(this);

    //     jQuery(this).hide();

    //     }

    // });

    $imgForm.on('change', '.image-upload input[type=file]', function (e) {

        image_files[inp_numb] = this.files;

        files = jQuery.merge( jQuery.merge([],files),image_files[inp_numb]);

        //console.log(e.data);

        if (files.length < 7) {

            console.log(image_files[inp_numb]);

            var deleteButton = document.createElement('button');

            deleteButton.append('Delete');

            deleteButton.className='btn btn-outline-danger btn-xs delete-image-imput-'+($imgFile.length+1)+' generated-delete-button';

            deleteButton.setAttribute('data-delete-input-number', $imgFile.length+1);

            //jQuery('.image-upload').append(newInput);

            jQuery(this).after(deleteButton);

            inp_numb++;   

            vg_add_imput(this);

            jQuery(this).attr("disabled", true);

        }



    });



    $imgForm.on('click', '.generated-delete-button', function (e) {

        event.stopPropagation(); // Остановка происходящего

        event.preventDefault();  // Полная остановка происходящего

        //files = jQuery.merge( jQuery.merge([],files),this.files);

        //console.log(e.data);

        //numb = jQuery(this).attr('data-delete-input-number');

        //console.log(jQuery(this).parent());

        jQuery(this).parent().remove();

        inp_numb--;

    });



    jQuery('.sound-upload input[type=file]').change(function(){

        file = this.files;

        //console.log(file);

    });

    // Вешаем функцию ан событие click и отправляем AJAX запрос с данными файлов

    jQuery('.upload.button').click(function( event ){



        var send_attachment_bkp = wp.media.editor.send.attachment;

        var $this = jQuery(this);

        //var seclab_box_menu_item_settings = $this.parents('.admin_seclab_box_menu_item_settings');

        //seclab_box_menu_item_settings.find('.admin-sl-box_option_inside_icon_font .menu-item-seclab-link_icon').val('');

        //seclab_box_menu_item_settings.find('.admin-sl-box_option_inside_icon_font .admin_sl_icon_font_icon_box_preview').children('span').attr('class','');

        wp.media.editor.send.attachment = function(props, attachment) {

            $this.parent().find('.admin_sl_upload_input').val(attachment.url);

            // back to first state

            wp.media.editor.send.attachment = send_attachment_bkp;

        };



        wp.media.editor.open();



        //return false;



        event.stopPropagation(); // Остановка происходящего

        event.preventDefault();  // Полная остановка происходящего

        //console.log(files.length);

        if( files !== undefined ){

            if( files.length <= 10 ){

                // Создадим данные формы и добавим в них данные файлов из files

                var all_files = jQuery.merge( jQuery.merge([],files),file);

                //var all_files = files;

                //console.log(all_files);

                var data = new FormData();

                data.append('action', 'my_upload_attachment');

                //data.append('async-upload', jQueryimgFile[0].files[0]);

                //data.append('name', jQueryimgFile[0].files[0].name);

                //data.append('_wpnonce', mup_config.nonce);

                jQuery.each( all_files, function( key, value ){

                    i = true;

                    if( value.size <= 5242880 ){

                        data.append( key, value );

                        //data.append('async-upload', value);

                        data.append('name', value.name);

                    }else{

                        alert( 'Файл '+value.name+' больше 5Мб, уменьшите размер файла' );

                        //break;

                        i = false;

                        return i;

                    }

                    //console.log(data.getAll(key));

                //console.log(value);

                });

                // jQuery.each( file, function( key, value ){

                //     i = true;

                //     if( value.size <= 5242880 ){

                //         data.append( key, value );

                //         //data.append('name', value.name);

                //     }else{

                //         alert( 'Файл '+value.name+' больше 5Мб, уменьшите размер файла' );

                //         //break;

                //         i = false;

                //         return i;

                //     }

                // //console.log(value);

                // });

                if( i === true ){

                    // Отправляем запрос

                    //console.log(data.getAll('name'));

                    // jQuery.ajax({

                    //     url: mup_config.ajax_url,

                    //     data: data,

                    //     timeout: 60*60*1000*2,

                    //     processData: false,

                    //     contentType: false,

                    //     cache: false,

                    //     dataType: 'json',

                    //     type: 'POST',

                    //     beforeSend: function() {

                    //         jQuery('.image-upload input[type=file]').hide();

                    //         jQuery('.image-notice').html('Uploading&hellip;').show();

                    //     },

                    //     success: function(resp) {

                    //         if ( resp.success ) {

                    //             $imgNotice.html('Successfully uploaded.');

                         

                    //             var img = jQuery('<img>', {

                    //                 src: resp.data.url,

                    //                 class: 'col-xs-1'

                    //             });

                         

                    //             $imgId.val( resp.data.id );

                    //             $imgPreview.html( img ).show();

                         

                    //         } else {

                    //             $imgNotice.html('Fail to upload image. Please try again.');

                    //             $imgFile.show();

                    //             $imgId.val('');

                    //         }

                    //         console.log(resp);

                    //     },

                    //     error: function( jqXHR, textStatus, errorThrown ){

                    //              console.log('ОШИБКИ AJAX запроса: ' + textStatus );

                    //              console.log(jqXHR);

                    //              console.log(errorThrown);

                    //     }

                    // });

                    // jQuery.ajax({

                    //     url: '../inc/upload.php?uploadfiles',

                    //     type: 'POST',

                    //     data: data,

                    //     cache: false,

                    //     dataType: 'json',

                    //     processData: false, // Не обрабатываем файлы (Don't process the files)

                    //     contentType: false, // Так jQuery скажет серверу что это строковой запрос

                    //     success: function( respond, textStatus, jqXHR ){

                 

                    //         // Если все ОК



                    //         //console.log(respond);

                    //         //console.log(textStatus);

                    //         //console.log(jqXHR);

                 

                    //         if( typeof respond.error === 'undefined' ){

                    //             // Файлы успешно загружены, делаем что нибудь здесь

                 

                    //             // выведем пути к загруженным файлам в блок '.uploaded-imgs'

                 

                    //             var files_path = respond.files;

                    //             var sound_html = img_html = ''; 

                    //                 //console.log(files_path);

                    //             jQuery.each( files_path, function( key, val ){

                    //                 if ( key != (files_path.length-1) ) {

                    //                     img_html += '<img src="' + val + '" class="col-xs-1">';

                    //                 } else { sound_html += val; }

                    //                  } )

                    //             jQuery('.uploaded-imgs').html( img_html );

                    //             jQuery('.uploaded-sound').html( sound_html );

                    //             jQuery('a.generate').show();

                    //         }

                    //         else{

                    //             console.log('ОШИБКИ ОТВЕТА сервера: ' + respond.error );

                    //         }

                    //     },

                    //     error: function( jqXHR, textStatus, errorThrown ){

                    //         console.log('ОШИБКИ AJAX запроса: ' + textStatus );

                    //     }

                    // });

                }

            }else{alert('Вы можете загрузить максимум 10 изображений');}



        } else if( files === undefined ) {

            alert('Загрузите изображения');

        } else {

            alert('Загрузите файлы');

        }

    });



    jQuery('.generate.button').click(function( event ){

        jQuery('a.upload').hide();

        jQuery('a.generate').hide();

        event.stopPropagation(); // Остановка происходящего

        event.preventDefault();  // Полная остановка происходящего

     

        // Создадим данные формы и добавим в них данные файлов из files

     

        /*var images = jQuery('.uploaded-imgs img');

        var arr = jQuery.makeArray( images );

        var data = new FormData();

        jQuery.each( arr, function( key, value ){

            data.append( key, value.src );

        });*/



        var images = jQuery('.uploaded-imgs img');

        var sound = jQuery('.uploaded-sound').text();

      

        var data = [];

        jQuery.each( images, function( key, value ){

            data[key] = value.getAttribute('src');

        });

        // Отправляем запрос

        data = JSON.stringify(data);

        //console.log(data);

        //console.log(sound);

        jQuery.ajax({

            url: '../inc/generate.php',

            type: 'POST',

            data: { data: data, sound: sound },

            cache: false,

            dataType: 'json',

            success: function( str ){

                // Если все ОК

                var video = jQuery('<video />', {

                id: 'video',

                src: str['video_link'],

                type: 'video/mp4',

                controls: true,

                width: 300

                });

                var vid_url = window.location.href;

                vid_url = vid_url.substring(0, vid_url.length-1)+str['video_link']; 

                var soc = [

                    '<li id="facebook"><a href="https://www.facebook.com/sharer.php?src=pluso&u='+vid_url+'"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>',

                    '<li id="google-plus"><a href="https://plus.google.com/share?url='+vid_url+'"><i class="fa fa-google-plus" aria-hidden="true"></i></a></li>'

                ];

                var ul = jQuery('#share-btns');

                jQuery.each(soc, function(index, value){

                    ul.append(value);

                    //console.log("INDEX: " + index + " VALUE: " + value);

                });

                video.appendTo(jQuery('.result'));

                jQuery('.soc-share').show();

            },

            error: function( str ){

                console.log('ОШИБКИ AJAX запроса: ' + str );

            }

        });  

    });



    jQuery(function($){

        $('#true_loadmore').click(function(){

            $(this).text('Загружаю...'); // изменяем текст кнопки, вы также можете добавить прелоадер

            var data = {

              'action': 'loadmore',

              'query': true_posts,

              'page' : current_page

            };

            $.ajax({

                url:ajaxurl, // обработчик

                data:data, // данные

                type:'POST', // тип запроса

                success:function(data){

                    if( data ) { 

                        $('#true_loadmore').text('Загрузить ещё').before(data); // вставляем новые посты

                        current_page++; // увеличиваем номер страницы на единицу

                        if (current_page == max_pages) $("#true_loadmore").remove(); // если последняя страница, удаляем кнопку

                    } else {

                        $('#true_loadmore').remove(); // если мы дошли до последней страницы постов, скроем кнопку

                    }

                }

            });

        });

    });

});