{% spaceless %}
<!DOCTYPE html>
<html>
    <head>
		<meta charset="UTF-8" />
		<title>{{app.app_name}}</title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />

		<meta http-equiv="Cache-Control" content="public">
		<meta http-equiv="Cache-Control" content="max-age=3600, must-revalidate">

		<link rel="shortcut icon" href="favicon.ico">

		<script>
            var ave_path = '{{ ABS_PATH }}';
            var csrf_token = '{{ csrf_token }}';
		</script>

    </head>
    <body id="signin">
		<div class="top_promo_block">
			<div class="infinite-background"></div>
		</div>

        <div class="bg">
			<h3>
			    <i class="icon-rocket"></i> {{app.app_name}}
			</h3>

            {{ content }}
        </div>

		<script async src="{{ ABS_PATH }}assets/build/js/frontend.min.js" type="text/javascript"></script>
		<link rel="stylesheet" href="{{ ABS_PATH }}assets/build/css/frontend.min.css" type="text/css">
        <link href='//fonts.googleapis.com/css?family=PT+Sans:400,700&subset=latin,cyrillic-ext' rel='stylesheet' type='text/css'>
    </body>
</html>
{% endspaceless %}
