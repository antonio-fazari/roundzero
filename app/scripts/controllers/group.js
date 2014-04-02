'use strict';

angular.module('roundzeroApp')
    .controller('GroupCtrl', ['$scope', '$routeParams', '$location', 'GroupService',
        function ($scope, $routeParams, $location, GroupService) {
            // Get all group details
            $scope.group = GroupService.get({id: $routeParams.groupId});
        }
    ]);
