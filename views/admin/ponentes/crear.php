<h2 class="dashboard__heading"><?php echo $titulo; ?></h2>

<div class="dashboard__contenedor-boton">
    <a 
        href="/admin/ponentes"
        class="dashboard__boton"
    >
        <i class="fa-solid fa-circle-arrow-left"></i>
        Volver
    </a>
</div>

<div class="dashboard__formulario">
    <?php 
        # __DIR__ = "C:\xampp\htdocs\curso-valdez-php\lp-2023-dev-web-camp\views\admin\ponentes"
        include_once __DIR__ . "./../../templates/alertas.php";
    ?>

    <!-- enctype="multipart/form-data" -> atributo HTML para poder subir archivos (VIDEO 700) -->
    <form 
        method="POST" 
        class="formulario"
        enctype="multipart/form-data"     
    >
        <?php include_once __DIR__ . "./formulario.php"; ?>
        <input 
            type="submit" 
            value="Registrar Ponente"
            class="formulario__submit"    
        >
    </form>
</div>