'use strict';

angular.module('roundzeroApp')
    .factory('OrderService', ['$resource', 'TokenHandler',
        function($resource, TokenHandler){
            var resource = $resource('http://api.roundzeroapp.com/v1/rounds/:roundId/orders/:id', {
                id:'@id',
                roundId:'@roundId',
            }, {
                update: {method: 'PUT'}
            });

            resource = TokenHandler.wrapActions(resource, ['query', 'get', 'save', 'update', 'delete']);

            return resource;
        }
    ]);
