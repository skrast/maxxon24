<form action="{{ HOST_NAME }}/resume/access/" method="post" class="ajax_form" enctype="multipart/form-data">
	<input type="hidden" name="csrf_token" value="{{ csrf_token }}">
	<input type="hidden" name="save" value="1">
	<input type="hidden" name="void_id" value="{{ resume_info.resume_owner }}">

	<h2 class="respons_title">
		{{ lang.resume_respons_title }} <strong>{{ resume_info.resume_title.title|escape|stripslashes }}</strong> #{{ resume_info.resume_owner }}
	</h2>

	<div class="form-group">
		<label for="">{{ lang.resume_respons_desc }}</label>
		<textarea class="form-control" placeholder="{{ lang.resume_respons_desc }}" name="response_desc" rows="5"></textarea>
	</div>

	<button type="submit" class="btn btn-block btn-go-on">{{ lang.btn_send }}</button>
</form>
