/**
 * JS for Groupable Widgets
 */
 jQuery( function( $ ) {

  var groupableWidgetsCustomizer = (function( api ) {

   if ( 'undefined' !== typeof wp && 'undefined' !== typeof wp.customize ) {

     wp.customize.bind('pane-contents-reflowed', function(){

      if ( groupableWidgetsAffected() ) {
        markWidgetGroups();
        wp.customize.previewer.refresh();
      }

     });

   }

  })( wp.customize );

  function getActiveWidgets() {
    var activeWidgets = [];
    if ( wp.customize ) {
      $('.control-section.open').find('.customize-control-widget_form').each(function(){
       activeWidgets.push( $(this).attr('id') );
      });
    } else {
      $('.widgets-holder-wrap').find('.widget').each(function(){
        activeWidgets.push( $(this).attr('id') );
      });
    }

    return activeWidgets;
  }

  function markWidgetGroups() {

    var activeWidgets = getActiveWidgets();

    var colors = [ 'gold', 'green', 'red', 'blue', 'purple' ];
    var colorIndex = 0;

    for (var i = 0; i < activeWidgets.length; i++ ) {

      for (var j = 0; j < colors.length; j++ ) {
        $('#'+activeWidgets[i]).removeClass('organic-widgets-groupable-indicator-'+colors[j]);
      }
      $('#'+activeWidgets[i]).removeClass('organic-widgets-groupable-indicator');

      if ( isGroupableWidget( activeWidgets[i]) && isConsecutive( i ) ) {
        $('#'+activeWidgets[i]).addClass('organic-widgets-groupable-indicator');
        $('#'+activeWidgets[i]).addClass('organic-widgets-groupable-indicator-'+colors[colorIndex % colors.length]);
      }

      //Increment Color if last item
      if ( isLastInGroup( i ) ) {
        colorIndex++;
      }

    }

    // Set variables if not set yet
    var activePaneID = $('.control-section.open').attr('id');
    if ( ! GroupableWidgets['active_pane'] && activePaneID ) {
      GroupableWidgets['active_pane'] = activePaneID;
      GroupableWidgets['widgets'] = getActiveWidgets();
    }

  }

  function isConsecutive( i ) {

    var activeWidgets = getActiveWidgets();

    var groupableIDs = [
     'organic_widgets_profile',
     'organic_widgets_featured_content',
     'organic_widgets_pricing_table'
    ];

    for ( var j = 0; j < groupableIDs.length; j++ ) {
      if ( activeWidgets[i].indexOf(groupableIDs[j]) !== -1 && ( ( i-1 > -1 && activeWidgets[i-1].indexOf(groupableIDs[j]) !== -1 ) || ( i+1 < activeWidgets.length && activeWidgets[i+1].indexOf(groupableIDs[j]) !== -1  ) ) ) {
        return true;
      }
    }

    return false;

  }

  function isLastInGroup( i, widgetsToCheck ) {

    if ( widgetsToCheck == null ) {
      var activeWidgets = getActiveWidgets();
    } else {
      var activeWidgets = widgetsToCheck;
    }

    if ( activeWidgets == null ) return false;

    if ( ! isGroupableWidget( activeWidgets[i] ) ) {
      return false;
    }

    var groupableIDs = [
     'organic_widgets_profile',
     'organic_widgets_featured_content',
     'organic_widgets_pricing_table'
    ];

    for ( var j = 0; j < groupableIDs.length; j++ ) {
      if ( activeWidgets[i].indexOf(groupableIDs[j]) !== -1 && ! ( i+1 < activeWidgets.length && activeWidgets[i+1].indexOf(groupableIDs[j]) !== -1  ) ) {
        return true;
      }
    }

    return false;

  }

  function getConsecutiveGroups( widgetArray ) {

    var groups = [];
    var groupIndex = 0;
    groups[groupIndex] = [];

    for ( var i = 0; i < widgetArray.length; i++ ) {

      if ( isGroupableWidget( widgetArray[i] ) ) {
        groups[groupIndex].push( widgetArray[i] );

        if ( isLastInGroup( i, widgetArray ) ) {
          groupIndex++;
          groups[groupIndex] = [];
        }

      }

    }

    groups.pop();
    return groups;

  }

  function groupableWidgetsAffected() {

    var activeWidgets = getActiveWidgets();

    // Check if no pane open
    var activePaneID = $('.control-section.open').attr('id');
    if ( ! activePaneID ) return false;

    var newGroups = getConsecutiveGroups(activeWidgets);
    var oldGroups = getConsecutiveGroups(GroupableWidgets['widgets']);

    GroupableWidgets['widgets'] = getActiveWidgets();

    /* Testing only if group composition is affected */
    // if ( arraysEqual( oldGroups, newGroups ) ) {
    //   return false;
    // } else {
    //   return true;
    // }

    /* Temporary force refresh if groupable widgets were are are present */
    if ( newGroups.length || oldGroups.length ) return true;

    return false;

  }

  function arraysEqual( arr1, arr2 ) {
    if ( arr1.length !== arr2.length ) {
      return false;
    }
    for ( var i = 0; i < arr1.length; i++ ) {
      if ( arr1[i].length !== arr2[i].length ) {
        return false;
      }
      for ( var j = 0; j < arr1.length; j++ ) {
        if ( arr1[i][j] !== arr2[i][j] )  {
          return false;
        }
      }
    }
    return true;
  }

  function isGroupableWidget( widgetID ) {

    var groupableIDs = [
     'organic_widgets_profile',
     'organic_widgets_featured_content',
     'organic_widgets_pricing_table'
    ];

    for (var k = 0; k < groupableIDs.length; k++ ) {

      if ( widgetID.indexOf(groupableIDs[k]) !== -1 ) {
       return true;
      }
    }

    return false;

  }

  $(window).on("load", function() {
		markWidgetGroups();
    if ( typeof wp.customize !== "undefined" ) {
			wp.customize.state.bind('change', function() {
				markWidgetGroups();
			});
			$('.customize-control-widget_form').on('click',function(){
				markWidgetGroups();
			});
		} else {
      $( document ).ajaxStop( function() {
        markWidgetGroups();
      } );
    }
	});

} );
