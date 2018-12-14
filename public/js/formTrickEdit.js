$(document).ready(function () {
    // Field Image Edit
    $('.field-img-edit').hide();
    $('.btn-img-edit').click(function (e) {
        $('.field-img-edit').show();

        e.preventDefault();
        return false
    });

    // Image Delete
    $('.btn-img-delete').click(function (e) {
        $('.card-img-display').css({opacity : 0.5});

        e.preventDefault();
        return false
    });

    // Field Video Edit
    $('.field-video-edit').hide();
    $('.btn-video-edit').click(function (e) {
        $('.field-video-edit').show();

        e.preventDefault();
        return false
    });

    // Video Delete
    $('.btn-video-delete').click(function (e) {
        $('.card-video-display').css({opacity : 0.5});

        e.preventDefault();
        return false
    });
});