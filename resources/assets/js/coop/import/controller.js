(function (){

    var coopImportModule = angular.module('coopImportModule',[]);

    coopImportModule.controller('CoopImportCtrl',['$scope','$http','$location','tokenService','$rootScope'
        ,function($scope, $http, $location, tokenService,$rootScope) {

            $scope.afficherChoixLivre = false;
            $scope.isAuthenticated = tokenService.isAuthenticatedGestionnaire();

            $scope.livreSelectionne = [];

            getLivre();

            $scope.selectionnerLivre = function(livre){
                $scope.livres.splice($scope.livres.indexOf(livre),1);
                $scope.livreSelectionne.push(livre);
            };

            $scope.enleverLivre = function(livre){
                $scope.livreSelectionne.splice($scope.livreSelectionne.indexOf(livre),1);
                $scope.livres.push(livre);
            };

            $scope.envoyer = function(){
                var data = {};
                data['livres'] = $scope.livreSelectionne;
                console.log(data);
                $http.post($rootScope.requestUrl+'/livre/import/confirm',data).
                then(function(response) {
                    $scope.livreSelectionne = [];
                    getLivre();
                }, function(response) {
                    console.log(response.status + " Failure");
                    $scope.error = true;
                });
            };


            function getLivre(){
                $http.get($rootScope.requestUrl+'/livre/import').
                then(function(response) {
                    $scope.livres = response.data;

                }, function(response) {
                    console.log(response.status + " Failure");
                    $scope.error = true;
                });
            }

        }]);
})();