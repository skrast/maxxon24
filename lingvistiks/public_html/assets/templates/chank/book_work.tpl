<form method="post" action="{{ ABS_PATH_ADMIN_LINK }}?do=book&sub=book_work">
	<input type="hidden" name="csrf_token" value="{{ csrf_token }}">
	<input type="hidden" name="void_id" value="{{ void_id }}">
	<input type="hidden" name="save" value="1">

	<div class="form-group">
		<label for="">{{ lang.book_title }}</label>
		<input type="text" value="{{book.title_default|escape|stripslashes}}" class="form-control" name="title" placeholder="{{ lang.book_title }}">
	</div>

	<div class="form-group">
		<label>{{ lang.book_title_lang }}</label>

		<ul class="list-unstyled">
			{% for lang_var in lang_array %}
				<li class="row">
					<div class="col-md-3">
						{{ lang_var|escape|stripslashes }}
					</div>
					<div class="col-md-9">
						<input type="text" class="form-control input-sm" name="title_lang[{{ lang_var|escape|stripslashes }}]" autocomplete="off" placeholder="{{ lang.book_title }}" value="{{ book.title_lang[lang_var]|escape|stripslashes }}">
					</div>
				</li>
			{% endfor %}
		</ul>
	</div>

	<div class="form-group">
		<label for="">{{ lang.book_types }}</label>
		<div class="clearfix"></div>
		<select name="essense_id" class="selectpicker">
			{% for key, value in book_type_array %}
				<option value="{{ key }}" {% if book.type == key or (not book.type and key == essense_id) %}selected{% endif %}>{{ value.title }}</option>
			{% endfor %}
		</select>
	</div>

	<div class="form-group">
		<label for="">{{ lang.book_color }}</label>
		<input type="text" value="{{book.color|escape|stripslashes}}" class="form-control colorpicker" name="color" placeholder="{{ lang.book_color }}">
	</div>

	<div class="actions">
		<input type="submit" class="btn-flat gray" value="{{ lang.save_data }}">
	</div>
</form>

<link href="{{ ABS_PATH }}vendor/assets/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css" rel="stylesheet">
<script src="{{ ABS_PATH }}vendor/assets/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>

<script>
    $(function(){
        $('.colorpicker').colorpicker();
    });
</script>
