'use strict';

angular.module('roundzeroApp')
    .controller('AccountDetailsCtrl', function ($scope, $controller, AuthService) {
        $controller('FormCtrl', {$scope: $scope});
        $scope.editMode = false;
        $scope.user = AuthService.user;
        console.log($scope.user);

        $scope.update = function () {
            $scope.setStateSubmitted();

            if (!$scope.form.$invalid) {
                $scope.setStateLoading();

                $scope.user.$update(
                    function success() {
                        $scope.setStateSuccess();
                        $scope.editMode = false;
                    },
                    $scope.setStateError
                );
            }
        };
    });
