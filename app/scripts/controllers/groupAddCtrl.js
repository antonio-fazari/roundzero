'use strict';

angular.module('roundzeroApp')
    .controller('GroupAddCtrl', function ($scope, $controller, $location, AuthService, GroupService, MembershipService) {
        $controller('FormCtrl', {$scope: $scope});
        $scope.group = new GroupService();
        $scope.user = AuthService.user;

        $scope.submit = function () {
            $scope.setStateSubmitted();

            if (!$scope.form.$invalid) {
                $scope.setStateLoading();

                $scope.group.$save(
                    function success(response) {
                        // Add member that created the group.
                        var membership = new MembershipService({
                            groupId: response.id,
                            userId: AuthService.userId
                        });

                        membership.$save(
                            function success(response) {
                                $scope.setStateSuccess();
                                AuthService.user.memberships.push(response);
                                $location.path('/group/' + response.groupId + '/members');
                            },
                            $scope.setStateError
                        );
                    },
                    $scope.setStateError
                );
            }
        };
    });
