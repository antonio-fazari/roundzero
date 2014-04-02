'use strict';
 
/**
 * ui-ladda
 * 
 * To use, simply use normal Ladda classes and attributes and pass a model to ui-ladda. You can
 * use truthy or falsey values, or pass decimals from 0 to 1 to show the progress bar animation.
 * 
 * 
 * @param uiLadda (mixed): Sets wether or not to show the ladda loading
 *      truthy - show loading animation
 *      falsey (non-zero) - stop loading animation
 *      number (decimal from 0 to 1) - show loading and progress animation
 * @example:
 *      <button ui-ladda="loading" class="ladda-button" data-style="expand-right" ng-click="loading=true">
 *          <span class="ladda-label">Submit</span>
 *      </button>
 */
angular.module('roundzeroApp')
    .directive('uiLadda', [function () {
        return {
            link: function postLink(scope, element, attrs) {
                var ladda = Ladda.create(element[0]);
                scope.$watch(attrs.uiLadda, function(newVal, oldVal){
                    if (angular.isNumber(oldVal)) {
                        if (angular.isNumber(newVal)) {
                            ladda.setProgress(newVal);
                        } else {
                            newVal && ladda.setProgress(0) || ladda.stop();
                        }
                    } else {
                        newVal && ladda.start() || ladda.stop();
                    }
                });
            }
        };
    }]);