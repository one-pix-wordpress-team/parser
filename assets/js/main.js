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

  jQuery('.items-row').on('click', '.test-connection', function(form){
      var host = jQuery('.inp-host').val();
      var name = jQuery('.inp-username').val();
      var pass = jQuery('.inp-password').val();
      $.ajax({
          method: 'POST',
          url: '/inc/ajax/handlers/handler.php',
          data: { action: 'addItem', host: host , username: name, password: pass }
      })
          .success(function(data) {
              alert( "Data Saved: " + data );
          });
  jQuery('.items-row').append('<div class="parser-item w-100">' +
      '<div class="row">' +
      '<div class="col-lg-6 host-inner item-inner">'+host+'</div>' +
      '<div class="col-lg-3 item-inner" style="color:#28a745;">' +
      'Working' +
      '</div>' +
      '<div class="col-lg-3">' +
      '<div class="remove-item btn btn-danger">-</div>' +
      '</div>' +
      '</div>' +
      '</div>');
      jQuery(this).parents('.new-parser-item').remove();
  });

//delete item action

    jQuery('.items-row').on('click', '.remove-item', function(){
        var host = jQuery(this).parents('.row').children('.host-inner').text();;
        $.ajax({
            method: 'POST',
            url: '/inc/ajax/handlers/handler.php',
            data: { action: 'deleteItem', host: host }
        })
            .success(function(data) {
                alert('Success delete!');
                jQuery(this).parents('.parser-item').remove();
            });

        jQuery(this).parents('.parser-item').remove();
    });


});
