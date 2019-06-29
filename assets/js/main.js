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
        jQuery(this).parents('.row').children('.popup-add-files').slideDown(200);
    })
    jQuery('.items-row').on('click', '.close', function(){
        jQuery(this).parents('.popup-config').slideUp(200);
    });
    jQuery('body').on('click', '.load-docs', function(){
        var that = jQuery('.load-docs');
        that.text('Combining...');

        jQuery.ajax({
            method: 'POST',
            url: ajaxurl,
            data: { action: 'moto_parser', pAction: 'loadAndCombine' }
        })
            .success(function(data) {
console.log(data);
                    jQuery('.success-message').text('Combining files was successfull click button bellow to download').slideDown(600);
                    setTimeout(function(){
                        jQuery('.success-message').slideUp(600);
                    }, 6000);
                  that.html('<a style="color:white;font-weight: 700;text-decoration: none;" href="' + data + '">Download</a>');

            });
    });
});
