<section >
        <?php if($chromebooks):?>

        <?php echo form_open('admin/chromebooks?tab='.$status,'class="form-inline" method="get" ') ?>

          <div class="form-group col-md-5">
            <?php echo form_input('f_keywords', '', 'style="width: 100%;" class="form-control" placeholder="Buscar por Numero de Serie"') ?>
          </div>
          <div class="form-group col-md-5">
            <label >Estatus</label>
                    
                <?=form_dropdown('f_status',array('libres'=>' Sin Asignar','asignados'=>' Asignados'),false,'class="form-control"');?>
          </div>
    
          <button class="md-raised btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
          
        <?php echo form_close() ?>
  
 <hr />
    <?php endif;?>
        <ul class="nav nav-tabs">
           <li class="<?=$status=='libres'?'active':''?>"><a href="<?=base_url('admin/chromebooks?tab=libres')?>">Sin Asignar</a></li>

          <li class="<?=$status=='asignados'?'active':''?>"><a href="<?=base_url('admin/chromebooks?tab=asignados')?>">Asignados</a></li>
          

        </ul>
 <?php if ($chromebooks && empty($f_keywords)==true):?>
    <div class="tab-content" >
        <div  class="tab-pane fade in active">
                
            <div class="row">
            
            <?php if($status=='asignados'):?>                         
                <div class="col-lg-12">

             <?php else:?>
                         <div class="col-lg-6">

            <?php endif;?>

               <table class="table table-striped">
                    <thead>
                        <tr>
                           <th>Serie</th>
                           <?php if($status=='asignados'):?>
                           <th>Email</th>
                           <th>Org</th>
                           <?php endif;?>
                            
                            <th width="15%"></th>
                        </tr>

                    </thead>

                    <tbody>
                    <?php if($status=='asignados'):?>
                    <?php foreach($chromebooks as $chromebook):?>               
                        <tr>
                            <td ><a href="<?=base_url('admin/chromebooks/history/'.$chromebook->id_chromebook)?>"><?=$chromebook->serial?></a></td>
                            <td><?=$chromebook->email?></td>
                            <td><?=$chromebook->org_path?></td>
                             <td>                           
                            <a href="<?=base_url('admin/chromebooks/remover/'.$chromebook->id_chromebook)?>" ui-wave class="btn-icon  btn-icon-sm btn-tumblr" title="Desvincular del Correo"><i class="fa fa-minus" ></i></a>
                              <a href="<?=base_url('admin/chromebooks/acuse/'.$chromebook->id_chromebook)?>" ui-wave class="btn-icon  btn-icon-sm btn-primary" title="Descargar Acuse"><i class="fa fa-download" ></i></a>                 
                              </td>
                        </tr>
                    <?php endforeach;?>
                  <?php endif;?>

           <?php if($status=='libres'):?>
                    <?php foreach($chromebooks as $chromebook):?>
                      <?php  static  $count1 = 0;  if ($count1 == "10") { break; } else { ?>
                        <tr>
                            <td ><a href="<?=base_url('admin/chromebooks/history/'.$chromebook->id)?>"><?=$chromebook->serial?></a></td>
                             <td> 
                            <a href="<?=base_url('admin/chromebooks/asignar/'.$chromebook->id)?>" ui-wave class="btn-icon  btn-icon-sm btn-primary" title="Destalles"><i class="fa fa-plus" ></i></a> 
                              </td>
                        </tr>
                         <?php $count1++;} ?>
                    <?php endforeach;?>
                       <?php endif;?>

                    </tbody>
                </table>

            </div> 
            <?php if($status=='libres'):?>                         

            <div class="col-lg-6">

            
               <table class="table table-striped">
                    <thead>
                        <tr>
                           <th>Serie</th>
                           <th width="15%"></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                   $chromebooks2 =  array_reverse ($chromebooks );?>                    
                    <?php foreach($chromebooks2 as $chromebook):?>
                      <?php  static  $count2 = 0;  if ($count2 == "10") { break; } else { ?>
                        <tr>
                            <td ><a href="<?=base_url('admin/chromebooks/history/'.$chromebook->id)?>"><?=$chromebook->serial?></a></td>
                             <td> 
                            <a href="<?=base_url('admin/chromebooks/asignar/'.$chromebook->id)?>" ui-wave class="btn-icon  btn-icon-sm btn-primary" title="Destalles"><i class="fa fa-plus" ></i></a> 
                              </td>
                        </tr>
                         <?php $count2++;} ?>
                    <?php endforeach;?>
                    </tbody>
                </table>

            </div> 
<?php endif;?>
            <div class="col-lg-12">
                                    <p class="text-right text-muted">Total registros: <?=$pagination['total_rows']?> </p>

                    <p><?=$pagination['links']?></p>
                  </div>
            </div>                              
        </div>
    </div> 
  </div>  
<?php endif;?> 

    <?php if($_GET && $f_keywords):?>
            <div class="row">
            
            <?php if($status=='asignados'):?>                         
                <div class="col-lg-12">

             <?php else:?>
                         <div class="col-lg-6">

            <?php endif;?>

               <table class="table table-striped">
                    <thead>
                        <tr>
                           <th>Serie</th>
                           <?php if($status=='asignados'):?>
                           <th>Email</th>
                           <th>Org</th>
                           <?php endif;?>
                            
                            <th width="15%"></th>
                        </tr>

                    </thead>

                    <tbody>
                    <?php if($status=='asignados'):?>
                    <?php foreach($chromebooks as $chromebook):?>               
                        <tr>
                            <td ><a href="<?=base_url('admin/chromebooks/history/'.$chromebook->id_chromebook)?>"><?=$chromebook->serial?></a></td>
                            <td><?=$chromebook->email?></td>
                            <td><?=$chromebook->org_path?></td>
                             <td>                           
                            <a href="<?=base_url('admin/chromebooks/remover/'.$chromebook->id_chromebook)?>" ui-wave class="btn-icon  btn-icon-sm btn-tumblr" title="Desvincular del Correo"><i class="fa fa-minus" ></i></a>
                              <a  ui-wave class="btn-icon  btn-icon-sm btn-primary" title="Descargar Acuse"><i class="fa fa-download" ></i></a>                 
                              </td>
                        </tr>
                    <?php endforeach;?>
                  <?php endif;?>

           <?php if($status=='libres'):?>
                    <?php foreach($chromebooks as $chromebook):?>
                      <?php  static  $count1 = 0;  if ($count1 == "10") { break; } else { ?>
                        <tr>
                            <td ><a href="<?=base_url('admin/chromebooks/history/'.$chromebook->id)?>"><?=$chromebook->serial?></a></td>
                             <td> 
                            <a href="<?=base_url('admin/chromebooks/asignar/'.$chromebook->id)?>" ui-wave class="btn-icon  btn-icon-sm btn-primary" title="Destalles"><i class="fa fa-plus" ></i></a> 
                              </td>
                        </tr>
                         <?php $count1++;} ?>
                    <?php endforeach;?>
                       <?php endif;?>

                    </tbody>
                </table>

            </div> <!--fin_col 6 - 12-->

            <?php if($status=='libres' &&  ($count1>="10")):?>                         

            <div class="col-lg-6">

            
               <table class="table table-striped">
                    <thead>
                        <tr>
                           <th>Serie</th>
                           <th width="15%"></th>
                        </tr>
                    </thead>
                    <tbody>
                     
                    <?php $chromebooks2 =  array_reverse ($chromebooks );?>                    
                    <?php foreach($chromebooks2 as $chromebook):?>
                      <?php  static  $count2 = 0;  if ($count2 == "10") { break; } else { ?>
                        <tr>
                            <td ><a href="<?=base_url('admin/chromebooks/history/'.$chromebook->id)?>"><?=$chromebook->serial?></a></td>
                             <td> 
                            <a href="<?=base_url('admin/chromebooks/asignar/'.$chromebook->id)?>" ui-wave class="btn-icon  btn-icon-sm btn-primary" title="Destalles"><i class="fa fa-plus" ></i></a> 
                              </td>
                        </tr>
                         <?php $count2++;} ?>
                    <?php endforeach;?>
                    </tbody>
                </table>

            </div> 
<?php endif;?>
            <div class="col-lg-12">
                                    <p class="text-right text-muted">Total registros: <?=$pagination['total_rows']?> </p>

                    <p><?=$pagination['links']?></p>
                  </div>
            </div>                              
                                   
        
                
        <div class="alert alert-info text-center">
          
                                <a class="btn btn-default" href="<?=base_url('admin/chromebooks')?>">Mostrar todos</a>
         
        </div>
<?php elseif($status=='asignados' && !$chromebooks):?>

      <div class="divider"></div>
<div class="alert alert-info text-center">
        <?=lang('chromebook:not_found_asignado');?>

<?php elseif($status=='libres' && !$chromebooks):?>

      <div class="divider"></div>
<div class="alert alert-info text-center">
        <?=lang('chromebook:not_found_libres');?>
    <?php endif;?>

       

</section>