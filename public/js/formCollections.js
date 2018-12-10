$(document).ready(function () {
    var $container = $('div#trick_images');
    var index = $container.find(':input').length;

    $('#add_image').click(function (e) {
        addImage($container);

        e.preventDefault();
        return false;
    });

    if (index === 0) {
        addImage($container);
    } else {
        $container.children('div').each(function () {
            addDeleteLink($(this));
        });
    }

    function addImage($container) {
        var template = $container.attr('data-prototype')
            .replace(/__name__label__/g, '')
            .replace(/__name__/g, index);
        var $prototype = $(template);

        addDeleteLink($prototype);
        $container.append($prototype);

        index++;
    }

    function addDeleteLink($prototype) {
        var $deleteLink = $('<a href="#" class="btn btn-warning">Supprimer l\'image</a>');

        $prototype.append($deleteLink);


        $deleteLink.click(function (e) {
            $prototype.remove();

            e.preventDefault();
            return false;
        });
    }
});