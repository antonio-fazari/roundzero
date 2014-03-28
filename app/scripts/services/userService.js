'use strict';

angular.module('roundzeroApp')
    .factory('UserService', ['$resource',
        function($resource){
            return $resource('http://api.roundzeroapp.com/v1/users/:id', {id:'@id'});
        }
    ]);
