jQuery(document).ready(function(){

    //Adding new item form

    jQuery('.add-new-item').click(function(){
        if(jQuery('.new-parser-item').exists()){
            jQuery('.error-message').text('You should complete current new item first!').slideDown(600);
            setTimeout(function(){
                jQuery('.error-message').slideUp(600);
            }, 6000);
        } else {
            jQuery('.items-row').append(new_item);
        }
    });

    jQuery('body').on('click', '.new-parser-item .close', function(){
        jQuery(this).parents('.new-item-append').html('').animate({
            right:'-420px',
        });
    })

  //add item action

  jQuery('.items-row').on('submit', '.new-form', function(form){
      form.preventDefault();
      var host = jQuery('.inp-host').val();
      var name = jQuery('.inp-username').val();
      var pass = jQuery('.inp-password').val();
      var new_item = jQuery(this).parents('.new-parser-item');
      var button = jQuery(this).find('button');

      jQuery.ajax({
          method: 'POST',
          url: ajaxurl,
          data: {action: 'moto_parser', pAction: 'addItem', host: host , username: name, password: pass},
          beforeSend: function(){
              button.append('<span class="load open"></span>');
          }
      })

          .success(function(data) {
              console.log(data);
              if(data == 'true') {
                  button.text('Connect');
                  jQuery('.success-message').text('The sourse was successfully connected!').slideDown(600);
                  setTimeout(function(){
                      jQuery('.success-message').slideUp(600);
                  }, 6000);
                  jQuery('.items-row').append('<div class="parser-item w-100">' +
                      '<div class="row">' +
                      '<div class="col-lg-6 host-inner item-inner">'+host+'</div>' +
                      '<div class="col-lg-3 item-inner" style="color:#28a745;">' +
                      'successfully connected' +
                      '</div>' +
                      '<div class="col-lg-3">\n' +
                      '                    <div class="add-files btn"><i class="fa fa-plus" aria-hidden="true"></i></div><div class="item-info btn"><i class="fa fa-eye" aria-hidden="true"></i></div><div class="remove-item btn btn-danger"><i class="fa fa-minus" aria-hidden="true"></i></div>\n' +
                      '                </div>\n' +
                      '                <div style="display:none" class="popup-config status-popup"><div class="spoiler col-lg-12">\n' +
                      '                        <div class="row">\n' +
                      '                            <div class="close"><i class="fa fa-times" aria-hidden="true"></i></div>\n' +
                      '                            <div class="popup-headers row">\n' +
                      '                            <div class="col-lg-9 head-item">\n' +
                      '                                File name\n' +
                      '                            </div>\n' +
                      '                            <div class="col-lg-3 head-item">\n' +
                      '                                Status\n' +
                      '                            </div>\n' +
                      '                            </div>\n' +
                      '                            <div class="row file-row">\n' +
                      '                                <div class="col-lg-9 host-inner item-inner"></div>\n' +
                      '                                <div class="col-lg-3 item-inner" style="color:#28a745;">\n' +
                      '                                    Working\n' +
                      '                                </div>\n' +
                      '                            </div>\n' +
                      '                            </div>\n' +
                      '                        </div>\n' +
                      '</div>\n' +
                      '                    </div>');

                  new_item.remove();
                  setTimeout(function () {
                      location.reload()
                  }, 1000)
              } else {
                  jQuery('.error-message').text('An error was accured. Check your connection details').slideDown(600);
                  setTimeout(function(){
                      jQuery('.error-message').slideUp(600);
                  }, 6000);
              }

          })

  });

//delete item action

    jQuery('.items-row').on('click', '.remove-item', function(){
        var host = jQuery(this).parents('.row').children('.host-inner').text();
        var here = jQuery(this).parents('.parser-item');

        jQuery.ajax({
            method: 'POST',
            url: ajaxurl,
            data: { action: 'moto_parser', pAction: 'deleteItem', host: host }
        })
            .success(function(data) {
                console.log(data);
                if(data == 'true') {
                    jQuery('.success-message').text('The object was successfully deleted').slideDown(600);
                    setTimeout(function(){
                        jQuery('.success-message').slideUp(600);
                    }, 6000);
                    here.remove();
                }
            });
    });
jQuery('.items-row').on('click', '.item-info', function(){
    jQuery(this).parents('.row').children('.status-popup').slideDown(200);
})


    jQuery('.items-row').on('click', '.add-files', function(){

        function dancingLikeVanDamme(min, max) {
            var rand = min + Math.random() * (max + 1 - min);
            rand = Math.floor(rand);
            return rand;
        }

        var vanDamme = dancingLikeVanDamme(1, 8);
        var host = jQuery(this).parents('.row').children('.host-inner').text();
        var popup = jQuery(this).parents('.row').children('.popup-add-files');
        popup.find('.add-files-inner').html('');

            popup.find('.add-files-inner').html('<div class="preloader"><img style="float:right;" src="/wp-content/plugins/moto-parser/assets/img/dance_' + vanDamme + '.gif"></div>');

        popup.slideDown(200);
        jQuery.ajax({
            method: 'POST',
            url: ajaxurl,
            data: {action: 'moto_parser', pAction: 'getFiles', host: host},
            beforeSend: function(){
                console.log('Go');

            }
        })
            .success(function(data) {

                var arr = JSON.parse(data);

                for (var k in arr) {
console.log(typeof k);

                        popup.find('.add-files-inner').append('<details class="wow-details col-lg-12 ' + k + '"><summary>' + k + '</summary></details>');

                        var a = arr[k];
                        for (var i = 0; i < a.length; ++i) {
                            popup.find('.' + k).append('<label class="col-lg-4 wow-details-item"><input name="' + a[i] + '" type="checkbox">' + a[i] + '</label>');

                        }


                }
                popup.find('.add-files-inner').append('<button type="submit" style="padding:15px;margin-top:15px;" class="save-files btn btn-primary col-lg-3">Save</button>');
                popup.find('.preloader').html('');
            })
    });

    jQuery('.accept-files').on('submit', function(s){
        s.preventDefault();
        var button = jQuery(this).find('button');
        var that = jQuery(this);
        var popup = jQuery(this).parents('.row').children('.popup-add-files');
        let formData = new FormData(s.target);

        // Собираем данные формы в объект
        let obj = {};
        formData.forEach((value, key) => obj[key] = value);
        var files = JSON.stringify(obj);
        var host = that.parents('.parser-item').find('.host-inner').text();
        console.log(files);
        console.log(host);
        jQuery.ajax({
            method: 'POST',
            url: ajaxurl,
            data: {action: 'moto_parser', pAction: 'acceptFiles', host: host, files: files},
            beforeSend: function(){
                button.append('<span class="load open"></span>');
            }

        })
            .success(function(data) {
                console.log(data)
                popup.find('.add-files-inner').html('');
                var arr = JSON.parse(data);
                popup.find('form').remove();
                popup.find('.file-row').children('.container').html('<form class="file-fields"><div class="row add-files-inner"></div></form>')
                   for (var k in arr) {
                    if (k == 'files_fields') {
                        var a = arr[k];
                        for (var i = 0; i < a.length; ++i) {
                            popup.find('.add-files-inner').append('<div style="padding:15px" class="file_fields-row fields-row-'+ i +' col-lg-4"><label class="file_field row-label">' + a[i] + '</label><select style="padding:15px; height: 52px;" class="static_fields static-field-'+ i +'"></select></div>');

                        }
                    }
                }
                for (var k in arr) {
                    if (k == 'static_fields') {
                        popup.find('.static_fields').append('<option>Nothing</option>');
                        var a = arr[k];
                        for (var i = 0; i < a.length; ++i) {
                            popup.find('.static_fields').append('<option>' + a[i] + '</option>');

                        }
                    }
                    popup.find('.file_fields-row').each(function(){
                        var name = jQuery(this).find('.file_field').text()
                        jQuery(this).find('select').attr('name', name)
                    })


                }
                popup.find('.add-files-inner').append('<div class="row w-100"><button type="submit" style="padding:15px;margin-top:15px;" class="m-auto save-files-fields btn btn-primary col-lg-3">Save</button></div>');

                for (var i = 0; i < jQuery('.static_fields').length; ++i) {
                    jQuery('.static-field-'+ i ).select2({
                        dropdownParent: jQuery('.fields-row-'+ i ),
                        placeholder: "Nothing selected",
                    });
                }


            })
    })

    jQuery('body').on('submit', '.file-fields', function(r) {
        r.preventDefault();
        var button = jQuery(this).find('button');
        var that = jQuery(this);
        var popup = jQuery(this).parents('.row').children('.popup-add-files');
        let formData = new FormData(r.target);

        // Собираем данные формы в объект
        let obj = {};
        formData.forEach((value, key) => obj[key] = value);
        var files = JSON.stringify(obj);
        var host = that.parents('.parser-item').find('.host-inner').text();
        console.log(files);
        console.log(host);
        jQuery.ajax({
            method: 'POST',
            url: ajaxurl,
            data: {action: 'moto_parser', pAction: 'setFilesFields', host: host, files_fields: files},
            beforeSend: function(){
                button.append('<span class="load open"></span>');
            }
        })
            .success(function (data) {
                console.log(data)
                button.html('Saved');
                setTimeout(function(){
                    that.parents('.popup-config').find('.close').click();
                }, 2500)

            })
    })

    jQuery('.items-row').on('click', '.close', function(){
        jQuery(this).parents('.popup-config').slideUp(200);
    });
    jQuery('body').on('click', '.load-docs', function(){
        var that = jQuery('.load-docs');
        jQuery.ajax({
            method: 'POST',
            url: ajaxurl,
            data: { action: 'moto_parser', pAction: 'loadAndCombine' },
            beforeSend: function(){
                that.append('<span class="load open"></span>');
            }
        })
            .success(function(data) {
                that.removeClass('load-docs').addClass('generating-docs');
                    jQuery('.success-message').text('Combining files was successfull click button bellow to download').slideDown(600);
                    setTimeout(function(){
                        jQuery('.success-message').slideUp(600);
                    }, 6000);
                  jQuery('.generating-docs').html('<a style="color:white;font-weight: 700;text-decoration: none;" href="' + data + '">Download</a>');

            }).error(function (error) {
            that.text('Combine');
            jQuery('.error-message').text('There was an error, please try again!').slideDown(600);
            setTimeout(function(){
                jQuery('.error-message').slideUp(600);
            }, 6000);
        });
    });
    jQuery(document).on('change', '.static_fields', function(){
        console.log(jQuery(this).val())
        if(jQuery(this).val != 'Nothing'){
            console.log('hasntClass')
            jQuery(this).parents('.file_fields-row').addClass('row-changed');
        }
        if(jQuery(this).val == 'Nothing'){
            console.log('hasClass')
            jQuery(this).parents('.file_fields-row').removeClass('row-changed');
        }
    })
    jQuery('body').on('click', '.update-docs', function(){
        var that = jQuery('.update-docs');
        console.log('go!')
        jQuery.ajax({
            method: 'POST',
            url: ajaxurl,
            data: { action: 'moto_parser', pAction: 'updateRecords' },
            beforeSend: function(){
                that.append('<span class="load open"></span>');
            }
        }).success(function(data) {
            if(data=='true'){
                jQuery('.success-message').text('Updated files was successfull').slideDown(600);
                setTimeout(function () {
                    jQuery('.success-message').slideUp(600);
                }, 6000);
                that.text('Updated');
            } else {
                jQuery('.error-message').text('There was an error, please try again!').slideDown(600);
            }
        }).error(function (error) {
            that.text('Update database');
            jQuery('.error-message').text('There was an error, please try again!').slideDown(600);
            setTimeout(function(){
                jQuery('.error-message').slideUp(600);
            }, 6000);
        });
    });

    jQuery('.parts-unlimited-form').on('submit', function(asdf){
        asdf.preventDefault();
        var access =jQuery(this).serialize();
        var button = jQuery(this).find('button');
        jQuery.ajax({
            method: 'POST',
            url: ajaxurl,
            data: {action: 'moto_parser', pAction: 'partsUnlimited', access:access},
            beforeSend: function(){
                button.append('<span class="load open"></span>');
            }
        }).success(function(data) {
                button.text('Save')
                console.log(data);
                if (data == 'true') {
                    jQuery('.success-message').text('The cridentials was successfully saved!').slideDown(600);
                    setTimeout(function(){
                        jQuery('.success-message').slideUp(600);
                    }, 6000);
                }
                else
                {
                    jQuery('.error-message').text('There was an error, please try again!').slideDown(600);
                    setTimeout(function(){
                        jQuery('.error-message').slideUp(600);
                    }, 6000);
                }
            }).error(function(){
            button.text('Save')
            jQuery('.error-message').text('There was an error, please try again!').slideDown(600);
            setTimeout(function(){
                jQuery('.error-message').slideUp(600);
            }, 6000);
        })
    })
});
