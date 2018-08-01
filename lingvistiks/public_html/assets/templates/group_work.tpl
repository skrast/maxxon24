<div class="menubar">
    <div class="page-title">
        <a href="{{ ABS_PATH_ADMIN_LINK }}?do=group" class="bread"><i class="fa fa-angle-double-left"></i></a>

        <h1>
            {% if REQUEST.group_id %}
                {{ group_info.user_group_name|escape|stripslashes }}
            {% else %}
                {{ lang.group_add }}
            {% endif %}
        </h1>
    </div>
</div>

<div class="datatables">
    <div class="content-wrapper">
        {% if REQUEST.error %}
            <div class="row filter-block">
                <div class="alert alert-danger" role="alert">{{ lang.group_error }}</div>
            </div>
        {% endif %}

        <form role="form" enctype="multipart/form-data" method="post" action="{{ ABS_PATH_ADMIN_LINK }}?do=group&sub=work_group">
            <input type="hidden" name="csrf_token" value="{{ csrf_token }}">
            <input type="hidden" name="save" value="1">
            <input type="hidden" name="group_id" value="{{ REQUEST.group_id|escape|stripslashes }}">

            <div class="form-group">
				<label for="">{{ lang.group_title }}</label>
                <input type="text" name="user_group_name" class="form-control" placeholder="{{ lang.group_title|escape|stripslashes }}" value="{{ group_info.user_group_name|escape|stripslashes }}">
			</div>

            <div class="form-group">
				<label for="">{{ lang.group_desc }}</label>
                <textarea class="form-control" rows="3" name="user_group_desc" placeholder="{{ lang.group_desc|escape|stripslashes }}">{{ group_info.user_group_desc|escape|stripslashes }}</textarea>
            </div>

            {% if active_modules %}
				<div class="form-group">
					<label for="">{{ lang.group_module }}</label>
					<ul class="list-unstyled">
						{% for module in active_modules %}
	                    	<li>
		                        <div class="checkbox checkbox-primary">
		                            <input id="module{{loop.index}}" type="checkbox" name="module[]" value="{{ module.module_tag }}"{% if (module.module_tag in group_info.user_group_module) %} checked="checked"{% endif %} />
		                            <label for="module{{loop.index}}">{{ module.module_name }}</label>
		                        </div>
		                    </li>
	                	{% endfor %}
					</ul>
				</div>
			{% endif %}

            <div class="form-group">
				<label for="">{{ lang.group_permission }}</label>
				<ul class="list-unstyled">
					{% for perm,value in main_permissions %}
                    	<li>
	                        <div class="checkbox checkbox-primary">
	                            <input id="checkbox{{loop.index}}" type="checkbox" name="perms[]" value="{{ perm }}"{% if (perm in group_permissions) %} checked="checked"{% endif %}{% if ('alles' in group_permissions) and perm != 'alles' %} disabled="disabled"{% endif %} />
	                            <label for="checkbox{{loop.index}}">{{ value }}</label>
	                        </div>
	                    </li>
                	{% endfor %}
				</ul>
			</div>

            <div class="actions">
                <input type="submit" class="btn-flat gray" value="{{ lang.save_data }}">
            </div>
        </form>
    </div>
</div>
<script>
    $(function () {
        $("input[value=alles]").on('click',function() {
            if($(this).prop("checked")) {
                $("input[type=checkbox]").not("input[value=alles]").attr("disabled", true);
            } else {
                $("input[type=checkbox]").not("input[value=alles]").attr("disabled", false);
            }
        });
    });
</script>
