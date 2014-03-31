'use strict';

angular.module('roundzeroApp')
    .service('AuthService', ['$cookieStore', 'TokenHandler',
        function($cookieStore, TokenHandler) {
            var token = $cookieStore.get('token') || null;

            this.loggedIn = false;
            this.userId = null;

            this.login = function (token) {
                this.loggedIn = true;
                this.userId = token.userId;
                TokenHandler.set(token.id);

                $cookieStore.put('token', token);
            };
            this.logout = function () {
                this.loggedIn = false;
                this.userId = null;
                TokenHandler.set(null);

                $cookieStore.remove('token');
            };

            if (token) {
                this.login(token);
            }
        }
    ]);
