
;(function(){

// Cache Library //
// Designed to have a similar syntax as the the PHP caching library //

var ttlPrefix = "!!#";
var dataPrefix = "!!$";
	
// The cache uses Session Storage (free'd after closing browser) //
var storage = window.sessionStorage;
var canWrite = false;

if ( storage ) {
	// Confirm that writing works (i.e. will fail on Safari in Incogneto). //
	// That said, we assume if there are ANY keys, then writing is allowed //
	if ( storage.length > 0 ) {
		canWrite = true;
	}
	else {
		// If there are no keys, we write something to find out if it works //
		try {
			storage.setItem( "!", "" );
			storage.removeItem( "!" );
			canWrite = true;
		}
		catch (e) {
		}
	}
	
	window.cache_Exists = function( key ) {
		var ttl = storage.getItem( ttlPrefix+key );
	
		// Does it exist? //
		if ( ttl == null ) {
			return false;
		}
		// Has the TTL expired? //
		else if ( Date.now() > ttl ) {
			// Remove in reverse order, just in case //
			storage.removeItem( dataPrefix+key );
			storage.removeItem( ttlPrefix+key );
			// removeItem does nothing on failure //
			return false;
		}
		
		return true;
	}
	
	window.cache_Fetch = function( key ) {
		if ( !cache_Exists(key) )
			return null;
	
		// Return Data //
		return storage.getItem( dataPrefix+key );
	}
		
	// Remove expired items. 
	// Optionally, specify the maximum number of items to remove.
	//
	// NOTE: Due to differences in browser implementations, this is not guaranteed
	//   to remove all expired items. But if there is one, it will remove it.
	//   Think of this as a single pass cache flush that *may* catch everything.
	window.cache_Flush = function( max_items ) {
		var itemsRemoved = 0;
		// Reverse order, just in case keys are like an array, so removing
		// elements off the end wont give us bad indexes
		for ( var idx = storage.length; idx--; ) {
			var key = storage.key(idx);
			// For this, we only care about items with the ttlPrefix
			if ( key.indexOf(ttlPrefix) === 0 ) {
				key = key.substr(ttlPrefix.length);
				if ( Date.now() > storage.getItem(ttlPrefix+key) ) {
					// Remove in reverse order, just in case
					storage.removeItem( dataPrefix+key );
					storage.removeItem( ttlPrefix+key );
					itemsRemoved++;
					
					// CLEVERNESS: If omitted, max_items is undefined, and
					//   itemsRemoved will always be 1+.
					if ( itemsRemoved == max_items ) {
						return itemsRemoved;
					}
				}
			}
		}
		return itemsRemoved;
	}
}
else /* storage */ {
	window.cache_Exists = function() { return false; }
	window.cache_Fetch = function() { return null; }
	window.cache_Flush = function() {}
} /* storage */

if ( canWrite ) {
	window.cache_Store = function( key, value, ttl ) {
		// Store TTL first, then Data //
		try {
			if ( ttl )
				storage.setItem( ttlPrefix+key, Date.now()+ttl );
			else
				storage.setItem( ttlPrefix+key, Number.MAX_VALUE );
			storage.setItem( dataPrefix+key, value );
		}
		catch (e) {
			// Flush and try again //
			cache_Flush();
			try {
				if ( ttl )
					storage.setItem( ttlPrefix+key, Date.now()+ttl );
				else
					storage.setItem( ttlPrefix+key, Number.MAX_VALUE );
				storage.setItem( dataPrefix+key, value );
			}
			catch (e2) {
				// Cleanup - Only ttlPrefix should exist, but just in case //
				storage.removeItem( dataPrefix+key );
				storage.removeItem( ttlPrefix+key );
				
				// Failure //
				return false;
			}
		}
	
		// Success //	
		return true;
	}

	window.cache_Create = function( key, value, ttl ) {
		if ( cache_Exists(key) )
			return false;
		
		return cache_Store( key, value, ttl );
	}

	window.cache_Touch = function( key, new_ttl ) {
		if ( !cache_Exists(key) )
			return false;
	
		// Update the TTL //
		try {
			if ( new_ttl )
				storage.setItem( ttlPrefix+key, Date.now()+new_ttl );
			else
				storage.setItem( ttlPrefix+key, Number.MAX_VALUE );
		}
		catch (e) {
			// Flush and try again //
			cache_Flush();
			try {
				if ( new_ttl )
					storage.setItem( ttlPrefix+key, Date.now()+new_ttl );
				else
					storage.setItem( ttlPrefix+key, Number.MAX_VALUE );
			}
			catch (e2) {
				// Failure //
				return false;
			}
		}
	
		// Success //
		return true;
	}
}
else /* canwrite */ {
	window.cache_Store = function() { return false; }
	window.cache_Create = function() { return false; }
	window.cache_Touch = function() { return false; }
} /* canwrite */

})();
