<main class="no-sidebar">
	<div class="container">

		{{ breadcrumb }}

		<div class="row">
			<div class="col-md-12">
				<div class="news-item">
					{% if page_info.page_preview %}
						<img src="{{ ABS_PATH }}?thumb={{ page_info.page_preview_site }}&width=400&height=400" alt="" class="pull-right">
					{% endif %}

					<h1>{{ page_info.page_title|escape|stripslashes }}</h1>

                    {{ page_info.page_text|stripslashes }}


                    <br><br>
                    
                    <ul class="nav nav-tabs nav-tabs-line" role="tablist">
                        <li role="presentation" class="nav-item active"><a href="#group_1" aria-controls="group_1" role="tab" data-toggle="tab">{{ lang.billing_tariff_group_1 }}</a></li>
                        <li role="presentation" class="nav-item"><a href="#group_2" aria-controls="group_2" role="tab" data-toggle="tab">{{ lang.billing_tariff_group_2 }}</a></li>
                    </ul>

                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="group_1">
                            <div class="profile-block price-state">
                                <h2>{{ lang.billing_tariff_type_1 }}</h2>
                                <div class="row change-cart">                        
                                    {{ tariff_block_3 }}
                                </div>
                            </div>
        
                            <div class="profile-block price-state">
                                <h2>{{ lang.billing_tariff_type_2 }}</h2>
                                <div class="row change-cart">                        
                                    {{ tariff_block_4 }}
                                </div>
                            </div>
                        </div>

                        <div role="tabpanel" class="tab-pane" id="group_2">
                            <div class="profile-block price-state">
                                <h2>{{ lang.billing_tariff_type_1 }}</h2>
                                <div class="row change-cart">                        
                                    {{ tariff_block_1 }}
                                </div>
                            </div>
        
                            <div class="profile-block price-state">
                                <h2>{{ lang.billing_tariff_type_2 }}</h2>
                                <div class="row change-cart">                        
                                    {{ tariff_block_2 }}
                                </div>
                            </div>
                        </div>
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
