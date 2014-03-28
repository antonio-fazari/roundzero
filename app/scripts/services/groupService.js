'use strict';

angular.module('roundzeroApp')
    .factory('GroupService', ['$resource',
        function($resource){
            return $resource('http://api.roundzeroapp.com/v1/groups/:id', {id:'@id'});
        }
    ]);
