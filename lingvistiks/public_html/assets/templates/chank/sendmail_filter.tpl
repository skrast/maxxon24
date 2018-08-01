<div class="block-margin-n20">
	<form method="post" action="{{ ABS_PATH_ADMIN_LINK }}?do=sendmail{% if REQUEST.sub == 'contact' %}&sub=contact{% endif %}">
	    <input type="hidden" name="csrf_token" value="{{ csrf_token }}">

	    <div class="form-group filter-search">
			<i class="fa fa-search icon-search"></i>
	    	<input class="form-control inline-search" placeholder="{{ lang.search_placeholder }}" name="q" value="{{ REQUEST.q|escape|stripslashes }}" autocomplete="off" type="text">
	    </div>
	</form>
</div>

<div class="block-margin-n20">
	<p><b>{{ lang.sendmail_folder }}</b></p>
	<ul class="nav nav-pills nav-stacked nav-sm">
		<li {% if not REQUEST.folder %}class="active"{% endif %}><a href="{{ ABS_PATH_ADMIN_LINK }}?do=sendmail"><i class="fa fa-flag fa-fw"></i> {{ lang.sendmail_folder_all }}</a></li>
	    {% for key, folder in mail_folder %}
	        <li {% if REQUEST.folder == key %}class="active"{% endif %}><a href="{{ ABS_PATH_ADMIN_LINK }}?do=sendmail&folder={{ key }}"><i class="fa fa-{{ folder.icon }} fa-fw"></i> {{ folder.title }}</a></li>
	    {% endfor %}
	</ul>
</div>

<div class="block-margin-n20">
	<p><b>{{ lang.sendmail_label }}</b></p>
	<ul class="nav nav-pills nav-stacked nav-sm">
		{% for key, label in mail_label %}
	        <li {% if REQUEST.label == key %}class="active"{% endif %}><a href="{{ ABS_PATH_ADMIN_LINK }}?do=sendmail&label={{ key }}"><i class="fa fa-fw fa-circle-o text-{{ label.color }}"></i> {{ label.title }}</a></li>
	    {% endfor %}
	</ul>
</div>

{% for hook_name, hook_value in hook_sendmail_start %}
	{{ hook_value.bild }}
{% endfor %}
