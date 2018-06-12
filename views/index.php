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
<body >
<div class="container">
        <h3>Asignacion Chromebooks</h3>

</div>
<table>
<thead>
            <tr>
                <th WIDTH="255">Plantel</th>
                
                <th width="90">Alumnos</th>
                <th>Asignadas</th>
   
            </tr>
        </thead>

<tbody>
          <?php foreach($total_asignados as $elemento):?>
            <tr>

                <td><?= $elemento->org_path?></td>
                
                <td><?= $elemento->alumnos?></td>

                
                <td><?= $elemento->numrows?></td>

               
            </tr>
        <?php endforeach;?>
            <tr height="40">

                <td><strong>Disponibles</strong></td>
                
                <td><strong><?php echo $disponibles?></strong></td>

               
            </tr>
    </tbody>
</table>

<table>
<br>
<tbody>
    <tr>

        <td  WIDTH="100"><a type="button" href="<?=base_url('/chromebooks/agregar')?>"> Dar de Alta</a></td>
                
        <td WIDTH="100" > <a href="<?=base_url('/chromebooks/remover')?>" > Dar de Baja</a></td>

        <td> <a href="<?=base_url('/chromebooks/consulta')?>" > Levantamiento</a></td>

      </tr>

    </tbody>
</table>

 
 <br> <br>

 




</body>
</html>