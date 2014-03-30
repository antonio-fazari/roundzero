'use strict';

angular.module('roundzeroApp', [
    'ngCookies',
    'ngResource',
    'ngSanitize',
    'ngRoute',
    'ui.bootstrap'
])
.config(function ($routeProvider) {
    $routeProvider
        .when('/', {
            templateUrl: 'views/main.html'
        })
        .when('/sign-in', {
            templateUrl: 'views/sign-in.html'
        })
        .when('/account', {
            templateUrl: 'views/account.html',
            controller: 'AccountCtrl'
        })
        .when('/groups', {
            templateUrl: 'views/group.html',
            controller: 'MainCtrl'
        })
        .when('/group/:groupId', {
            templateUrl: 'views/group.html',
            controller: 'MainCtrl'
        })
        .when('/group/add', {
            templateUrl: 'views/add-group.html',
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
})
.run(function($rootScope, $location) {
    // register listener to watch route changes
    $rootScope.$on('$routeChangeStart', function(event, next) {
        if (!$rootScope.user) {
            // no logged user, we should be going to #login
            if (next.templateUrl !== 'partials/sign-in.html') {
                $location.path('/sign-in');
            }
        }
    });
});
