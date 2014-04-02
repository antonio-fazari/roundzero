'use strict';

angular.module('roundzeroApp')
    .factory('GroupService', ['$resource', 'TokenHandler',
        function($resource, TokenHandler){
            var resource = $resource('http://api.roundzeroapp.com/v1/groups/:id', {
                id:'@id'
            }, {
                update: {method: 'PUT'}
            });

            resource = TokenHandler.wrapActions(resource, ['query', 'get', 'save', 'update', 'delete']);

            return resource;
        }
    ]);
