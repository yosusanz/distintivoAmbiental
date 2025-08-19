<?php
	ob_start();
?>
<div id="container-404">
    <h1>404</h1>
    <p>La página solicitada no existe</p>
</div>
<?php
	$contentHTML = ob_get_clean();
	ob_start();

	$contentJs = ob_get_clean();

	include '../App/Sections/Web/Views/template.php';
?>