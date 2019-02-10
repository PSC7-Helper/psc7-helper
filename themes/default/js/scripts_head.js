$(document).ready (function(){

    $('#alert').fadeIn(750).delay(3500).fadeOut(750);
    
    $('#tool_corruptimages').submit(function() {
        $('#btn-oclke', this).text('Please Wait...').attr('disabled', 'disabled');
    });

    $('form').submit(function() {
        $('.postOverlayContainer').show();
        return true;
    });

});
