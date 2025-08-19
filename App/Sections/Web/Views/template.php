<?php use App\Config; ?>

<!doctype html>
<html>

<head>
	<meta charset="UTF-8">
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<meta http-equiv="content-language" content="es">
	<meta name="language" content="es-ES">
	<meta name="geo.region" content="ES">
	<meta name="geo.placename" content="España">
	<base href="/">

	<title>
		<?php echo $params['title']; ?>
	</title>
	<meta name="description" content="<?php echo $params['description']; ?>">
	<meta name="keywords" content="<?php echo $params['keywords']; ?>">
	<meta name="author" content="Yosu Sanz Iriarte">

	<meta content='width=device-width, initial-scale=1.0, maximum-scale=3.0, user-scalable=1' name='viewport' />
	<meta name="mobile-web-app-capable" content="yes">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self' 'unsafe-inline' https:; style-src 'self' 'unsafe-inline' https:; img-src 'self' data: https:;">
	<meta name="referrer" content="no-referrer-when-downgrade">

	<meta name="theme-color" content="#0A74DA">

	<link rel="icon" href="images/favicons/favicon.ico" sizes="any">
	<link rel="icon" href="images/favicons/icon.svg" type="image/svg+xml">
	<link rel="apple-touch-icon" href="images/favicons/apple-touch-icon.png">
	<link rel="manifest" href="/manifest.json">

	<meta name="theme-color" content="#0A74DA">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

	<link rel="stylesheet" href="./css/others/toastify/toastify.css" >
	<?php
		foreach ($params['css'] as $cssFile) {
			echo '<link rel="stylesheet" type="text/css" href="./css/' . $cssFile . '.css">';
		}

		unset($params['css']);
		unset($cssFile);
	?>
</head>

<body>
	<div id="loading-background"></div>
	<div id="loading-container">
		<span class="loader"></span>
	</div>

	<?php echo $contentHTML; ?>
	<?php echo $contentJs; ?>

	<?php if (Config::IS_PRODUCTION) { ?>
		<script src="./js/others/antiDebug/antiDebug.js"></script>
	<?php } ?>
</body>

</html>