<main class="no-sidebar">
	<div class="container">

		<ol class="breadcrumb">
			<li><a href="{{ HOST_NAME }}">{{ lang.page_breadcrumb }}</a></li>
			<li class="active text-uppercase">{{ lang.billing_name }}</li>
		</ol>

		<h1>
			{{ lang.billing_name }}
		</h1>

		<div class="row">
			{{ perfomens_col }}

			<div class="col-md-{% if perfomens_col %}9{% else %}12{% endif %} col-sm-{% if perfomens_col %}9{% else %}12{% endif %}">

				<div class="content-right">
					<div class="profile-block price-state">

						<div class="row change-cart">
							<div class="col-md-6">
								<h5>{{ lang.billing_my_tariff }}
						
									<small>
										{% if profile_info.user_billing %}
											{{ app.app_tariff[SESSION.user_group][SESSION.user_subgroup][profile_info.user_billing].name }}
										{% else %}
											{{ lang.billing_my_tariff_none }}
										{% endif %}
									</small>
								</h5>
							</div>
							<div class="col-md-6 text-right">
								<h5>
									{{ lang.billing_my_balance }} 
									
									<small>
										{{ profile_info.user_balance|format_number }} {{ app.site_currency }}
								
										{% if profile_info.user_billing_date and profile_info.user_balance %}
											({{ profile_info.user_billing_date|escape|stripslashes }})
										{% endif %}
									</small>
								</h5>
							</div>
						
							<div class="clearfix"></div>

							{% if not REQUEST.change_price %}
								<div class="col-md-12">
									<h3>{{ lang.billing_tariff_change }}</h3>
								</div>
							{% endif %}
							
							{{ tariff_block }}
						</div>

						<div class="row row-buy">
							<div class="col-md-12">
								<h3>{{ lang.billing_history }}</h3>

								{% if history_list %}
								<table class="table text-center">
									<thead>
										<tr>
											<td><strong>{{ lang.billing_tbl_date }}</strong></td>
											<td><strong>{{ lang.billing_tbl_sum }}</strong></td>
											<td><strong>{{ lang.billing_tbl_id }}</strong></td>
											<td><strong>{{ lang.billing_tbl_desc }}</strong></td>
										</tr>
									</thead>
									<tbody>
										{% for history in history_list %}
											<tr>
												<td><p>{{ history.history_date|escape|stripslashes }}</p></td>
												<td><p>{{ history.history_sum|escape|stripslashes|format_number }} {{ app.site_currency }}</p></td>
												<td><p>AA{{ history.history_id|format_number_tranc }}</p></td>
												<td><p>{{ history.history_desc|escape|stripslashes }}</p></td>
											</tr>
										{% endfor %}
									</tbody>
								</table>
								{% else %}
									<div class="album_empty">{{ lang.billing_history_empty }}</div>
								{% endif%}
							</div>
						</div>

						{% if page_nav %}
							{{ page_nav }}
						{% endif %}

					</div>
				</div>

			</div>
		</div>

	</div>
</main>

<link rel="stylesheet" href="{{ ABS_PATH }}assets/site/assets/malihu-custom-scrollbar-plugin-master/jquery.mCustomScrollbar.css">
<script src="{{ ABS_PATH }}assets/site/assets/malihu-custom-scrollbar-plugin-master/jquery.mCustomScrollbar.concat.min.js"></script>
<script>
  	$(document).ready(function(){
		// init
	  	App.billing_core();
	  	// init

	    $('#carouselOne').owlCarousel({
			items:11,
			autoplay:true,
			nav:true,
			autoWidth:true,
			margin:20,
			loop:true,
			navText:["<img src='{{ ABS_PATH }}assets/site/template/images/prev.png'>","<img src='{{ ABS_PATH }}assets/site/template/images/next.png'>"]
	    });

	    /*$("#content-5").mCustomScrollbar({
			scrollButtons:{enable:true},
			theme:"dark",
			axis:"y",
			setHeight: 160,
			scrollbarPosition: "outside"
		});*/

		$(".change_price_for_tarif .active_price").on("click",function() {
			var this_disc = parseInt($(this).attr("data-disc"));
			var this_price = parseInt($(this).attr("data-price"));
			var this_first_price = parseInt($(this).attr("data-first"));
			var this_tarif = parseInt($(this).attr("data-tarif"));

			$(this).parent().find(".active_price").removeClass("active");
			$(this).addClass("active");

			$(this).parent().parent().parent().parent().find("input[name=change_price]").val(this_disc);


			if(this_disc!=1) {
				$(this).parent().parent().parent().next().find("ul li").eq(0).removeClass("hidden");
				$(this).parent().parent().parent().next().find("ul li").eq(0).find("span").text(this_first_price*this_disc);
			} else {
				$(this).parent().parent().parent().next().find("ul li").eq(0).addClass("hidden");
			}
			$(this).parent().parent().parent().next().find("ul li").eq(1).find("span").text(this_price*this_disc);

			console.log(this_disc);

			return false;
		});

		$(".change_price_for_tarif").each(function( index ) {
			$(this).find(".active_price").eq(0).click();
		});
	});
</script>
