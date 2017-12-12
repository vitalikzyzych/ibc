(function( $ ) {
	'use strict';

	$(document).ready( function() {

		$('#gallery-upload-button').click(function (e) {
			var el = $(this).parent().parent();
			var button = $(this);
			e.preventDefault();
			var uploader = wp.media({
				title : button.data('upload-title'),
				button : {
					text : button.data('upload-button')
				},
				editing:    true,
				type:	'image',
				multiple : 'add'
			})
			.on('select', function () {
				var selection = uploader.state().get('selection');
				var attachment = selection.toJSON();
				var list = '';
				$( '.images .attachment-thumbnail', el ).each( function( index ){
					$(this).remove();
				});
				$.each( attachment, function( index, image ){
					$( '.images', el ).append('<img src="'+ image.sizes.thumbnail.url +'" class="attachment-thumbnail">');
					list += image.id + ',';
				});
				$( '#curly_galleries', el ).val( list ).trigger( 'change' );
				if (!el.hasClass('upload_file')) {
					if ($('img', el).length > 0) {
						$('.image-preview', el).attr('src', attachment.url);
					} else {
						$('<img src="'+ attachment.url +'" class="image-preview">').insertBefore($(':last-child', el));
						$('.image-clear-button', el).attr('style', 'display:inline-block');
					}
				}
			})
			.open();
		});


		$('#gallery-clear-button').click(function (e) {

			e.preventDefault();

			$('#curly_galleries').val('');
			$('#gallery-images').children().remove();
		});
	});

})( jQuery );
