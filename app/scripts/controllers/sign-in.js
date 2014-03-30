'use strict';

angular.module('roundzeroApp')
    .controller('SignInCtrl', ['$scope', '$rootScope', '$http', '$location',
        function ($scope, $rootScope, $http, $location) {
            $scope.login = {
                email: '',
                password: ''
            };
            $scope.submitted = false;
            $scope.loading = false;
            $scope.error = null;

            $scope.hideError = function () {
                $scope.error = null;
            };

            $scope.signIn = function () {
                $scope.submitted = true;
                $scope.error = null;
                if (!$scope.form.$invalid) {
                    $scope.loading = true;
                    $http.post('http://api.roundzeroapp.com/v1/tokens/authenticate', $scope.login)
                    .success(function(response) {
                        $scope.loading = false;
                        $scope.error = null;

                        $rootScope.token = response.id;
                        $rootScope.user = response.user;
                        $location.path('/account');
                    })
                    .error(function(response) {
                        $scope.loading = false;

                        if (response.error) {
                            $scope.error = response.error;
                        } else {
                            $scope.error = 'There was an error logging in. Please try later.';
                        }
                    });
                }
            };
        }
    ]);
