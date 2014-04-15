'use strict';

angular.module('roundzeroApp')
    .controller('AccountPasswordCtrl', function ($scope, $controller, AuthService) {
        $controller('FormCtrl', {$scope: $scope});
        $scope.editMode = false;

        $scope.update = function () {
            $scope.setStateSubmitted();

            if (!$scope.form.$invalid) {
                $scope.setStateLoading();

                AuthService.user.$update(
                    function success() {
                        $scope.setStateSuccess();
                        $scope.editMode = false;
                    },
                    $scope.setStateError
                );
            }
        };
    });
