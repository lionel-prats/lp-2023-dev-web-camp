<h2 class="dashboard__heading"><?php echo $titulo; ?></h2>
<div class="dashboard__contenedor-boton">
    <a 
        href="/admin/eventos/crear"
        class="dashboard__boton"
    >
        <i class="fa-solid fa-circle-plus"></i>
        Añadir evento
    </a>
</div>

<div class="dashboard__contenedor">
    <?php if (!empty($eventos)): ?>
        <table class="table">
            <thead class="table__thead">
                <tr>
                    <th scope="col" class="table__th">Nombre</th>
                    <th scope="col" class="table__th">Tipo</th>
                    <th scope="col" class="table__th">Día y Hora</th>
                    <th scope="col" class="table__th">Ponente</th>
                    <th scope="col" class="table__th"></th>
                </tr>
            </thead>
            <tbody class="table__tbody">
                <?php foreach($eventos as $evento): ?>
                    <tr class="table__tr">
                        <td class="table__td">
                            <?php echo $evento->nombre; ?>
                        </td>
                        <td class="table__td">
                            <?php echo $evento->categoria_id; ?>
                        </td>
                        
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="text-center">No Hay Eventos Aún</p>
    <?php endif; ?>
</div>

<?php echo $paginacion; ?>