<section ng-controller="IndexCtrlAsig">
    <?php if(group_has_role('chromebooks','create')): ?>
        <a href="#"  ng-click="newChrome()" uib-tooltip="Nueva Chromebook" class="btn btn-primary pull-right">Nuevo</a>
    <?php endif;?>
    <?php if(!group_has_role('chromebooks','admin_chrome') && !$chromebooks): ?>
       <div class="alert alert-info text-center"><?=sprintf(lang('chromebook:not_asigned_chrome'))?></div>
    <?php else:?>
        <div class="row col-md-12">
            <h4 class="text-success">Buscar</h4>
            <input type="text" class="form-control" ng-model="txt_disponibles" />
            <hr />
            <p class="text-right">Total registros: {{(chromebooks).length}}</p>
            <table class="table">
                <thead>
                    <tr>
                        <th>SERIAL</th>
                        <th>Propietario</th>    
                        <th>Co-propietario</th>
                        <?php if(group_has_role('chromebooks','admin_chrome')): ?>
                            <th width="20%"></th>
                        <?php endif;?>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="chrome in chromebooks | filter:txt_disponibles|limitTo:20">
                        <td>{{chrome.id}}<br />
                            <span ng-if="chrome.email == null" class="text-muted">Disponible</span>
                            <span ng-if="chrome.email != null" class="text-muted">Asignado</span>
                        </td>
                        <td>{{chrome.email}}</td>
                        <td>{{chrome.org_path}}</td>
                        <?php if(group_has_role('chromebooks','admin_chrome')): ?>
                          <td ng-if="chrome.org_path == null"><a  href="#" ng-click="asignar(chrome)">Asignar</a></td>
                          <td ng-if="chrome.org_path != null"><a href="#" ng-click="remover(chrome)">Remover</a></td>
                        <?php endif;?>
                    </tr>
                </tbody>
            </table>
    </div>
    <?php endif;?>
</section>
<script type="text/ng-template" id="modalFormAsig.html">
    <div class="modal-header" >
        <h3>Asignar/Remover</h3>
    </div>
          <?php  echo form_open('','name="frm" id="frm"');?>

    <div class="modal-body">
                    <div ng-bind-html="message" ng-if="message" class="alert alert-danger" ></div>
                    <div class="form-group">
                            <label>No. serial</label>
                            <input type="text" class="form-control" ng-model="form.id" disabled/>
                     </div>   
                      <div class="form-group" ng-if="method=='create'" >
                            <label>Organización</label>
                            <select class="form-control" name="org" ng-blur="change()" ng-model="form.org"  ng-options="org.name for org in orgs track by org.org_path" required>
                                <option value=""> [ Elegir ] </option>
                            </select>
                     </div> 
                      <div class="form-group" ng-if="method=='edit'" >
                            <label></label>
                            <input class="form-control" name="org" ng-model="form.org" disabled/>
                     </div> 

                
    </div>
    <div class="modal-footer">
        <button type="button" ui-wave class="btn btn-flat" ng-click="cancel()">Cancelar</button>
        <button type="button" ui-wave class="btn btn-flat btn-primary" ng-disabled="!form.org" ng-click="save()" ng-if="method=='create'">Aceptar</button>
        <button type="button" ui-wave class="btn btn-flat btn-primary" ng-disabled="!form.org" ng-click="remove()" ng-if="method=='edit'">Remover</button>

    </div>    
     <?php echo form_close(); ?>                       
</script>

<script type="text/ng-template" id="modalAdd.html">
    <div class="modal-header" >
        <h3>Agregar Chromebook</h3>
    </div>
     <?php  echo form_open('','name="frm_add" id="frm_add"');?>
    <div class="modal-body">
        <div ng-bind-html="message" ng-if="message" class="alert alert-danger"></div>                    
                    <div class="form-group">
                            <label>No. serial</label>
                            <input type="text" class="form-control" ng-model="frm_add.serie"/>
                     </div>   
                      <div class="form-group">
                            <label>Organización</label>
                            <select class="form-control" name="org" ng-readonly="frm_add.id" ng-model="frm_add.org" ng-options="org.name for org in orgs track by org.org_path" required>
                                <option value=""> [ Elegir ] </option>
                            </select>
                            <div ng-messages="frm_add.org.$error"  role="alert" ng-if="frm_add.org.$dirty">
                                    <div class="text-danger" ng-message="required">Este campo es requerido</div>
                             </div>
                     </div> 
                    
                      

                
    </div>
    <div class="modal-footer">
       
                        
        <button type="button" ui-wave class="btn btn-flat" ng-click="cancel()">Cancelar</button>
        <button type="button" ui-wave class="btn btn-flat btn-primary" ng-click="save()">Aceptar</button>
    </div>    
     <?php echo form_close(); ?>                       
</script>
