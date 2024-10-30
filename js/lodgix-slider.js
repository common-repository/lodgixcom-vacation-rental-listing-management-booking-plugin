var LodgixSlider = (function ($, LodgixSlider) {
    'use strict';

    LodgixSlider.init = function (selector) {
        this.selector = selector;
        $(selector).royalSlider({
            autoScaleSlider: true,
            autoScaleSliderWidth: 640,
            autoScaleSliderHeight: 480,
            imageScalePadding: 0,
            controlNavigation: 'thumbnails',
            arrowsNavAutoHide: false,
            loop: true,
            keyboardNavEnabled: true,
            globalCaption: true,
            globalCaptionInside: true,
            thumbs: {
                spacing: 0,
                fitInViewport: false,
                appendSpan: true
            },
            fullscreen: {
                enabled: true
            }
        });
        var rs = $('.royalSlider').data('royalSlider');
        rs.ev.on('rsEnterFullscreen', function() {
            // To prevent overlapping by parent elements in fullscreen mode
            $('.ldgxSlider').prependTo('html');
        });
        rs.ev.on('rsExitFullscreen', function() {
            $('.ldgxSlider').appendTo('.ldgxSliderWrapper');
        });
    };

    LodgixSlider.initOnDocumentReady = function (selector) {
        $(document).ready(function () {
            LodgixSlider.init(selector);
        });
    };

    LodgixSlider.resize = function () {
        var slider = $(this.selector).data('royalSlider');
        if (slider) {
            slider.updateSliderSize(true);
        }
    };

    return LodgixSlider;

}(window.jQLodgix, LodgixSlider || {}));
