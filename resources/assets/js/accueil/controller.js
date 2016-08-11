(function () {

    var accueilModule = angular.module('accueilModule',[]);

    accueilModule.controller('AccueilCtrl',['$scope','$http',function($scope, $http){
        $scope.poulet = "test";
    }]);

})();