var Places = {
	onedit : false,
	showLinks : function( id ) {
		if ( !Places.onedit ) {
			g( 'peditlink_' + id ).style.display = 'inline';
			g( 'pdeletelink_' + id ).style.display = 'inline';
		}
	}
	,hideLinks : function( id ) {
		g( 'peditlink_' + id ).style.display = 'none';
		g( 'pdeletelink_' + id ).style.display = 'none';
	}
	,deletep : function( id ) {
		if ( confirm( 'Θέλεις σίγουρα να διαγράψεις τη συγκεκριμένη τοποθεσία;' ) ) { 
			var element = g( 'place_' + id );
			while( element.firstChild ) {
				element.removeChild( element.firstChild );
			}
			element.appendChild( d.createElement( 'Διαγραφή...' ) );
			Coala.Warm( 'place/delete' , {'placeid':id} ); 
		}
	}
	,edit : function( id ) {
		if( Places.onedit ) {
			return;
		}
		
		var place = g( 'praw_' + id ).innerHTML;
		
		var pform = d.createElement( 'form' );
		pform.id = 'editpform';
		pform.name = 'editp';
		pform.action = 'do/place/new';
		pform.method = 'post';
						
		var pid = d.createElement( 'input' );
		pid.type = 'hidden';
		pid.name = 'eid';
		pid.value = id;
		
		var pinput = d.createElement( 'input' );
		pinput.size = '100';
		pinput.type = 'text';
		pinput.name = 'name';
		pinput.value = place;
		pinput.className = 'bigtext';
		
		var psubmit = d.createElement( 'input' );
		psubmit.type = 'submit';
		psubmit.value = 'Επεξεργασία';
		
		var pcancel = d.createElement( 'input' );
		pcancel.type = 'button';
		pcancel.value = 'Ακύρωση';
		pcancel.onclick = ( function( id ) { 
            return function() { 
                Places.cancelEdit( id );
            };
        } )( id );
		
		pform.appendChild( pid );
		pform.appendChild( pinput );
		pform.appendChild( d.createTextNode( ' ' ) );
		pform.appendChild( psubmit );
		pform.appendChild( d.createTextNode( ' ' ) );
		pform.appendChild( pcancel );
		pform.appendChild( d.createElement( 'br' ) );
		
		g( 'place_' + id ).style.display = 'none';
		g( 'place_' + id ).parentNode.insertBefore( pform, g( 'place_' + id ).nextSibling );
		
		Places.onedit = true;
	}
	,cancelEdit : function( id ) {
		g( 'place_' + id ).parentNode.removeChild( g( 'editpform' ) );
		g( 'place_' + id ).style.display = '';
		
		Places.onedit = false;
	}
	,create : function() {
		g( 'newp' ).style.display = 'none';
		g( 'newpform' ).style.display = 'block';
	}
	,cancelCreate : function() {
		g( 'newp' ).style.display = '';
		g( 'newpform' ).style.display = 'none';
	}
};
