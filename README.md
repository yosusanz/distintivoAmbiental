<h1>Distintivo ambiental desde fotografía de matrícula – PHP + YOLO + OCR</h1>
<p>
  <img src="https://img.shields.io/badge/IA-Deep%20Learning-blue?style=flat-square" alt="IA: Deep Learning">
  <img src="https://img.shields.io/badge/modelo-YOLOv8%20%2B%20OCR-9cf?style=flat-square" alt="Modelo: YOLOv8 + OCR">
  <img src="https://img.shields.io/badge/lenguaje-Python-orange?style=flat-square" alt="Lenguaje: Python">
  <img src="https://img.shields.io/badge/lenguaje-PHP-777BB4?style=flat-square&logo=php&logoColor=white" alt="Lenguaje: PHP">
  <img src="https://img.shields.io/badge/lenguaje-Vanilla%20JS-F7DF1E?style=flat-square&logo=javascript&logoColor=black" alt="Lenguaje: Vanilla JS">
  <img src="https://img.shields.io/badge/licencia-Apache%202.0-green?style=flat-square" alt="Licencia: Apache 2.0">
</p>

<section>
  <img src="https://raw.githubusercontent.com/yosusanz/yosusanz/984b4d8f5f8e82ce912412d1a407845018945d3a/images/proyecto-actual-distintivo-ambiental.png" alt="Proyecto Obtención distintivo ambiental" width="846"/>
  <p align="center">Debian Linux | NGINX | PHP | MariaDB | Python | HTML 5 | JS</p>
  <br>
  <p>Sistema basado en <strong>deep learning</strong> capaz de obtener el <strong>distintivo ambiental</strong> de un vehículo (CERO, ECO, C, B) a partir de una fotografía de su matrícula.</p>
  <p>El objetivo es demostrar cómo combinar <strong>Computer Vision, OCR avanzado y procesamiento eficiente</strong> de datos para resolver un problema real con múltiples retos técnicos: detección precisa, OCR robusto, alto rendimiento y validación con fuentes externas.</p>
  <br>
  <br>

  <br>
  <h2>📑 Índice</h2>
    <nav>
      <ul>
        <li><a href="#flujo-de-informacion">🔄 Flujo de información</a></li>
        <li><a href="#caracteristicas-principales">✅ Características principales</a></li>
        <li><a href="#casos-de-uso">Casos de uso</a></li>
        <li><a href="#por-que-es-interesante">💡 ¿Por qué es interesante?</a></li>
        <li><a href="#capturas-de-pantalla">🖼️ Capturas de pantalla</a></li>
        <li><a href="#licencia">📄 Licencia</a></li>
      </ul>
    </nav>
  <br>
  
  <br>
  <h2 id="flujo-de-informacion">🔄 Flujo de información</h2>
  <p>Diagrama simplificado del recorrido que sigue una petición típica desde que entra al sistema hasta que se genera una respuesta.</p>
  <p align="center">
    <img src="https://github.com/yosusanz/yosusanz/blob/main/images/distintivo-ambiental/flowchart-info.png?raw=true" alt="Flujo de información" width="846"/>
  </p>


  <h2 id="caracteristicas-principales">✅ Características principales</h2>
  <ul>
    <li>📸 Entrada: <strong>fotografía</strong> del vehículo</li>
    <li>🧠 Detección de matrículas con <strong>YOLOv8</strong></li>
    <li>🔎 Reconocimiento óptico de caracteres con <strong>EasyOCR</strong></li>
    <li>🕸️ Consulta automática a la <strong>DGT</strong> para obtener el distintivo ambiental</li>
    <li>🔁 <strong>Fallback inteligente</strong>: si la API no responde, se lanza scraping</li>
    <li>💾 <strong>Resultados persistidos</strong> en base de datos MariaDB</li>
    <li>⚙️ <strong>Arquitectura multicapa</strong>, sin dependencias externas PHP</li>
    <li>📦 Integración backend en <strong>PHP</strong> con llamadas a scripts <strong>Python</strong></li>
    <li>🔐 Soporte para <strong>ofuscación de rutas</strong></li>
  </ul>

  <br>
  <h2 id="casos-de-uso">Casos de uso</h2>
  <p>Este proyecto ha sido desarrollado como <strong>continuación del proyecto final</strong> presentado en el <strong>curso intensivo de 1 mes de Deep Learning</strong>, el cual incluyó prácticas con redes neuronales convolucionales, procesamiento de imágenes, creación de una API, webscrapping y la creación de una interface web.</p>
  <p>El objetivo es <strong>profesionalizar y evolucionar</strong> un proyecto sencillo aplicando buenas prácticas, arquitectura limpia y la integración de PHP con Deep Learning.</p>
  <p>Para el desarrollo se ha utilizado el proyecto <strong>"Template PHP Multicapa"</strong>.</p>

  <br>
  <h2 id="por-que-es-interesante">💡 ¿Por qué es interesante?</h2>
  <ul>
    <li>Porque <strong>resuelve un problema real</strong> combinando IA y software tradicional</li>
    <li>Porque muestra cómo <strong>integrar Python</strong> (YOLO + OCR) dentro de una arquitectura <strong>PHP</strong></li>
    <li>Porque se enfrenta a imágenes en <strong>condiciones difíciles</strong>: ángulos, reflejos, suciedad</li>
    <li>Porque incluye <strong>fallback automático</strong> (scraping) cuando no existen registros previos</li>
  </ul>

  <br>
  <h2 id="capturas-de-pantalla">🖼️ Capturas de pantalla</h2>
  <p align="center">
    <img src="https://github.com/yosusanz/yosusanz/blob/main/images/distintivo-ambiental/screenshots.png?raw=true" alt="Capturas de pantalla de la aplicación" width="846"/>
  </p>

  <br>
  <h2 id="licencia">📄 Licencia</h2>
  <p>
    <strong>Apache License 2.0</strong> – puedes usar esta plantilla libremente en proyectos personales o comerciales.<br>
    Es <strong>obligatorio</strong> mantener el aviso de copyright, la licencia y cualquier nota de atribución.<br>
    Las modificaciones también deben incluir una <strong>nota explicando los cambios</strong> realizados.
  </p>

  <br>
  <p align="center"><i>Desarrollado por <a href="https://www.linkedin.com/in/yosusanz" target="_blank">@yosusanz</a> – más proyectos en mi perfil de <a href="https://github.com/yosusanz" target="_blank">GitHub</a></i></p>
</section>
