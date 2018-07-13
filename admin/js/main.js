/*$(function () {
    $('#modalButton').click(function () {
        $('#modal').modal('show')
            .find('#modalContent')
            .load($(this).attr('value'));
    });
});*/
/*$("#toggle").click(function() {
    $(this).toggleClass("active ");
    $("#menu").toggle();
});
$(".fa").click(function () {
    $(this).toggleClass("active ");
    $(".treeview-menu").show();
})*/
/*$(document).ready(function(){
    $("#toggle").click(function(){
        $("#menu").animate({
            width: "toggle"
        });
    });
});*/
var $ = jQuery.noConflict();

$(window).load(function () {
    if ($('#changeLanguageModal').length)
        $('#changeLanguageModal').modal('show');

    //check if we need to show login modal
    if (window.location.hash)
        $(window.location.hash).modal('show');

    //add more image for news section
    $('#add_more_iamge_button').on('click',function(e){
        e.preventDefault();
        var element = '<input type="file" id="news-thumbnail_image" name="News[more_thumbnail_images][]" aria-invalid="false">'
        $('#more_thumbnail_images').append(element);
    });

    //deleting more thumbnail image on click close button
    $('body').on('click','.thumnail_more_image_close',function(e){
        e.preventDefault();
        var element = $(this);
        var action_url = element.attr('href');
        $.ajax({
            url: action_url,
            type: 'GET',
            //data: formData,
            dataType: 'html',
            success: function(response) {
                if(response == '1'){
                    element.parent().find('img').fadeOut();
                    element.fadeOut();
                }
            }
        });
    });

});
