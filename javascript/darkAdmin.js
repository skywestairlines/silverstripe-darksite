(function($) {
	$(document).ready(function() {
		
		$('#right input:submit').unbind('click').die().live('click', function() {
			var form = $('#right form');
			var formAction = form.attr('action') + '?' + $(this).fieldSerialize();
			if(typeof tinyMCE != 'undefined') {
				tinyMCE.triggerSave();
			}
			
			
			$.ajax({
				type: 'POST',
				url: formAction,
				data: form.formToArray(),
				dataType: "json",
				success: function(json) {
					tinymce_removeAll();
					
					$('#right #ModelAdminPanel').html(json.html);
					if($('#right #ModelAdminPanel form').hasClass('validationerror')) {
						statusMessage(ss.i18n._t('ModelAdmin.VALIDATIONERROR', 'Validation Error'), 'bad');
					} else {
						statusMessage(json.message, json.class);
					}
					
					Behaviour.apply();
					if(window.onResize) {
						window.onResize();
					}
					$('ul.tabstrip li').click(function(){
						
						$('.tab.current').height("auto");
					});
				}
			});
			return false;
		});
		
	}); 
})(jQuery);