(function(){
    //When the device is ready we configure angularJs
    var cooplivre = angular.module('cooplivre', [
        'ngRoute',
        'ngTouch',
        'accueilModule',
        'inscriptionModule',
        'inscriptionEtudiantModule',
        'inscriptionGestionnaireModule',
        'loginModule',
        'loginGestionnaireModule',
        'loginEtudiantModule',
        'ajoutLivreModule',
        'reservationLivreModule',
        'recuperationLivreModule',
        'remiseLivreModule',
        'coopTransfertModule',
        'coopImportModule',
        'ngAlertify'
    ]);


    cooplivre.run(['$rootScope',function($rootScope) {
        var isProd = false;
        $rootScope.requestUrl = (isProd)? "http://ets.arid.me":"http://localhost:8080";

    }]);

    //List of all the route with their template and controllers
    cooplivre.config(['$routeProvider','$locationProvider',
        function($routeProvider,$locationProvider) {
            $routeProvider.
                when('/', {
                    templateUrl: '../views/accueil-view.html',
                    controller: 'AccueilCtrl'
                }).
                when('/inscription', {
                    templateUrl: '../views/inscription-view.html',
                    controller: 'InscriptionCtrl'
                }).
                when('/inscription/etudiant', {
                    templateUrl: '../views/inscription-etudiant-view.html',
                    controller: 'InscriptionEtudiantCtrl'
                }).
                when('/inscription/gestionnaire', {
                    templateUrl: '../views/inscription-gestionnaire-view.html',
                    controller: 'InscriptionGestionnaireCtrl'
                }).
                when('/livre/ajout', {
                    templateUrl: '../views/ajout-livre-view.html',
                    controller: 'AjoutLivreCtrl'
                }).
                when('/livre/reservation', {
                    templateUrl: '../views/reservation-livre-view.html',
                    controller: 'ReservationLivreCtrl'
                }).
                when('/livre/recuperation', {
                    templateUrl: '../views/recuperation-livre-view.html',
                    controller: 'RecuperationLivreCtrl'
                }).
                when('/livre/remise', {
                    templateUrl: '../views/remise-livre-view.html',
                    controller: 'RemiseLivreCtrl'
                }).
                when('/login', {
                    templateUrl: '../views/login-view.html',
                    controller: 'LoginCtrl'
                }).
                when('/login/gestionnaire', {
                    templateUrl: '../views/login-view-gestionnaire.html',
                    controller: 'LoginGestionnaireCtrl'
                }).
                when('/login/etudiant', {
                    templateUrl: '../views/login-view-etudiant.html',
                    controller: 'LoginEtudiantCtrl'
                }).
                when('/coop/transfert', {
                    templateUrl: '../views/coop-transfert-view.html',
                    controller: 'CoopTransfertCtrl'
                }).
                when('/coop/import', {
                templateUrl: '../views/coop-import-view.html',
                controller: 'CoopImportCtrl'
                }).
                otherwise({
                    redirectTo: '/'
                });
        }]);

    //Global directive, very usefull for password comparison
    cooplivre.directive("compareTo",[function(){
        return {
            require:"ngModel",
            scope:{
                compareTo:'='
            },
            link: function(scope,element,attrs,ngModelController){
                scope.$watch(function(){
                    return scope.compareTo;
                },function(){
                    validate();
                });
                scope.$watch(
                    function(){
                        return ngModelController.$modelValue;
                    }, function(){
                        validate();
                    }, true);

                function validate(){
                    ngModelController.$setValidity("compareTo", ngModelController.$modelValue == scope.compareTo);
                }
            }
        }
    }]);//Global directive, very usefull for password comparison
    cooplivre.directive("coopHeader",['tokenService','$location',function(tokenService,$location){
        return {
            transclude: true,
            templateUrl: '../views/header-view.html',
            link: function(scope,element,attrs){

                setAllAuthentificationStates();

                scope.disconnect = function(){
                    tokenService.delete();
                    $location.path('/');
                };

                scope.$on("authentificationChange",function(){
                    setAllAuthentificationStates();
                });

                function setAllAuthentificationStates(){
                    scope.isAuthenticated = tokenService.isAuthenticated();
                    scope.isEtudiant = tokenService.isAuthenticatedEtudiant();
                    scope.isGestionnaire = tokenService.isAuthenticatedGestionnaire();
                }
            }
        }
    }]);

    //Global service the authentification state of the user.
    cooplivre.factory("tokenService",["$rootScope","$window",function($rootScope,$window){

        var that = this;
        this.token = $window.localStorage.token;
        this.isEtudiant = $window.localStorage.isEtudiant || false;
        this.isGestionnaire = $window.localStorage.isGestionnaire || false;
        console.log(this.isGestionnaire+ "EST GESTIONNAIRE");
        console.log(this.isEtudiant+ "EST ÉTUDIANT");
        return{
            set:function(token,isEtudiant){
                that.token = token;
                $window.localStorage.token = that.token;
                if(isEtudiant){
                    $window.localStorage.isEtudiant = true;
                    that.isEtudiant = true;
                }
                else{
                    $window.localStorage.isGestionnaire = true;
                    that.isGestionnaire = true;
                }
                $rootScope.$broadcast('authentificationChange');
            },
            get:function(){
                return that.token;
            },
            delete:function(){
                that.token = null;
                that.isStudent = false;
                that.isGestionnaire = false;
                $window.localStorage.removeItem("token");
                $window.localStorage.removeItem("isEtudiant");
                $window.localStorage.removeItem("isGestionnaire");
                $rootScope.$broadcast('authentificationChange');
            },
            isAuthenticatedGestionnaire:function(){
                return that.token != null && that.isGestionnaire;
            },
            isAuthenticatedEtudiant:function(){
                return that.token != null && that.isEtudiant;
            },
            isAuthenticated:function(){
                return that.token != null;
            }

        }
    }]);
})();