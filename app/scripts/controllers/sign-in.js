'use strict';

angular.module('roundzeroApp')
    .controller('SignInCtrl', function ($scope, $rootScope, $resource, $http) {
        $scope.login = {
            email: '',
            password: ''
        };

        $scope.signIn = function () {
            $http.post('http://api.roundzeroapp.com/v1/tokens/authenticate', $scope.login)
            .success(function(response) {
                $rootScope.token = response.id;
                $rootScope.user = response.user;
            })
            .error(function(response) {
                $scope.error = response.error;
            });
        };
    });
