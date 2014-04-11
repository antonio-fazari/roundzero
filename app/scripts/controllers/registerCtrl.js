'use strict';

angular.module('roundzeroApp')
    .controller('RegisterCtrl', function ($scope, $controller, $location, $http, UserService, AuthService) {
        $controller('FormCtrl', {$scope: $scope});

        $scope.user = new UserService();

        $scope.register = function () {
            $scope.setStateSubmitted();

            // Store credentials so we can login to new account.
            var login = {
                email: $scope.user.email,
                password: $scope.user.password
            };

            if (!$scope.form.$invalid) {
                $scope.setStateLoading();

                // Create new user.
                $scope.user.$save(
                    function success() {
                        // Now login using stored credentials.
                        $http.post('http://api.roundzeroapp.com/v1/tokens/authenticate', login)
                        .success(function(response) {
                            $scope.setStateSuccess();

                            AuthService.login(response);
                            $location.path('/group/add');
                        })
                        .error($scope.setStateError);
                    },
                    $scope.setStateError
                );
            }
        };
    });
