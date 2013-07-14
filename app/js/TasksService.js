'use strict';

TSManager.factory('Tasks', function($resource) {

	// Need to add the fucking PUT method to $resource
	// DAFUQ ANCULAR ?
    return $resource(
    			'/tasks/:id', 
    			{ id: '@id' },
    			{ "update": {method:"PUT"}}
    		);

});