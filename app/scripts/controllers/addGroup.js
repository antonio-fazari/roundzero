'use strict';

angular.module('roundzeroApp')
    .controller('AddGroupCtrl', ['$scope', '$rootScope', '$location', 'GroupService',
        function ($scope, $rootScope, $location, GroupService) {
            $scope.submitted = false;
            $scope.loading = false;
            $scope.error = null;

            $scope.group = new GroupService();

            $scope.hideError = function () {
                $scope.error = null;
            };

            $scope.submit = function () {
                $scope.submitted = true;
                $scope.error = null;
                if (!$scope.form.$invalid) {
                    $scope.loading = true;
                    $scope.group.$save(
                        function success() {
                            $scope.loading = false;
                            $scope.error = null;

                            $location.path('/account');
                        },
                        function error(response) {
                            $scope.loading = false;

                            if (response.error) {
                                $scope.error = response.error;
                            } else {
                                $scope.error = 'There was an error creating your group. Please try later.';
                            }
                        });
                }
            };
        }
    ]);
