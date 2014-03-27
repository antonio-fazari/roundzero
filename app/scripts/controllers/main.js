'use strict';

angular.module('roundzeroApp')
  .controller('MainCtrl', function ($scope, AuthService) {
    $scope.$watch(AuthService.isLoggedIn, function (isLoggedIn) {
      $scope.isLoggedIn = isLoggedIn;
      $scope.currentUser = AuthService.currentUser();
    });
  });
