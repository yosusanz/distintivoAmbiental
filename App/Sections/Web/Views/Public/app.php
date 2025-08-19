<?php
	ob_start();
?>

    <div class="container">

		<section id="image-load-section">
			<div id="image-container">
				<img id="image-preview" src="./images/icons/icon-camera.svg">
			</div>
			<input type="file" id="image-input" accept="image/*">

			<p id="please-load-photo">Por favor, carga una foto</p>
			<button id="obtain-environmental-codes" class="btn-primary">Obtener distintivo</button>
		</section>

		<section id="plates-section">
			<h2>MATRICULAS DETECTADAS</h2>
			<div class="plates-table">
				<div class="table-row header">
					<span>Imagen</span>
					<span>Matrícula</span>
					<span>Confianza</span>
				</div>
			</div>
			<div id="plates-buttons-container">
				<button class="btn-primary small">Reprocesar</button>
			</div>
		</section>

		<section id="stickers-section">
			<h2>DISTINTIVOS OBTENIDOS</h2>
			<div class="stickers">
			</div>
		</section>

	</div>

	<footer class="app-footer">
		<div class="footer-left">
			<span>Apache License 2.0</span>
			<span class="subspan">Desarrollado por <strong>Yosu Sanz Iriarte</strong></span>
		</div>
		<div class="footer-right">
			<span class="subspan">Sígueme en</span>
			<div class="linkedin-block">
			<a href="https://www.linkedin.com/in/yosusanz" target="_blank" rel="noopener noreferrer" class="linkedin-link">
				<img src="./images/icons/linkedin.png" alt="LinkedIn" class="linkedin-icon">
			</a>
			</div>
		</div>
	</footer>
<?php
	$contentHTML = ob_get_clean();
	ob_start();
?>

	<script type="module" src="/js/app/appController.js"></script>

<?php
	$contentJs = ob_get_clean();

	include '../App/Sections/Web/Views/template.php';
?>