$(function generate_menu(){
    var $box = $('#pages-box');

    var menuTree = {};

    $box.find('a').each(function(){
        var url = $(this).attr('href');
        var tree = url.replace(/^\/own-memo\/|\/$/g, '').split('/');
        var $group = getGroup(1 , tree[0]);
        /* il parent e' "li" */
        $(this).parent().detach();
        $group.append($(this).parent());
    });

    /* costruisco l'elemento ul in cui inserire gli item, o lo recupero se gia esiste */
    function getGroup(level , group){

        var $g = $box.find('[data-level="' + level + '"][data-name="' + group + '"] ul');

        if(! $g.length){
            $g = $('<h3/>').attr('data-level', level).attr('data-name', group).html(group+' <ul></ul>').appendTo($box);
            $g = $g.find('ul');
        }

        return $g;
    }

});
