{% spaceless %}
	<div class="content">
	    {% if SESSION.user_group and SESSION.user_id %}
	        <p>{{ lang.login_is_login }} <a href="{{ ABS_PATH_ADMIN_LINK }}?do=start">{{SESSION.user_name}}</a>.</p><br>
	    {% endif %}

	    {% if REQUEST.success == 'restore' %}
	        <div class="alert alert-success" role="alert">{{ lang.login_pass_save }}</div>
	    {% endif %}

	    {% if login_make_ban %}
	        <div class="alert alert-info" role="alert">{{ login_make_ban }}</div>
	    {% endif %}

	    {% if login_fail2ban %}
	        <div class="alert alert-info" role="alert">{{ lang.login_fail2ban }}</div>
	    {% endif %}

	    <form action="{{ ABS_PATH }}auth.php" method="post">
	        <input type="hidden" name="csrf_token" value="{{ csrf_token }}">
	        <input name="login" type="hidden" value="1" />

	        <div class="fields">
	            <input class="form-control" name="user_email" type="text" placeholder="{{ lang.login_form_email }}" value="" />
	        </div>
	        <div class="fields">
	            <input class="form-control" name="user_pass" type="password" placeholder="{{ lang.login_form_pass }}" value="" />
	        </div>
	        <div class="actions">
				<input type="submit" class="btn-flat gray" value="{{ lang.login_form_enter }}">
	        </div>
	    </form>
	</div>
	<div class="bottom-wrapper">
	    <div class="message">
	        <span>{{ lang.login_need_pass }}</span>
	        <a href="{{ ABS_PATH_ADMIN_LINK }}auth.php?do=restore">{{ lang.login_get_pass }}</a>.
	    </div>
	</div>
{% endspaceless %}
