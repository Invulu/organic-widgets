/**
 * Profile Section Widget JS
 */
 jQuery( function( $ ) {

 var groupableWidgetsCustomizer = (function( api ) {

   if ( 'undefined' !== typeof wp && 'undefined' !== typeof wp.customize ) {

     wp.customize.bind('pane-contents-reflowed', function(args,args2){

      var activeWidgets = [];
      $('.control-section.open').find('.customize-control-widget_form').each(function(){
        activeWidgets.push( $(this).attr('id') );


      });

      if ( groupableWidgetsAffected( activeWidgets ) ) {
        wp.customize.previewer.refresh();
      }

     });

   }

 })( wp.customize );


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
     'widget_organic_widgets_profile',
     'organic_widgets_featured_content'
   ];

   for (var i = 0; i < groupableIDs.length; i++ ) {
     if ( widgetID.indexOf(groupableIDs[i]) !== -1 ) {
       return true;
     }
   }
   return false;
 }

} );
