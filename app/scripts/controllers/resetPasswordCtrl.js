'use strict';

angular.module('roundzeroApp')
    .controller('ResetPasswordCtrl', function ($scope, $location, $controller, AuthService) {
        $controller('FormCtrl', {$scope: $scope});

        $scope.update = function () {
            $scope.setStateSubmitted();

            if (!$scope.form.$invalid) {
                $scope.setStateLoading();

                AuthService.user.$update(
                    function success() {
                        $scope.setStateSuccess();
                        $location.path('/');
                    },
                    $scope.setStateError
                );
            }
        };
    });
