'use strict';

TSManager.controller('ListCtrl', function ($scope, Tasks) {

	$scope.todos =  Tasks.query();

	$scope.submitForm = function (data) {

		Tasks.update({id: data.id},data);
		console.log(data);
	}
});