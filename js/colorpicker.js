var ColorPicker = {
	Create : function ( clickaria ) {
		var table = document.createElement( 'table' );
		table.border="0";
		table.cellpadding="0";
		table.cellspacing="0";
		table.style.borderCollapse = "collapse";
		var caption = document.createElement( 'caption' );
		caption.appendChild( document.createTextNode( "Επέλεξε ένα χρώμα" ) );
		table.appendChild( caption );
		var r,g,b;
		r=g=b=0;
		for( var y=0;y<27;++y ) {
			var tr = document.createElement( 'tr' ); 
			for ( var i=0;i<60;++i ) {
				var td = document.createElement( 'td' );
				td.style.backgroundColor = "rgb(" + r + "," + g + "," + b + ")";
				td.onmouseover= (function (r, g, b, preview) {
								return function () {
									document.body.style.cursor = "pointer";
									ColorPicker.Preview( r, g, b, "preview" );
								}
							})(r,g,b,"preview");
				td.onmouseout = function () {
							document.body.style.cursor = "default";
						};
				td.onclick = (function ( r, g, b ) {
								return function () {
									clickaria( r, g, b );
									Modals.Destroy();
								}
							})(r,g,b);
				td.style.height = "2px";
				td.style.width = "2px";
				tr.appendChild( td );
				
				if( b < 255 ) {
					b+=26;
				}
				else if( g < 255 ) {
					g+=25;
					b=0;
				}
				else if( r < 255 ) {
					r+=24;
					b=g=0;
				}
			}
			table.appendChild( tr );
		}
		var dived = document.createElement( 'div' );
		dived.style.width = "234px";
		dived.style.height = "30px";
		dived.id = "preview";
		
		var div = document.createElement( 'div' );
		div.appendChild( table );
		div.appendChild( tabled );
		
		return div;
	},
	Preview : function( r, g, b, id ) {
		var ted = document.getElementById( id );
		ted.style.backgroundColor = "rgb(" + r + "," + g + "," + b + ")";
	}
};
