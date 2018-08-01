<div class="get_statistic">
	<hr>
	<ul class="list-unstyled">
		<li>{{ lang.stat_total_time }} {{ stat.total_time }} {{ lang.stat_time_sec }}</li>
		<li>{{ lang.stat_memory }} {{ stat.memory }} {{ lang.stat_memory_kb }}</li>
		<li>{{ lang.stat_count_time }} {{ stat.count }} {{  lang.stat_count_by_time }} {{ stat.time }} {{ lang.stat_time_sec }}</li>
		<li>{{ lang.stat_average }} {{ stat.average }} {{ lang.stat_time_sec }}</li>
		<li>{{ lang.stat_status }} {{ stat.status }}</li>
		<li>PHP: {{ stat.php }}, mysql: {{ stat.mysql }}</li>
	</ul>

	{{ stat.list }}
</div>
