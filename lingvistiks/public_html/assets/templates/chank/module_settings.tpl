<form method="post" action="{{ ABS_PATH_ADMIN_LINK }}?do=module&sub=module_settings">
    <input type="hidden" name="csrf_token" value="{{ csrf_token }}">
    <input type="hidden" name="void_id" value="{{ module_info.module_id }}">
    <input type="hidden" name="save" value="1">

    <div class="form-group">
		<label for="">{{ lang.module_settings_desc }}</label>
		<ul class="list-unstyled">
			{% for key, value in module_permission %}
				<li>
					<div class="checkbox checkbox-primary">
						<input id="perm{{ key }}" type="checkbox" name="module_permission[]" value="{{ key }}" {% if key in module_info.module_hook %}checked{% endif %}>
						<label for="perm{{ key }}">{{ value }}</label>
					</div>
				</li>
			{% endfor %}
		</ul>
    </div>

    <div class="clearfix"></div><br>

    <div class="actions">
        <input class="btn-flat gray" value="{{ lang.save_data }}" type="submit">
    </div>
</form>
