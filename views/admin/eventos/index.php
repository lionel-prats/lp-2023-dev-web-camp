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
                            <?php echo $evento->categoria->nombre; ?>
                        </td>
                        <td class="table__td">
                            <?php echo $evento->dia->nombre . ", " . $evento->hora->hora; ?>
                        </td>
                        <td class="table__td">
                            <?php echo $evento->ponente->nombre . " " . $evento->ponente->apellido; ?>
                        </td>
                        <td class="table__td--acciones">
                            <a  
                                href="/admin/eventos/editar?id=<?php echo $evento->id; ?>"
                                class="table__accion table__accion--editar"    
                            >
                                <i class="fa-solid fa-pencil"></i>
                                Editar
                            </a>
                            <form action="/admin/ponentes/eliminar" method="POST" class="table__formulario">
                                <input type="hidden" name="id" value="<?php echo $evento->id; ?>">
                                <button 
                                    type="submit"
                                    class="table__accion table__accion--eliminar"    
                                >
                                    <i class="fa-solid fa-circle-xmark"></i>    
                                    Eliminar
                                </button>
                            </form>
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