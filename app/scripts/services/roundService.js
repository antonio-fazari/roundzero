'use strict';

angular.module('roundzeroApp')
    .factory('RoundService', function($resource, TokenHandler){
            var resource = $resource('http://api.roundzeroapp.com/v1/rounds/:id', {
                id:'@id'
            });

            resource = TokenHandler.wrapActions(resource, ['get', 'save', 'delete']);

            return resource;
        }
    );
