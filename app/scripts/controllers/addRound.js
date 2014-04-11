'use strict';

angular.module('roundzeroApp')
    .controller('AddRoundCtrl', ['$scope', '$routeParams', '$location', '$q', 'GroupService', 'RoundService', 'OrderService', 'AuthService',
        function ($scope, $routeParams, $location, $q, GroupService, RoundService, OrderService, AuthService) {
            $scope.submitted = false;
            $scope.loading = false;
            $scope.error = null;

            $scope.group = GroupService.get({id: $routeParams.groupId});
            $scope.orders = [];
            $scope.group.$promise.then(function (res) {
                var memberships = res.memberships;
                for (var i = 0; i < memberships.length; i++) {
                    $scope.orders.push({
                        active: false,
                        user: memberships[i].user,
                        type: 'Tea',
                        milk: 0,
                        sugars: 0,
                        notes: ''
                    });
                }
            });

            $scope.round = new RoundService();
            $scope.round.groupId = $routeParams.groupId;
            $scope.round.userId = AuthService.userId;

            console.log($scope.round);

            $scope.hideError = function () {
                $scope.error = null;
            };

            $scope.submit = function () {
                $scope.submitted = true;
                $scope.error = null;
                if (!$scope.form.$invalid) {
                    $scope.loading = true;

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
                                $scope.loading = false;
                                $scope.error = null;
                                $scope.submitted = false;

                                $location.path('/group/' + $routeParams.groupId);
                            });
                        },
                        function error(response) {
                            $scope.loading = false;

                            if (response.error) {
                                $scope.error = response.error;
                            } else {
                                $scope.error = 'There was an error creating your round. Please try later.';
                            }
                        });
                }
            };
        }
    ]);
