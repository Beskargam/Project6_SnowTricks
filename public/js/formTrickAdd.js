$(document).ready(function () {

    // Images
    var $containerImage = $('div#trick_images');
    var indexImage = $containerImage.find(':input').length;

    $('#add_image').click(function (e) {
        addImage($containerImage);
        mainButton();

        e.preventDefault();
        return false;
    });

    mainButton();
    
    function mainButton() {
        $('.mainButton').click(function(){
            $('.mainButton').each(function (index) {
                console.log(index);
                $(this).attr('checked', true);
            });
            $(this).attr('checked', true);
        });
    }

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

    // Videos
    var $containerVideo = $('div#trick_videos');
    var indexVideo = $containerVideo.find(':input').length;

    $('#add_video').click(function (e) {
        addVideo($containerVideo);

        e.preventDefault();
        return false;
    });

    if (indexVideo === 0) {
        addVideo($containerVideo);
    } else {
        $containerVideo.children('div').each(function () {
            addDeleteVideoLink($(this));
        });
    }

    function addVideo($containerVideo) {
        var templateVideo = $containerVideo.attr('data-prototype')
            .replace(/__name__label__/g, '')
            .replace(/__name__/g, indexVideo);
        var $prototypeVideo = $(templateVideo);

        addDeleteVideoLink($prototypeVideo);
        $containerVideo.append($prototypeVideo);

        indexVideo++;
    }

    function addDeleteVideoLink($prototypeVideo) {
        var $deleteVideoLink = $('<a href="#" class="btn btn-warning">Supprimer la vidéo</a>');

        $prototypeVideo.append($deleteVideoLink);


        $deleteVideoLink.click(function (e) {
            $prototypeVideo.remove();

            e.preventDefault();
            return false;
        });
    }
});