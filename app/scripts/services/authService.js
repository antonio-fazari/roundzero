'use strict';

angular.module('roundzeroApp')
  .factory('AuthService', function() {
    var currentUser;

    return {
      login: function() { /* TODO */ },
      logout: function() { /* TODO */ },
      isLoggedIn: function() { /* TODO */ return true; },
      currentUser: function() { return currentUser; }
    };
  });
