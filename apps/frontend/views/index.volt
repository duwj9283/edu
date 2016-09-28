<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>Phalcon PHP Framework</title>
		{{ stylesheet_link("3rdpart/bootstrap/css/bootstrap.min.css") }}
    </head>
    <body>
        <div class="container">
            <?php echo $this->getContent(); ?>
        </div>
        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		{{ javascript_include("3rdpart/jquery/jquery.min.js") }}
		{{ javascript_include("3rdpart/bootstrap/js/bootstrap.min.js") }}
        <!-- Latest compiled and minified JavaScript
        <script src="3rdpart/bootstrap/js/bootstrap.min.js"></script> -->
    </body>
</html>
