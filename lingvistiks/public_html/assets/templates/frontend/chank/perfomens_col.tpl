{% if SESSION.user_id %}
<div class="col-md-3">
	<div class="sidebar">
		<div class="menu-profile">

			<img src="{{ ABS_PATH }}?thumb={{ app.app_upload_dir }}/users/{{ SESSION.user_photo|default('no-avatar.png') }}&width=100&height=100" alt="" class="img-circle">

			<a href="{{ HOST_NAME }}{{ link_lang_pref }}/profile/" class="pull-right mr-5"><i class="fa fa-cog" aria-hidden="true"></i></a>

			<div class="col-profile-bar pull-left">
				<a href="{{ HOST_NAME }}{{ link_lang_pref }}/profile-{{ SESSION.user_id|escape|stripslashes }}/" class="profile-name">{{ SESSION.user_login|escape|stripslashes }}</a>
				<div>{{ SESSION.full_user_id }}</div>
			</div>

			<ul class="list-unstyled">
				<li><a href="{{ HOST_NAME }}/customers-partners/" class="get_friends"><i class="fa fa-users" aria-hidden="true"></i> {{ lang.lk_customers_partners }} <span></span></a></li>
				<li><a href="{{ HOST_NAME }}/message/" class="get_message"><i class="fa fa-envelope" aria-hidden="true"></i> {{ lang.lk_message }} <span></span></a></li>
				{% if SESSION.user_group in [3, 4] %}
					<li><a href="{{ HOST_NAME }}/bank_zakazov/?order_owner={{ SESSION.user_id }}&order_status=1&filter=1" class="get_briefcase"><i class="fa fa-briefcase" aria-hidden="true"></i> {{ lang.lk_briefcase }}</a></li>
					<li><a href="{{ HOST_NAME }}/billing/"><i class="fa fa-credit-card-alt" aria-hidden="true"></i> {{ lang.lk_billing }}</a></li>
				{% endif %}
				<li><a href="{{ HOST_NAME }}/translations/"><i class="fa fa-pencil" aria-hidden="true"></i> {{ lang.service_translations }}</a></li>
			</ul>
		</div>

		{% for banner in banner_list %}
			<div class="banner">
				<a href="{{ HOST_NAME }}banner-{{ banner.banner_id }}/">
					{{ banner.banner_title|escape|stripslashes }}
					<img src="{{ ABS_PATH }}assets/site/template/images/banner.jpg" alt="">
				</a>
			</div>
		{% endfor %}

	</div>
</div>

<script>
	$(document).ready(function() {
		// init
		App.init_message();
		// init	
	});
</script>
{% endif %}
