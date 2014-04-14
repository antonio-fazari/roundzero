'use strict';

angular.module('roundzeroApp')
    .controller('GroupDeleteCtrl', function ($scope, $controller, $location, $routeParams, AuthService, GroupService) {
        $controller('FormCtrl', {$scope: $scope});
        $scope.group = GroupService.get({id: $routeParams.groupId});
        $scope.user = AuthService.user;
        $scope.progress = false;

        $scope.delete = function () {
            $scope.progress = true;
            $scope.group.$delete(
                function success() {
                    $scope.progress = false;
                    $location.path('/');
                }
            );
        };
    });
