<ul class="nav navbar-nav nav-top-link">
	<li class="hidden-xs dropdown nav-top-link-add">
		<a aria-expanded="false" data-toggle="dropdown" href="#" class="dropdown-toggle"><i class="fa fa-plus-circle" aria-hidden="true"></i></a>

		<ul class="dropdown-menu dropdown-notification">

            <li class="notification-item">
                <a class="get_ajax_form" href="" data-void="" data-type="sendmail" data-sub="compose" data-ajax="1">
                    <i class="fa fa-inbox"></i> {{ lang.add_sendmail }}
                </a>
            </li>

		</ul>
	</li>

	{% if SESSION.alles or SESSION.module_access %}
		{% if module_list %}
		<li class="dropdown">
			<a aria-expanded="false" href="{{ ABS_PATH_ADMIN_LINK }}?do=module" data-toggle="dropdown" class="{% if REQUEST.do == "module" %}active{% endif %} dropdown-toggle">
				<i class="fa fa-microchip"></i>
				<span>{{ lang.module }}</span>
				{% if module_list %}&nbsp<i class="caret"></i>{% endif %}
			</a>

			<ul class="dropdown-menu dropdown-notification pull-right">
				{% for module in module_list %}
		        <li class="notification-item">
		            <a href="{{ ABS_PATH_ADMIN_LINK }}?do=module&sub=mod_edit&module_tag={{ module.module_tag }}&module_action={{ module.module_tag }}_start" {% if REQUEST.module_tag == module.module_tag %}class="active"{% endif %}>
		                <span>{{ module.module_name }}</span>
		            </a>
		        </li>
				{% endfor %}
				<li role="separator" class="divider"></li>
				<li class="notification-item">
					<a href="{{ ABS_PATH_ADMIN_LINK }}?do=module">
						<span>{{ lang.module }}</span>
					</a>
				</li>
			</ul>
		{% else %}
		<li data-toggle="tooltip" data-placement="bottom" title="{{lang.module}}">
			<a href="{{ ABS_PATH_ADMIN_LINK }}?do=module" {% if REQUEST.do == "module" %}class="active"{% endif %}>
				<i class="fa fa-microchip"></i>
				<span>{{ lang.module }}</span>
			</a>
		{% endif %}
	</li>
	{% endif %}
</ul>
