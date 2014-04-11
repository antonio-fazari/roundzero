'use strict';

angular.module('roundzeroApp')
    .controller('GroupCtrl', function ($scope, $routeParams, MembershipService, GroupService, AuthService) {
        $scope.group = GroupService.get({id: $routeParams.groupId});
        $scope.user = AuthService.user;
        $scope.lowestBalance = null;

        $scope.group.$promise.then(function () {
            angular.forEach($scope.group.memberships, function (membership) {
                console.log(membership);
                if ($scope.lowestBalance === null || membership.balance < $scope.lowestBalance) {
                    $scope.lowestBalance = membership.balance;
                }
            });

            console.log($scope.lowestBalance);
        });
    });
