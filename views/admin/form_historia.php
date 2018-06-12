<section >
           <div class="lead text-success"><?=lang('chromebook:'.$this->method)?></div>

    <div class="row">
        <div class="col-lg-12">
             <?php if ($chromebook):?>
            <table class="table table-striped">
                <thead>
                    <tr>
                       <th>Evento</th>
                       <th>Fecha</th>
                       <th>Email</th>
                       <th>Email</th>
                       <th>Observaciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($chromebook as $chromebook):?>
                    <?php if(empty($chromebook->asignado)==false):?>
                    <tr>
                        <td>Asignado</td>
                        <td><?=$chromebook->asignado?></td>
                        <td><?=$chromebook->email?></td>
                        <td><?=$chromebook->obs_asig?></td>
                    </tr>
                    <?php endif;?>
                    <?php if(empty($chromebook->removido)==false):?>
                    <tr>
                        <td>Removido</td>
                        <td><?=$chromebook->removido?></td>
                        <td><?=$chromebook->email?></td>
                        <td><?=$chromebook->org_path?></td>
                        <td><?=$chromebook->obs_remov?></td>
                    </tr>
                    <?php endif;?>
                <?php endforeach;?>
                </tbody>
            </table>
        <?php else:?>

                <div class="alert alert-info text-center">          
                                La Chromebook No Cuenta con Historial
                </div>
        <?php endif;?>    
        </div> 
    </div> 
    <div class="divider"></div>
         <div class="form-actions">
            <a href="<?=base_url('admin/chromebooks')?>" class="btn btn-w-md ui-wave btn-default">Regresar</a>
         </div>                             

</section>