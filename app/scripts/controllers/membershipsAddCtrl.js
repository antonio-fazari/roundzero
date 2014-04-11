'use strict';

angular.module('roundzeroApp')
    .controller('MembershipsAddCtrl', function ($scope, $routeParams, $location, MembershipService, UserService) {
        $scope.submitted = false;
        $scope.loading = false;
        $scope.error = null;

        $scope.user = null;

        $scope.membership = new MembershipService();
        $scope.membership.groupId = $routeParams.groupId;

        $scope.getSuggestions = function (text) {
            if (text) {
                return UserService.getSuggestions({
                    partial: text,
                    groupId: $routeParams.groupId
                }).$promise.then(function(res) {
                    return res;
                });
            }
        };

        $scope.hideError = function () {
            $scope.error = null;
        };

        $scope.submit = function () {
            $scope.submitted = true;
            $scope.error = null;
            if (!$scope.form.$invalid) {
                $scope.loading = true;
                $scope.membership.userId = $scope.user.id;
                $scope.membership.user = $scope.user;

                $scope.membership.$save(
                    function success(response) {
                        response.user = $scope.user;
                        $scope.$parent.group.memberships.push(response);

                        $scope.user = null;
                        $scope.loading = false;
                        $scope.error = null;
                        $scope.submitted = false;
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
    });
