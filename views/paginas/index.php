<?php 
    // __DIR__ = "C:\xampp\htdocs\curso-valdez-php\lp-2023-dev-web-camp\views\paginas"
    include __DIR__ . "/conferencias.php";  // sliders con las conferencias y workshops
?>

<!-- sass = /src/scss/paginas/inicio/_resumen.scss-->
<section class="resumen">
    <div class="resumen__grid">
        <div class="resumen__bloque">
            <p class="resumen__texto resumen__texto--numero">
                <?php echo $ponentes_total; ?>
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

<!-- sass = /src/scss/paginas/inicio/_speakers.scss-->
<section class="speakers">
    <h2 class="speakers__heading">Speakers</h2>
    <p class="speakers__descripcion">Conoce a nuestros expertos de DevWebCamp</p>
    <div class="speakers__grid">
        <?php foreach($ponentes as $ponente): ?>
            <div class="speaker">
                <picture class="speaker__imagen">
                    <source srcset="/img/speakers/<?php echo $ponente->imagen; ?>.webp" type="image/webp">
                    <source srcset="/img/speakers/<?php echo $ponente->imagen; ?>.png" type="image/png">
                    <img 
                        src="/img/speakers/<?php echo $ponente->imagen; ?>.png" 
                        alt="Imagen Ponente"
                        loading="lazy"
                        width="200"
                        height="300"
                    >
                </picture>
                <div class="speaker__informacion">
                    <h4 class="speaker__nombre">
                        <?php echo $ponente->nombre . " " . $ponente->apellido; ?>
                    </h4>
                    <p class="speaker__ubicacion">
                        <?php echo $ponente->ciudad . ", " . $ponente->pais; ?>
                    </p>
                    <nav class="speaker-sociales">
                        <?php 
                            /*
                            $redes = stdClass Object ( 
                                [facebook] => https://facebook.com/C%C3%B3digo-Con-Juan-103341632130628 
                                [twitter] => https://twitter.com/codigoconjuan 
                                [youtube] => https://youtube.com/codigoconjuan 
                                [instagram] => 
                                [tiktok] => 
                                [github] => https://github.com/codigoconjuan 
                            )  
                            */ 
                            $redes = json_decode($ponente->redes);
                        ?>
                        <?php if(!empty($redes->facebook)): ?>
                            <a class="speaker-sociales__enlace" rel="noopener noreferrer" target="_blank" href="<?php echo $redes->facebook; ?>">
                                <!-- <span class="speaker__ocultar">Facebook</span> -->
                            </a> 
                        <?php endif; ?>
                        <?php if(!empty($redes->twitter)): ?>
                            <a class="speaker-sociales__enlace" rel="noopener noreferrer" target="_blank" href="<?php echo $redes->twitter; ?>">
                                <!-- <span class="speaker__ocultar">Twitter</span> -->
                            </a> 
                        <?php endif; ?>
                        <?php if(!empty($redes->youtube)): ?>
                            <a class="speaker-sociales__enlace" rel="noopener noreferrer" target="_blank" href="<?php echo $redes->youtube; ?>">
                                <!-- <span class="speaker__ocultar">YouTube</span> -->
                            </a> 
                        <?php endif; ?>
                        <?php if(!empty($redes->instagram)): ?>
                            <a class="speaker-sociales__enlace" rel="noopener noreferrer" target="_blank" href="<?php echo $redes->instagram; ?>">
                                <!-- <span class="speaker__ocultar">Instagram</span> -->
                            </a> 
                        <?php endif; ?>
                        <?php if(!empty($redes->tiktok)): ?>
                            <a class="speaker-sociales__enlace" rel="noopener noreferrer" target="_blank" href="<?php echo $redes->tiktok; ?>">
                                <!-- <span class="speaker__ocultar">Tiktok</span> -->
                            </a> 
                        <?php endif; ?>
                        <?php if(!empty($redes->github)): ?>
                            <a class="speaker-sociales__enlace" rel="noopener noreferrer" target="_blank" href="<?php echo $redes->github; ?>">
                                <!-- <span class="speaker__ocultar">Github</span> -->
                            </a>
                        <?php endif; ?>
                    </nav>
                    <ul class="speaker__listado-skills">
                        <?php 
                            // $tags = "PHP,Laravel,Flutter,Mongo"
                            $tags = explode(",", $ponente->tags); 
                            foreach($tags as $tag):
                        ?>
                            <li class="speaker__skill">
                                <?php echo $tag?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
</section>