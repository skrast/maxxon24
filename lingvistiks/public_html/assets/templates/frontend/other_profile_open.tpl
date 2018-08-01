<main class="no-sidebar">
	<div class="container">

		<ol class="breadcrumb">
			<li><a href="{{ HOST_NAME }}">{{ lang.page_breadcrumb }}</a></li>
			<li class="active text-uppercase">{{ profile_info.user_group_name|escape|stripslashes }}</li>
		</ol>

		<h1>
			{{ profile_info.user_group_name|escape|stripslashes }}
		</h1>

		<div class="col-md-{% if perfomens_col %}9{% else %}12{% endif %}">
			<div class="content-right">
				<div class="profile-block">

					<p>{{ lang.lk_this_account_hidden }}</p>

				</div>
			</div>
		</div>
	</div>
</main>
