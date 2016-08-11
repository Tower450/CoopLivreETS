(function () {

    var loginEtudiantModule = angular.module('loginEtudiantModule',[]);

    loginEtudiantModule.controller('LoginEtudiantCtrl',['$scope','$http','$location','tokenService','$rootScope'
        ,function($scope, $http,$location,tokenService,$rootScope){

        $scope.loginEtudiant = function(){

            //If the form is valid (all regex are respected) we send the data.
            if(!$scope.etudiantForm.$valid){
                return;
            }
            console.log($scope.etudiant);
            $http.post($rootScope.requestUrl+'/etudiant/authenticate', $scope.etudiant).
                then(function(response) {
                    // this callback will be called asynchronously
                    // when the response is available
                    console.log(response.status + " Success");
                    console.log(response.data.token + "Token name");
                    tokenService.set(response.data.token,response.data.isEtudiant);
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