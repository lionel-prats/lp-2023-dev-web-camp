<fieldset class="formulario__fieldset">
    <legend class="formulario__legend">Información Personal</legend>
    <div class="formulario__campo">
        <label for="nombre" class="formulario__label">Nombre</label>
        <input 
            type="text" 
            class="formulario__input"
            id="nombre"
            name="nombre"
            placeholder="Nombre Ponente"
            value="<?php echo $ponente->nombre ?? "" ?>"
        >
    </div>
    <div class="formulario__campo">
        <label for="apellido" class="formulario__label">Apellido</label>
        <input 
            type="text" 
            class="formulario__input"
            id="apellido"
            name="apellido"
            placeholder="Apellido Ponente"
            value="<?php echo $ponente->apellido ?? "" ?>"
        >
    </div>
    <div class="formulario__campo">
        <label for="ciudad" class="formulario__label">Ciudad</label>
        <input 
            type="text" 
            class="formulario__input"
            id="ciudad"
            name="ciudad"
            placeholder="Ciudad Ponente"
            value="<?php echo $ponente->ciudad ?? "" ?>"
        >
    </div>
    <div class="formulario__campo">
        <label for="pais" class="formulario__label">País</label>
        <input 
            type="text" 
            class="formulario__input"
            id="pais"
            name="pais"
            placeholder="País Ponente"
            value="<?php echo $ponente->pais ?? "" ?>"
        >
    </div>
    <div class="formulario__campo">
        <label for="imagen" class="formulario__label">Imagen</label>
        <input 
            type="file" 
            class="formulario__input formulario__input--file"
            id="imagen"
            name="imagen"
        >
    </div>

    <!-- imagen previa del ponente en el formulario de edición de ponente -->
    <?php if(isset($ponente->imagen_actual)): ?>
        <p class="formulario__texto">ImagenActual:</p>
        <div class="formulario__imagen">

            <!-- si el navegador soporta .webp (hoy practicamente todos) se va a cargar la version .webp ganando en performance ya que este formato es mucho mas liviando que el formato .png (VIDEO 713) -->
            <picture>
                <source srcset="<?php echo $_ENV['HOST'] . '/img/speakers/' . $ponente->imagen_actual; ?>.webp" type="image/webp">
                <source srcset="<?php echo $_ENV['HOST'] . '/img/speakers/' . $ponente->imagen_actual; ?>.png" type="image/png">
                <img 
                    src="<?php echo $_ENV['HOST'] . '/img/speakers/' . $ponente->imagen_actual; ?>.png" 
                    alt="Imagen Ponente"
                >
            </picture>


        </div>
    <?php endif; ?>

</fieldset>

<fieldset class="formulario__fieldset">
    <legend class="formulario__legend">Información Extra</legend>
    <div class="formulario__campo">
        <label for="tags_input" class="formulario__label">Áreas de Experiencia (separadas por coma)</label>
        <input 
            type="text" 
            class="formulario__input"
            id="tags_input"
            placeholder="Ej. Node.js, PHP, CSS, Laravel, UX / UI"
        >
        
        <!-- 
        *** En el form de creación de ponente este <div> mostrará por pantalla en tiempo real <li> con las experiencia que va a ir ingresando el administrador en el <input id="tags_input"> 
        *** En el form de edición, apenas se cargue el form, ya aparecerá cargado con los <li> de cada experiencia asociada al ponente, data que viene de la BD (VIDEO 714).
         -->
        <div id="tags" class="formulario__listado"></div>

        <!-- este input es capturafo en tags.js y desde ahí se utiliza para completar el <div id="tags"> y para enviar los tags de un ponente al servidor -->
        <input type="hidden" name="tags" value="<?php echo $ponente->tags ?? ""; ?>">

    </div>
</fieldset>

<fieldset class="formulario__fieldset">
    <legend class="formulario__legend">Redes Sociales</legend>
    <div class="formulario__campo">
        <div class="formulario__contenedor-icono">
            <div class="formulario__icono">
                <i class="fa-brands fa-facebook"></i>
            </div>
            <input 
                type="text" 
                class="formulario__input--sociales"
                name="redes[facebook]"
                placeholder="Facebook"
                value="<?php echo $redes->facebook ?? "" ?>"
            >
        </div>
    </div>
    <div class="formulario__campo">
        <div class="formulario__contenedor-icono">
            <div class="formulario__icono">
                <i class="fa-brands fa-twitter"></i>
            </div>
            <input 
                type="text" 
                class="formulario__input--sociales"
                name="redes[twitter]"
                placeholder="Twitter"
                value="<?php echo $redes->twitter ?? "" ?>"
            >
        </div>
    </div>
    <div class="formulario__campo">
        <div class="formulario__contenedor-icono">
            <div class="formulario__icono">
                <i class="fa-brands fa-youtube"></i>
            </div>
            <input 
                type="text" 
                class="formulario__input--sociales"
                name="redes[youtube]"
                placeholder="YouTube"
                value="<?php echo $redes->youtube ?? "" ?>"
            >
        </div>
    </div>
    <div class="formulario__campo">
        <div class="formulario__contenedor-icono">
            <div class="formulario__icono">
                <i class="fa-brands fa-instagram"></i>
            </div>
            <input 
                type="text" 
                class="formulario__input--sociales"
                name="redes[instagram]"
                placeholder="Instagram"
                value="<?php echo $redes->instagram ?? "" ?>"
            >
        </div>
    </div>
    <div class="formulario__campo">
        <div class="formulario__contenedor-icono">
            <div class="formulario__icono">
                <i class="fa-brands fa-tiktok"></i>
            </div>
            <input 
                type="text" 
                class="formulario__input--sociales"
                name="redes[tiktok]"
                placeholder="TikTok"
                value="<?php echo $redes->tiktok ?? "" ?>"
            >
        </div>
    </div>
    <div class="formulario__campo">
        <div class="formulario__contenedor-icono">
            <div class="formulario__icono">
                <i class="fa-brands fa-github"></i>
            </div>
            <input 
                type="text" 
                class="formulario__input--sociales"
                name="redes[github]"
                placeholder="GitHub"
                value="<?php echo $redes->github ?? "" ?>"
            >
        </div>
    </div>
</fieldset>