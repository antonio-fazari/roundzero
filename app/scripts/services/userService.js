'use strict';

angular.module('roundzeroApp')
    .factory('UserService', ['$resource', 'TokenHandler',
        function($resource, TokenHandler){
            var resource = $resource('http://api.roundzeroapp.com/v1/users/:id', {
                id:'@id'
            }, {
                update: {method: 'PUT'}
            });

            resource = TokenHandler.wrapActions(resource, ['get', 'save', 'update', 'delete']);

            return resource;
        }
    ]);
