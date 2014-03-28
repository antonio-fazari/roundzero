'use strict';

angular.module('roundzeroApp')
    .controller('AccountCtrl', ['$scope', '$rootScope',
        function ($scope, $rootScope) {
            $scope.user = $rootScope.user;
        }
    ]);
