'use strict';

angular.module('roundzeroApp')
    .controller('AccountDetailsCtrl', function ($scope, AuthService, UserService) {
        $scope.submitted = false;
        $scope.loading = false;
        $scope.error = null;

        $scope.editMode = false;

        $scope.user = UserService.get({id: AuthService.userId});

        $scope.hideError = function () {
            $scope.error = null;
        };

        $scope.edit = function () {
            $scope.editMode = true;
        };

        $scope.cancel = function () {
            $scope.editMode = false;
        };

        $scope.update = function () {
            $scope.submitted = true;
            $scope.error = null;

            if (!$scope.form.$invalid) {
                $scope.loading = true;

                // Create new user.
                $scope.user.$update(
                    function success() {
                        $scope.submitted = false;
                        $scope.loading = false;
                        $scope.error = null;
                        $scope.editMode = false;
                    },
                    function error(response) {
                        $scope.loading = false;

                        if (response.error) {
                            $scope.error = response.error;
                        } else {
                            $scope.error = 'There was an updating your account. Please try later.';
                        }
                    });
            }
        };
    });
