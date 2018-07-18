<section ng-controller="IndexCtrl">
        
    <a href="#"  ng-click="report()" uib-tooltip="Reporte" class="btn btn-primary pull-right">Reporte</a>

    <div class="row col-md-12">

        <div class="col-md-6">
            <h4 class="text-success">Disponibles</h4>
            <input type="text" class="form-control" ng-model="txt_disponibles" />
            <hr />
            <p class="text-right">Total registros:{{(chromebooks).length}}</p>
            <table class="table">
                <thead>
                    <tr>
                        <th>SERIAL</th>
                        <th width="20%"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="chrome in chromebooks | filter:txt_disponibles|limitTo:20">
                        <td> <a href="#" ng-click="details(chrome)">{{chrome.id}}</a><br />
                            <span class="text-muted">Disponible</span>
                        </td>
                        <td><a href="#" ng-click="add(chrome)">Asignar</a></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-6">
            <h4 class="text-success">Asignados</h4>
            <div class="input-group">
                <input type="text" class="form-control" data-ng-model="search_asignados"
                       />
                     <span class="input-group-btn">
                        <button class="btn" ng-click="search()"><i class="fa fa-search"></i></button>
                     </span>
                
            </div>
            <hr />
            
            
            <uib-alert ng-repeat="alert in alerts" type="{{alert.type}}" close="closeAlert($index)">{{alert.message}}</uib-alert>
            <p class="text-right">Total registros:{{pagination.total_rows}}</p>
            <table class="table">
                <thead>
                    <tr>
                        <th>SERIAL</th>
                        <th width="20%"></th>
                    </tr>
                </thead>
                <tr ng-repeat="chrome in asignaciones">
                    <td><a href="#" ng-click="details(chrome)">{{chrome.id_chromebook}}</a><br />
                    <span class="text-muted">{{chrome.email}}</span></td>
                    <td><a href="#" ng-click="remove(chrome)">Remover</a></td>
                </tr>
            </table>
            
             <uib-pagination class="pagination-sm"
                    ng-model="currentPage"
                    total-items="pagination.total_rows"
                    max-size="4"
                    ng-change="select(currentPage)"
                    items-per-page="numPerPage"
                    rotate="false"
                    previous-text="&lsaquo;" next-text="&rsaquo;"
                    boundary-links="true"></uib-pagination>
        
        </div>
    </div>
</section>
<script type="text/ng-template" id="modalForm.html">
    <div class="modal-header" >
        <h3>Asignar/Remover</h3>
    </div>
     <?php  echo form_open('','name="frm" id="frm"');?>
    <div class="modal-body">
   
        <uib-tabset class="ui-tab">
            <uib-tab  heading="Asignacion"  ng-if="method!='details'" active="true" >
            
                    <div ng-bind-html="message" ng-if="message" class="alert alert-info" ng-class="{'alert-success':!form.id && status,'alert-danger':!form.id&& !status}"></div>
                    <div class="form-group">
                            <label>No. serial</label>
                            <input type="text" class="form-control" ng-model="form.id_chromebook" disabled/>
                     </div>   
                      <div class="form-group">
                            <label>Organización</label>
                            <select class="form-control" name="org" ng-readonly="form.id" ng-model="form.org" ng-options="org.name for org in orgs track by org.org_path" required>
                                <option value=""> [ Elegir ] </option>
                            </select>
                            <div ng-messages="frm.org.$error"  role="alert" ng-if="frm.org.$dirty">
                                    <div class="text-danger" ng-message="required">Este campo es requerido</div>
                             </div>
                     </div> 
                     <div class="form-group">
                            <label>Email</label>
                            <select class="form-control" name="email" ng-readonly="form.id" ng-model="form.email" ng-options="email.email for email in emails track by email.email" required>
                                <option value=""> [ Elegir ] </option>
                                
                            </select>
                            <div ng-messages="frm.email.$error"  role="alert" ng-if="frm.email.$dirty">
                                    <div class="text-danger" ng-message="required">Este campo es requerido</div>
                             </div>
                     </div>   
                     
                     <div class="form-group">
                            <label>Responsable</label>
                            <input type="text" class="form-control" name="responsable"  ng-readonly="form.id" ng-model="form.email.full_name" required>
                            <div ng-messages="frm.responsable.$error"  role="alert" ng-if="frm.responsable.$dirty">
                                    <div class="text-danger" ng-message="required">Este campo es requerido</div>
                             </div>
                     </div> 
                     <div class="form-group">
                            <label>Observaciones</label>
                            <textarea class="form-control" ng-model="form.observaciones"></textarea>
                     </div>  
                      
            </uib-tab>
            <uib-tab  heading="Historial" ng-click="history()" >
                 <div class="alert alert-info text-center" ng-if="!historial"><?=lang('chromebook:not_history')?></div>
                 <table class="table" ng-if="historial">
                    <thead>
                        <tr>
                            <th>Correo</th>
                            <th>Asignado</th>
                            <th>Removido</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="history in historial">
                        
                            <td>{{history.email}}</td>
                            <td>{{history.asignado|date :'dd/mm/yyyy'}}</td>
                            <td>{{history.removido}}</td>
                        </tr>
                    </tbody>
                 </table>
            </uib-tab>
        </uib-tabset>
                
    </div>
    <div class="modal-footer">
       
                        
        <div class="row" ng-if="!dispose">
            <div class="col-md-3 col-md-offset-4">
            <md-progress-circular md-mode="indeterminate"></md-progress-circular> <br/>Espere por favor....
            </div>
        </div>
        <button type="button" ui-wave class="btn btn-flat" ng-click="cancel()">Cancelar</button>
        <button type="button" ui-wave class="btn btn-flat btn-primary" ng-if="method!='details'" ng-click="save()" ng-disabled="!dispose || !valid_form()">Aceptar</button>
    </div>    
     <?php echo form_close(); ?>                       
</script>
<script type="text/ng-template" id="modalReport.html">
    <div class="modal-header" >
        <h3>Generar Reporte</h3>
    </div>
     <?php  echo form_open('','name="report" id="report"');?>
    <div class="modal-body">

                      <div class="form-group">
                            <label>Estatus</label>
                            <select class="form-control" name="estatus" ng-model="report.estatus" required>
                                <option value=""> [ Elegir ] </option>
                                <option value="0"> Disponibles </option>
                                <option value="1"> Asignados </option>
                            </select>
                       </div> 
                      <div class="form-group" ng-if="report.estatus == 1">
                            <label>Organización</label>
                            <select class="form-control" name="org"  ng-readonly="report.id" ng-model="report.org" ng-options="org.name for org in orgs track by org.org_path" required>
                                <option value=""> [ Elegir ] </option>
                            </select>
                            
                     </div>                 
                           
    </div>
    <div class="modal-footer">
       
                        
        <button type="button" ui-wave class="btn btn-flat" ng-click="cancel()">Cancelar</button>
        <button type="button" ui-wave class="btn btn-flat btn-primary" ng-click="save()" ng-disabled="!valid_form()">Aceptar</button>
    </div>    
     <?php echo form_close(); ?>                       
</script>