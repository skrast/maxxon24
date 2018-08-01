<div class="menubar">
    <div class="page-title">
        <a href="{{ ABS_PATH_ADMIN_LINK }}?do=module&sub=mod_edit&module_tag=page&module_action=page_start" class="bread"><i class="fa fa-angle-double-left"></i></a>

        <h1>{% if page_info.page_id %}{{ page_info.page_title|escape|stripslashes }}{% else %}{{ lang.page_add }}{% endif %}</h1>

		<div class="clearfix"></div>
        <ul class="page-title-menu list-inline">
            <li>
                <a class="btn-flat gray" href="{{ HOST_NAME }}/{{page_info.page_alias|escape|stripslashes}}" target="_blank"><i class="fa fa-external-link" aria-hidden="true"></i> {{ HOST_NAME }}/{{page_info.page_alias|escape|stripslashes}}</a>
            </li>
        </ul>
    </div>
</div>

<div class="datatables">
    <div class="content-wrapper">
        <form role="form" enctype="multipart/form-data" method="post" action="{{ ABS_PATH_ADMIN_LINK }}?do=module&sub=mod_edit&module_tag=page&module_action=page_work" class="ajax_form">
            <input type="hidden" name="csrf_token" value="{{ csrf_token }}">
            <input type="hidden" name="save" value="1">
            {% if page_info.page_id %}
				<input type="hidden" name="page_id" value="{{ page_info.page_id }}">
			{% endif %}

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ lang.page_title }}</label>
                        <input type="text" name="page_title" class="form-control" value="{{ page_info.page_title|escape|stripslashes }}" placeholder="{{ lang.page_title }}" required >
                    </div>

                    {% if page_info.page_id != 1 %}
						<div class="form-group">
	                        <label>{{ lang.page_alias }}</label>
                        	<input type="text" name="page_alias" class="form-control" value="{{ page_info.page_alias_clear|escape|stripslashes }}" placeholder="{{ lang.page_alias }}">
	                    </div>
					{% else %}
						<input type="hidden" name="page_alias" value="/">
					{% endif %}

					<div class="form-group">
						<div class="checkbox checkbox-primary">
							<input id="page_index" name="page_index" value="1" type="checkbox" {% if page_info.page_index == 1 %}checked{% endif %} >
							<label for="page_index">
								{{ lang.page_index }}
							</label>
						</div>

						<div class="checkbox checkbox-primary">
							<input id="page_landing" name="page_landing" value="1" type="checkbox" {% if page_info.page_landing == 1 %}checked{% endif %} >
							<label for="page_landing">
								{{ lang.page_landing }}
							</label>
						</div>

						<div class="checkbox checkbox-primary">
							<input id="page_landing_in_site" name="page_landing_in_site" value="1" type="checkbox" {% if page_info.page_landing_in_site == 1 %}checked{% endif %} >
							<label for="page_landing_in_site">
								{{ lang.page_landing_in_site }}
							</label>
						</div>
					</div>

					<div class="form-group">
                        <label>{{ lang.page_meta_description }}</label>
                        <input type="text" name="page_meta_description" class="form-control" value="{{ page_info.page_meta_description|escape|stripslashes }}" placeholder="{{ lang.page_meta_description }}" >
                    </div>

					<div class="form-group">
                        <label>{{ lang.page_meta_keywords }}</label>
                        <input type="text" name="page_meta_keywords" class="form-control" value="{{ page_info.page_meta_keywords|escape|stripslashes }}" placeholder="{{ lang.page_meta_keywords }}" >
                    </div>

					<div class="form-group">
                        <label>{{ lang.page_meta_robots }}</label>
                        <div class="clearfix"></div>
                        <select class="selectpicker" name="page_meta_robots">
                            {% for robot in lang.page_meta_robots_array %}
                                <option value="{{ robot }}" {% if page_info.page_meta_robots == robot %}selected{% endif %} >{{ robot }}</option>
                            {% endfor %}
                        </select>
                    </div>

				</div>
                <div class="col-md-6">
					<div class="form-group">
                        <label>{{ lang.page_lang }}</label>
                        <div class="clearfix"></div>
                        <select class="selectpicker" name="page_lang">
                            {% for lang in lang_array %}
                                <option value="{{ lang }}" {% if page_info.page_lang == lang %}selected{% endif %} >{{ lang }}</option>
                            {% endfor %}
                        </select>
                    </div>

                    <div class="form-group">
                        <label>{{ lang.page_status }}</label>
                        <div class="clearfix"></div>
                        <select class="selectpicker" name="page_status">
                            {% for status, title in page_status %}
                                <option value="{{ status }}" {% if page_info.page_status == status %}selected{% endif %} >{{title}}</option>
                            {% endfor %}
                        </select>
                    </div>

					<div class="form-group">
						<div class="row">
		                    {% if folders %}
		                        <div class="col-md-6">
		                            <label>{{ lang.page_folder_name }}</label>
		                            <div class="clearfix"></div>
		                            <select class="selectpicker" name="page_folder">
		                                <option value=""></option>
		                                {% for folder in folders %}
		                                    <option value="{{ folder.folder_id }}" {% if folder.folder_id == page_info.page_folder.folder_id %}selected{% endif %}>{{ folder.folder_title|escape|stripslashes }}</option>
		                                {% endfor %}
		                            </select>
		                        </div>
		                    {% endif %}
						</div>
                    </div>

					<div class="form-group">
                        <label>{{ lang.page_tags }}</label>
                        <input type="text" name="page_tags" class="form-control" value="{{ page_info.page_tags|escape|stripslashes }}" placeholder="{{ lang.page_tags }}" >
                    </div>

					{% if page_info.page_id %}
                        <div class="content-in-tab-block">
                            <div class="row">
                                <div class="col-md-6">
                                    {{ lang.page_reg }}
                                </div>
                                <div class="col-md-6">
                                    {{page_info.page_add}}<br>
                                    <a href="{{ ABS_PATH_ADMIN_LINK }}?do=profile&sub=open&user_id={{page_info.page_owner.id}}">{{page_info.page_owner.user_name|escape|stripslashes}}</a>
                                </div>
                            </div>

                            {% if page_info.page_edit %}
                                <div class="row">
                                    <div class="col-md-6">
                                        {{ lang.page_reg_edit }}
                                    </div>
                                    <div class="col-md-6">
                                        {{page_info.page_edit}}<br>
                                        <a href="{{ ABS_PATH_ADMIN_LINK }}?do=profile&sub=open&user_id={{page_info.page_edit_author.id}}">{{page_info.page_edit_author.user_name|escape|stripslashes}}</a>
                                    </div>
                                </div>
                            {% endif %}
                        </div>
                    {% endif %}
                </div>
            </div>

            <div class="form-group">
				<label>{{ lang.page_text }}</label>
				
				<div class="code_editor {% if not page_info.page_landing %}hidden{% endif %}">
					<ul class="list-unstyled">
						<li>[abs_path] - {{ lang.page_landing_abs_path }}</li>
						<li>[abs_link] - {{ lang.page_landing_abs_link }}</li>
					</ul>
					<textarea id="codemirror" name="page_landing_sourse">{{- page_info.page_landing_sourse|stripslashes -}}</textarea>
				</div>

				<div class="text_editor {% if page_info.page_landing %}hidden{% endif %}">
					<textarea name="page_text" id="" cols="30" rows="10" class="form-control summernote" placeholder="{{ lang.page_text }}">{{ page_info.page_text|stripslashes }}</textarea>
				</div>
            </div>

			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="">{{ lang.page_preview }}</label>
						{% if page_info.page_preview %}
							<img src="{{ ABS_PATH_ADMIN_LINK }}?thumb={{ app.app_upload_dir }}/{{ app.app_module_dir }}/{{ module_info.module_tag }}/preview/{{ page_info.page_id }}/{{page_info.page_preview}}&width={{ app.AVATAR_WIDTH }}&height={{ app.AVATAR_HEIGHT }}" class="img-rounded">
						{% endif %}
						<div class="clearfix"></div>

						<div class="fileinput-button">
							<div class="btn-flat btn-file">
								<i class="fa fa-fw fa-upload"></i>&nbsp
								<span>{{ lang.select_file }}</span>
								<input name="file_path" type="file">
							 </div>
							 <span class="file_name"></span>
						</div>
					</div>

					<div class="form-group">
						<label>{{ lang.page_youtube }}</label>
                        <input type="text" name="page_youtube" class="form-control" value="{{ page_info.page_youtube|escape|stripslashes }}" placeholder="{{ lang.page_youtube }}" >
					</div>
				</div>
				<div class="col-md-6">
					{% if page_photo %}
						<label for="">{{ lang.page_photos }}</label>
						<ul class="list-inline">
							{% for photo in page_photo %}
								<li>
									<a href="{{ ABS_PATH }}{{ app.app_upload_dir }}/{{ app.app_module_dir }}/{{ module_info.module_tag }}/photos/{{ page_info.page_id }}/{{ photo.file_path }}" data-lightbox="image-1"><img src="{{ ABS_PATH_ADMIN_LINK }}?thumb={{ app.app_upload_dir }}/{{ app.app_module_dir }}/{{ module_info.module_tag }}/photos/{{ page_info.page_id }}/{{ photo.file_path }}&width={{ app.AVATAR_WIDTH }}&height={{ app.AVATAR_HEIGHT }}" class="img-rounded"></a>

									<div class="checkbox checkbox-primary">
										<input id="file_delete{{ photo.file_id }}" name="file_delete[]" value="{{ photo.file_id }}" type="checkbox">
										<label for="file_delete{{ photo.file_id }}">
											{{ lang.save_delete }}
										</label>
									</div>
								</li>
							{% endfor %}
						</ul>
					{% endif %}
				</div>
			</div>

            <div class="clearfix"></div>
            <div class="actions">
                <input type="submit" class="btn-flat gray" value="{{ lang.save_data }}">
            </div>
        </form>

		{% if page_info.page_id %}

			<div class="row">
				<div class="col-md-6">
					<label for="">{{ lang.page_photos }}</label>

					<form method="post" action="{{ ABS_PATH_ADMIN_LINK }}?do=module&sub=mod_edit&module_tag=page&module_action=page_work_photo_uploads&page_id={{ page_info.page_id }}" id="upload" enctype="multipart/form-data">
						<input type="hidden" name="csrf_token" value="{{ csrf_token }}">
						<div class="form-group">
							<div class="upload-file-dropbox-zone" id="drop">
								<p class="upload-file-dropbox-zone-hint">
									{{ lang.storage_add_desc }}
								</p>
								<input type="file" name="page_photo" multiple>
							</div>
						</div>
						<ul class="list-unstyled"></ul>
					</form>

	<link href="{{ ABS_PATH }}vendor/assets/miniupload/css/style.css" rel="stylesheet" />
	<script src="{{ ABS_PATH }}vendor/assets/miniupload/js/jquery.knob.js"></script>

	<!-- jQuery File Upload Dependencies -->
	<script src="{{ ABS_PATH }}vendor/assets/miniupload/js/jquery.ui.widget.js"></script>
	<script src="{{ ABS_PATH }}vendor/assets/miniupload/js/jquery.iframe-transport.js"></script>
	<script src="{{ ABS_PATH }}vendor/assets/miniupload/js/jquery.fileupload.js"></script>

	<!-- Our main JS file -->
	<script src="{{ ABS_PATH }}vendor/assets/miniupload/js/script.js"></script>

				</div>
				<div class="col-md-6">

				</div>
			</div>
		{% endif %}
    </div>
</div>

<link rel="stylesheet" href="{{ ABS_PATH }}assets/site/assets/lightbox/css/lightbox.css" />
<script src="{{ ABS_PATH }}assets/site/assets/lightbox/js/lightbox.min.js"></script>


<link rel="stylesheet" href="{{ ABS_PATH }}assets/build/css/codemirror.min.css" type="text/css">
<script src="{{ ABS_PATH }}assets/build/js/codemirror.min.js" type="text/javascript"></script>

<script src="{{ ABS_PATH }}vendor/assets/codemirror/mode/xml/xml.js"></script>
<script src="{{ ABS_PATH }}vendor/assets/codemirror/mode/javascript/javascript.js"></script>
<script src="{{ ABS_PATH }}vendor/assets/codemirror/mode/htmlmixed/htmlmixed.js" type="text/javascript"></script>
<script src="{{ ABS_PATH }}vendor/assets/codemirror/formatting.js"></script>
<script>

	$(function () {
		$("input[name=page_landing]").on('change',function() {
			if($(this).is(":checked")) {
				build_editor();
			} else {
				destroy_editor();
			}
		});
	});

	var editor = CodeMirror.fromTextArea(document.getElementById("codemirror"), {
		lineNumbers: true,
		styleActiveLine: true,
		matchBrackets: true,
		theme: 'neo',
		viewportMargin: 50,
		mode: "text/html",
	});
	/*var totalLines = editor.lineCount(); 
	editor.autoFormatRange({line:0, ch:0}, {line:totalLines});*/


	function build_editor() {
		$(".code_editor").removeClass("hidden");
		$(".text_editor").addClass("hidden");

		editor.toTextArea();
		editor = CodeMirror.fromTextArea(document.getElementById("codemirror"), {
			lineNumbers: true,
			styleActiveLine: true,
			matchBrackets: true,
			theme: 'neo',
			viewportMargin: 50,
			mode: "text/html",
		});
		/*totalLines = editor.lineCount(); 
		editor.autoFormatRange({line:0, ch:0}, {line:totalLines});*/
	}

	function destroy_editor() {
		$(".text_editor").removeClass("hidden");
		$(".code_editor").addClass("hidden");

		$('.summernote').summernote(options_editor);
		editor.toTextArea();
	}

	var options_editor = {
		dialogsInBody: true,
		height: 400,
		lang: 'ru-RU',
		toolbar: [
		//[groupname, [button list]]
			['style', ['bold', 'italic', 'underline', 'clear']],
			['table', ['table']],
			['para', ['ul', 'ol', 'paragraph']],
			['insert', ['link', 'picture']],
		//['view', ['codeview']],
		]
	};


	
</script>
<style>
.CodeMirror {
    border: 1px solid #eee;
    height: 800px;
}
</style>
