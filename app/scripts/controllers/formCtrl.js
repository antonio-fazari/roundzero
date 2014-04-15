'use strict';

angular.module('roundzeroApp')
    .controller('FormCtrl', function ($scope) {
        $scope.submitted = false;
        $scope.loading = false;
        $scope.error = null;

        $scope.hideError = function () {
            $scope.error = null;
        };

        $scope.setStateSubmitted = function () {
            $scope.submitted = true;
            $scope.error = null;
        };

        $scope.setStateLoading = function () {
            $scope.loading = true;
        };

        $scope.setStateSuccess = function () {
            $scope.submitted = false;
            $scope.loading = false;
            $scope.error = null;
        };

        $scope.setStateError = function (response) {
            $scope.loading = false;
            if (response.error) {
                $scope.error = response.error;
            } else {
                $scope.error = 'There was an error processing your request. Please try later.';
            }
        };
    });
