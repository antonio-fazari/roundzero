'use strict';

angular.module('roundzeroApp', [
    'ngCookies',
    'ngResource',
    'ngSanitize',
    'ngRoute',
    'angularLocalStorage',
    'mgcrea.ngStrap'
])
.config(function ($routeProvider) {
    $routeProvider
        .when('/', {
            templateUrl: 'views/main.html',
            controller: 'MainCtrl'
        })
        .when('/sign-in', {
            templateUrl: 'views/signIn.html'
        })
        .when('/account', {
            templateUrl: 'views/account.html'
        })
        .when('/group/add', {
            templateUrl: 'views/groupAdd.html',
            controller: 'GroupAddCtrl'
        })
        .when('/group/:groupId', {
            templateUrl: 'views/group.html',
            controller: 'GroupCtrl'
        })
        .when('/group/:groupId/edit', {
            templateUrl: 'views/groupEdit.html',
            controller: 'GroupEditCtrl'
        })
        .when('/group/:groupId/members', {
            templateUrl: 'views/memberships.html',
            controller: 'MembershipsCtrl'
        })
        .when('/group/:groupId/round', {
            templateUrl: 'views/roundAdd.html',
            controller: 'RoundAddCtrl'
        })
        .otherwise({
            redirectTo: '/'
        });
})
.run(function($rootScope, $location, AuthService) {
        // register listener to watch route changes
        $rootScope.$on('$routeChangeStart', function(event, next) {
            if (!AuthService.loggedIn) {
                // no logged user, we should be going to #login
                if (next.templateUrl !== 'partials/sign-in.html') {
                    $location.path('/sign-in');
                }
            }
        });
    });
