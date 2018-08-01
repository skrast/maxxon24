<form method="post" action="{{ ABS_PATH }}search" class="header-search">
	<input type="hidden" name="csrf_token" value="{{ csrf_token }}">
	<input type="search" name="search" class="search-top" placeholder="">
	<button type="submit"></button>
</form>


{% if not SESSION.user_id %}

<div class="login">
	<ul class="list-inline">
		<li><a href="#" data-toggle="modal" data-target="#login">{{ lang.page_enter_btn }}</a></li>
		<li class="login-ic"><img src="{{ ABS_PATH }}assets/site/template/images/login-new.png" alt=""></li>
		<li><a href="#" data-toggle="modal" data-target="#registration">{{ lang.page_reg_btn }}</a></li>
	</ul>
</div>

<div class="modal fade" id="login" tabindex="-1" role="dialog" aria-labelledby="myLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close pull-right" data-dismiss="modal" aria-hidden="true">{{ lang.btn_close }} x</button>
				<h2 class="modal-title">{{  lang.page_enter_btn }} <img src="{{ ABS_PATH }}assets/site/template/images/login-modal.png" alt=""></h2>
			</div>
			<div class="modal-body">
				<form action="{{ HOST_NAME }}/auth/" method="post" class="ajax_form" data-reset="1">
					<input type="hidden" name="csrf_token" value="{{ csrf_token }}">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<input type="text" class="form-control-modal" name="user_email" placeholder="{{ lang.auth_email_or_login }}">
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<input type="password" class="form-control-modal" name="user_pass" placeholder="{{ lang.auth_password }}">
							</div>
							<button type="submit" class="btn btn-block btn-go-on">{{ lang.login_form_enter }}</button>
							<p class="text-info-repeat"><a href="#" data-toggle="modal" data-target="#recoverPassword" data-dismiss="modal">{{ lang.page_remind_btn }}</a></p>
							<a href="#" class="btn btn-block btn-reg" data-toggle="modal" data-target="#registration" data-dismiss="modal">{{ lang.page_reg_btn }}</a>
						</div>
					</div>
					<p class="error-message-color error-message"></p>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="registration" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close pull-right" data-dismiss="modal" aria-hidden="true">{{ lang.btn_close }} x</button>
				<h2 class="modal-title">{{ lang.page_reg_title }}</h2>
			</div>
			<div class="modal-body">
				<form action="{{ HOST_NAME }}/register/" method="post" class="ajax_form">
					<input type="hidden" name="csrf_token" value="{{ csrf_token }}">
					<input type="hidden" name="user_group" value="4">

					<input type="hidden" name="user_type_form" value="">

					<h3 class="text-center">{{ lang.page_reg_title_type }}</h3>
					<ul class="list-inline change_group_register">
						<li class="active"><a href="#" data-type="4">{{ lang.auth_owner }}</a></li>
						<li class="login-ic"><img src="{{ ABS_PATH }}assets/site/template/images/login-m.png" alt=""></li>
						<li><a href="#" data-type="3">{{ lang.auth_responsible }}</a></li>
					</ul>

					<ul class="row text-center list-inline show_skill">
						<li class="col-md-1"></li>
						<li class="col-md-5">
							<div class="btn btn-block btn-search change_type_form" data-type="1">
								{{ lang.lk_user_type_form_single }}
							</div>
						</li>
						<li class="col-md-5">
							<div class="btn btn-block btn-search change_type_form" data-type="2">
								{{ lang.lk_user_type_form_company }}
							</div>
						</li>
					</ul>

					<div class="form-group">
						<input type="text" class="form-control-modal" name="user_email" placeholder="{{ lang.auth_field_email }}">
					</div>
					<div class="form-group">
						<input type="password" class="form-control-modal" name="user_password" placeholder="{{ lang.auth_field_pass }}">
					</div>
					<div class="form-group">
						<input type="password" class="form-control-modal" name="user_password_copy" placeholder="{{ lang.auth_field_repass }}">
					</div>
					<div class="form-group">
						<div class="checkbox">
							<label>
								<input class="checkbox-input" type="checkbox" name="privacy" value="1">
								<span class="checkbox-custom"></span>
								<span class="label">{{ lang.auth_btn_secure }} <a href="{{ HOST_NAME }}{{ link_lang_pref }}/privacy{{ app.URL_SUFF }}">{{ lang.auth_link_secure }}</a> {{ lang.auth_btn_secure2 }}</span>
							</label>
						</div>
					</div>
					<p class="text-center"><button type="submit" class="btn btn-block btn-go-on">{{ lang.page_reg_btn }}</button></p>
					<p class="text-info-repeat">
					<a href="#" data-toggle="modal" data-target="#login" data-dismiss="modal">{{ lang.page_reg_btn_ready }}</a></p>

					<p class="error-message-color error-message"></p>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="registration_perfomens" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close pull-right" data-dismiss="modal" aria-hidden="true">{{ lang.btn_close }} x</button>
				<h2 class="modal-title">{{ lang.page_reg_title }}</h2>
			</div>
			<div class="modal-body">
				<form action="{{ HOST_NAME }}/register/" method="post" class="ajax_form" data-reset="1">
					<input type="hidden" name="csrf_token" value="{{ csrf_token }}">
					<input type="hidden" name="user_group" value="3">

					<input type="hidden" name="user_type_form" value="">
					<ul class="list-inline show_skill">
						<li>
							<div class="btn btn-block btn-search change_type_form" data-type="1">
								{{ lang.lk_user_type_form_single }}
							</div>
						</li>
						<li>
							<div class="btn btn-block btn-search change_type_form" data-type="2">
								{{ lang.lk_user_type_form_company }}
							</div>
						</li>
					</ul>

					<div class="form-group">
						<input type="text" class="form-control-modal" name="user_email" placeholder="{{ lang.auth_field_email }}">
					</div>
					<div class="form-group">
						<input type="password" class="form-control-modal" name="user_password" placeholder="{{ lang.auth_field_pass }}">
					</div>
					<div class="form-group">
						<input type="password" class="form-control-modal" name="user_password_copy" placeholder="{{ lang.auth_field_repass }}">
					</div>
					<div class="form-group">
						<div class="checkbox">
							<label>
								<input class="checkbox-input" type="checkbox" name="privacy" value="1">
								<span class="checkbox-custom"></span>
								<span class="label">{{ lang.auth_btn_secure }} <a href="{{ HOST_NAME }}{{ link_lang_pref }}/privacy{{ app.URL_SUFF }}">{{ lang.auth_link_secure }}</a> {{ lang.auth_btn_secure2 }}</span>
							</label>
						</div>
					</div>
					<p class="text-center"><button type="submit" class="btn btn-go-on">{{ lang.page_reg_btn }}</button></p>
					<p class="text-info-repeat">
					<a href="#" data-toggle="modal" data-target="#login" data-dismiss="modal">{{ lang.page_reg_btn_ready }}</a></p>

					<p class="error-message-color error-message"></p>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="recoverPassword" tabindex="-1" role="dialog" aria-labelledby="myLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close pull-right" data-dismiss="modal" aria-hidden="true">{{ lang.btn_close }} x</button>
				<h2 class="modal-title">{{ lang.auth_reset_pass }}</h2>
			</div>
			<div class="modal-body">
				<form action="">
					<input type="hidden" name="csrf_token" value="{{ csrf_token }}">

					<p class="text-info">{{ lang.auth_reset_pass_desc }}</p>
					<div class="form-group">
						<input type="text" class="form-control-modal" name="reset_by_email" value="" placeholder="{{ lang.auth_reset_pass_email }}">
					</div>
							
					<button type="submit" class="btn btn-block btn-go-on">{{ lang.btn_next }}</button>

					<p class="error-message-color error-message"></p>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="vvodCode" tabindex="-1" role="dialog" aria-labelledby="myLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close pull-right" data-dismiss="modal" aria-hidden="true">{{ lang.btn_close }} x</button>
				<h2 class="modal-title">{{ lang.auth_recover_code }}</h2>
			</div>
			<div class="modal-body">
				<form action="/auth/recover/verify/">
					<input type="hidden" name="csrf_token" value="{{ csrf_token }}">
					<input type="hidden" name="user_email" value="{{ REQUEST.email|escape|stripslashes }}">

					<p class="text-info">{{ lang.auth_recover_code_desc }}</p>
					<div class="row">
						
						<div class="col-md-6 col-sm-6 text-left">
							<span class="label">
								<strong>{{ lang.auth_recover_code_email }}</strong>
								<br>{{ REQUEST.email|escape|stripslashes }}
							</span>
						</div>
						<div class="col-md-6 col-sm-6">
							<div class="form-group">
								<input type="text" class="form-control-modal vvod" placeholder="{{ lang.auth_recover_code }}" name="code" value="{{ REQUEST.code|escape|stripslashes }}" autocomplete="off">
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<input type="password" class="form-control-modal vvod" name="user_password" placeholder="{{ lang.auth_field_pass }}">
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<input type="password" class="form-control-modal vvod" name="user_password_copy" placeholder="{{ lang.auth_field_repass }}">
							</div>
						</div>
						<p class="text-info-repeat">
							<a href="#" class="recoverPasswordRepeat">{{ lang.auth_recover_code_repeat }}</a>
						</p>
					</div>
					<button type="submit" class="btn btn-block btn-go-on">{{ lang.btn_save }}</button>
					<p class="error-message-color error-message"></p>
				</form>

			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="recoverPasswordRepeat">
	<form action="">
		<input type="hidden" name="csrf_token" value="{{ csrf_token }}">

		{% if REQUEST.email %}
			<input type="hidden" name="auth_reset" value="email">
			<input type="hidden" name="reset_by_email" value="{{ REQUEST.email|escape|stripslashes }}">
		{% else %}
			<input type="hidden" name="auth_reset" value="phone">
			<input type="hidden" name="reset_by_phone" value="{{ REQUEST.phone|escape|stripslashes }}">
		{% endif %}
	</form>
</div>

<script>
$(function () {
	// init
	App.reg_core();
	// init
});
</script>

{% else %}

<div class="login sine-in">
	<ul class="list-inline">
		<li>
			<a href="{{ HOST_NAME }}/profile-{{ SESSION.user_id }}/">
				<img class="img-circle" src="{{ ABS_PATH }}?thumb=uploads/users/{{SESSION.user_photo}}&width=50&height=50" alt="">{{ SESSION.user_name|escape|stripslashes|truncate(18) }}
			</a>
		</li>
		<li>
            <a href="#" class="dropdown-toggle" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="false">
              <i class="fa fa-cog" aria-hidden="true"></i>
		  </a>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
			  <li><a href="{{ HOST_NAME }}/profile/">{{ lang.lk_profile_edit }}</a></li>
			  <li><a href="{{ HOST_NAME }}/translations/">{{ lang.service_translations }}</a></li>
              <li><a href="#" data-toggle="modal" data-target="#support">{{ lang.lk_support }}</a></li>
              <li><a href="{{ HOST_NAME }}/profile/exit/">{{ lang.lk_exit }}</a></li>
            </ul>
		</li>
	</ul>
</div>

{% endif %}
