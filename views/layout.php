<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>App Salón</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;700;900&display=swap" rel="stylesheet"> 
    <link rel="stylesheet" href="/build/css/app.css">
     <!--Icon-Font-->
     <script src="https://kit.fontawesome.com/9029fc54ec.js" crossorigin="anonymous"></script>
</head>
<body>
    <div class="contenedor-app">
        <div class="imagen">
            <a href="https://wa.me/34123456789" class="btn-whatsapp" target="_blank">
	        <i class="fa fa-whatsapp"></i>
	        </a>
        </div>
        <div class="app">
            <?php echo $contenido; ?>
        </div>
    </div>
    <?php echo $script ?? ""; ?> 
</body>
</html>

