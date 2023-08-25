<fieldset class="formulario__fieldset">
    <legend class="formulario__legend">Información Evento</legend>
    <div class="formulario__campo">
        <label for="nombre" class="formulario__label">Nombre Evento</label>
        <input 
            type="text" 
            class="formulario__input"
            id="nombre"
            name="nombre"
            placeholder="Nombre Evento"
            value="<?php echo $ponente->nombre ?? "" ?>"
        >
    </div>
    <div class="formulario__campo">
        <label for="nombre" class="formulario__label">Descripción</label>
        <textarea 
            class="formulario__input"
            placeholder="Descripción Evento" 
            name="descripcion" 
            id="descripcion"
            rows="8"></textarea>
    </div>
</fieldset>