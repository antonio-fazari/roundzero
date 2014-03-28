'use strict';

angular.module('roundzeroApp')
    .factory('RoundService', ['$resource',
        function($resource){
            return $resource('http://api.roundzeroapp.com/v1/rounds/:id', {id:'@id'});
        }
    ]);
