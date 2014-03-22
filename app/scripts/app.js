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
      .when('/add-group', {
        templateUrl: 'views/add-group.html',
        controller: 'MainCtrl'
      })
      .when('/add-people', {
        templateUrl: 'views/add-people.html',
        controller: 'MainCtrl'
      })
      .when('/group', {
        templateUrl: 'views/group.html',
        controller: 'MainCtrl'
      })
      .otherwise({
        redirectTo: '/'
      });
  });
