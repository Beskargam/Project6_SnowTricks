$(document).ready(function () {
// Videos
    var $containerVideo = $('div#add_video_videos');
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