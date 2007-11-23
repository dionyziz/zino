function Graph( id ) {
	this.id = id;
	
	this.graphsFolder = "graphs"
	
	switch ( this.id ) {
		case "newcom":
			this.size = 0;
			this.smooth = 3;
			break;
		case "newmem":
			this.size = 0;
			this.smooth = 0;
			break;
		case "pageviews":
			this.size = 2;
			this.smooth = 0;
			break;
	}
	
	this.maxSize = 2;
	this.minSize = 0;
	
	this.maxSmooth = 5;
	this.minSmooth = 0;
	
	this.adjust = function ( graph ) {
		adjuster = g( 'adjust_' + this.id );
		switch( adjuster.style.display ) {
			case "":
			case "block":
				adjuster.style.display = "none";
				break;
			case "none":
				adjuster.style.display = "block";
				break;
		}
	}
	
	this.sizeText = function() {
		switch( this.size ) {
			case 0:
				return "small";
				break;
			case 1:
				return "normal";
				break;
			case 2:
				return "big";
				break;
		}
	}
	
	this.resize = function ( action ) {
		if ( action == "smaller" ) {
			if ( this.size > this.minSize )
				--this.size;
		}
		if ( action == "bigger" ) {
			if ( this.size < this.maxSize )
				++this.size;
		}
		
		element = g( this.id + "_sz" );
		while( element.firstChild ) {
			element.removeChild( element.firstChild );
		}
		sizedisplay = d.createTextNode( this.sizeText() );
		element.appendChild( sizedisplay );
		this.display();
	}
	
	this.display = function () {
		graphimg = g( 'graph_' + this.id );
		parent = graphimg.parentNode;
		parent.removeChild( graphimg );
		
		graphimg = d.createElement( 'img' );
		src = this.graphsFolder;
		switch ( this.id ) {
			case "newcom":
				src += "/newcomments.php";
				graphimg.style.marginLeft = "3px";
				break;
			case "newmem":
				src += "/newmembers.php";
				break;
			case "pageviews":
				src += "/activity.php";
				break;
		}
		src += "?size=" + this.size;
		graphimg.src = src;
		
		parent.appendChild( graphimg );
	}
}

NewCGraph = new Graph( "newcom" );
NewMGraph = new Graph( "newmem" );
PagGraph = new Graph( "pageviews" );