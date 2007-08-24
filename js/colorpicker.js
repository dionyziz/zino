var ColorPicker = {
	Create : function ( clickaria ) {
		var table = document.createElement( 'table' );
		table.border="0";
		table.cellpadding="0";
		table.cellspacing="0";
		var r,g,b;
		r=g=b=0;
		for( var y=0;y<27;++y ) {
			var tr = document.createElement( 'tr' ); 
			for ( var i=0;i<60;++i ) {
				var td = document.createElement( 'td' );
				td.style.backgroundColor = "rgb(" + r + "," + g + "," + b + ")";
				td.onmouseover= (function (r, g, b, preview) {
								return function () {
									ColorPicker.Preview( r, g, b, "preview" );
								}
							})(r,g,b,"preview");
				td.onclick = (function ( r, g, b ) {
								return function () {
									clickaria( r, g, b );
								}
							})(r,g,b);
				
				var img = document.createElement( 'img' );
				img.src = "http://webringworld.org/pics/blank.gif";
				img.height="2";
				img.width="2";
				
				td.appendChild( img );
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
		var tabled = document.createElement( 'table' );
		var trd = document.createElement( 'tr' );
		var tdd = document.createElement( 'td' );
		tdd.id = "preview";
		var imgd = document.createElement( 'img' );
		imgd.width = "355";
		imgd.height = "30";
		imgd.src = "http://webringworld.org/pics/blank.gif";
		tdd.appendChild(imgd);
		trd.appendChild(tdd);
		tabled.appendChild(trd);
		
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
