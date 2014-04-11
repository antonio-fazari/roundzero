'use strict';

angular.module('roundzeroApp')
    .controller('SignInCtrl', function ($scope, $controller, $http, $location, AuthService) {
        $controller('FormCtrl', {$scope: $scope});

        $scope.login = {
            email: '',
            password: '',
            remember: true
        };

        $scope.signIn = function () {
            $scope.setStateSubmitted();

            if (!$scope.form.$invalid) {
                $scope.setStateLoading();

                $http.post('http://api.roundzeroapp.com/v1/tokens/authenticate', $scope.login)
                .success(function(response) {
                    $scope.setStateSuccess();

                    AuthService.login(response, $scope.login.remember);

                    if (AuthService.user.memberships.length) {
                        $location.path('/group/' + AuthService.user.memberships[0].groupId);
                    } else {
                        $location.path('/group/add');
                    }
                })
                .error($scope.setStateError);
            }
        };
    });
