(function ( $ ) {
	"use strict";

	$(function () {

		if ($('body.post-php').length === 0){
			return;
		}
		var mediaTypes = [
			{name:'Photo', value:'photo'},
			{name:'Youtube', value:'youtube'}
		];


		$('.add-new-ingredients-item').click(function(event){
			var $this = $(this),
				$parent = $this.parent(),
				form_name = $parent.data('name'),
				$rows = $parent.find('.ingredients_rows'),
				html = '';

			html += '<li class="ingredients_row">';
			html += '<span class="ingredients_move"></span>';
			html += '<select class="ingredients_type" name="'+form_name+'_type[]">';
			for(var index = 0; index < mediaTypes.length; index++) {
				var name = mediaTypes[index]['name'],
					value = mediaTypes[index]['value'];
				html += '<option value="'+value+'">'+name+'</option>';
			}
			html += '</select>';
			html += '<div>';
			html += '<input class="ingredients_url" name="'+form_name+'_url[]" type="text" readonly="readonly" onfocus="this.blur();" value="">';
			html += '<a href="javascript:;" class="ingredients_url_button button">Select Image</a>';
			html += '</div>';
			html += '<div>';
			html += '<input class="ingredients_cover" name="'+form_name+'_cover[]" type="text" readonly="readonly" onfocus="this.blur();" style="display: none;" value="">';
			html += '<a href="javascript:;" class="ingredients_cover_button button" style="display: none;">Select Cover</a>';
			html += '</div>';
			html += '<input class="ingredients_title" name="'+form_name+'_title[]" type="text" placeholder="Media title" value="">';
			html += '<a class="ingredients_close">close</a>';
			html += '</li>';

			$rows.append(html);

			$( ".ingredients_rows" ).sortable();

		});

		var _custom_media = true,
			_orig_send_attachment = wp.media.editor.send.attachment,
			$metabox = $('#ingredients_metabox');

		$metabox.on('click', '.ingredients_url_button', function(e) {
			var send_attachment_bkp = wp.media.editor.send.attachment,
				$button = $(this),
				$input = $button.parent().find('.ingredients_url');
			_custom_media = true;
			wp.media.editor.send.attachment = function(props, attachment){
				if ( _custom_media ) {
					$input.val(attachment.url);
				} else {
					return _orig_send_attachment.apply( this, [props, attachment]);
				}
			};

			wp.media.editor.open($button);
			return false;
		}).on('click', '.ingredients_cover_button', function(e) {
			var send_attachment_bkp = wp.media.editor.send.attachment,
				$button = $(this),
				$input = $button.parent().find('.ingredients_cover');
			_custom_media = true;
			wp.media.editor.send.attachment = function(props, attachment){
				if ( _custom_media ) {
					$input.val(attachment.url);
				} else {
					return _orig_send_attachment.apply( this, [props, attachment]);
				}
			};

			wp.media.editor.open($button);
			return false;
		}).on('change', '.ingredients_type', function(e) {
			var $this = $(this),
				val = $this.val(),
				$url = $this.parent().find('.ingredients_url'),
				$urlButton = $this.parent().find('.ingredients_url_button'),
				$cover = $this.parent().find('.ingredients_cover'),
				$coverButton = $this.parent().find('.ingredients_cover_button');
			if (val === 'photo'){
				$url.attr({
					readonly:"readonly",
					onfocus:"this.blur();",
					placeholder: "Photo Path"
				}).val('');
				$cover.val('').hide();
				$urlButton.show();
				$coverButton.hide();
			} else { //video
				$url.attr({
					placeholder: "Youtube Url"
				}).removeAttr('onfocus').removeAttr('readonly').val('');
				$cover.val('').show();
				$urlButton.hide();
				$coverButton.show();
			}
		});

		$('.add_media').on('click', function(){
			_custom_media = false;
		});

		$( ".ingredients_rows" ).sortable();

		$('#ingredients').on('click', 'a.ingredients_close', function(){
			var $this = $(this);
			$this.closest('li').remove();
		});
	});

}(jQuery));