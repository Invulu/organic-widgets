/**
 * JS for page editor.
 * Add a button that links to the customizer when Organic Custom page template is set.
 */
(function ($) {
  'use strict'

  function checkSelectedTemplate () {
    var selectedTemplate = $('#page_template').find(':selected').val()
    // var gutenbergTemplate = $('select[id^="template-selector"]').find(':selected').val()

    // Customizer button for Gutenberg and Classic editor
    if ($('#postdivrich' + name).length === 0) {
      if ($('.editor-page-attributes__template select').val() === 'templates/organic-custom-template.php') {
        hideEditor()
      } else {
        showEditor()
      }
    } else {
      if (selectedTemplate.indexOf('organic-custom-template.php') !== -1) {
        hideEditor()
      } else {
        showEditor()
      }
    }
  }

  function hideEditor () {
    var wpContentEditorDiv = $('#postdivrich, #editor .editor-block-list__layout')
    var organicCustomEditDiv = $(document.createElement('div'))

    if (!organicWidgets.isCustomTemplate) {
      var customizeDisabled = 'disabled'
      var submitDisabled = ''
      var customizeLink = '#'
      var updateButton = '<div id="organic-widgets-update-post" class="button button-primary button-large organic-button-large" ' + submitDisabled + '>Update</div>'
      var setPageTemplate = '<div class="organic-widgets-post-editor-update-post"><p>Please update post to apply custom template</p>' + updateButton + '<p>And then...</p></div>'
      var buttonSize = ''
    } else {
      var setPageTemplate = ''
      var customizeDisabled = ''
      var submitDisabled = 'disabled'
      var customizeLink = organicWidgets.customizeURL
      var buttonSize = 'organic-button-large'
    }

    var customizeButton = '<a href="' + customizeLink + '" class="button button-primary button-large organic-widgets-customize-page-button ' + buttonSize + '" ' + customizeDisabled + '>Customize Page</a>'
    organicCustomEditDiv.attr('id', 'organic-widgets-post-editor')
    organicCustomEditDiv.addClass('postbox')
    organicCustomEditDiv.html('<div class="organic-widgets-post-editor-content"><h2><img src="' + organicWidgets.leafIcon + '"/><span>Organic Custom Widgets Page</span></h2>' + setPageTemplate + customizeButton + '</div>')
    wpContentEditorDiv.before(organicCustomEditDiv)
    wpContentEditorDiv.hide()
    updatePostListener()
  }

  function showEditor () {
    $('#postdivrich, #editor .editor-block-list__layout').show()
    $('#organic-widgets-post-editor').remove()
  }

  function pageTemplateListener () {
    $('#page_template, .editor-page-attributes__template select').on('change', function () {
      checkSelectedTemplate()
    })
  }

  function updatePostListener () {
    $('#organic-widgets-update-post').on('click', function () {
      $('#post').submit()
    })
    $('.gutenberg__editor #organic-widgets-update-post').on('click', function () {
      $('.editor-post-publish-button').click()
      setTimeout(function () {
        location.reload()
      }, 2000)
    })
  }

  $(window)
    .load(checkSelectedTemplate)
    .load(pageTemplateListener)

})(jQuery)
