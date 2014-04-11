'use strict';

angular.module('roundzeroApp')
    .controller('AddGroupCtrl', ['$scope', '$location', 'AuthService', 'GroupService', 'MembershipService',
        function ($scope, $location, AuthService, GroupService, MembershipService) {
            $scope.submitted = false;
            $scope.loading = false;
            $scope.error = null;

            $scope.group = new GroupService();
            $scope.user = AuthService.user;

            $scope.hideError = function () {
                $scope.error = null;
            };

            $scope.submit = function () {
                $scope.submitted = true;
                $scope.error = null;
                if (!$scope.form.$invalid) {
                    $scope.loading = true;
                    $scope.group.$save(
                        function success(response) {
                            $scope.loading = false;
                            $scope.error = null;
                            // Add member that created the group.
                            var membership = new MembershipService();
                            membership.groupId = response.id;
                            membership.userId = AuthService.userId;

                            membership.$save(
                                function success(response) {
                                    AuthService.user.memberships.push(response);
                                    $location.path('/group/' + response.groupId + '/members');
                                },
                                function error(response) {
                                    $scope.loading = false;

                                    if (response.error) {
                                        $scope.error = response.error;
                                    } else {
                                        $scope.error = 'There was an error logging in. Please try later.';
                                    }
                                }
                            );
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
