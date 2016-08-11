(function () {


    var loginGestionnaireModule = angular.module('loginGestionnaireModule',[]);

    loginGestionnaireModule.controller('LoginGestionnaireCtrl',['$scope','$http','$location','tokenService','$rootScope'
        ,function($scope, $http,$location,tokenService,$rootScope){

        $scope.loginGestionnaire = function(){

            //If the form is valid (all regex are respected) we send the data.
            if(!$scope.gestionnaireForm.$valid){
                return;
            }

            console.log($scope.gestionnaire);

            $http.post($rootScope.requestUrl+'/gestionnaire/authenticate', $scope.gestionnaire).
                then(function(response) {
                    // this callback will be called asynchronously
                    // when the response is available
                    console.log(response.status + " Success");
                    console.log(response.data.token);
                    console.log(response.data.isEtudiant);
                    tokenService.set(response.data.token,response.data.isEtudiant);
                    console.log(tokenService.isAuthenticatedGestionnaire());
                    $location.path('/');
                }, function(response) {
                    // called asynchronously if an error occurs
                    // or server returns response with an error status.

                    console.log(response.status + " Failure");
                    $scope.error = true;
                });

        }

    }]);

})();