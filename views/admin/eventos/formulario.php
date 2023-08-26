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
    <div class="formulario__campo">
        <label for="nombre" class="formulario__label">Categoría o Tipo de Evento</label>
        <select class="formulario__select" name="categoria_id" id="categoria">
            <option value="">- Seleccionar -</option>
            <?php foreach($categorias as $categoria): ?>
                <option value="<?php echo $categoria->id; ?>">
                    <?php echo $categoria->nombre; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="formulario__campo">
        <label for="nombre" class="formulario__label">Selecciona el día</label>
        <div class="formulario__radio">
            <?php foreach($dias as $dia): ?>
                <div>
                    <label for="<?php echo strtolower($dia->nombre); ?>">
                        <?php echo $dia->nombre; ?>
                    </label>
                    <input 
                        type="radio"
                        id="<?php echo strtolower($dia->nombre); ?>" 
                        name="dia"
                        value="<?php echo $dia->id; ?>"   
                    >
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <div id="horas" class="formulario__campo">
        <label class="formulario__label">Seleccionar Hora</label>
        <ul class="horas">
            <?php foreach($horas as $hora): ?>
                <li class="horas__hora"><?php echo $hora->hora; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
</fieldset>
<fieldset class="formulario__fieldset">
    <legend class="formulario__legend">Información Exta</legend>
    <div class="formulario__campo">
        <label for="ponentes" class="formulario__label">Ponente</label>
        <input 
            type="text" 
            class="formulario__input"
            id="ponentes"
            placeholder="Buscar Ponente"
        >
    </div>
    <div class="formulario__campo">
        <label for="disponibles" class="formulario__label">Lugares Disponibles</label>
        <input 
            type="number" 
            min="1"
            class="formulario__input"
            id="disponibles"
            name="disponibles"
            placeholder="Ej. 20"
        >
    </div>
</fieldset>
