var table_type = "",
table_void = "",

table_init = function () {
	table_type = $(".show_table_config").attr("data-essense");
	table_void = $(".show_table_config").attr("data-void");

	$(".show_table_work").on('click',function() {
		show_table_work();
	});

	$(".show_table_save").on('click',function() {
		show_table_save();
	});
},

// Настройки колонок
show_table_work = function (table_id) {
	// загрузка формы
	$.ajax({
		url: ave_path+'?do=table&sub=table_work',
		data: {"table_type": table_type, "table_id": table_id, "table_void": table_void, csrf_token: csrf_token},
		success: function( data ) {
			$(".table_work").html(data.html);

			init_refresh_script();

			// сохранение формы
			$(".ajax_form").on('submit',function() {
				var form_data = $(this).formSerialize();
				var target_form = $(this).attr("action");
				var this_form = $(this);

				this_form.find(".actions input").attr("disabled", true);

				$.ajax({
					url: target_form,
					data: form_data,
					success: function( data ) {

						show_alert(data.status, data.respons);
						this_form.find(".actions input").attr("disabled", false);

						if(data.status == "success") {
							show_table_save();
						}
					}
				});

				return false;
			});

		}
	});
},

// Список сохраненных настроек
show_table_save = function () {
	// загрузка списка
	$.ajax({
		url: ave_path+'?do=table&sub=table_save',
		data: {"table_type": table_type, "table_void": table_void, csrf_token: csrf_token},
		success: function( data ) {
			$(".table_work").html(data.html);

			$(".show_edit_table").on('click',function() {
				var table_id = $(this).attr("data-table");
				show_table_work(table_id);
			});

			$(".show_column_table").on('click',function() {
				var table_id = $(this).attr("data-table");
				var table_type = $(this).attr("data-type");
				var table_void = $(this).attr("data-void");
				active_table(table_id, table_type, table_void);
			});

		}
	});
},

// Список сохраненных настроек
active_table = function (table_id, table_type, table_void) {

	// загрузка списка
	$.ajax({
		url: ave_path+'?do=table&sub=table_active',
		data: {"table_id": table_id, "table_type": table_type, "table_void": table_void, csrf_token: csrf_token},
		success: function( data ) {
			$(".reset-table a").click();
			window.location.reload();
		}
	});

},

// init app
table_app = function () {
  	return {
	    init: function () {
	    	table_init(),
			show_table_save()
	    },
	}
}();
// init app

$(function () {
	table_app.init();
});
