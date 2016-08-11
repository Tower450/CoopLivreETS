(function () {

    var ajoutLivreModule = angular.module('ajoutLivreModule',[]);

    ajoutLivreModule.controller('AjoutLivreCtrl',['$scope','$http','$location','tokenService','$rootScope'
        ,function($scope, $http, $location,tokenService,$rootScope){

            $scope.afficherInfoManquante = false;
            $scope.afficherChoixLivre = false;
            $scope.isAuthenticated = tokenService.isAuthenticatedEtudiant();
            $scope.livreTrouve=true;


            $scope.ajouterLivre = function(){

                if(!$scope.descriptionLivreForm.$valid){
                    return;
                }

                console.log($scope.livre);

                var data = {token:tokenService.get()};
                angular.extend(data,$scope.livre);

                $http.post($rootScope.requestUrl+'/livre/store', data).
                    then(function(response) {
                        // this callback will be called asynchronously
                        // when the response is available
                        console.log(response.status + " Success");
                        alert("Votre livre a été ajouté");
                        $scope.afficherInfoManquante = false;

                    }, function(response) {
                        // called asynchronously if an error occurs
                        // or server returns response with an error status.
                        console.log(response.status + " Failure");

                    });
            };

            $scope.rechercherISBN = function(){
                $scope.livreTrouve=true;
                if(!$scope.searchISBNForm.$valid) {
                    return;
                }
                $scope.afficherInfoManquante = false;
                $scope.livre=null;
                var data = {isbn:$scope.codeIsbn};
                $http.get($rootScope.requestUrl+'/livre/show', {
                    params:data
                }).
                    then(function(response) {
                        // this callback will be called asynchronously
                        // when the response is available
                        console.log(response.status + " Success");
                        console.log(response.data);
                        console.log("DATA");
                        $scope.livres = response.data.books;
                        if($scope.livres==null || $scope.livres[0]==null){
                            $scope.afficherInfoManquante = true;
                            $scope.livreTrouve=false;
                        }
                        else{
                            $scope.afficherChoixLivre = true;
                        }

                    }, function(response) {
                        // called asynchronously if an error occurs
                        // or server returns response with an error status.
                        console.log(response.status + " Failure");
                    });

            };

            $scope.selectionnerLivre = function(livre){
                $scope.livre = livre;
                //Si trouvé a vérifier avec le webservice
                $scope.afficherInfoManquante = true;
                $scope.afficherChoixLivre = false;
            };


            //Les différents états du livre et la valueurs du livre vendu
            $scope.etats = [
                {id:'0', name:"Comme neuf", value:0.75},
                {id:'1', name:"Usager altéré", value:0.5},
                {id:'2', name:"Détruit", value:0.25}
            ];

        }]);

})();