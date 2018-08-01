<ul class="nav navbar-nav navbar-right pull-right">
	<li class="dropdown hidden-xs">
		<a aria-expanded="false" data-toggle="dropdown" href="#" class="dropdown-toggle name">
			{{ SESSION.user_name|escape|stripslashes }}
		</a>

		<ul class="dropdown-menu dropdown-notification">
	        <li class="notification-item">
				<span>{{ SESSION.user_group_name|escape|stripslashes }}</span>
			</li>
			<li role="separator" class="divider"></li>
			<li class="notification-item">
				<a href="{{ ABS_PATH_ADMIN_LINK }}?do=profile&sub=profile_work&user_id={{ SESSION.user_id }}">
					<i class="fa fa-user"></i> {{ lang.profile_edit_link }}
				</a>
			</li>
	        <li class="notification-item">
				<a href="{{ ABS_PATH_ADMIN_LINK }}?do=sendmail">
					<i class="fa fa-inbox"></i> {{ lang.sendmail_name }}
				</a>
			</li>
			<li class="notification-item">
				<a href="{{ ABS_PATH_ADMIN_LINK }}?do=auth&sub=logout" class="confirm">
					<i class="fa fa-sign-out"></i> {{ lang.logout }}
				</a>
			</li>
		</ul>
	</li>

	{% if SESSION.alles %}
		<li class="dropdown">
			<a aria-expanded="false" href="#" data-toggle="dropdown" class="dropdown-toggle">
				<i class="fa fa-cog"></i>
			</a>

			<ul class="dropdown-menu dropdown-notification pull-right">
				<li class="notification-item">
					<a href="{{ ABS_PATH_ADMIN_LINK }}?do=settings" {% if REQUEST.do == "settings" %}class="active"{% endif %}>
						<i class="fa fa-cog"></i>
						<span>{{ lang.settings }}</span>
					</a>
				</li>
				<li class="notification-item">
					<a href="{{ ABS_PATH_ADMIN_LINK }}?do=group" {% if REQUEST.do == "group" %}class="active"{% endif %}>
						<i class="fa fa-users"></i>
						<span>{{ lang.group }}</span>
					</a>
				</li>
				<li  class="notification-item">
					<a href="{{ ABS_PATH_ADMIN_LINK }}?do=profile" {% if REQUEST.do == "profile" %}class="active"{% endif %}>
						<i class="fa fa-user"></i>
						<span>{{ lang.users }}</span>
					</a>
				</li>
				<li class="notification-item">
					<a href="{{ ABS_PATH_ADMIN_LINK }}?do=book" {% if REQUEST.do == "book" %}class="active"{% endif %}>
						<i class="fa fa-flag"></i>
						<span>{{ lang.book }}</span>
					</a>
				</li>
				<li class="notification-item">
					<a href="{{ ABS_PATH_ADMIN_LINK }}?do=logs" {% if REQUEST.do == "logs" %}class="active"{% endif %}>
						<i class="fa fa-hdd-o"></i>
						<span>{{ lang.logs }}</span>
					</a>
				</li>
			</ul>
		</li>
	{% endif %}
</ul>
