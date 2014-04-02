'use strict';

angular.module('roundzeroApp')
    .controller('AccountCtrl', ['$scope', 'AuthService', 'UserService',
        function ($scope, AuthService, UserService) {
            $scope.user = UserService.get({id: AuthService.userId});
        }
    ]);