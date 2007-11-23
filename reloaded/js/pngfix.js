var arVersion = navigator.appVersion.split( "MSIE" );
var version = parseFloat( arVersion[ 1 ] );

if ( version >= 5.5 && document.body && document.body.filters && version < 7.0 ) {
	for ( var i = 0; i < document.images.length; ++i ) {
		var img = document.images[i];

		if ( img.src.substring( img.src.length - 3, img.src.length ).toUpperCase() == "PNG" ) {
			if ( img.width > 0 && img.height > 0 ) {
				var imgID = img.id ? "id='" + img.id + "' " : "";
				var imgClass = img.className ? "class='" + img.className + "' " : "";
				var imgTitle = img.title ? "title='" + img.title + "' " : "title='" + img.alt + "' ";
				var imgStyle = "display:inline-block;" + img.style.cssText;
				if ( img.align == "left" ) {
					imgStyle = "float:left;" + imgStyle;
				}
				if ( img.align == "right" ) {
					imgStyle = "float:right;" + imgStyle;
				}
				if ( img.parentElement.href ) {
					imgStyle = "cursor:hand;" + imgStyle;
				}
				var strNewHTML = "<span " + imgID + imgClass + imgTitle
				+ " style=\"" + "width:" + img.width + "px; height:" + img.height + "px;" + imgStyle + ";"
				+ "filter:progid:DXImageTransform.Microsoft.AlphaImageLoader"
				+ "(src=\'" + img.src + "\', sizingMethod='scale');\"></span>"; 
				img.outerHTML = strNewHTML;
				--i;
			}
		}
	}
}
