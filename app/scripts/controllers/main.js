'use strict';

angular.module('roundzeroApp')
    .controller('MainCtrl', ['$scope', '$location', 'AuthService',
        function ($scope, $location, AuthService) {
            AuthService.user.$promise.then(function () {
                if (AuthService.user.memberships.length) {
                    $location.path('/group/' + AuthService.user.memberships[0].groupId);
                } else {
                    $location.path('/group/add');
                }
            });
        }
    ]);
