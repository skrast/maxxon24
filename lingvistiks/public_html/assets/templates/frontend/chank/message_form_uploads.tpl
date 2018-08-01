<form action="{{ HOST_NAME }}/message/?message_to={{ send_message_to }}" data-default="{{ HOST_NAME }}/message/?message_to={{ send_message_to }}" method="post" enctype="multipart/form-data" class="ajax_form_message_and_file" id="ajax_form_message_and_file" data-reset="1">

	<input type="hidden" name="csrf_token" value="{{ csrf_token }}">
	<input type="hidden" name="save" value="1">
	<input type="hidden" name="parent_id" value="">
	<input type="hidden" name="message_to_hidden" value="{{ send_message_to }}">

	<div class="filer-uploader" data-empty="{{ lang.message_upload_drag }}">
		<input type="file" name="files" id="upload_file">
	</div>

	<div class="text-center filer-uploader-link">
		<a href="" class="more_uploads">{{ lang.message_upload_drag_lnk }}</a>
		<p>{{ lang.message_upload_drag_max }}</p>
	</div>

<script type="text/javascript">

	var allow_ext_photo = [{% for ext in app_allow_ext_arr %}'{{ ext }}',{% endfor %}];
	var captions = {
		feedback: '{{ lang.storage_add_desc }}',
		feedback2: '{{ lang.storage_add_desc }}',
		drop: '{{ lang.storage_add_desc }}',
		cancel: '{{ lang.save_reset }}',
		confirm: '{{ lang.save_close }}',
		name: '{{ lang.storage_file_name }}',
		type: '{{ lang.storage_file_type }}',
		size: '{{ lang.storage_file_size }}',
		dimensions: '{{ lang.storage_file_dimensions }}',
		remove: '{{ lang.save_delete }}',
		errors: {
			filesLimit: '{{ lang.message_uploader_filesLimit }}',
			filesType: '{{ lang.message_uploader_filesType }}',
			fileSize: '{{ lang.message_uploader_fileSize }}',
			filesSizeAll: '{{ lang.message_uploader_filesSizeAll }}',
			fileName: '{{ lang.message_uploader_fileName }}',
			folderUpload: '{{ lang.message_uploader_folderUpload }}',
		}
	};

</script>

	<div class="form-group">
		<textarea name="message_desc" id="" rows="2" placeholder="{{ lang.message_desc }}"></textarea>
	</div>

	<div class="form-group text-center">
		<button type="submit" class="btn btn-block btn-send btn-go-on">{{ lang.btn_send }}</button>
	</div>

	<p class="text-center info-message-color send-message hidden">{{ lang.message_upload_send_message }}</p>

	<p class="error-message-color error-message"></p>
</form>

<div id="container" class="hidden"></div>

<script>
	$(function () {
		// init
		App.message_uploader_core('.filer-uploader');
		// init
	});
</script>