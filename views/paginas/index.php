<?php 
    // __DIR__ = "C:\xampp\htdocs\curso-valdez-php\lp-2023-dev-web-camp\views\paginas"
    include __DIR__ . "/conferencias.php"; 
?>

<!-- sass = /src/scss/paginas/inicio/_resumen.scss-->
<section class="resumen">
    <div class="resumen__grid">
        <div class="resumen__bloque">
            <p class="resumen__texto resumen__texto--numero">
                <?php echo $ponentes; ?>
            </p>
            <p class="resumen__texto">Speakers</p>
        </div>
        <div class="resumen__bloque">
            <p class="resumen__texto resumen__texto--numero">
                <?php echo $conferencias; ?>
            </p>
            <p class="resumen__texto">Conferencias</p>
        </div>
        <div class="resumen__bloque">
            <p class="resumen__texto resumen__texto--numero">
                <?php echo $workshops; ?>
            </p>
            <p class="resumen__texto">Workshops</p>
        </div>
        <div class="resumen__bloque">
            <p class="resumen__texto resumen__texto--numero">
                <?php echo $asistentes; ?>
            </p>
            <p class="resumen__texto">Asisistentes</p>
        </div>
    </div>
</section>