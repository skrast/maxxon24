<ul class="sidebar-stat list-unstyled">
	<li>
		<a href="#" data-toggle="tooltip" data-placement="top" title="{{ curent_info_load.disk_use }} {{lang.start_in}} {{ curent_info_load.disk_allow }}">
			<span class="fa fa-inbox text-info"></span>
			<span>{{lang.start_disk}}</span>
			<span class="pull-right text-muted">{{ curent_info_load.disk_use_persent|format_number }}%</span>
			<div class="progress progress-bar-xs">
				<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: {{ curent_info_load.disk_use_persent|format_number }}%">
					<span class="sr-only">{{ curent_info_load.disk_use_persent|format_number }}% {{lang.start_use}}</span>
				</div>
			</div>
		</a>
	</li>
	<li>
		<a href="#" data-toggle="tooltip" data-placement="top" title="{{ curent_info_load.db_use }} {{lang.start_in}} {{ curent_info_load.db_allow }}">
			<span class="fa fa-inbox text-success"></span>
			<span>{{lang.start_db}}</span>
			<span class="pull-right text-muted">{{ curent_info_load.db_use_persent|format_number }}%</span>
			<div class="progress progress-bar-xs">
				<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: {{ curent_info_load.db_use_persent|format_number }}%">
					<span class="sr-only">{{ curent_info_load.db_use_persent|format_number }}% {{lang.start_use}}</span>
				</div>
			</div>
		</a>
	</li>

	{% if curent_info_load.dropbox_allow %}
		<li>
			<a href="#" data-toggle="tooltip" data-placement="top" title="{{ curent_info_load.dropbox_use }} {{lang.start_in}} {{ curent_info_load.dropbox_allow }}">
				<span class="fa fa-dropbox text-danger"></span>
				<span>{{ lang.start_dropbox }}</span>
				<span class="pull-right text-muted">{{ curent_info_load.dropbox_use_persent|format_number }}%</span>
				<div class="progress progress-bar-xs">
					<div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: {{ curent_info_load.dropbox_use_persent|format_number }}%">
						<span class="sr-only">{{ curent_info_load.dropbox_use_persent|format_number }}% {{lang.start_use}}</span>
					</div>
				</div>
			</a>
		</li>
	{% endif %}

	{% if curent_info_load.yandex_allow %}
		<li>
			<a href="#" data-toggle="tooltip" data-placement="top" title="{{ curent_info_load.yandex_use }} {{lang.start_in}} {{ curent_info_load.yandex_allow }}">
				<span class="fa fa-hdd-o text-danger"></span>
				<span>{{ lang.start_yandex }}</span>
				<span class="pull-right text-muted">{{ curent_info_load.yandex_use_persent|format_number }}%</span>
				<div class="progress progress-bar-xs">
					<div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: {{ curent_info_load.yandex_use_persent|format_number }}%">
						<span class="sr-only">{{ curent_info_load.yandex_use_persent|format_number }}% {{lang.start_use}}</span>
					</div>
				</div>
			</a>
		</li>
	{% endif %}
</ul>
