'use strict';

angular.module('roundzeroApp', [
    'ngCookies',
    'ngResource',
    'ngSanitize',
    'ngRoute',
    'ui.bootstrap',
    'angularLocalStorage',
    'mgcrea.ngStrap',
    'mgcrea.ngStrap.tooltip',
    'mgcrea.ngStrap.helpers.parseOptions'
])
.config(function ($routeProvider) {
    $routeProvider
        .when('/', {
            templateUrl: 'views/main.html',
            controller: 'MainCtrl'
        })
        .when('/sign-in', {
            templateUrl: 'views/sign-in.html'
        })
        .when('/account', {
            templateUrl: 'views/account.html'
        })
        .when('/group/add', {
            templateUrl: 'views/add-group.html',
            controller: 'AddGroupCtrl'
        })
        .when('/group/:groupId', {
            templateUrl: 'views/group.html',
            controller: 'GroupCtrl'
        })
        .when('/group/:groupId/members', {
            templateUrl: 'views/group-members.html',
            controller: 'GroupMembersCtrl'
        })
        .when('/group/:groupId/round', {
            templateUrl: 'views/add-round.html',
            controller: 'AddRoundCtrl'
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
