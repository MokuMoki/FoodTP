// https://codepen.io/mrtial/pen/OXXmPx //

var app = angular.module('typeSet', []);

app.controller("typeSetControllerLoc", function($scope, $interval) {
    window.scope = $scope
    $scope.view = {};
    $scope.word = {};
    $scope.word.locations = ['APU', 'APIIT', 'TPM', '"On Campus"', 'Vista', 'Endah', 'Parkhill', '"South City"'];

    var idx = 0;
    var n = 0;
    var up = true;

    $interval(function() {
        var word = $scope.word.locations[idx];
        var ln = word.length + 7;

        if (up) {
            $scope.view.type = word.slice(0, n);
            n++
        };
        if (n === ln + 1) { up = false };
        if (!up) {
            $scope.view.type = word.slice(0, n);
            n--
        }
        if (n === 0) {
            up = true
            idx++
        }
        if (idx === $scope.word.locations.length) { idx = 0 }

    }, 100);

})