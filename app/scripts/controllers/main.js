'use strict';

angular.module('roundzeroApp')
  .controller('MainCtrl', function ($scope) {
    // Testing.
    $scope.testPeople = [
      {name: 'Fred', id: 1, val: -2},
      {name: 'Tony', id: 2, val: 2},
      {name: 'Graham', id: 3, val: 1}
    ];

    // Messing around on add-group page.
    $scope.newPeople = [];
    var id = 1;
    $scope.addPerson = function (formNewPerson) {
      if (formNewPerson) {
        $scope.newPeople.push({
          name: formNewPerson,
          id: id
        });
        id++;
      }
    };

  });
