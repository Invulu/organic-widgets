(function ($) {
  'use strict'

  /* Masonry --------------------- */
  function masonrySetup () {
    var $container = $('.organic-widgets-masonry-container')
    var $gallery = $('.organic-widgets-masonry-container.organic-widgets-gallery')
    $container.masonry({
      itemSelector: '.organic-widgets-masonry-wrapper'
    })
    $gallery.masonry({
      gutter: '.organic-widgets-grid-spacer',
      itemSelector: '.organic-widgets-masonry-wrapper'
    })
    // if (typeof wp.customize !== 'undefined') {
    //   wp.customize.selectiveRefresh.bind('sidebar-updated', function () {
    //     $container.masonry('reloadItems')
    //     $container.masonry('layout')
    //     $gallery.masonry('reloadItems')
    //     $gallery.masonry('layout')
    //   })
    // }
  }

  $(window).on('load', masonrySetup)
  $(window).on('resize', masonrySetup)

  $(window).on('load', function () {
    // Check for customizer and listen for changes that require rebindings
    if (typeof wp.customize !== 'undefined') {
      wp.customize.selectiveRefresh.bind('partial-content-rendered', function () {
        setTimeout(masonrySetup, 250)
      })
    }
  })

})(jQuery)
