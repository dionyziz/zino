var ColorPicker = {
	Create : function () {
		var table = document.createElement( 'table' );
		table.border="0";
		table.cellpadding="0";
		table.cellspacing="0";
		var k,l,m;
		k=l=m=0;
		for( var y=0;y<40;++y ) {
			var tr = document.createElement( 'tr' ); 
			for ( var i=0;i<60;++i ) {
				var td = document.createElement( 'td' );
				td.style.backgroundColor = "rgb(" + k + "," + l + "," + m + ")";
				
				var img = document.createElement( 'img' );
				img.src = "http://webringworld.org/pics/blank.gif";
				img.height="2";
				img.width="2";
				
				td.appendChild( img );
				tr.appendChild( td );
				
				if( m < 255 ) {
					m+=26;
				}
				else if( l < 255 ) {
					l+=25;
					m=0;
				}
				else if( k < 255 ) {
					k+=24;
					l=m=0;
				}
			}
			table.appendChild( tr );
		}
		return table;
	},
};
