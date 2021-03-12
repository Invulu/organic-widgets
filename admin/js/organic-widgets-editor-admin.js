/**
 * JS for page editor.
 * Add a button that links to the customizer when Organic Custom page template is set.
 */
(function ($) {
  'use strict'

  function checkSelectedTemplate () {
    var selectedTemplate = $('#page_template').find(':selected').val()

    // Check if Block Editor
    if ($('.block-editor').length !== 0) {
      const { select, subscribe } = wp.data
      subscribe(() => {
        if (select('core/editor').getEditedPostAttribute('template') === 'templates/organic-custom-template.php') {
          if ($('#organic-widgets-post-editor').length === 0) {
            hideEditor()
          }
        } else if ($('#organic-widgets-post-editor').length !== 0) {
          showEditor()
        }
      })
    } else { // Check if Classic Editor
      if (selectedTemplate.indexOf('organic-custom-template.php') !== -1) {
        hideEditor()
      } else {
        showEditor()
      }
    }
  }

  function hideEditor () {
    var wpContentEditorDiv = $('#post-body #titlediv, #editor .block-editor-writing-flow .editor-post-title')
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
    wpContentEditorDiv.after(organicCustomEditDiv)
    // wpContentEditorDiv.hide()
    updatePostListener()
  }

  function showEditor () {
    $('#post-body #titlediv, #editor .block-editor-writing-flow').show()
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
    var wpContentEditorDiv = $('#postdivrich, #editor .block-editor-writing-flow .editor-post-title')
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

  $(document).bind('change', '#page_template, .editor-page-attributes__template select', function () {
    checkSelectedTemplate()
  })

  $(window).on('load', checkSelectedTemplate)

  if (wp.domReady) {
    wp.domReady(function () {
      setTimeout(function () {
        checkSelectedTemplate()
      }, 1500)
    })
  }
})(jQuery)
