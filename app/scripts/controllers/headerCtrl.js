'use strict';

angular.module('roundzeroApp')
    .controller('HeaderCtrl', function ($scope, $location, AuthService) {
        $scope.AuthService = AuthService;
        $scope.logout = function () {
            AuthService.logout();
            $location.path('/sign-in');
        };
    });
