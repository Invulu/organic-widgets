/**
 * JS for page editor.
 * Add a button that links to the customizer when Organic Custom page template is set.
 */
(function ($) {
  'use strict'

  function modifyAdmin () {
    $('.plugins tr.active[data-slug="organic-customizer-widgets"]').after('<tr class="plugin-update-tr active" style="position:relative;top:-1px;"><td class="plugin-update colspanchange" colspan="3"><div class="notice inline notice-warning notice-alt"><p><i class="fa fa-arrow-circle-up" style="color:#f56e28;font-size:18px;margin-right:4px;position:relative;top:2px;"></i> Upgrade available. Purchase <b>Organic Builder Widgets Pro</b> for more styles, options, support, and 4 additional builder widgets. <a href="https://organicthemes.com/builder/" target="_blank">Upgrade now</a></p></div></td></tr>')
  }

  $(document)
    .ready(modifyAdmin)

})(jQuery)
