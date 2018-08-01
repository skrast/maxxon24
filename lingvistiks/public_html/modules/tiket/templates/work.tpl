<div class="menubar">
	<div class="page-title">
		<a href="{{ ABS_PATH_ADMIN_LINK }}?do=module&sub=mod_edit&module_tag=tiket&module_action=tiket_start" class="bread"><i class="fa fa-angle-double-left"></i></a>

		<h1>{% if tiket_info %}{{ lang.tiket_edit }}: {{ tiket_info.tiket_title|escape|stripslashes }}{% else %}{{ lang.tiket_add }}{% endif %}</h1>
		<div class="clearfix"></div>

        {% if tiket_info %}
			<ul class="page-title-menu list-inline">
				<li>
					<a href="{{ ABS_PATH_ADMIN_LINK }}?do=module&sub=mod_edit&module_tag=tiket&module_action=tiket_open&tiket_id={{tiket_info.tiket_id|escape|stripslashes}}" class="btn-flat gray">{{ lang.tiket_open }}</a>
				</li>
				{% if SESSION.alles %}
					<li>
						<a href="{{ ABS_PATH_ADMIN_LINK }}?do=module&sub=mod_edit&module_tag=tiket&module_action=tiket_work&delete_tiket_id={{ tiket_info.tiket_id }}" class="btn-flat danger confirm"><i class="fa fa-times"></i> {{ lang.tiket_delete }}</a>
					</li>
				{% endif %}
			</ul>
		{% endif %}
	</div>
</div>

<div class="datatables">
    <div class="content-wrapper">

		<form role="form" enctype="multipart/form-data" method="post" action="{{ ABS_PATH_ADMIN_LINK }}?do=module&sub=mod_edit&module_tag=tiket&module_action=tiket_work" class="ajax_form">
		    <input type="hidden" name="csrf_token" value="{{ csrf_token }}">
		    <input type="hidden" name="save" value="1">
		    <input type="hidden" name="tiket_id" value="{{ tiket_info.tiket_id }}">

			<div class="form-group">
				<label>{{ lang.tiket_title }}</label>
				<input type="text" name="tiket_title" class="form-control" value="{{ tiket_info.tiket_title|escape|stripslashes }}" placeholder="{{ lang.tiket_title }}" pattern=".{3,}">
			</div>

			<div class="form-group">
		        <label>{{ lang.tiket_group }}</label>
				<select class="selectpicker" name="tiket_group">
					<option value=""></option>
					{% for group in tiket_group_list %}
						<option value="{{group.tiket_group_id}}" {% if group.tiket_group_id==tiket_info.tiket_group.tiket_group_id %}selected{% endif %}>{{group.tiket_group_title|escape|stripslashes}}</option>
					{% endfor %}
				</select>
		    </div>

			<div class="form-group">
				<label>{{ lang.tiket_text }}</label>
				<textarea name="tiket_text" id="" cols="30" rows="10" class="form-control summernote" placeholder="{{ lang.tiket_text }}">{{tiket_info.tiket_text|stripslashes}}</textarea>
			</div>

			<div class="form-group">
				<label>{{ lang.tiket_tags }}</label>
				<input type="text" name="tiket_tags" class="form-control" value="{{ tiket_info.tiket_tags|escape|stripslashes }}" placeholder="{{ lang.tiket_tags }}">
			</div>

		    <div class="clearfix"></div>

		    <div class="actions">
		        <input type="submit" class="btn-flat gray" value="{{ lang.save_data }}">
		    </div>
		</form>

    </div>
</div>
