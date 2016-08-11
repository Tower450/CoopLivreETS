(function (){

    var remiseLivreModule = angular.module('remiseLivreModule',[]);

    remiseLivreModule.controller('RemiseLivreCtrl',['$scope','$http','$location','tokenService', '$timeout','$rootScope'
        ,function($scope, $http, $location, tokenService, $timeout,$rootScope) {

            $scope.isAuthenticated = tokenService.isAuthenticatedGestionnaire();
            $scope.afficherChoixLivre = false;
            $scope.afficherConfirmationEtat = false;
            $scope.afficherConfirmationRemise = false;
            $scope.typeRechercheSelectionnee = null;
            $scope.alerts = [];

            //Les types de recherche possible
            $scope.typeRecherches = [
                {id:"0", name:"ISBN"},
                {id:"1", name:"UPC"},
                {id:"2", name:"titre"},
                {id:"3", name:"nom de l'étudiant"}
            ];

            $scope.typeRechercheChoisi = {id:'0',name:"ISBN"};

            //Les différents états du livre et sa valeur.
            $scope.etats = [
                {id:'0', name:"Comme neuf", value:0.75},
                {id:'1', name:"Usager altéré", value:0.5},
                {id:'2', name:"Détruit", value:0.25}
            ];

            $scope.rechercherLivre = function(){
                if(!$scope.searchBookForm.$valid){
                    return;
                }
                console.log($scope.typeRecherches);
                var data = {};
                data['token'] = tokenService.get();
                data['request'] = $scope.livreRechercher;
                $http.post($rootScope.requestUrl+'/livre/remise', data).
                then(function(response) {
                    $scope.livres = response.data;
                    $scope.afficherChoixLivre = true;
                }, function(response) {

                    console.log(response.status + " Failure");
                    $scope.error = true;
                });
            };



            $scope.sauvegarderRemise = function(isConfirm){
                if(!$scope.confirmationEtatForm.$valid){
                    return;
                }
                $scope.livre.estApprouve = true;
                $scope.afficherChoixLivre = false;
                $scope.afficherConfirmationEtat = false;
                $scope.afficherConfirmationRemise = true;

                console.log($scope.livre);

                //TODO: Faire la requete au server
                var data = {};
                data['token'] = tokenService.get();
                data['data'] = $scope.livre;
                data['confirm'] = isConfirm;
                $scope.livreRechercher = null;
                $scope.searchBookForm.$setPristine();
                $scope.searchBookForm.$setUntouched();
                $http.post($rootScope.requestUrl+'/livre/remise/confirm', data).
                then(function(response) {
                        $scope.alerts.push(alertAccepter);
                }, function(response) {

                    console.log(response.status + " Failure");
                    $scope.error = true;
                });
            };


            $scope.selectionnerLivre = function(livre){
                console.log(livre);
                $scope.livre = livre;
                $scope.afficherChoixLivre = false;
                $scope.afficherConfirmationEtat = true;
            };

            $scope.selectionTypeRecherche = function(typeRecherche){
                console.log($scope.recherche.type);
                $scope.typeRechercheSelectionnee = typeRecherche;
            };

            //TODO: Faire fonctionner les alerts
            var alertAccepter = {
                msg:"Remise enregistré. Un email de confirmation va être envoyer à l'étudiant"
            };

            var alertSupprimer = {
                msg:"Livre supprimé. Un email de confirmation va être envoyer à l'étudiant"
            };

            alertAccepter.close = function(){
                $scope.alerts.splice($scope.alerts.indexOf(this),1);
            };

            alertSupprimer.close = function(){
                $scope.alerts.splice($scope.alerts.indexOf(this),1);
            };

            $timeout(function(){
                $scope.alerts.splice($scope.alerts.indexOf(alertAccepter),1);
            },4000);

            $timeout(function(){
                $scope.alerts.splice($scope.alerts.indexOf(alertSupprimer),1);
            },4000);

        }]);
})();


