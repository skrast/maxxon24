{% spaceless %}
    <div class="content">
        {% if REQUEST.success %}
            <div class="alert alert-success" role="alert">
                {{ lang.login_pass_mess }}
            </div>
        {% endif %}

        <form action="{{ ABS_PATH_ADMIN_LINK }}auth.php?do=restore" method="post">
            <input name="restore" type="hidden" value="1" />
            <input type="hidden" name="csrf_token" value="{{ csrf_token }}">

            <div class="fields">
                <input class="form-control" name="user_email" type="text" placeholder="{{ lang.login_form_email }}" value="" />
            </div>
            <div class="actions">
				<input type="submit" class="btn-flat gray" value="{{ lang.login_pass_enter }}">
            </div>
        </form>
    </div>

    <div class="bottom-wrapper">
        <div class="message">
            <span>{{ lang.login_pass_find }}</span>
            <a href="{{ ABS_PATH_ADMIN_LINK }}auth.php">{{ lang.login_pass_login }}</a>.
        </div>
    </div>
{% endspaceless %}
