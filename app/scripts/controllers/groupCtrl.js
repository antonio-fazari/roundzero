'use strict';

angular.module('roundzeroApp')
    .controller('GroupCtrl', function ($scope, $routeParams, MembershipService, GroupService, AuthService) {
        $scope.group = GroupService.get({id: $routeParams.groupId});
        $scope.user = AuthService.user;
    });
