(function () {
    'use strict';
    
    angular.module('app')
    .controller('IndexCtrl',['$scope','$http','$uibModal','$filter','logger',IndexCtrl])
    .controller('InputCtrl',['$scope','$http','$uibModalInstance','chrome','asignaciones','chromebooks','method',InputCtrl])
    .controller('InputModalReport',['$scope','$http','$uibModalInstance','$window',InputModalReport]);
    
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
            
            $http.get(SITE_URL+'admin/chromebooks/load/asignaciones/'+page,{params:{q:q}}).then(function(response){
                
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
                
                id_chromebook : chrome.id
            };
        }
        
        if(method=='details')
        {
            history();
        }
        function history()
        {
            $http.post(SITE_URL+'admin/chromebooks/history/'+$scope.form.id_chromebook,{}).then(function(response){
                
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
            var url = chrome.id_chromebook?'admin/chromebooks/remover/':'admin/chromebooks/asignar/';
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
            
            $http.post(SITE_URL+'admin/chromebooks/get_emails',{org_path:org_path.org_path}).then(function(response){
                
               

               $scope.emails = response.data;
              
                
            });
           
            
            
            
        });
        $scope.valid_form = function () {
            return $scope.frm.$valid;
        }
    }
    function InputModalReport($scope,$http,$uibModalInstance,$window)
    {
          $scope.orgs = orgs;



         $scope.cancel = function(){
             $uibModalInstance.dismiss("cancel");
        }


        $scope.valid_form = function (){
           return $scope.report.$valid;
        } 
                
        $scope.save = function(){

            var estatus = $scope.report.estatus;
            var org_path = $scope.report.org?$scope.report.org.org_path:'';

            console.log(org_path);
           
            $window.open(SITE_URL+'admin/chromebooks/report/?estatus='+estatus+'&org='+org_path); 

           $uibModalInstance.close();

                  
        }



    }

    
})();