'use strict';

angular.module('roundzeroApp')
    .controller('RegisterCtrl', ['$scope', '$rootScope', '$location', 'UserService',
        function ($scope, $rootScope, $location, UserService) {

            $rootScope.user = new UserService();
            $scope.user = $rootScope.user;

            $scope.register = function () {
                $scope.user.$save();
                $location.path('/account');
            };
        }
    ]);
