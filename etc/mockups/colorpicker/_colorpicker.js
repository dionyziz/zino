var Colorpicker = {
	prevcol: null,
	been: false,
	func: new Function(),
	Create : function( clickaria, closing, titlos ) {
		Colorpicker.func = clickaria;
		
		var div = document.createElement( 'div' );
		
		var close = document.createElement( 'a' );
		close.onclick = function() {
					closing();
					Modals.Destroy();
				};
		close.style.cssFloat = "right";
		close.style.marginRight = "30px";
		close.onmouseover = function() {
						document.body.style.cursor = "pointer";
					};
		close.onmouseout = function () {
						document.body.style.cursor = "default";
					};
		
		var closeimg = document.createElement( 'img' );
		closeimg.src = "close.png";
		closeimg.alt = "Κλείσιμο";
		closeimg.title = "Κλείσιμο";
		
		var tablef = document.createElement( 'table' );
		Colorpicker.parseTable( tablef );
		tablef.id = "table";
		tablef.style.cssFloat = "right";
		tablef.style.marginRight = "80px";
		tablef.style.marginTop = "27px";
		
		var tables = document.createElement( 'table' );
		Colorpicker.parseTable( tables );
		tables.id = "table_main";
		tables.style.cssFloat = "left";
		tables.style.marginLeft = "90px";
		tables.style.marginTop = "30px";
		
/*		var caption = document.createElement( 'caption' );
		caption.appendChild( document.createTextNode( titlos ) );
		caption.style.margin = "10px auto 10px auto";*/
		
		var preview = document.createElement( 'div' );
		preview.id = "preview";
		preview.style.width = "240px";
		preview.style.height = "30px";
		preview.style.cssFloat = "left";
		preview.style.marginLeft = "100px";
		preview.style.marginTop = "180px";
		
		close.appendChild( closeimg );
		div.appendChild( document.createElement( 'br' ) );
		div.appendChild( close );
		div.appendChild( tablef );
//		tables.appendChild( caption );
		div.appendChild( tables );
		div.appendChild( document.createElement( 'br' ) );
		div.appendChild( preview );
		
		Modals.Create( div, 500, 300 );
		Colorpicker.createRainbow();
	},
	parseTable : function( table ) {
		table.border="0";
		table.cellpadding="0";
		table.cellspacing="0";
		table.style.borderCollapse = "collapse";
//		table.style.margin = "auto";
		table.style.cursor = "pointer";
//		table.style.position = "absolute";
	},
	createRainbow : function() {
		var table = document.getElementById( 'table' );
		var tbody = document.createElement( 'tbody' );
		var s,v;
		s=v=1;
		for(var h=0;h<360;h+=4 ) {
			var tr = document.createElement( 'tr' );
			var color = Colorpicker.hsv2rgb( h, s, v );
			tr.appendChild( Colorpicker.createRainbowColor( color.r, color.g, color.b, h ) );
			tbody.appendChild( tr );
		}
		table.appendChild( tbody );
	},
	makeBigPreview : function( h ) {
		var table = document.getElementById( "table_main" );
		if( table.childNodes.length != 0 ) {
			table.removeChild( table.firstChild );
		}
		var tbody = document.createElement( 'tbody' );
		for( var v=255;v>=0;v-=5 ) {
			var tr = document.createElement( 'tr' );
			for( var s=0;s<=255;s+=5 ) {
				var color = Colorpicker.hsv2rgb( h, s/255, v/255 );
				tr.appendChild( Colorpicker.createPreviewColor( color.r,color.g,color.b ) );
			}
			tbody.appendChild( tr );
		}
		table.appendChild( tbody );
	},
	
	/*
	The following function maps a color that belongs to the HSV color space to the according one in the RGB color space.
	HSV stands for Hue,Saturation,Value.
	RGB stands for Red,Green,Blue.
	The variables Hd,f,p,q,t are necessary for the calculation of the equivalent RGB color and the equations were taken
	from http://en.wikipedia.org/wiki/HSV_color_space#From_HSV_to_RGB.

	The domain of:
		h is [0,360)
		s,v,r,g,b is [0,1]
		
	Notice the domain of r,g,b values!In computer technology the domain of r,g,b is [0,255].
	Let y be a real number in [0,1] and x be an integer in [0,255].
	The following ratio appears:

		 1     255
		--- = -----
		 y      x
		 
	<=>  x = 255*y
	Since x belongs to Z, x = parseInt( 255*y );
	We don't care if y=0,coz that means that x would be 0
	*/
	
	hsv2rgb : function( h, s, v ) {
		var Hd = Math.floor( h/60 )%6;
		var f = h/60 - Hd;
		var p = v*(1-s);
		var q = v*(1-f*s);
		var t = v*(1-(1-f)*s);
		var r,g,b;
		switch( Hd ) {
			case 0:
				r = v;
				g = t;
				b = p;
				break;
			case 1:
				r = q;
				g = v;
				b = p;
				break;
			case 2:
				r = p;
				g = v;
				b = t;
				break;
			case 3:
				r = p;
				g = q;
				b = v;
				break;
			case 4:
				r = t;
				g = p;
				b = v;
				break;
			case 5:
				r = v;
				g = p;
				b = q;
				break;
			default:
				alert( "Something's wrong" );
		}
		var color = new Array();
		color.r=parseInt(r*255);
		color.g=parseInt(g*255);
		color.b=parseInt(b*255);
		return color;
	},
	Preview : function( r,g,b ) {
		var ted = document.getElementById( "preview" );
		ted.style.backgroundColor = "rgb(" + r + "," + g + "," + b + ")";
	},
	createRainbowColor : function( red, green, blue, h ) {
		var td = document.createElement( 'td' );
		td.style.backgroundColor = "rgb(" + red + "," + green + "," + blue + ")";
		td.style.height="1px";
		td.style.width="10px";
		td.onclick = (function( h,td ) {
				return function () {
					document.getElementById( "preview" ).style.marginTop = "10px";
					Colorpicker.makeBorder( td );
					Colorpicker.makeBigPreview( h );
				}
			})( h, td );
		td.onmousemove= (function (r, g, b ) {
				return function () {
					Colorpicker.Preview( r,g,b );
				}
			})(red,green,blue);
		td.onmouseout = function () {
				document.body.style.cursor = "default";
			};
		return td;
	},
	createPreviewColor : function( red,green,blue ) {
		var td = document.createElement( 'td' );
		td.style.backgroundColor = "rgb(" + red + "," + green + "," + blue + ")";
		td.style.height="3px";
		td.style.width="3px";
		td.onmousemove= (function (r, g, b ) {
				return function () {
					Colorpicker.Preview( r,g,b );
				}
			})(red,green,blue);
		td.onmouseout = function () {
				document.body.style.cursor = "default";
			};
		td.onclick = (function ( r, g, b ) {
				return function () {
					Colorpicker.func( r, g, b );
				}
			})(red, green, blue );
		return td;
	},
	makeBorder : function( td ) {
		if( Colorpicker.been ) {
			var prev = document.getElementById( "selected" );
			prev.style.backgroundColor = Colorpicker.prevCol;
			prev.id = '';
		}
		Colorpicker.been = true;
		td.id = "selected";
		Colorpicker.prevCol = td.style.backgroundColor;
		td.style.backgroundColor = "#000000";
	}
};
