<form action="{{ HOST_NAME }}/jobs/respons/" method="post" class="ajax_form" enctype="multipart/form-data">
	<input type="hidden" name="csrf_token" value="{{ csrf_token }}">
	<input type="hidden" name="save" value="1">
	<input type="hidden" name="void_id" value="{{ jobs_info.jobs_id }}">

	<h2 class="respons_title">
		{{ lang.jobs_respons_title }} <strong>{{ jobs_info.jobs_title.title|escape|stripslashes }}</strong> #{{ jobs_info.jobs_id }}
	</h2>
	
	<div class="form-group">
			<label for="">{{ lang.jobs_respons_desc }}</label>
		<textarea class="form-control" placeholder="{{ lang.jobs_respons_desc }}" name="response_desc" rows="5"></textarea>
	</div>

	<button type="submit" class="btn btn-block btn-go-on">{{ lang.btn_send }}</button>
</form>
