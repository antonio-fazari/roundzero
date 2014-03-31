'use strict';

angular.module('roundzeroApp')
    .service('AuthService', ['storage', 'TokenHandler',
        function(storage, TokenHandler) {
            var token = storage.get('token') || null;

            this.loggedIn = false;
            this.userId = null;

            this.login = function (token, remember) {
                this.loggedIn = true;
                this.userId = token.userId;
                TokenHandler.set(token.id);

                if (remember) {
                    storage.set('token', token);
                }
            };
            this.logout = function () {
                this.loggedIn = false;
                this.userId = null;
                TokenHandler.set(null);

                storage.remove('token');
            };

            if (token) {
                this.login(token);
            }
        }
    ]);
