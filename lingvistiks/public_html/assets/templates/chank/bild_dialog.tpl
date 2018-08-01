{% if SESSION.user_use_notify %}
	<div class="hidden">
		<div class="show_bild_dialog" data-title="{{lang.dialog_title_task}}"><ul class="list-unstyled dialog-content-block"></ul></div>
	</div>
	<audio id="playAudio" class="hidden">
		<source src="{{ ABS_PATH }}assets/sound/notify.ogg" type="audio/ogg">
		<source src="{{ ABS_PATH }}assets/sound/notify.mp3" type="audio/mpeg">
		<source src="{{ ABS_PATH }}assets/sound/notify.wav" type="audio/wav">
	</audio>
{% endif %}
