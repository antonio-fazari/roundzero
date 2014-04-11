'use strict';

angular.module('roundzeroApp')
    .controller('GroupMembersCtrl', ['$scope', '$routeParams', 'MembershipService', 'GroupService', 'AuthService',
        function ($scope, $routeParams, MembershipService, GroupService, AuthService) {
            $scope.group = GroupService.get({id: $routeParams.groupId});
            $scope.user = AuthService.user;

            $scope.removeMembership = function (membership) {
                for (var i = 0; i < $scope.group.memberships.length; i++) {
                    if ($scope.group.memberships[i] === membership) {
                        $scope.group.memberships.splice(i, 1);
                        var membershipRes = new MembershipService(membership);
                        membershipRes.$delete();
                        break;
                    }
                }
            };
        }
    ]);
