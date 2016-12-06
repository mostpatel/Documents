/**
 * COD Check JS
 *
 * @category    JS
 * @package     Gremlin_Codcheck
 * @author      Junaid Bhura <info@gremlin.io>
 */

/*-----------------------------------------------------------------------------------*/
/*	COD Check AJAX
/*-----------------------------------------------------------------------------------*/
document.observe( 'dom:loaded', function() {

	$$( '#gremlin-codcheck .check-zip' ).invoke( 'observe', 'click', function( e ) {
		// Validate ZIP code
		var the_zip_code = $( 'gremlin-zip' ).value;
		if ( the_zip_code == '' ) {
			$$( '#gremlin-codcheck .cod-available' ).each( function (e) { e.setStyle({ display: 'none' }); } );
			$$( '#gremlin-codcheck .cod-not-available' ).each( function (e) { e.setStyle({ display: 'none' }); } );
			$$( '#gremlin-codcheck .cod-validation' ).each( function (e) { e.setStyle({ display: 'block' }); } );
			$$( '#gremlin-codcheck .cod-error' ).each( function (e) { e.setStyle({ display: 'none' }); } );

			return;
		}

		// Show loading image and reset messages
		$$( '#gremlin-codcheck .loading-spinner' ).each( function (e) { e.setStyle({ display: 'inline-block' }); } );
		$$( '#gremlin-codcheck .cod-available' ).each( function (e) { e.setStyle({ display: 'none' }); } );
		$$( '#gremlin-codcheck .cod-not-available' ).each( function (e) { e.setStyle({ display: 'none' }); } );
		$$( '#gremlin-codcheck .cod-validation' ).each( function (e) { e.setStyle({ display: 'none' }); } );
		$$( '#gremlin-codcheck .cod-error' ).each( function (e) { e.setStyle({ display: 'none' }); } );

		// Send an AJAX request
		new Ajax.Request( gremlin_codcheck_ajax_url, {
			method: 'post',
			parameters: { zip_code: the_zip_code },
			asynchronous: true,
			onSuccess: function( response ) {
				var json_response = response.responseText.evalJSON();
				
				if ( json_response.status == 'success' ) {
					if ( json_response.allowed_zip == true ) {
						$$( '#gremlin-codcheck .cod-available' ).each( function (e) { e.setStyle({ display: 'block' }); } );
						$$( '#gremlin-codcheck .cod-not-available' ).each( function (e) { e.setStyle({ display: 'none' }); } );
						$$( '#gremlin-codcheck .cod-validation' ).each( function (e) { e.setStyle({ display: 'none' }); } );
						$$( '#gremlin-codcheck .cod-error' ).each( function (e) { e.setStyle({ display: 'none' }); } );
					}
					else {
						$$( '#gremlin-codcheck .cod-available' ).each( function (e) { e.setStyle({ display: 'none' }); } );
						$$( '#gremlin-codcheck .cod-not-available' ).each( function (e) { e.setStyle({ display: 'block' }); } );
						$$( '#gremlin-codcheck .cod-validation' ).each( function (e) { e.setStyle({ display: 'none' }); } );
						$$( '#gremlin-codcheck .cod-error' ).each( function (e) { e.setStyle({ display: 'none' }); } );
					}
				}
				else {
					$$( '#gremlin-codcheck .cod-available' ).each( function (e) { e.setStyle({ display: 'none' }); } );
					$$( '#gremlin-codcheck .cod-not-available' ).each( function (e) { e.setStyle({ display: 'none' }); } );
					$$( '#gremlin-codcheck .cod-validation' ).each( function (e) { e.setStyle({ display: 'none' }); } );
					$$( '#gremlin-codcheck .cod-error' ).each( function (e) { e.setStyle({ display: 'block' }); } );
				}

				$$( '#gremlin-codcheck .loading-spinner' ).each( function (e) { e.setStyle({ display: 'none' }); } );
			},
			onException: function( request, ex ) {
				//alert( ex.toSource() );
			},
			onFailure: function() {
				$$( '#gremlin-codcheck .cod-available' ).each( function (e) { e.setStyle({ display: 'none' }); } );
				$$( '#gremlin-codcheck .cod-not-available' ).each( function (e) { e.setStyle({ display: 'none' }); } );
				$$( '#gremlin-codcheck .cod-validation' ).each( function (e) { e.setStyle({ display: 'none' }); } );
				$$( '#gremlin-codcheck .cod-error' ).each( function (e) { e.setStyle({ display: 'block' }); } );

				$$( '#gremlin-codcheck .loading-spinner' ).each( function (e) { e.setStyle({ display: 'none' }); } );
			}
		});

		e.stop();
	});

});
