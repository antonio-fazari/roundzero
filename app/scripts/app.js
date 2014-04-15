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
            templateUrl: 'views/account.html',
            controller: 'GroupAddCtrl'
        })
        .when('/forgot-password', {
            templateUrl: 'views/forgotPassword.html',
            controller: 'ForgotPasswordCtrl'
        })
        .when('/reset-password', {
            templateUrl: 'views/resetPassword.html',
            controller: 'ResetPasswordCtrl'
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
        .when('/group/:groupId/delete', {
            templateUrl: 'views/groupDelete.html',
            controller: 'GroupDeleteCtrl'
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
.run(function($rootScope, $location, AuthService, TokenService) {
        // register listener to watch route changes
        $rootScope.$on('$routeChangeStart', function(event, next) {
            if (!AuthService.loggedIn) {
                if ($location.search().token) {
                    TokenService.get({id: $location.search().token},
                        function success(response) {
                            AuthService.login(response, false);
                        },
                        function error() {
                            $location.path('/sign-in');
                        }
                    );
                } else {
                    // No logged user, we should be going to #login
                    if (next.templateUrl !== 'views/signIn.html' &&
                        next.templateUrl !== 'views/forgotPassword.html') {
                        $location.path('/sign-in');
                    }
                }
            }
        });
    });
