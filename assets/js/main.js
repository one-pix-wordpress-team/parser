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
              button.html("<img style='height:23px;' src='//evrootel.ruhotel.su/new_1/images/loading_spinner.gif'>");
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
                      '                    <div class="add-files btn"><i class="fa fa-plus" aria-hidden="true"></i></div><div class="item-info btn"><i class="fa fa-eye" aria-hidden="true"></i></div><div class="remove-item btn btn-danger">-</div>\n' +
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
        console.log(vanDamme);
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
                button.html("<img style='height:23px;' src='//evrootel.ruhotel.su/new_1/images/loading_spinner.gif'>");
            }

        })
            .success(function(data) {
                 console.log(data)
                // if (data == 'true') {
                //     that.find('.btn').removeClass('btn-primary').addClass('btn-success').text('Successfully saved');
                //     setTimeout(function () {
                //         that.find('.btn').removeClass('btn-success').addClass('btn-primary').text('Save');
                //     }, 5000)
                // } else {
                //     that.find('.btn').removeClass('btn-primary').addClass('btn-danger').text('An error was accured!');
                //     setTimeout(function () {
                //         that.find('.btn').removeClass('btn-danger').addClass('btn-primary').text('Save');
                //     }, 5000)
                // }
                popup.find('.add-files-inner').html('');
                var arr = JSON.parse(data);
                var select = '<select style="padding:15px; height: 52px;" class="static_fields col-lg-6"></select>'
                popup.find('form').remove();
                popup.find('.file-row').children('.container').html('<form class="file-fields"><div class="row add-files-inner"></div></form>')
                   for (var k in arr) {
                    if (k == 'files_fields') {
                        console.log(k);
                        var a = arr[k];
                        for (var i = 0; i < a.length; ++i) {
                            console.log(a[i])

                            popup.find('.add-files-inner').append('<div style="padding:15px" class="file_fields-row w-100 row"><div class="col-lg-6 file_field">' + a[i] + '</div>' + select + '</div>');

                        }
                    }
                }
                for (var k in arr) {
                    if (k == 'static_fields') {
                        
                        console.log(k);
                        popup.find('.static_fields').append('<option name="">Nothing</option>');
                        var a = arr[k];
                        for (var i = 0; i < a.length; ++i) {
                            console.log(a[i])

                            popup.find('.static_fields').append('<option name="' + a[i] + '">' + a[i] + '</option>');

                        }
                    }
                    popup.find('.file_fields-row').each(function(){
                        var name = jQuery(this).find('.file_field').text()
                        jQuery(this).find('select').attr('name', name)
                    })

                        // popup.find('.add-files-inner').append('<details class="wow-details col-lg-12 ' + k + '"><summary>' + k + '</summary></details>');
                        // var a = arr[k];
                        // for (var i = 0; i < a.length; ++i) {
                        //     popup.find('.' + k).append('<label class="col-lg-4 wow-details-item"><input name="' + a[i] + '" type="checkbox">' + a[i] + '</label>');

                       // }


                }
                popup.find('.add-files-inner').append('<button type="submit" style="padding:15px;margin-top:15px;" class="save-files-fields btn btn-primary col-lg-3">Save</button>');


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
                button.html("<img style='height:23px;' src='//evrootel.ruhotel.su/new_1/images/loading_spinner.gif'>");
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
        that.removeClass('load-docs').addClass('generating-docs');


        jQuery.ajax({
            method: 'POST',
            url: ajaxurl,
            data: { action: 'moto_parser', pAction: 'loadAndCombine' },
            beforeSend: function(){
                that.text('Combining...');
            }
        })
            .success(function(data) {
console.log(data);
                    jQuery('.success-message').text('Combining files was successfull click button bellow to download').slideDown(600);
                    setTimeout(function(){
                        jQuery('.success-message').slideUp(600);
                    }, 6000);
                  jQuery('.generating-docs').html('<a style="color:white;font-weight: 700;text-decoration: none;" href="' + data + '">Download</a>');

            });
    });
});
