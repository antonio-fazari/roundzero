'use strict';

angular.module('roundzeroApp')
  .controller('HeaderCtrl', function ($scope, AuthService) {
    $scope.$watch(AuthService.isLoggedIn, function (isLoggedIn) {
        $scope.isLoggedIn = isLoggedIn;
        $scope.currentUser = AuthService.currentUser();
    });
});
