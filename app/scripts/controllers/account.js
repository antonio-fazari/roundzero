'use strict';

angular.module('roundzeroApp')
    .controller('AccountCtrl', function ($scope, $rootScope) {
        $scope.user = $rootScope.user;
    });
