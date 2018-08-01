<form role="form" enctype="multipart/form-data" method="post" action="{{ ABS_PATH_ADMIN_LINK }}?do=sendmail&sub=sendmail_signature_work" class="">
	<input type="hidden" name="csrf_token" value="{{ csrf_token }}">
	<input type="hidden" name="save" value="1">
    <input type="hidden" name="void_id" value="{{ signature_info.signature_id }}">

	<div class="form-group">
		<label for="">{{ lang.sendmail_signature_title }}</label>
		<input type="text" name="signature_title" class="form-control" value="{{ signature_info.signature_title|escape|stripslashes }}" placeholder="{{ lang.sendmail_signature_title }}">
    </div>

	<div class="form-group">
		<textarea class="form-control" name="signature_desc" cols="30" rows="5" placeholder="{{ lang.sendmail_signature_desc }}">{{ signature_info.signature_desc|stripslashes }}</textarea>
    </div>

    <div class="actions">
        <input type="submit" class="btn-flat gray" value="{{ lang.save_data }}">
    </div>
</form>


<script>
    $(function () {
        $(".tagit-close").on('click',function() {
            $(this).parent().fadeOut().remove();
            return false;
        });

		$(".insert_template").on('change',function(event) {
			event.stopPropagation();
			var template_id = $(this).val();

			$.ajax({
				url: ave_path+'?do=template&sub=get_template_html',
				data: {'type': 2, 'template_id': template_id, csrf_token: csrf_token},
				success: function( data ) {
					if(data.status == "success") {
						$('.summernote[name=mail_body]').summernote('code', data.html);
						//$("textarea[name=sms_text]").val(data.html);
					}
				},
			});
			return false;
		});


		var search_index_email = {
			serviceUrl: ave_path+'?do=sendmail&sub=email_search',
			minChars: 2,
			delimiter: /(,|;)\s*/,
			maxHeight: 400,
			width: 300,
			zIndex: 99999,
			deferRequestBy: 500,
			onSelect: function(suggestion) {
				switch (suggestion.data) {
					case 'company':
						$("#email-to").append('<li>'+suggestion.value+'<a class="tagit-close"><span class="text-icon">×</span></a><input value="'+suggestion.company_email+'" name="company_email['+suggestion.company_id+'][]" type="hidden"></li>');
					break;
					case 'users':
						$("#email-to").append('<li>'+suggestion.value+'<a class="tagit-close"><span class="text-icon">×</span></a><input value="'+suggestion.user_email+'" name="user_email['+suggestion.user_id+'][]" type="hidden"></li>');
					break;
				}

				init_refresh_script();
				return false;
			},
		};
		$("#search_index_email").autocomplete(search_index_email); // поиск получателей в письмах
    });
</script>
