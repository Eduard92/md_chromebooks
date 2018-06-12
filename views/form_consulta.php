    <!DOCTYPE html>

    <html lang="en-US">
    <head>
        
        <meta charset="UTF-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        {{ asset:css file="bootstrap.css" }}
        <!-- Begin scripts -->
        
        
        {{ asset:render }}
        <script type="text/javascript">
           function submit_form()
           {
              document.getElementById("form").submit();
           }
                
                 
                 function set_focus()
                 {
                    document.getElementById("serial").focus();
                   
                 }
        </script>
    </head>

    <body onload="set_focus()">
    <div class="container">
            <h4>Levantamiento</h4>

        <form action="" id="form" method="post">
        <?php if($_POST){ ?>

        <?php }?>
        <input type="text" onchange="submit_form()" name="serial" id="serial" class="form-control" style="border: 2px solid #000;" />
       
        <a href="<?=base_url('/chromebooks/')?>" > Inicio</a>
          <br>
          <br>

            <?php if($_POST && $message): ?>
        <?=$message?>
        <?php endif;?>
        
        
        </form>
    </div>

    </body>
    </html>