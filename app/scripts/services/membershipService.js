'use strict';

angular.module('roundzeroApp')
    .factory('MembershipService', function($resource, TokenHandler){
            var resource = $resource('http://api.roundzeroapp.com/v1/memberships/:id', {
                id:'@id'
            });

            resource = TokenHandler.wrapActions(resource, ['get', 'save', 'update', 'delete']);

            return resource;
        }
    );
