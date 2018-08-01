<div class="menubar">
    <div class="page-title">
        <h1>{{ lang.translate_name }}</h1>

		<div class="clearfix"></div>

		{% if lang_var %}
	        <ul class="page-title-menu list-inline">
				<li><a href="" class="get_ajax_form btn-flat gray" data-type="translate" data-sub="translate_copy" data-void="{{ lang_default|escape|stripslashes }}" data-ajax="1" data-module="1"><i class="fa fa-clock-o"></i> {{ lang.translate_copy }}</a></li>
			</ul>
		{% endif %}
    </div>
</div>

{% spaceless %}
<div class="datatables">
    <div class="content-wrapper sendmail">

		<div class="row">
			<div class="col-lg-3 col-md-4">
				<div class="block-margin-n20">
					<ul class="nav nav-pills nav-stacked nav-sm">
						{% for lang in lang_array %}
		                    <li {% if lang == REQUEST.lang %}class="active"{% endif %}>
								<a href="{{ ABS_PATH_ADMIN_LINK }}?do=module&sub=mod_edit&module_tag=translate&module_action=translate_start&lang={{ lang|escape|stripslashes }}">{{ lang|escape|stripslashes }}</a>
							</li>
		                {% endfor %}
					</ul>
				</div>
			</div>

			<div class="col-lg-9 col-md-8">

				{% if not lang_var %}
					<p>{{ lang.translate_var_all }}</p>
				{% endif %}

				{% if lang_var %}

					{% if lang_array %}
						<label for="">{{ lang.translate_sourse_core }}</label>
						<ul class="list-inline tagit list_tagit">
							{% for var in lang_var %}
								<li><a href="?do=module&sub=mod_edit&module_tag=translate&module_action=translate_start&lang={{ lang_default|escape|stripslashes }}&var={{ var|escape|stripslashes }}&type=core">{{ var|escape|stripslashes }}</a></li>
							{% endfor %}
						</ul>
					{% endif %}

					{% if lang_var_module %}
						<label for="">{{ lang.translate_sourse_module }}</label>
						<ul class="list-inline tagit list_tagit">
							{% for var in lang_var_module %}
								<li><a href="?do=module&sub=mod_edit&module_tag=translate&module_action=translate_start&lang={{ lang_default|escape|stripslashes }}&var={{ var|escape|stripslashes }}&type=module">{{ var|escape|stripslashes }}</a></li>
							{% endfor %}
						</ul>
					{% endif %}

					{% if lang_var_notif %}
						<label for="">{{ lang.translate_sourse_notif }}</label>
						<ul class="list-inline tagit list_tagit">
							{% for var in lang_var_notif %}
								<li><a href="?do=module&sub=mod_edit&module_tag=translate&module_action=translate_start&lang={{ lang_default|escape|stripslashes }}&var={{ var|escape|stripslashes }}&type=notif">{{ var|escape|stripslashes }}</a></li>
							{% endfor %}
						</ul>
					{% endif %}

					{% if lang_var_site %}
						<label for="">{{ lang.translate_sourse_site }}</label>
						<ul class="list-inline tagit list_tagit">
							{% for var in lang_var_site %}
								<li><a href="?do=module&sub=mod_edit&module_tag=translate&module_action=translate_start&lang={{ lang_default|escape|stripslashes }}&var={{ var|escape|stripslashes }}&type=site">{{ var|escape|stripslashes }}</a></li>
							{% endfor %}
						</ul>
					{% endif %}

					{% if lang_sourse %}
						<form class="ajax_form" role="form" enctype="multipart/form-data" method="post" action="{{ ABS_PATH_ADMIN_LINK }}?do=module&sub=mod_edit&module_tag=translate&module_action=translate_work" data-ajax="1">
						    <input type="hidden" name="csrf_token" value="{{ csrf_token }}">
						    <input type="hidden" name="save" value="1">
						    <input type="hidden" name="lang" value="{{ lang_default|escape|stripslashes }}">
						    <input type="hidden" name="var" value="{{ var|escape|stripslashes }}">
						    <input type="hidden" name="type" value="{{ type|escape|stripslashes }}">

							<div class="form-group">
								<label for="">{{ lang.translate_sourse }} {{ var|escape|stripslashes }}</label>
								<textarea id="codemirror" name="lang_sourse">
									{{ lang_sourse }}
								</textarea>
							</div>

							<div class="clearfix"></div>
							<p class="help-block">{{ lang.translate_sourse_help }}</p>

						    <div class="actions">
						        <input type="submit" class="btn-flat gray" value="{{ lang.save_data }}">
						    </div>
						</form>

<link rel="stylesheet" href="{{ ABS_PATH }}assets/build/css/codemirror.min.css" type="text/css">
<script src="{{ ABS_PATH }}assets/build/js/codemirror.min.js" type="text/javascript"></script>
<script>
  var editor = CodeMirror.fromTextArea(document.getElementById("codemirror"), {
	  lineNumbers: true,
      styleActiveLine: true,
      matchBrackets: true,
	  theme: 'neo',
	  viewportMargin: 50,
	  tabMode: 'shift',
  });
</script>
<style>
.CodeMirror {
    border: 1px solid #eee;
    height: 100%;
}
</style>
					{% endif %}

				{% else %}
		            <p>{{ lang.empty_data }}</p>
		        {% endif %}

			</div>
		</div>
	</div>
</div>
{% endspaceless %}