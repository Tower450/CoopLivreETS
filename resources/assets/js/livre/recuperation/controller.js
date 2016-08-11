(function () {

    var recuperationLivreModule = angular.module('recuperationLivreModule',[]);

    recuperationLivreModule.controller('RecuperationLivreCtrl',['$scope','$http','$location','tokenService','$rootScope'
        ,function($scope, $http, $location,tokenService,$rootScope){

            $scope.afficherReservationChoisit = false;
            $scope.isAuthenticated = tokenService.isAuthenticatedGestionnaire();

            recupererLivre();

            $scope.selectionnerReservation = function(reservation){

                $scope.reservation = reservation;

                $scope.afficherReservation = false;
                $scope.afficherReservationChoisit = true;


            };

            $scope.confirmerReservation = function(){
                var data = {};
                data['idReservation'] = $scope.reservation.idReservation;
                console.log(data['idReservation']);

                $http.post($rootScope.requestUrl+'/livre/reservation/confirm',data).
                then(function(response) {
                    $scope.afficherReservationChoisit = false;
                }, function(response) {
                    console.log(response.status + " Failure");
                    $scope.error = true;
                });

            };

            function recupererLivre(){
                $http.get($rootScope.requestUrl+'/livre/reservation/get-confirm').
                then(function(response) {
                    $scope.lesReservations = response.data;
                    $scope.afficherReservation = true;
                    console.log( $scope.lesReservations);

                }, function(response) {

                    console.log(response.status + " Failure");
                    $scope.error = true;
                });
            }

        }]);


})();