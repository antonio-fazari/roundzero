'use strict';

angular.module('roundzeroApp')
    .controller('RegisterCtrl', function ($scope, $rootScope, $resource) {
        var User = $resource('http://api.roundzeroapp.com/v1/users/:id', {id:'@id'});

        $rootScope.user = new User();
        $scope.user = $rootScope.user;

        $scope.register = function () {
            $scope.user.$save();
        };
    });
