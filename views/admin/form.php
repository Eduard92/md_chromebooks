<div ng-controller="InputCtrl" >
         <div class="lead text-success"><?=lang('chromebook:'.$this->method)?></div>

      <?php echo form_open();?>

    <div class="row">
        <div class=" col-md-6">
            <div class="form-group">
                <label>Org</label>
                <?php if ($this->method!='asignar'):?>
                <?php echo form_input('org_path',$chromebook->org_path,'class="form-control" disabled')?>
                <?php elseif ($this->method=='asignar'):?>
                <?=form_dropdown('org_path',array(''=>' [ Todos los centros/planteles ] ')+$orgs,null,'class="form-control"  ng-init="org_path.selected=\''.$chromebook->org_path.'\'" ng-model="org_path.selected" '.($this->method!='asignar'?'disabled':''));?>
                            <?php endif;?>


            </div>            
            <div class="form-group">
                <label>Nombre</label>
                <?php if ($this->method!='asignar'):?>
                <?php echo form_input('full_name',$chromebook->full_name,'class="form-control" disabled')?>
                <?php elseif ($this->method=='asignar'):?>
                <select name="alumno" ng-options="alumno.full_name for alumno in alummos track by alumno.id" ng-disabled="!alummos"  class="form-control" ng-model="alumno.selected">
                <option value=""> [ Elegir ] </option>
                </select>
            <?php endif;?>

            </div>
            <div class="form-group">
                <label>Email</label>
                <?php echo form_input('email',$chromebook->email,'class="form-control" ng-model="email_alum" required ng-init="email_alum=\''.$chromebook->email.'\'"'.($this->method!='asignar'?'disabled':''));?>
            </div>



        </div>
        <div class="col-lg-6">   
            <div class="form-group">
                <label>Serial</label>
                <?php echo form_input('serial',$chromebook->serial,'class="form-control" disabled')?>
            </div>
                <div class="form-group">
                <label>Responsable</label>
                <?php echo form_input('responsable',$chromebook->responsable,'class="form-control" required '.($this->method!='asignar'?'disabled':''));?>
            </div>
        <div class="form-group" >
            <label>Observaciones</label>
                <?php if($this->method=='details'):?>
                    <?php $data= array('name'=>'observaciones',
                    'value'=>$chromebook->observaciones,
                    'class'=>'form-control' ,'rows'=>'5',
                    'placeholder'=>'Anota tus observaciones aquí',
                    'disabled'=>'disabled') ?>
                <?php else:?>   
                        <?php $data= array('name'=>'observaciones',
                    
                    'class'=>'form-control' ,'rows'=>'5',
                    'placeholder'=>'Anota tus observaciones aquí' ) ?>
                <?php endif;?>
                     <?=form_textarea($data)?> 
        </div>

       </div>
    </div>                              
<div class="divider"></div>
         <div class="form-actions">
        <?php if($this->method!='details'):?>

            <?php $this->load->view('admin/partials/buttons', array('buttons' => array('save') )) ?>
        <?php endif;?>

            <a href="<?=base_url('admin/chromebooks')?>" class="btn btn-w-md ui-wave btn-default">Regresar</a>
         </div>

 
    <?php echo form_close();?>
</div>
