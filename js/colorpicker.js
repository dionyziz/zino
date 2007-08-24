var ColorPicker = {
	Create : function ( clickaria, titlos, dr, dg, db ) {
		var table = document.createElement( 'table' );
		table.border="0";
		table.cellpadding="0";
		table.cellspacing="0";
		table.style.borderCollapse = "collapse";
		table.style.margin = "auto";
        table.style.cursor = "pointer";
		var caption = document.createElement( 'caption' );
		caption.appendChild( document.createTextNode( titlos ) );
		table.appendChild( caption );
        caption.style.margin = "10px auto 10px auto";
		var r,g,b;
		r=g=b=0;
		for( var y=0;y<27;++y ) {
			var tr = document.createElement( 'tr' ); 
			for ( var i=0;i<60;++i ) {
				var td = document.createElement( 'td' );
				td.style.backgroundColor = "rgb(" + r + "," + g + "," + b + ")";
				td.onmouseover= (function (r, g, b, preview) {
								return function () {
									ColorPicker.Preview( ColorPicker.fix( r ), ColorPicker.fix( g ), ColorPicker.fix( b ), "preview" );
								}
							})(r,g,b,"preview");
				td.onmouseout = function () {
							document.body.style.cursor = "default";
						};
				td.onclick = (function ( r, g, b ) {
								return function () {
									clickaria( ColorPicker.fix( r ), ColorPicker.fix( g ), ColorPicker.fix( b ) );
									Modals.Destroy();
								}
							})(r,g,b);
				td.style.height="4px";
				td.style.width="4px";

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
		dived.id = "preview";
		dived.style.width = "240px";
		dived.style.height = "30px";
		dived.style.margin = "6px auto 0 auto";
		if( dr && dg && db ) {
			dived.style.backgroundColor = "rgb(" + dr + "," + dg + "," + db + ")";
		}
		
		var div = document.createElement( 'div' );
		div.appendChild( table );
		div.appendChild( dived );
		
		Modals.Create( div, 400, 190 );
	},
	Preview : function( r, g, b, id ) {
		var ted = document.getElementById( id );
		ted.style.backgroundColor = "rgb(" + r + "," + g + "," + b + ")";
	},
	fix : function( num ) {
		return ( num > 255 )?255:num;
	}
};

