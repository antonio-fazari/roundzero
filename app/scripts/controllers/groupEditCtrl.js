'use strict';

angular.module('roundzeroApp')
    .controller('GroupEditCtrl', function ($scope, $controller, $location, $routeParams, AuthService, GroupService) {
        $controller('FormCtrl', {$scope: $scope});
        $scope.group = GroupService.get({id: $routeParams.groupId});
        $scope.user = AuthService.user;

        $scope.submit = function () {
            $scope.setStateSubmitted();

            if (!$scope.form.$invalid) {
                $scope.setStateLoading();

                $scope.group.$update(
                    function success(response) {
                        $scope.setStateSuccess();
                        $location.path('/group/' + response.id);
                    },
                    $scope.setStateError
                );
            }
        };
    });
