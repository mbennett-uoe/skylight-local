/**
 * Created by Kristiyan Tsvetanov on 24/02/2017.
 * email: kristiyan.c@gmail.com for any questions
 */

// A little bit overloaded, move some of code
window.onload = function(){
    $('#loader').fadeOut();
    $('#loader').click(function(){ $('#loader').fadeOut(); });

    $('.cover-image-container').addClass("loaded");
    $('.record-info').addClass("showing");
};








