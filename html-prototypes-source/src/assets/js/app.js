import $ from 'jquery';
import whatInput from 'what-input';

window.$ = $;
window.jQuery = $;

require('owl.carousel/dist/owl.carousel.min.js');
require('masonry-layout/dist/masonry.pkgd.min.js');
require('imagesloaded/imagesloaded.pkgd.min.js');

import Foundation from 'foundation-sites';
// If you want to pick and choose which modules to include, comment out the above and uncomment
// the line below
//import './lib/foundation-explicit-pieces';

$(document).foundation();

// Everything Grid

$(function() {
    var owl = $('.everything-carousel'),
        owlOptions = {
            autoHeight:true,
            nav: true,
            dots: false,
            responsive: {
                0:{
                    items:1
                },
                900:{
                    items:2
                },
            }
        };

    if ( $(window).width() < 1200 ) {
        var owlActive = owl.owlCarousel(owlOptions);
    } else {
        owl.addClass('off');
    }

    $(window).resize(function() {
        if ( $(window).width() < 1200 ) {
            if ( $('.owl-carousel').hasClass('off') ) {
                var owlActive = owl.owlCarousel(owlOptions);
                owl.removeClass('off');
            }
        } else {
            if ( !$('.owl-carousel').hasClass('off') ) {
                owl.addClass('off').trigger('destroy.owl.carousel');
                owl.find('.owl-stage-outer').children(':eq(0)').unwrap();
            }
        }
    });
});

$( ".search-button" ).click(function() {
    console.log("clicked");
    $( "#searchInput" ).focus();
});

// init Masonry Social Wall
var $socialWall = $('.social-posts').masonry({
    itemSelector: '.social-post',
    columnWidth: '.grid-sizer',
    // gutter: '.social-post-gutter',
    percentPosition: true,
    horizontalOrder: false,
    layoutMode: 'packery'
});

// layout Masonry after each image loads
$socialWall.imagesLoaded().progress( function() {
    $socialWall.masonry('layout');
});


// This is an optional application of a carousel
$(".profile-list, .fact-timeline, .job-posting-card-list").owlCarousel({
    margin: 30,
    nav: true,
    dots: false,
    items: 3,
    loop: true,
    responsive:{
        0:{
            items:1
        },
        640:{
            items:2
        },
        1024:{
            items:3,
            autoWidth: false,
        },
    }
})

$(".image-gallery-carousel").owlCarousel({
    margin: 6,
    autoHeight:true,
    dots: false,
    nav: true,
    responsive:{
        0:{
            items:1
        },
        640:{
            items:2
        },
        1024:{
            autoWidth:true,
            autoHeight:false
        },
    }
});

function hasTouch() {
    return 'ontouchstart' in document.documentElement
           || navigator.maxTouchPoints > 0
           || navigator.msMaxTouchPoints > 0;
}

if (hasTouch()) { // remove all :hover stylesheets
    try { // prevent exception on browsers not supporting DOM styleSheets properly
        for (var si in document.styleSheets) {
            var styleSheet = document.styleSheets[si];
            if (!styleSheet.rules) continue;

            for (var ri = styleSheet.rules.length - 1; ri >= 0; ri--) {
                if (!styleSheet.rules[ri].selectorText) continue;

                if (styleSheet.rules[ri].selectorText.match(':hover')) {
                    styleSheet.deleteRule(ri);
                }
            }
        }
    } catch (ex) {}
}