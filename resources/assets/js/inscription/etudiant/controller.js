(function () {

    var inscriptionEtudiantModule = angular.module('inscriptionEtudiantModule',[]);

    inscriptionEtudiantModule.controller('InscriptionEtudiantCtrl',['$scope','$http','$location','$rootScope'
        ,function($scope, $http, $location,$rootScope){

            $scope.log = function(){

                if(!$scope.etudiantForm.$valid) {
                    return;
                }

                //If the form is valid (all regex are respected) we send the data.
                //Send http via service.
                $http.post($rootScope.requestUrl+'/etudiant/store', $scope.etudiant).
                    then(function(response) {
                        // this callback will be called asynchronously
                        // when the response is available
                        console.log(response.status + " Success");

                        //TODO keep track of the token given by the server
                        //We redirect the connected user and show im new things well if we want to.
                        $location.path('/');
                    }, function(response){
                        // called asynchronously if an error occurs
                        // or server returns response with an error status.
                        console.log(response.status + " Failure");
                        $scope.error = true;
                    });
            };

        }]);

})();