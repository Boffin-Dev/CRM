<?php
    require 'checklogin.php';
?>
<html>
    <head>
        <style>
            .photo{
                width: 32px;
                height: 32px;
                //border-radius: 50%;
                float: right;
                border: solid 1px blue;
            }

            .photo img{
                width:100%;
            }
        </style>
    </head>
    <body>
        <div><?php echo $_SESSION['username']; ?></div>
        <div class='photo'>
            <?php 
                echo '<img src="data:image/jpeg;base64,' . base64_encode($_SESSION['photo']) . '"  alt="F"/>';
            ?>
        </div>
    </body>

</html>