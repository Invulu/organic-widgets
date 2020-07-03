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
    if ($('#editor' + name).length === 0) {
      if (selectedTemplate.indexOf('organic-custom-template.php') !== -1) {
        hideEditor()
      } else {
        showEditor()
      }
    } else if ($('#postdivrich' + name).length === 0) {
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
    var wpContentEditorDiv = $('#postdivrich, #editor .block-editor-writing-flow')
    var organicCustomEditDiv = $(document.createElement('div'))

    if (!organicWidgets.isCustomTemplate) {
      var customizeDisabled = 'disabled'
      var submitDisabled = ''
      var customizeLink = '#'
      if ($('.block-editor-page')[0]) {
        var updateButton = '<div id="organic-widgets-update-post" class="button button-primary button-large organic-button-large" ' + submitDisabled + '>Publish / Update</div>'
      } else {
        var updateButton = '<div id="organic-widgets-update-post" class="button button-primary button-large organic-button-large" ' + submitDisabled + '>Update</div>'
      }
      var setPageTemplate = '<div class="organic-widgets-post-editor-update-post"><p>Please update post to apply custom template</p>' + updateButton + '<p>And then...</p></div>'
      var buttonSize = ''
    } else {
      setPageTemplate = ''
      customizeDisabled = ''
      submitDisabled = 'disabled'
      customizeLink = organicWidgets.customizeURL
      buttonSize = 'organic-button-large'
    }

    var customizeButton = '<a href="' + customizeLink + '" class="button button-primary button-large organic-widgets-customize-page-button ' + buttonSize + '" ' + customizeDisabled + '>Customize Page</a>'
    organicCustomEditDiv.attr('id', 'organic-widgets-post-editor')
    organicCustomEditDiv.addClass('postbox')
    organicCustomEditDiv.html('<div class="organic-widgets-post-editor-content"><h2><img src="' + organicWidgets.leafIcon + '"/><span>Organic Custom Widgets Page</span></h2><div class="information"><p><strong>Please Note:</strong> Only Widgets are displayed using this page template.</p> <p>Click "Customize Page" to add Builder Widgets to the page.</p></div>' + setPageTemplate + customizeButton + '</div>')
    wpContentEditorDiv.before(organicCustomEditDiv)
    // wpContentEditorDiv.hide()
    updatePostListener()
  }

  function showEditor () {
    $('#postdivrich, #editor .block-editor-writing-flow').show()
    $('#organic-widgets-post-editor').remove()
  }

  function updateButtons () {
    var organicCustomEditDiv = $('#organic-widgets-post-editor')

    var setPageTemplate = ''
    var customizeDisabled = ''
    var submitDisabled = 'disabled'
    var customizeLink = organicWidgets.customizeURL
    var buttonSize = 'organic-button-large'

    var customizeButton = '<a href="' + customizeLink + '" class="button button-primary button-large organic-widgets-customize-page-button ' + buttonSize + '" ' + customizeDisabled + '>Customize Page</a>'
    organicCustomEditDiv.html('<div class="organic-widgets-post-editor-content"><h2><img src="' + organicWidgets.leafIcon + '"/><span>Organic Custom Widgets Page</span></h2><div class="information"><p><strong>Please Note:</strong> Only Widgets are displayed using this page template.</p> <p>Click "Customize Page" to add Builder Widgets to the page.</p></div>' + setPageTemplate + customizeButton + '</div>')
  }

  function updatePostListener () {
    var wpContentEditorDiv = $('#postdivrich, #editor .block-editor-writing-flow')
    $('#organic-widgets-update-post').on('click', function () {
      $('#post').submit()
    })
    $('.block-editor #organic-widgets-update-post').on('click', function () {
      $('.editor-post-publish-panel__toggle').click()
      $('.editor-post-publish-button').click()
      if (wp.data) {
        wp.data.subscribe(function () {
          // var isSuccess = wp.data.select('core/editor').didPostSaveRequestSucceed()
          var isSavingPost = wp.data.select('core/editor').isSavingPost()
          var isAutosavingPost = wp.data.select('core/editor').isAutosavingPost()
          if (isSavingPost && !isAutosavingPost) {
            setTimeout(function () {
              updateButtons()
            }, 1500)
          }
        })
      } else {
        setTimeout(function () {
          updateButtons()
        }, 2500)
      }
    })
  }

  $(document).on('change', '#page_template, .editor-page-attributes__template select', function () {
    checkSelectedTemplate()
  })

  $(window)
    .load(checkSelectedTemplate)

})(jQuery)
