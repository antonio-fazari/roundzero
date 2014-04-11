'use strict';

angular.module('roundzeroApp')
    .controller('MembershipsAddCtrl', function ($scope, $controller, $routeParams, $location, MembershipService, UserService) {
        $controller('FormCtrl', {$scope: $scope});
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

        $scope.submit = function () {
            $scope.setStateSubmitted();

            if (!$scope.form.$invalid) {
                $scope.setStateLoading();

                $scope.membership.userId = $scope.user.id;
                $scope.membership.user = $scope.user;

                $scope.membership.$save(
                    function success(response) {
                        $scope.setStateSuccess();

                        response.user = $scope.user;
                        $scope.$parent.group.memberships.push(response);
                        $scope.user = null;
                    },
                    $scope.setStateError
                );
            }
        };
    });
