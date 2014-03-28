'use strict';

angular.module('roundzeroApp')
    .controller('SignInCtrl', ['$scope', '$rootScope', '$http', '$location',
        function ($scope, $rootScope, $http, $location) {
            $scope.login = {
                email: '',
                password: ''
            };
            $scope.submitted = false;

            $scope.signIn = function () {
                $http.post('http://api.roundzeroapp.com/v1/tokens/authenticate', $scope.login)
                .success(function(response) {
                    $rootScope.token = response.id;
                    $rootScope.user = response.user;
                    $location.path('/account');
                })
                .error(function(response) {
                    $scope.error = response.error;
                    $scope.submitted = true;
                });
            };
        }
    ]);
