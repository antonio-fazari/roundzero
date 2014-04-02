'use strict';

angular.module('roundzeroApp')
    .controller('GroupCtrl', ['$scope', '$routeParams', 'AuthService', 'GroupService', 'UserService',
        function ($scope, $routeParams, AuthService, GroupService, UserService) {
            // Get all group details.
            $scope.group = GroupService.get({id: $routeParams.groupId});
            // TODO: Fix API so group object comes along with user memberships.
            $scope.user = UserService.get({id: AuthService.userId});
        }
    ]);
