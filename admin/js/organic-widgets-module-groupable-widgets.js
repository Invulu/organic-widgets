/**
 * Profile Section Widget JS
 */
 jQuery( function( $ ) {

  var groupableWidgetsCustomizer = (function( api ) {

   if ( 'undefined' !== typeof wp && 'undefined' !== typeof wp.customize ) {

     wp.customize.bind('pane-contents-reflowed', function(args,args2){

      var activeWidgets = getActiveWidgets();

      if ( groupableWidgetsAffected( activeWidgets ) ) {
        markWidgetGroups( activeWidgets );
        wp.customize.previewer.refresh();
      }

     });

   }

  })( wp.customize );

  function getActiveWidgets() {
    var activeWidgets = [];
    $('.control-section.open').find('.customize-control-widget_form').each(function(){
     activeWidgets.push( $(this).attr('id') );
    });

    return activeWidgets;
  }

  function markWidgetGroups( activeWidgets ) {

    if (typeof activeWidgets === 'undefined' ) {
     var activeWidgets = getActiveWidgets();
    }

    var colors = [ 'gold', 'green', 'red', 'blue', 'purple' ];
    var colorIndex = 0;

    for (var i = 0; i < activeWidgets.length; i++ ) {

      for (var j = 0; j < colors.length; j++ ) {
        $('#'+activeWidgets[i]).removeClass('organic-widgets-groupable-indicator-'+colors[j]);
      }
      $('#'+activeWidgets[i]).removeClass('organic-widgets-groupable-indicator');

      if ( isGroupableWidget( activeWidgets[i]) && isConsecutive( activeWidgets, i ) ) {
        $('#'+activeWidgets[i]).addClass('organic-widgets-groupable-indicator');
        $('#'+activeWidgets[i]).addClass('organic-widgets-groupable-indicator-'+colors[colorIndex % colors.length]);
      }

      //Increment Color if last item
      if ( isLastInGroup( activeWidgets, i ) ) {
        colorIndex++;
      }

    }

  }

  function isConsecutive( activeWidgets, i ) {

    if ( ! isGroupableWidget( activeWidgets[i] ) ) {
      return false;
    }

    var groupableIDs = [
     'organic_widgets_profile',
     'organic_widgets_featured_content',
     'organic_widgets_pricing_table'
    ];

    for ( var j = 0; j < groupableIDs.length; j++ ) {
      console.log(j);
      if ( activeWidgets[i].indexOf(groupableIDs[j]) !== -1 && ( ( i-1 > -1 && activeWidgets[i-1].indexOf(groupableIDs[j]) !== -1 ) || ( i+1 < activeWidgets.length && activeWidgets[i+1].indexOf(groupableIDs[j]) !== -1  ) ) ) {
        return true;
      }
    }

    return false;

  }

  function isLastInGroup( activeWidgets, i ) {

    if ( ! isGroupableWidget( activeWidgets[i] ) ) {
      return false;
    }

    var groupableIDs = [
     'organic_widgets_profile',
     'organic_widgets_featured_content',
     'organic_widgets_pricing_table'
    ];

    for ( var j = 0; j < groupableIDs.length; j++ ) {
      console.log(j);
      if ( activeWidgets[i].indexOf(groupableIDs[j]) !== -1 && ( ( i-1 > -1 && activeWidgets[i-1].indexOf(groupableIDs[j]) !== -1 ) || ( i+1 < activeWidgets.length && activeWidgets[i+1].indexOf(groupableIDs[j]) !== -1  ) ) ) {
        return true;
      }
    }
    
    return true;

  }

  function groupableWidgetsAffected( activeWidgets ) {

    for (var i = 0; i < activeWidgets.length; i++ ) {
     if ( isGroupableWidget( activeWidgets[i]) ) {
       return true;
     }
    }
    return false;

  }

  function isGroupableWidget( widgetID ) {

    var groupableIDs = [
     'organic_widgets_profile',
     'organic_widgets_featured_content',
     'organic_widgets_pricing_table'
    ];

    for (var i = 0; i < groupableIDs.length; i++ ) {

      if ( widgetID.indexOf(groupableIDs[i]) !== -1 ) {
       return true;
      }
    }

    return false;

  }

  markWidgetGroups();

} );