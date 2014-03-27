'use strict';

angular.module('roundzeroApp')
  .controller('AccountCtrl', ['$scope', 'GroupService',
    function($scope, GroupService) {
      $scope.groups = GroupService.query();
    }
  ]);
