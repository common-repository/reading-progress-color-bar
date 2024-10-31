jQuery(document).ready(function($) {
    var progressBar = $('#rpbp-progress-bar');
    var maxHeight = $(document).height() - $(window).height();

    $(window).on('scroll', function() {
        var scrollTop = $(window).scrollTop();
        var width = (scrollTop / maxHeight) * 100;
        progressBar.css('width', width + '%');
    });
});
