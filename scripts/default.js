$(document).ready(function(){
    // open links in new tab
    $('a').each(function(){
        $(this).attr('target', '_blank');
    });
});
