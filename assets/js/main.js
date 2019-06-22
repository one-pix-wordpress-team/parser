jQuery(document).ready(function(){

    //Adding new item form

  jQuery('.add-new-item').click(function(){
   if(jQuery('.new-parser-item').exists()){
       alert('You should complete current new item first!');
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
      var path1 = jQuery('.inp-file-1').val();
      var path2 = jQuery('.inp-file-2').val();
      var path3 = jQuery('.inp-file-3').val();
      var new_item = jQuery(this).parents('.new-parser-item');
      var button = jQuery(this).find('button');
      alert(button.text())
      $.ajax({
          method: 'POST',
          url: '/handler.php',
          data: { action: 'addItem', host: host , username: name, password: pass, path1: path1, path2: path2, path3: path3},
          beforeSend: function(){
              button.html("<img style='height:23px;' src='//evrootel.ruhotel.su/new_1/images/loading_spinner.gif'>");
          }
      })

          .success(function(data) {
              console.log(data);
              if(data == 'true') {
                  button.text('Connect');
                  alert("Adding a source was successful!");
                  jQuery('.items-row').append('<div class="parser-item w-100">' +
                      '<div class="row">' +
                      '<div class="col-lg-6 host-inner item-inner">'+host+'</div>' +
                      '<div class="col-lg-3 item-inner" style="color:#28a745;">' +
                      'Working' +
                      '</div>' +
                      '<div class="col-lg-3">\n' +
                      '                    <div class="item-info btn"><i class="fa fa-eye" aria-hidden="true"></i></div><div class="remove-item btn btn-danger">-</div>\n' +
                      '                </div>\n' +
                      '                <div style="display:none" class="popup-config"><div class="spoiler col-lg-12">\n' +
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
                      '                                <div class="col-lg-9 host-inner item-inner">'+path1+'</div>\n' +
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
                  alert("An error occurred! Please check your connection details!");
                  button.text('Connect');
              }

          })

  });

//delete item action

    jQuery('.items-row').on('click', '.remove-item', function(){
        var host = jQuery(this).parents('.row').children('.host-inner').text();
        var here = jQuery(this).parents('.parser-item');

            $.ajax({
            method: 'POST',
            url: '/handler.php',
            data: { action: 'deleteItem', host: host }
        })
            .success(function(data) {
                alert(data);
                if(data == 'true') {
                    alert('The object was successfully deleted!');
                    here.remove();
                }
            });
    });
jQuery('.items-row').on('click', '.item-info', function(){
    jQuery(this).parents('.row').children('.popup-config').slideDown(200);
})
    jQuery('.items-row').on('click', '.close', function(){
        jQuery(this).parents('.popup-config').slideUp(200);
    })
});
