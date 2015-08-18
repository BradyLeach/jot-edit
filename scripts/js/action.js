//Log in widget
$( "#login" ).submit(function( e ) {  
    e.preventDefault ? e.preventDefault() : event.returnValue = false;
    
    $( "#login_loading" ).remove();
    $( ".form_error" ).remove();
    $( "#login" ).append( "<div id=\"login_loading\" class=\"loading\">PROCESSING</div>");

    $.ajax({
    url      : "/jot-edit/scripts/php/action/ALogin.php",
    data     : $( "#login" ).serialize(),
    async: true,
    //cache: false,
    dataType : 'json',
    type     : 'post', 
    success  : function(Result){
        $("#login_loading").remove();
            //Depending out login result we will redirect or present the appropriate error.
            console.log(Result);
        switch(Result.result){
                 case 'pass':                   
                    window.location.reload(true);
                    break;
                case 'fail':
                    $( "#login" ).prepend( Result.message );                
                    break;
                case 'locked':
                    $( "#login" ).prepend( Result.message ); 
                    $('#submit').attr('disabled','disabled');
                    break;
                default:
                    $( "#login" ).prepend( Result.message );
                    break;
            }
        },
    error : function(xhr, status, error){
        console.log(xhr);
        console.log(status);
        console.log(error);
            $( "#login_loading" ).remove();
            alert(xhr.statusText);//handle this better
            alert(status);//handle this better
            alert(error);//handle this better
        }
    }
  );
   
});
