(function () {

    var reservationLivreModule = angular.module('reservationLivreModule',[]);

    reservationLivreModule.controller('ReservationLivreCtrl',['$scope','$http','$location','tokenService','$rootScope','alertify'
        ,function($scope, $http, $location,tokenService,$rootScope,alertify){

            $scope.selected = true;
            $scope.typeReservation = 0;
            $scope.isAuthenticated = tokenService.isAuthenticatedEtudiant();
            $scope.afficherChoixLivre = false;
            $scope.afficherLivreChoisit = false;
            $scope.afficherNotificationIsbn = false;


            $scope.reserverLivre = function (stripeToken){

                console.log("le livre a été reservé merci bonsoir.");
                console.log($scope.livre);
                console.log(stripeToken);

                var data = {};
                data['token'] = tokenService.get();
                data['stripeToken'] = stripeToken;
                data['book'] = $scope.livre;
                $http.post($rootScope.requestUrl+'/livre/reservation/pay', data).
                then(function(response) {
                    console.log(response);
                    alert("Votre livre est reservé pendant 48 heures");
                    $scope.afficherChoixLivre = false;
                    $scope.afficherLivreChoisit = false;
                    $scope.livre = null;
                }, function(response) {

                    console.log(response.status + " Failure");
                    $scope.error = true;
                });

            };

            $scope.rechercherListeLivre = function(requete){

                console.log("sa va chercher la liste de livre avec  requete");
                var data = $scope.requete;
                console.log($scope.requete);

                $http.get($rootScope.requestUrl+'/livre/reservation', {
                    params:data
                }).
                then(function(response) {
                    $scope.livres = response.data;
                    $scope.afficherChoixLivre = true;
                    if(requete == "isbn" && $scope.livres.length == 0){
                        $scope.afficherNotificationIsbn = true;
                    }
                }, function(response) {

                    console.log(response.status + " Failure");
                    $scope.error = true;
                });

            };


            $scope.resetAffichageLivre = function(){

                console.log("rentre dans afficherChoixLivre");
                $scope.afficherChoixLivre = false;
                $scope.afficherLivreChoisit = false;

            };

            $scope.selectionnerLivre = function(livre){
                $scope.livre = livre;
                //Si trouvé a vérifier avec le webservice
                $scope.afficherLivreChoisit = true;
                $scope.afficherChoixLivre = false;

            };

            $scope.ajouterNotification = function(){

                if($scope.requete['isbn']==null){
                    alertify.error("L'isbn est vide");
                    return;
                }

                var data = {'isbn':$scope.requete['isbn']};
                data['token'] =  tokenService.get();

                $http.post($rootScope.requestUrl+'/livre/reservation/notification', data).
                then(function(response) {
                    alertify.success("Succès");
                }, function(response) {
                    alertify.error("Erreur, essayez à nouveau");
                    console.log(response.status + " Failure");
                    $scope.error = true;
                });

                $scope.afficherNotificationIsbn = false;
            }


        }]);

    reservationLivreModule.directive("stripeSubmit",[function(){
        return {
            link: function(scope,element,attrs){
                var handler = StripeCheckout.configure({
                    key: 'pk_test_2LQqfAArzsLg9f0gOXAMAxec',
                    image: '/img/documentation/checkout/marketplace.png',
                    locale: 'auto',
                    token: function(token) {
                        scope.reserverLivre(token);
                    }
                });

                $('#reserver').on('click', function(e) {
                    // Open Checkout with further options
                    handler.open({
                        name: 'CoopETS',
                        description: 'Achat de livre',
                        currency: "cad",
                        amount: scope.livre.price*100
                    });
                    e.preventDefault();
                });

                // Close Checkout on page navigation
                $(window).on('popstate', function() {
                    handler.close();
                });
            }
        }
    }]);
})();