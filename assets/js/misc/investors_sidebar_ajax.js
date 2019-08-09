$(document).ready(function(){
    $("#financial-results").html('<div class="preloader"><img src="/images/site_images/COHR-preloader.gif" /></div>');

    // Grabbing the respective content.
    $.ajax({
       url: "/assets_api_inc/investors_sidebar.html?v=" + Date.now(),
        success: function(html){
            $("#financial-results").html(html);
        },
        error: function(error){
            $("#financial-results").hide().html("<h2>Temporarily Unavailable</h2><br /><p>Sorry for the inconvenience. Please try again later.<p>").fadeIn("slow");
        }
    });
});
