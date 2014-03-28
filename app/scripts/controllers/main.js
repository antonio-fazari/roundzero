'use strict';

angular.module('roundzeroApp')
    .controller('MainCtrl', ['$scope', '$rootScope',
        function ($scope, $rootScope) {
            $scope.user = $rootScope.user;
        }
    ]);
