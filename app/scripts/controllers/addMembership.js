'use strict';

angular.module('roundzeroApp')
    .controller('AddMembershipCtrl', ['$scope', '$routeParams', '$location', 'MembershipService', 'UserService',
        function ($scope, $routeParams, $location, MembershipService, UserService) {
            $scope.submitted = false;
            $scope.loading = false;
            $scope.error = null;

            $scope.membership = new MembershipService();
            $scope.membership.groupId = $routeParams.groupId;

            $scope.hideError = function () {
                $scope.error = null;
            };

            $scope.submit = function () {
                $scope.submitted = true;
                $scope.error = null;
                if (!$scope.form.$invalid) {
                    $scope.loading = true;
                    var userId = 1; // TODO: Change to api call.
                    $scope.membership.userId = userId;

                    $scope.membership.$save(
                        function success(response) {
                            $scope.loading = false;
                            $scope.error = null;
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
