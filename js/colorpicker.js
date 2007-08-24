var numen = new Array("0","1","2","3","4","5","6","7","8","9","a","b","c","d","e","f");

var ColorPicker = {
	Create : function () {
		var table = document.createElement( 'table' );
		table.border="0";
		table.cellpadding="0";
		table.cellspacing="0";
		for( var y=0;y<40;++y ) {
			var tr = document.createElement( 'tr' ); 
			for ( var i=0;i<600;i+=10 ) {
				var td = document.createElement( 'td' );
				td.style.backgroundColor = "#00ff00";
				
				var img = document.createElement( 'img' );
				img.src = "http://webringworld.org/pics/blank.gif";
				img.height="2";
				img.width="2";
				
				td.appendChild( img );
				tr.appendChild( td );
			}
			table.appendChild( tr );
		}
		return table;
	}
};
