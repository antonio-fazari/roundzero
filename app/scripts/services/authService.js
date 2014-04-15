'use strict';

angular.module('roundzeroApp')
    .service('AuthService', function($location, storage, TokenHandler, UserService) {
            var token = storage.get('token') || null;

            this.loggedIn = false;
            this.userId = null;
            this.user = null;

            this.login = function (token, remember) {
                this.loggedIn = true;
                this.userId = token.userId;

                TokenHandler.set(token.id);

                // Get user from fresh token.
                if (token.user) {
                    this.user = new UserService(token.user);
                } else {
                    this.user = UserService.get({id: this.userId});
                }

                if (remember) {
                    storage.set('token', {
                        id: token.id,
                        userId: token.userId
                    });
                }
            };
            this.logout = function () {
                this.loggedIn = false;
                this.userId = null;
                this.user = null;
                TokenHandler.set(null);

                storage.remove('token');
            };

            if (token) {
                this.login(token);
            }
        }
    );
