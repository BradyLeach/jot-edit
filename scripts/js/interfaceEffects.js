$(document).ready(function() {
    $("#loginLink").click(function(event) {
        event.preventDefault();
        $("#loginBox").slideToggle("fast");
        $("#username").focus();
    });
});

/*
$(document).ready(function() {
    $("#account_widget_link").click(function(event) {
        event.preventDefault();
        $("#account_tools").slideToggle("fast");
        $("#btnName_widget").focus();
    });
});
*/


$(document).mousedown(function (e)
{
    var container = $("#loginBox");
    if (!container.is(e.target) // if the target of the click isn't the container...
        && container.has(e.target).length === 0) // ... nor a descendant of the container
    {
        container.hide("slow");
    }
});


/*
$(document).mousedown(function (e)
{
    var container = $("#account_tools");
    if (!container.is(e.target) // if the target of the click isn't the container...
        && container.has(e.target).length === 0) // ... nor a descendant of the container
    {
        container.hide("slow");
    }
});
*/


/*
$(window).scroll(function(){
  $('#toolbar').toggleClass('scrolling', $(window).scrollTop() > $('#main_header').offset().top);
});
*/

$(document).ready(function() {
    $("#blogs_btn").click(function(event) {
        event.preventDefault();
        $("#blogs_list").slideToggle("slow");
    });
});
