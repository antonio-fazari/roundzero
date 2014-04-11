'use strict';

angular.module('roundzeroApp')
    .directive('groupTypeahead', function($window, $parse, $q, $typeahead, $parseOptions) {
        var defaults = $typeahead.defaults;
        return {
            restrict: 'EAC',
            require: 'ngModel',
            link: function postLink(scope, element, attr, controller) {
                // Directive options
                var options = {
                    scope: scope,
                    controller: controller
                };
                angular.forEach([
                    'placement',
                    'container',
                    'delay',
                    'trigger',
                    'keyboard',
                    'html',
                    'animation',
                    'template',
                    'filter',
                    'limit',
                    'minLength'
                ], function(key) {
                    if (angular.isDefined(attr[key])) {
                        options[key] = attr[key];
                    }
                });
                // Build proper ngOptions
                var filter = options.filter || defaults.filter;
                var limit = options.limit || defaults.limit;
                var ngOptions = attr.ngOptions;
                if (filter) {
                    ngOptions += ' | ' + filter + ':$viewValue';
                }
                if (limit) {
                    ngOptions += ' | limitTo:' + limit;
                }
                var parsedOptions = $parseOptions(ngOptions);

                // Initialize typeahead
                var typeahead = $typeahead(element, options);
                // if(!dump) var dump = console.error.bind(console);
                // Watch model for changes
                scope.$watch(attr.ngModel, function(newValue) {
                    scope.$modelValue = newValue;
                    //Set model value on the scope to custom templates can use it.
                    parsedOptions.valuesFn(scope, controller).then(function(values) {
                        if (values.length > limit) {
                            values = values.slice(0, limit);
                        }
                        // if(matches.length === 1 && matches[0].value === newValue) return;
                        typeahead.update(values);
                        // Queue a new rendering that will leverage collection loading
                        controller.$render();
                    });
                });
                // Model rendering in view
                controller.$render = function() {
                    // console.warn('$render', element.attr('ng-model'), 'controller.$modelValue', typeof controller.$modelValue, controller.$modelValue, 'controller.$viewValue', typeof controller.$viewValue, controller.$viewValue);
                    if (controller.$isEmpty(controller.$viewValue)) {
                        return element.val('');
                    }
                    var index = typeahead.$getIndex(controller.$modelValue);
                    var selected = angular.isDefined(index) ? typeahead.$scope.$matches[index].label : controller.$viewValue;
                    if (selected.name) {
                        element.val(selected.name.replace(/<(?:.|\n)*?>/gm, '').trim());
                    } else {
                        element.val(selected.replace(/<(?:.|\n)*?>/gm, '').trim());
                    }
                };
                // Garbage collection
                scope.$on('$destroy', function() {
                    typeahead.destroy();
                    options = null;
                    typeahead = null;
                });
            }
        };
    });
