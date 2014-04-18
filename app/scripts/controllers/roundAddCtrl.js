'use strict';

angular.module('roundzeroApp')
    .controller('RoundAddCtrl', function ($scope, $controller, $routeParams, $location, $q, GroupService, RoundService, OrderService, AuthService) {
        $controller('FormCtrl', {$scope: $scope});

        var previousOrders = {};

        $scope.user = AuthService.user;
        $scope.group = GroupService.get({id: $routeParams.groupId});
        $scope.orders = [];

        $scope.changeType = function (order) {
            angular.forEach(previousOrders[order.user.id], function (previousOrder) {
                if (previousOrder.type === order.type) {
                    order.milk = previousOrder.milk;
                    order.sugars = previousOrder.sugars;
                    order.notes = previousOrder.notes;
                }
            });
        };

        $scope.group.$promise.then(function (res) {
            angular.forEach(res.memberships, function (membership) {
                previousOrders[membership.user.id] = membership.lastOrders;
                if (membership.lastOrders.length) {
                    // Use last order details.
                    $scope.orders.push({
                        active: false,
                        user: membership.user,
                        type: membership.lastOrders[0].type,
                        milk: membership.lastOrders[0].milk,
                        sugars: membership.lastOrders[0].sugars,
                        notes: membership.lastOrders[0].notes
                    });
                } else {
                    // Default order.
                    $scope.orders.push({
                        active: false,
                        user: membership.user,
                        type: 'Tea',
                        milk: 0,
                        sugars: 0,
                        notes: ''
                    });
                }
            });
        });

        $scope.round = new RoundService({
            groupId: $routeParams.groupId,
            userId: AuthService.userId
        });

        $scope.typeOptions = [
            {key: 'Tea', value:'Tea'},
            {key: 'Coffee', value:'Coffee'},
        ];
        $scope.milkOptions = [
            {key: '0', value:'No milk'},
            {key: '1', value:'Some milk'},
            {key: '2', value:'Lots of milk'},
        ];
        $scope.sugarOptions = [
            {key: '0', value:'No sugar'},
            {key: '1', value:'1 sugar'},
            {key: '2', value:'2 sugars'},
            {key: '3', value:'3 sugars'},
        ];

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
