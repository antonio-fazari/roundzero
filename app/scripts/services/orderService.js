'use strict';

angular.module('roundzeroApp')
    .factory('OrderService', ['$resource',
        function($resource){
            return $resource('http://api.roundzeroapp.com/v1/rounds/:roundId/orders/:id', {
                id:'@id',
                roundId:'@roundId',
            });
        }
    ]);
