'use strict';

angular.module('roundzeroApp', [
  'ngCookies',
  'ngResource',
  'ngSanitize',
  'ngRoute'
])
  .config(function ($routeProvider) {
    $routeProvider
      .when('/', {
        templateUrl: 'views/main.html',
        controller: 'MainCtrl'
      })
      .when('/sign-in', {
        templateUrl: 'views/sign-in.html',
        controller: 'MainCtrl'
      })
      .when('/register', {
        templateUrl: 'views/register.html',
        controller: 'MainCtrl'
      })
      .when('/account', {
        templateUrl: 'views/account.html',
        controller: 'MainCtrl'
      })
      .when('/profile/edit', {
        templateUrl: 'views/edit-profile.html',
        controller: 'MainCtrl'
      })
      .when('/group/add', {
        templateUrl: 'views/add-group.html',
        controller: 'MainCtrl'
      })
      .when('/group/:groupId', {
        templateUrl: 'views/group.html',
        controller: 'MainCtrl'
      })
      .when('/round', {
        templateUrl: 'views/round.html',
        controller: 'MainCtrl'
      })
      .when('/order', {
        templateUrl: 'views/order.html',
        controller: 'MainCtrl'
      })
      .otherwise({
        redirectTo: '/'
      });
  });
