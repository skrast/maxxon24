<form class="search_filter_writer" action="" method="post">
	<input type="hidden" name="csrf_token" value="{{ csrf_token }}">
	<div class="row">
		<div class="col-md-9">
			<div class="row">
				<div class="col-md-4">
					<label>
						<input class="checkbox-input" type="checkbox" name="search_free" value="1">
						<span class="checkbox-custom"></span>
						<span class="label label-modal">{{ lang.search_writer_free }}</span>
					</label>
				</div>
				<div class="col-md-4">
					<label>
						<input class="checkbox-input" type="checkbox" name="search_online" value="1">
						<span class="checkbox-custom"></span>
						<span class="label label-modal">{{ lang.search_writer_online }}</span>
					</label>
				</div>
				<div class="col-md-4 pd-0">
					<label>
						<input class="checkbox-input" type="checkbox" name="select_all" value="1">
						<span class="checkbox-custom"></span>
						<span class="label label-modal">{{ lang.search_writer_all }}</span>
					</label>
				</div>
			</div>
		</div>
		<div class="col-md-3 pl-0">
			<div class="form-group">
				<input type="text" class="form-control form-control-search" name="search_title" value="" placeholder="{{ lang.search_writer_title }}">
			</div>
		</div>
	</div>
</form>

<form class="search_filter_perfomens" action="" method="post">
	<input type="hidden" name="csrf_token" value="{{ csrf_token }}">
	<input type="hidden" name="document_id" value="{{ document_id }}">
	<div class="row">
		<div class="col-md-12 content mCustomScrollbar" id="content-2">
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<textarea class="form-control-modal" name="writer_comment" placeholder="{{ lang.search_writer_comments }}" rows="3"></textarea>
			</div>
		</div>

		<div class="col-md-12">
			<p><button type="submit" class="btn btn-block btn-go-on">{{ lang.search_writer_send_message }}</button></p>
		</div>
	</div>
</form>

<link rel="stylesheet" href="{{ ABS_PATH }}assets/site/assets/malihu-custom-scrollbar-plugin-master/jquery.mCustomScrollbar.css">
<script src="{{ ABS_PATH }}assets/site/assets/malihu-custom-scrollbar-plugin-master/jquery.mCustomScrollbar.concat.min.js"></script>
<script src="{{ ABS_PATH }}assets/site/assets/OwlCarousel2-2.1.6/dist/owl.carousel.min.js"></script>
<script>
	$(document).ready(function(){
		$("#content-2").mCustomScrollbar({
			scrollButtons:{enable:true},
			theme:"dark",
			axis:"y",
			setHeight: 180,
			scrollbarPosition: "outside"
		});

		$(".search_filter_writer input[name=search_free], .search_filter_writer input[name=search_online], .search_filter_writer input[name=search_title]").on('change',function() {
			var search_free = $(".search_filter_writer input[name=search_free]:checked").val();
			var search_online = $(".search_filter_writer input[name=search_online]:checked").val();
			var search_title = $(".search_filter_writer input[name=search_title]").val();

			$.ajax({
				url: ave_path+'site.php?do=siteSearch&sub=writer_search&search=1',
				data: {search_free: search_free, search_online: search_online, search_title: search_title, csrf_token: csrf_token},
				success: function( data ) {

					$("#content-2").mCustomScrollbar('destroy');

					$("#content-2").html(data.html);


					$("#content-2").mCustomScrollbar({
						scrollButtons:{enable:true},
						theme:"dark",
						axis:"y",
						setHeight: 180,
						scrollbarPosition: "outside"
					});

					$(".search_filter_writer input[name=select_all]").on('click',function() {
						$("#content-2 .checkbox-search").find("input[type=checkbox]").click();
					});
				}
			});
		});

		$(".search_filter_writer input[name=search_free]").click();

		$(".search_filter_perfomens").on('submit',function() {
			var form_data = $(this).formSerialize();

			$.ajax({
				url: ave_path+'site.php?do=siteMessage&sub=writer_search_send',
				data: form_data,
				success: function( data ) {
					show_alert(data.status, data.respons);
				}
			});

			return false;
		});


	});
</script>
