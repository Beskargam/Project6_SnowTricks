$(document).ready(function () {
    // Images
    var $containerImage = $('div#add_image_images');
    var indexImage = $containerImage.find(':input').length;

    $('#add_image').click(function (e) {
        addImage($containerImage);

        e.preventDefault();
        return false;
    });

    if (indexImage === 0) {
        addImage($containerImage);
    } else {
        $containerImage.children('div').each(function () {
            addDeleteImageLink($(this));
        });
    }

    function addImage($containerImage) {
        var templateImage = $containerImage.attr('data-prototype')
            .replace(/__name__label__/g, '')
            .replace(/__name__/g, indexImage);
        var $prototypeImage = $(templateImage);

        addDeleteImageLink($prototypeImage);
        $containerImage.append($prototypeImage);

        indexImage++;
    }

    function addDeleteImageLink($prototypeImage) {
        var $deleteImageLink = $('<a href="#" class="btn btn-warning">Supprimer l\'image</a>');

        $prototypeImage.append($deleteImageLink);


        $deleteImageLink.click(function (e) {
            $prototypeImage.remove();

            e.preventDefault();
            return false;
        });
    }
});