(function () {
    'use strict';
    
    angular.module('app')
    .controller('IndexCtrl',['$scope','$http','$uibModal','$filter','logger',IndexCtrl])
    .controller('InputCtrl',['$scope','$http','$uibModalInstance','chrome','asignaciones','chromebooks','method',InputCtrl])
    .controller('InputModalReport',['$scope','$http','$uibModalInstance','$window',InputModalReport])

    .controller('IndexCtrlAsig',['$scope','$http','$uibModal','$filter','logger',IndexCtrlAsig])
    .controller('InputModalAsig',['$scope','$http','$uibModalInstance','chrome','method','logger',InputModalAsig])
    .controller('InputModalAdd',['$scope','$http','$uibModalInstance','logger',InputModalAdd]);

    function IndexCtrl($scope,$http,$uibModal,$filter,logger)
    {
        var init;
        var q='';
        $scope.alerts = [];
        $scope.numPerPage = 20;
        $scope.currentPage = 1;
        $scope.currentPage = [];
        $scope.select = select;
        //$scope.onFilterChange = onFilterChange;
        //$scope.search = search;
        $scope.historial = [];
        $scope.chromebooks = resume.chromebooks;
        $scope.asignaciones   = resume.asignaciones;
        $scope.pagination = {total_rows:0};

        select();
        
        function select(page) {
            //var end, start;
            //start = (page - 1) * $scope.numPerPage;
            //end = start + $scope.numPerPage;
            page = page?page:1;
            
            $http.get(SITE_URL+'admin/chromebooks/asignaciones/load/'+page,{params:{q:q}}).then(function(response){
                
                var result = response.data;
                $scope.pagination = result.data.pagination;
                $scope.asignaciones = result.data.rows;
            });
            
            
            //return $scope.currentPageAsignaciones = $scope.filteredStores.slice(start, end);
        };

        
        
        $scope.details = function(chrome)
        {
             var modalInstance = $uibModal.open({
                            animation: $scope.animationsEnabled,
                            templateUrl: 'modalForm.html',
                            controller: 'InputCtrl',
                  
                            resolve: {
                                asignaciones: function () {
                                    return false;
                                },
                                chromebooks: function () {
                                    return false;
                                },
                                chrome: function () {
                                    return chrome;
                                },
                                method:function(){
                                    return 'details';
                                }
                            }
                      });
        }
        $scope.not_in_asignados = function(item,inverse)
        {
            
             if ($scope.asignaciones) {
                
                  var result = true;
                  $.each($scope.asignaciones,function(index,data){
                    
                      if(data.id_chromebook == item.id)
                      {
                        result = false;
                         
                      }
                  });
                  
                  return result;

            }
            
            return true;
        }
       $scope.add = function(chrome)
       {
           
              var modalInstance = $uibModal.open({
                            animation: $scope.animationsEnabled,
                            templateUrl: 'modalForm.html',
                            controller: 'InputCtrl',
                  
                            resolve: {
                                chrome: function () {
                                    return chrome;
                                },
                                asignaciones: function () {
                                    return $scope.asignaciones;
                                },
                                chromebooks: function () {
                                    return $scope.chromebooks;
                                },
                                 method: function () {
                                    return 'create';
                                }
                            }
                      });
              modalInstance.result.then(function (result) {
                
                //$scope.asignaciones.push(result);
                //$scope.vehiculo_select = result;
                
                if(result.status)
                {
                    select(1);
                }
                if(result.message)
                {
                    if(result.status)
                    {
                        logger.logSuccess(result.message);
                    }
                    else
                    {
                        logger.logError(result.message);
                    }
                }
            }, function (result) {



            });
       }   
       $scope.remove = function(chrome)
       {
           
              var modalInstance = $uibModal.open({
                            animation: $scope.animationsEnabled,
                            templateUrl: 'modalForm.html',
                            controller: 'InputCtrl',
                  
                            resolve: {
                                chrome: function () {
                                    return chrome;
                                },
                                asignaciones: function () {
                                    return $scope.asignaciones;
                                },
                                chromebooks: function () {
                                    return $scope.chromebooks;
                                },
                                 method: function () {
                                    return 'edit';
                                }
                            }
                           
                      });
              modalInstance.result.then(function (result) {
                
                //$scope.asignaciones.push(result);
                //$scope.vehiculo_select = result;
                
                if(result.status)
                {
                    select(1);
                } 
                
               // $scope.status = result.status;
                
                if(result.message)
                {
                    if(result.status)
                    {
                        logger.logSuccess(result.message);
                    }
                    else
                    {
                        logger.logError(result.message);
                    }
                    //$scope.alerts.push({type:result.status?'success':'danger',message:result.message});
                }
                
            }, function (result) {



            });
       }  
       $scope.search = function()
       {
            q = $scope.search_asignados;
            select(1);
       }

        $scope.report = function()
        {
             var modalInstance = $uibModal.open({
                            animation: $scope.animationsEnabled,
                            templateUrl: 'modalReport.html',
                            controller: 'InputModalReport',
                  
 
                      });
        }
    }

    function IndexCtrlAsig($scope,$http,$uibModal,$filter,logger)
    {

        $scope.chromebooks = resume;

       $scope.asignar = function(chrome)
       {
           
              var modalInstance = $uibModal.open({
                            animation: $scope.animationsEnabled,
                            templateUrl: 'modalFormAsig.html',
                            controller: 'InputModalAsig',
                            resolve: {
                                chrome: function () {
                                    return chrome;
                                },
                                method: function () {
                                    return 'create';
                                }                                  
                            }
                      });

       } 
       $scope.remover = function(chrome)
       {
           
              var modalInstance = $uibModal.open({
                            animation: $scope.animationsEnabled,
                            templateUrl: 'modalFormAsig.html',
                            controller: 'InputModalAsig',
                            resolve: {
                                chrome: function () {
                                    return chrome;
                                } ,
                                method: function () {
                                    return 'edit';
                                }                              
                            }
                      });

       }  

       $scope.newChrome = function()
       {
           
              var modalInstance = $uibModal.open({
                            animation: $scope.animationsEnabled,
                            templateUrl: 'modalAdd.html',
                            controller: 'InputModalAdd',
                  
                            resolve: {
                                chrome: function () {
                                    return chrome;
                                },
                            }
                      });

       }  
    }

    function InputCtrl($scope,$http,$uibModalInstance,chrome,asignaciones,chromebooks,method)
    {
        
        $scope.orgs = orgs;
        $scope.dispose = true;
        $scope.method = method;
        $scope.history = history;
        
        
        if(chrome.id_chromebook)
        {
            $scope.form = {
                id:chrome.id,
                id_chromebook : chrome.id_chromebook,
                org:{org_path:chrome.org_path},
                email:{email:chrome.email,full_name:chrome.responsable},
                observaciones:chrome.observaciones
            };
            
            $scope.message = '<strong>ATENCION</strong><br/>  Este equipo va a ser removido';
        }
        else
        {
            $scope.form = {
                
                id_chromebook : chrome.id,
                org:{org_path:chrome.org_path},

            };
        }
        
        if(method=='details')
        {
            history();
        }
        function history()
        {
            $http.post(SITE_URL+'admin/chromebooks/asignaciones/history/'+$scope.form.id_chromebook,{}).then(function(response){                
                var result =response.data;
                
                if(result.status)
                    $scope.historial = result.data;
                
            });
        }
        $scope.cancel = function () {
            $uibModalInstance.dismiss("cancel");
        }
        
        $scope.save = function()
        {
            $scope.dispose = false;
            var url = chrome.id_chromebook?'admin/chromebooks/asignaciones/remover/':'admin/chromebooks/asignaciones/asignar/';
            $http.post(SITE_URL+url+$scope.form.id_chromebook,$scope.form).then(function(response){
                
                $scope.dispose = true;
               var result = response.data;
               
               if(result.status)
               {
                    //console.log(chrome);
                    //console.log(asignados);
                    if(chrome.id_chromebook)
                    {
                        var index = asignaciones.indexOf(chrome);
                        asignaciones.splice(index,1);
                        
                        chromebooks.push({id:chrome.id_chromebook})
                    }
                    else{
                        ///Verificar viabilidad
                        var index = chromebooks.indexOf(chrome);
                        chromebooks.splice(index,1);
                        
                        result.data.org_path = $scope.form.org.org_path;
                        result.data.full_name = $scope.form.email.full_name;
                        //result.data.id = String(result.data.id);
                        //console.log(result.data);
                        //asignaciones.push(result.data);
                    }
                     $uibModalInstance.close(result);
                   
               }
               else{
                   $scope.status = result.status;
                   $scope.message = result.message;
               }
              
               //$scope.emails = response.data;
               //$scope.alummos = response.data;

               //console.log(emails);
                 
            });
        }
        $scope.$watch('form.org',function(newValue,oldValue){
            
            
            
            var org_path = newValue;
           
           
            if(!newValue)
            {
               return ;
            }
            
            
            if(oldValue && oldValue != newValue )$scope.org_path='';
            
            $http.post(SITE_URL+'admin/chromebooks/asignaciones/get_emails',{org_path:org_path.org_path}).then(function(response){
 
               
               $scope.emails = response.data;
              
                
            });
            
            
        });

        $scope.valid_form = function () {
            return $scope.frm.$valid;
        }


        $scope.change = function()
        {
          var org_path = $scope.form.org.org_path;
          var id_chromebook = $scope.form.id_chromebook

          $http.post(SITE_URL+'admin/chromebooks/asignaciones/getOrgChrome',{org_path:org_path,id_chromebook:id_chromebook}).then(function(response){
                  
                  var result = response.data;

                   $scope.message = result.message;

                   if (result.status == false){
                     $scope.form.org_distinct = true;
                   }else
                   {
                     $scope.form.org_distinct = false;
                   }
                                                 
            });
         
        }
    }

    function InputModalReport($scope,$http,$uibModalInstance,$window)
    {
          $scope.orgs = orgs;

          $scope.org = $scope.orgs[0];

         $scope.cancel = function(){
             $uibModalInstance.dismiss("cancel");
        }
               
        $scope.save = function(){

            var estatus = $scope.estatus?$scope.estatus:'';
            var org_path = $scope.org?$scope.org.org_path:'';
            
            if(org_path == null)
            {
              $scope.message = 'Favor de llenar todos los campos';
            }
            else
            {
              $window.open(SITE_URL+'admin/chromebooks/report/?estatus='+estatus+'&org='+org_path); 

              $uibModalInstance.close();
            }
                                  
        }

    }

    function InputModalAsig($scope,$http,$uibModalInstance,chrome,method,logger)
    {
        $scope.orgs = orgs;
        
        var org = chrome.org_path?chrome.org_path:'';
        
        $scope.method = method;
        
        $scope.form = {
                
                id : chrome.id,
                org : chrome.org_path?chrome.org_path:''
            };


         $scope.cancel = function(){
             $uibModalInstance.dismiss("cancel");
        }


        $scope.valid_form = function (){
           return $scope.report.$valid;
        } 
                
        $scope.save = function(){

            var serie = $scope.form.id;
            var org_path = $scope.form.org.org_path;

           
            $http.post(SITE_URL+'admin/chromebooks/asignarOrg',{org_path:org_path,serie:serie}).then(function(response){
              
                 var result = response.data;
               
               if(result.status)
               {
                   $scope.chrome = chrome;

                   chrome.org_path = org_path;
                   
                   logger.logSuccess(result.message);

                   $uibModalInstance.close();


               }
               else{
                   $scope.status = result.status;

                   $scope.message = result.message;

               }
            });
                  
        }

        $scope.remove = function(){

            var serie = $scope.form.id;
 
            $http.post(SITE_URL+'admin/chromebooks/removerOrg',{serie:serie}).then(function(response){
              
               var result = response.data;
               
               if(result.status)
               {
                   $scope.chrome = chrome;
                   
                   chrome.org_path = null;

                   logger.logSuccess(result.message);

                   $uibModalInstance.close();
               }
               else
               {
                   $scope.status = result.status;

                   $scope.message = result.message;

               }
            });
                  
        }


    }

    function InputModalAdd($scope,$http,$uibModalInstance,logger)
    {
        $scope.orgs = orgs; 
        $scope.chromebooks = resume.chromebooks;
      
         $scope.cancel = function(){
             $uibModalInstance.dismiss("cancel");
        }

   
        $scope.save = function(){

            var serie    = $scope.frm_add.serie;
            var org_path = $scope.frm_add.org.org_path?$scope.frm_add.org.org_path:null;

            $http.post(SITE_URL+'admin/chromebooks/newChromebook',{org_path:org_path,serie:serie}).then(function(response){
               var result = response.data;

               if(result.status)
               {
                     resume.chromebooks.unshift({'id':serie,'org_path':org_path});
                
                     $uibModalInstance.close();

                     logger.logSuccess(result.message);
               }
               else
               {
                   $scope.status = result.status;

                   $scope.message = result.message;
               }


            });

        }
    }

    
})();