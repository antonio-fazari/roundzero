'use strict';

angular.module('roundzeroApp')
    .controller('SignInCtrl', ['$scope', '$http', '$location', 'AuthService', 'UserService',
        function ($scope, $http, $location, AuthService, UserService) {
            $scope.login = {
                email: '',
                password: '',
                remember: true
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

                        AuthService.login(response, $scope.login.remember);

                        if (AuthService.user.memberships.length) {
                            $location.path('/group/' + AuthService.user.memberships[0].groupId);
                        } else {
                            $location.path('/group/add');
                        }
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
