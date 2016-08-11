(function () {

    var inscriptionGestionnaireModule = angular.module('inscriptionGestionnaireModule',[]);

    inscriptionGestionnaireModule.controller('InscriptionGestionnaireCtrl',
        ['$scope','$http','$location','$rootScope',function($scope, $http,$location,$rootScope){

            $scope.affForm1 = true;
            $scope.affForm2 = false;

            //Envoi des données concernant les informations du gestionnaire.
            $scope.envoyerGestionnaireInfo = function(){
                //If the form is valid (all regex are respected) we send the data.
                if(!$scope.gestionnaireForm.$valid){
                   return;
                }

                console.log($scope.gestionnaire);

                $http.post($rootScope.requestUrl+'/gestionnaire/exists', $scope.gestionnaire).
                    then(function(response) {
                        // this callback will be called asynchronously
                        // when the response is available
                        console.log(response.status + " Success");

                        //Cette ligne de codes seront executé quand la reponse du serveur sera valide.
                        //Pour l'instant elle s'execute peu importe le résultat du serveur.
                        afficherFormulaireCoop($scope);
                        $scope.error = false;

                    }, function(response) {
                        // called asynchronously if an error occurs
                        // or server returns response with an error status.
                        console.log(response.status + " Failure");
                        $scope.error = true;
                    });

            };


            //Envoi des données de la coop du gestionnaire.
            $scope.envoyerGestionnaireCoopInfo = function(){

                if(!$scope.coopForm.$valid){
                    return;
                }
                console.log($scope.gestionnaireCoop);
                var data = angular.extend($scope.coop,$scope.gestionnaire);
                console.log(data);
                $http.post($rootScope.requestUrl+'/gestionnaire/store', data).
                    then(function(response) {
                        // this callback will be called asynchronously
                        // when the response is available
                        console.log(response.status + " Success");
                        $location.path('/');

                    }, function(response) {
                        // called asynchronously if an error occurs
                        // or server returns response with an error status.
                        console.log(response.status + " Failure");
                    });


            };



        }]);

    function afficherFormulaireCoop($scope){

        $scope.affForm1 = false;
        $scope.affForm2 = true;

    }

    //Si éventuellement on retourne en arrière pour le premier formulaire.
    function afficherFormulaireGestionnaire($scope){

        $scope.affForm1 = false;
        $scope.affForm2 = true;
    }

})();