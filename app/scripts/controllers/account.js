'use strict';

angular.module('roundzeroApp')
    .controller('AccountCtrl', function ($scope, $rootScope, $resource) {
        $scope.user = $rootScope.user;
    });
