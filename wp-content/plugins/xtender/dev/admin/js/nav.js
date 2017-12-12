( function( $ ) {

	$(document).ready(function() {
		$('.btn-upload-image, .image-preview').on('click', upload_image );

		function upload_image(e) {
			e.preventDefault();

			var el = $(this).parent('.field-custom');
			var button = $(this);
			var remove = $(this).siblings('.image-remove');
			e.preventDefault();
			var uploader = wp.media({
				title : button.data('upload-title'),
				button : {
					text : button.data('upload-button')
				},
				multiple : false
			})
			.on('select', function () {
				var selection = uploader.state().get('selection');
				var attachment = selection.first().toJSON();
				$('input[type=hidden]', el).val(attachment.url);
				if (!el.hasClass('upload_file')) {
					if ($('img', el).length > 0) {
						$('.image-preview', el).attr('src', attachment.url);
					} else {
						$('<img src="'+ attachment.url +'" class="image-preview">').insertBefore(remove).on('click', upload_image );
						$('.image-remove', el).css( 'display', 'block' );
						$('.btn-upload-image', el).css( 'display', 'none' );
					}
				}
			})
			.open();
		}

		$('.image-remove').on('click', function (e) {
			e.preventDefault();

			$(this).siblings('.image-preview').remove();
			$(this).siblings('.btn-upload-image').css( 'display', 'block' );
			$(this).css( 'display', 'none' );
		});

	});

} )( jQuery );
