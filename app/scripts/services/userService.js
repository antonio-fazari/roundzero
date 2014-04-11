'use strict';

angular.module('roundzeroApp')
    .factory('UserService', function($resource, TokenHandler){
            var resource = $resource('http://api.roundzeroapp.com/v1/users/:id', {
                id:'@id'
            }, {
                update: {method: 'PUT'},
                getSuggestions: {
                    url: 'http://api.roundzeroapp.com/v1/users/suggestions/:partial',
                    method: 'GET',
                    isArray: true
                },
            });

            resource = TokenHandler.wrapActions(resource, ['get', 'getSuggestions', 'save', 'update', 'delete']);

            return resource;
        }
    );
