(function () {
    'use strict';
    
    angular.module('app')
    .controller('InputCtrl',['$scope','$http','$rootScope','$sce','$cookies','$timeout',InputCtrl]);
    

    function InputCtrl($scope,$http,$rootScope,$sce,$cookies,$timeout)
    {
        $scope.$watch('org_path.selected',function(newValue,oldValue){
            
            
            
            var org_path = newValue;
           
           console.log(org_path);
            if(!newValue)
            {
               return ;
            }
            
            
            if(oldValue && oldValue != newValue )$scope.org_path='';
            
            $http.post(SITE_URL+'admin/chromebooks/get_emails',{org_path:org_path}).then(function(response){
                
               

               emails = response.data;
               $scope.alummos = response.data;

               console.log(emails);
                
            });
           
            
            
            
        });

         $scope.$watch('alumno.selected',function(newValue,oldValue){
            
                if(newValue == oldValue)
                {
                   return ;
                }
                console.log(newValue);
              
              $scope.email_alum = newValue.email;

         
         });
    }
    
})();