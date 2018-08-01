<form action="{{ HOST_NAME }}/add_reklama/" method="post" class="ajax_form">
	<input type="hidden" name="csrf_token" value="{{ csrf_token }}">
	<input type="hidden" name="save" value="1">

	<div class="row">
		<div class="col-md-12">
			<p class="text-note">{{ lang.page_advert_desc }}</p>
			<div class="form-group">
				<select class="form-control" name="adv_theme">
					{% for theme in theme_list %}
						<option value="{{ theme.id }}">{{ theme.title|escape|stripslashes }}</option>
					{% endfor %}
				</select>
			</div>
			<div class="form-group">
				<textarea class="form-control" name="adv_message" placeholder="{{ lang.page_advert_message }}" rows="4"></textarea>
			</div>
			<button type="submit" class="btn btn-block btn-go-on">{{ lang.btn_send }}</button>
			<p class="error-message-color error-message">{{ lang.btn_field_requered }}</p>
		</div>
	</div>
</form>
