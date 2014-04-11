'use strict';

angular.module('roundzeroApp')
    .controller('RegisterCtrl', function ($scope, $location, $http, UserService, AuthService) {
        $scope.submitted = false;
        $scope.loading = false;
        $scope.error = null;

        $scope.user = new UserService();

        $scope.hideError = function () {
            $scope.error = null;
        };

        $scope.register = function () {
            // Store credentials so we can login to new account.
            var login = {
                email: $scope.user.email,
                password: $scope.user.password
            };
            $scope.submitted = true;
            $scope.error = null;

            if (!$scope.form.$invalid) {
                $scope.loading = true;

                // Create new user.
                $scope.user.$save(
                    function success() {
                        // Created user, now login using stored credentials.
                        $http.post('http://api.roundzeroapp.com/v1/tokens/authenticate', login)
                        .success(function(response) {
                            $scope.loading = false;
                            $scope.error = null;

                            AuthService.login(response);
                            $location.path('/group/add');
                        })
                        .error(function() {
                            $scope.loading = false;
                            $scope.error = 'There was an error logging in to your new account. Please try later.';
                        });
                    },
                    function error(response) {
                        $scope.loading = false;

                        if (response.error) {
                            $scope.error = response.error;
                        } else {
                            $scope.error = 'There was an error registering. Please try later.';
                        }
                    });
            }
        };
    });
