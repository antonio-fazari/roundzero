'use strict';

angular.module('roundzeroApp')
    .controller('RegisterCtrl', ['$scope', '$rootScope', '$location', 'UserService',
        function ($scope, $rootScope, $location, UserService) {
            $scope.submitted = false;
            $scope.loading = false;
            $scope.error = null;

            $rootScope.user = new UserService();
            $scope.user = $rootScope.user;

            $scope.hideError = function () {
                $scope.error = null;
            };

            $scope.register = function () {
                $scope.submitted = true;
                $scope.error = null;
                if (!$scope.form.$invalid) {
                    $scope.loading = true;
                    $scope.user.$save(
                        function success() {
                            $scope.loading = false;
                            $scope.error = null;

                            $location.path('/account');
                        },
                        function error(response) {
                            $scope.loading = false;

                            if (response.error) {
                                $scope.error = response.error;
                            } else {
                                $scope.error = 'There was an error registering in. Please try later.';
                            }
                        });
                }
            };
        }
    ]);
