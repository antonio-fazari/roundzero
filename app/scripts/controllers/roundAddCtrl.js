'use strict';

angular.module('roundzeroApp')
    .controller('RoundAddCtrl', function ($scope, $controller, $routeParams, $location, $q, GroupService, RoundService, OrderService, AuthService) {
        $controller('FormCtrl', {$scope: $scope});

        $scope.user = AuthService.user;
        $scope.group = GroupService.get({id: $routeParams.groupId});
        $scope.orders = [];

        $scope.group.$promise.then(function (res) {
            var memberships = res.memberships;
            for (var i = 0; i < memberships.length; i++) {
                if (memberships[i].lastOrder) {
                    // Use last order details.
                    $scope.orders.push({
                        active: false,
                        user: memberships[i].user,
                        type: memberships[i].lastOrder.type,
                        milk: memberships[i].lastOrder.milk,
                        sugars: memberships[i].lastOrder.sugars,
                        notes: memberships[i].lastOrder.notes
                    });
                } else {
                    // Default order.
                    $scope.orders.push({
                        active: false,
                        user: memberships[i].user,
                        type: 'Tea',
                        milk: 0,
                        sugars: 0,
                        notes: ''
                    });
                }
            }
        });

        $scope.round = new RoundService({
            groupId: $routeParams.groupId,
            userId: AuthService.userId
        });

        $scope.submit = function () {
            $scope.setStateSubmitted();

            if (!$scope.form.$invalid) {
                $scope.setStateLoading();

                $scope.round.$save(
                    function success(response) {
                        var promises = [];

                        angular.forEach($scope.orders, function (item) {
                            if (item.active) {
                                var order = new OrderService(item);
                                order.roundId = response.id;
                                order.userId = item.user.id;

                                promises.push(order.$save());
                            }
                        });

                        $q.all(promises).then(function () {
                            $scope.setStateSuccess();

                            $location.path('/group/' + $routeParams.groupId);
                        });
                    },
                    $scope.setStateError
                );
            }
        };
    });
