'use strict';

angular.module('roundzeroApp')
    .factory('TokenService', ['$resource',
        function($resource){
            return $resource('http://api.roundzeroapp.com/v1/tokens/:id', {id:'@id'});
        }
    ]);
