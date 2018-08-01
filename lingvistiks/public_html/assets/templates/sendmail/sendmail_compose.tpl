<form role="form" enctype="multipart/form-data" method="post" action="{{ ABS_PATH_ADMIN_LINK }}?do=sendmail&sub=save_mail" class="">
	<input type="hidden" name="csrf_token" value="{{ csrf_token }}">
    <input type="hidden" name="mail_id" value="{{ email_info.mail_id }}">

        <div class="form-group">
			<ul id="email-to" class="list-inline inverse tagit">
				{% if email_info.mail_email_user_explode %}
					{% for email, mail_email in email_info.mail_email_user_explode %}
						<li>
							{{ mail_email.user_name }}: {{ email }}
							<a class="tagit-close">
								<span class="text-icon">×</span>
							</a>

							<input value="{{ email }}" name="user_email[{{ mail_email.id }}][]" type="hidden">
						</li>
					{% endfor %}
				{% endif %}

				{% if email_info.mail_email_company_explode %}
					{% for email, mail_email in email_info.mail_email_company_explode %}
						<li>
							{{ mail_email.company_title }}: {{ email }}
							<a class="tagit-close">
								<span class="text-icon">×</span>
							</a>

							<input value="{{ email }}" name="company_email[{{ mail_email.company_id }}][]" type="hidden">
						</li>
					{% endfor %}
				{% endif %}
			</ul>
            <div class="clearfix"></div>
        	<div class="block-margin-n20">
                <label for="">{{ lang.sendmail_search_contact }}</label>
                <input id="search_index_email" type="text" class="form-control" value="" placeholder="{{ lang.sendmail_search_contact_exz }}">
            </div>

            <div class="block-margin-n20">
                <label for="">{{ lang.sendmail_search_contact_dop }}</label>
                <input type="text" class="form-control" value="{{ email_info.mail_email_dop|escape|stripslashes }}" name="mail_email_dop" placeholder="{{ lang.sendmail_search_contact_dop_exz }}">
            </div>
        </div>

    	<div class="form-group">
            <label for="">{{ lang.sendmail_mail_title }}</label>
            <input type="text" name="mail_title" class="form-control" value="{{ email_info.mail_title|escape|stripslashes }}" placeholder="{{ lang.sendmail_mail_title }}">
        </div>

    	<div class="form-group">
            <label for="">{{ lang.sendmail_mail_desc }}</label>
            <textarea name="mail_body" id="" class="form-control summernote" cols="30" rows="10">{{email_info.mail_body|stripslashes}}</textarea>
        </div>

		{% if signature_list %}
			<div class="form-group">
				<label for="">{{ lang.sendmail_signature }}</label>
				<select class="selectpicker" name="mail_signature">
					<option value=""></option>
					{% for signature in signature_list %}
						<option value="{{ signature.signature_id }}" {% if signature.signature_id == email_info.mail_signature %}selected{% endif %}>{{ signature.signature_title|escape|stripslashes }}</option>
					{% endfor %}
				</select>
			</div>
		{% endif %}

        <div class="form-group">
            <div class="checkbox checkbox-primary">
                <input id="mail_draft" name="mail_draft" value="1" type="checkbox" {% if email_info.mail_draft == 1 %}checked{% endif %}>
                <label for="mail_draft">
                    {{ lang.sendmail_save_draft }}
                </label>
            </div>

            <div class="checkbox checkbox-primary">
                <input id="mail_track" name="mail_track" value="1" type="checkbox" {% if email_info.mail_track == 1 %}checked{% endif %}>
                <label for="mail_track">
                    {{ lang.sendmail_tracker }}
                </label>
            </div>
        </div>

    <div class="actions">
        <input type="submit" class="btn-flat gray" value="{{lang.save_data}}">
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
