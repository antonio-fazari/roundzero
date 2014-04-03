'use strict';

angular.module('roundzeroApp', [
    'ngCookies',
    'ngResource',
    'ngSanitize',
    'ngRoute',
    'ui.bootstrap',
    'angularLocalStorage',
    'ui.autocomplete'
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
            templateUrl: 'views/account.html'
        })
        .when('/groups', {
            templateUrl: 'views/group.html',
            controller: 'MainCtrl'
        })
        .when('/group/add', {
            templateUrl: 'views/add-group.html',
            controller: 'AddGroupCtrl'
        })
        .when('/group/:groupId', {
            templateUrl: 'views/group.html',
            controller: 'GroupCtrl'
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
.run(['$rootScope', '$location', 'AuthService',
    function($rootScope, $location, AuthService) {
        // register listener to watch route changes
        $rootScope.$on('$routeChangeStart', function(event, next) {
            if (!AuthService.loggedIn) {
                // no logged user, we should be going to #login
                if (next.templateUrl !== 'partials/sign-in.html') {
                    $location.path('/sign-in');
                }
            }
        });
    }]);

