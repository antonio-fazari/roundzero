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
        controller: 'SignInCtrl'
      })
      .when('/register', {
        templateUrl: 'views/register.html',
        controller: 'RegisterCtrl'
      })
      .when('/account', {
        templateUrl: 'views/account.html',
        controller: 'AccountCtrl'
      })
      .when('/add-group', {
        templateUrl: 'views/add-group.html',
        controller: 'MainCtrl'
      })
      .when('/group', {
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
