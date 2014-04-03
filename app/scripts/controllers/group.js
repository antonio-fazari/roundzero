'use strict';

angular.module('roundzeroApp')
    .controller('GroupCtrl', ['$scope', '$routeParams', 'AuthService', 'GroupService', 'UserService',
        function ($scope, $routeParams, AuthService, GroupService, UserService) {
            // Get all group details.
            $scope.group = GroupService.get({id: $routeParams.groupId});
            // TODO: Fix API so group object comes along with user memberships.
            $scope.user = UserService.get({id: AuthService.userId});

            // TODO: Get the data for autocomplete.
            var userNames = ['Tony', 'Graham', 'Fred'];
            // See https://github.com/zensh/ui-autocomplete
            $scope.autocomplete = {
                options: {
                    source: userNames
                }
            };
        }
    ]);
