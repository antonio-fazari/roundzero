'use strict';

angular.module('roundzeroApp')
    .controller('ForgotPasswordCtrl', function ($scope, $controller, $http) {
        $controller('FormCtrl', {$scope: $scope});

        $scope.email = null;
        $scope.success = false;

        $scope.sendEmail = function () {
            $scope.setStateSubmitted();
            $scope.success = false;

            if (!$scope.form.$invalid) {
                $scope.setStateLoading();

                $http.post('http://api.roundzeroapp.com/v1/reset-password', {email: $scope.email})
                .success(function() {
                    $scope.setStateSuccess();
                    $scope.email = null;
                    $scope.success = true;
                })
                .error($scope.setStateError);
            }
        };
    });
