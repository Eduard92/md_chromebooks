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
        
        <form action="" id="form" method="post">
        <?php if($_POST){ ?>
        <input type="hidden" name="org_path" value="<?=$this->input->post('org_path')?>" class="form-control" />
        <?php }else{?>
        <?=form_dropdown('org_path',array('Organizacion')+$orgs,$this->input->post('org_path'),'class="form-control"')?>
        
        <?php }?>
        <input type="text" onchange="submit_form()" name="serial" id="serial" class="form-control" style="border: 2px solid #000;" />
        <p style="font-size: 12px;">Total alumnos: <?=$total_alumnos?> | Asignados : <?=$total_asignados?> | org:<?=$this->input->post('org_path')?></p>
    
        
        
            <?php if($_POST && $message): ?>
        <?=$message?>
        <?php endif;?>
        <?php if($asignado):  ?>
            <dl class="dl-horizontal">
                <dt>Asignado:</dt>
                <dd> <?=$asignado->full_name?></dd>
                <dt>Email:</dt>
                <dd> <?=$asignado->email?></dd>
                <dt>Serial:</dt>
                <dd> <?=$asignado->serial?></dd>
                <dt>Org:</dt>
                <dd> <?=$asignado->org_path?></dd>
            </dl>
        
        <?php endif;?>
        
        
        </form>
        
        <a href="<?=base_url('/chromebooks/')?>" > Inicio</a>
    </div>

    </body>
    </html>