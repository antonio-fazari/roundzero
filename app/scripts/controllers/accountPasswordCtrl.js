'use strict';

angular.module('roundzeroApp')
    .controller('AccountPasswordCtrl', function ($scope, $controller, AuthService, UserService) {
        $controller('FormCtrl', {$scope: $scope});
        $scope.editMode = false;
        $scope.user = UserService.get({id: AuthService.userId});

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
