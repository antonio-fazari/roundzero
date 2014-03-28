'use strict';

angular.module('roundzeroApp')
  .factory('GroupService', ['$resource',
    function($resource){
      return $resource('examples/:groupId.json', {}, {
        query: {
          method: 'GET',
          params: { groupId:'groups' },
          isArray: true
        }
      });
    }
  ]);
