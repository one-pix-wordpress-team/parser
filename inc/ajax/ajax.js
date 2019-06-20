jQuery('.new-form').submit(function(){
    var host = jQuery('.inp-host').val();
    var name = jQuery('.inp-username').val();
    var pass = jQuery('.inp-password').val();
    $.ajax({
        method: 'POST',
        url: '/inc/ajax/handlers/handler.php',
        data: { host: host , username: name, password: pass }
    })
        .done(function( msg ) {
            alert( "Data Saved: " + msg );
        });
})