package gr.zino.lib {
	import flash.net.Socket;
	import flash.net.FileReference;
	import flash.utils.ByteArray;
	import flash.events.Event;
	
	public class PostRequest  {
		private var url:String;
		private var port:int;
		private var socket:Socket;
		private var filelist:Array;
		private var headers:Array;
		private var variables:Array;
		
		public function PostRequest( url:String = "", port:int = 80){
			this.url = url;
			this.port = port;
			filelist = new Array();
			headers = new Array();
			variables = new Array();
			SetHeader( "Content-Type", "multipart/form-data; boundary=-----------------------------153501500631101" );
		}
		
		public function SetHeader( name:String, value:String ){
			for( var i = 0; i < this.headers.length; i++ ){
				if( this.headers[ i ][ 0 ] == name ){
					this.headers[ i ][ 1 ] = value;
					return;
				}
			}
			this.headers.push( new Array( name, value ) );
		}
		
		public function SetVariable( name:String, value:String ){
			for( var i = 0; i < this.variables.length; i++ ){
				if( this.variables[ i ][ 0 ] == name ){
					this.variables[ i ][ 1 ] = value;
					return;
				}
			}
			this.variables.push( new Array( name, value ) );
		}
		
		public function SetFile( file:FileReference ){
			this.filelist.push( file );
		}
		
		public function Send(){
			var host = url.split( "/" )[ 2 ];
			this.socket = new Socket( host, port );
			this.socket.addEventListener( Event.CONNECT, this.onConnect );
		}
		
		private function onConnect( event ){
			this.SendHeaders();
			this.SendBody();
			//this.socket.close();
		}
		
		private function SendHeaders(){
			this.socket.writeMultiByte( "POST " + this.url + " HTTP/1.1\n", "utf-8" );
			for( var i = 0; i < this.headers.length; i++ ){
				this.socket.writeMultiByte( this.headers[ i ][ 0 ] + ": " + this.headers[ i ][ 1 ] + "\n", "utf-8" );
			}
		}
		
		private function SendBody(){
			for( var i = 0; i < this.variables.length; i++ ){
				this.socket.writeMultiByte( "\n-----------------------------153501500631101\nContent-Disposition: form-data; name=\"" + this.variables[ i ][ 0 ] + "\"\n\n", "utf-8" );
				this.socket.writeMultiByte( this.variables[ i ][ 1 ], "utf-8" );
			}
			for( i = 0; i < this.filelist.length; i++ ){
				this.socket.writeMultiByte( "\n-----------------------------153501500631101\nContent-Disposition: form-data;  name=\"uploadimage\" filename=\"" + this.filelist[ i ].name + "\"\nContent-Type: " + getMimeTypeFromExtension( this.filelist[ i ].type ) + "\n\n", "utf-8" );
				this.socket.writeBytes( this.filelist[ i ].data );
			}
			this.socket.writeMultiByte( "\n-----------------------------153501500631101", "utf-8" );
			//this.socket.flush();
		}
		public static function getMimeTypeFromExtension( ext:String ):String {
			var mimeType:String = "";
			ext = ext.split( "." )[ 1 ];
			switch( ext.toLocaleLowerCase() ) {
				case "stl":
						mimeType = "application/SLA";
				break;
				case "stp":
						mimeType = "application/STEP";
				break;
				case "step":
						mimeType = "application/STEP";
				break;
				case "dwg":
						mimeType = "application/acad";
				break;
				case "ez":
						mimeType = "application/andrew-inset";
				break;
				case "ccad":
						mimeType = "application/clariscad";
				break;
				case "drw":
						mimeType = "application/drafting";
				break;
				case "tsp":
						mimeType = "application/dsptype";
				break;
				case "dxf":
						mimeType = "application/dxf";
				break;
				case "xls":
						mimeType = "application/excel";
				break;
				case "unv":
						mimeType = "application/i-deas";
				break;
				case "jar":
						mimeType = "application/java-archive";
				break;
				case "hqx":
						mimeType = "application/mac-binhex40";
				break;
				case "cpt":
						mimeType = "application/mac-compactpro";
				break;
				case "pot":
						mimeType = "application/vnd.ms-powerpoint";
				break;
				case "pps":
						mimeType = "application/vnd.ms-powerpoint";
				break;
				case "ppt":
						mimeType = "application/vnd.ms-powerpoint";
				break;
				case "ppz":
						mimeType = "application/vnd.ms-powerpoint";
				break;
				case "doc":
						mimeType = "application/msword";
				break;
				case "bin":
						mimeType = "application/octet-stream";
				break;
				case "class":
						mimeType = "application/octet-stream";
				break;
				case "dms":
						mimeType = "application/octet-stream";
				break;
				case "exe":
						mimeType = "application/octet-stream";
				break;
				case "lha":
						mimeType = "application/octet-stream";
				break;
				case "lzh":
						mimeType = "application/octet-stream";
				break;
				case "oda":
						mimeType = "application/oda";
				break;
				case "ogg":
						mimeType = "application/ogg";
				break;
				case "ogm":
						mimeType = "application/ogg";
				break;
				case "pdf":
						mimeType = "application/pdf";
				break;
				case "pgp":
						mimeType = "application/pgp";
				break;
				case "ai":
						mimeType = "application/postscript";
				break;
				case "eps":
						mimeType = "application/postscript";
				break;
				case "ps":
						mimeType = "application/postscript";
				break;
				case "prt":
						mimeType = "application/pro_eng";
				break;
				case "rtf":
						mimeType = "application/rtf";
				break;
				case "set":
						mimeType = "application/set";
				break;
				case "smi":
						mimeType = "application/smil";
				break;
				case "smil":
						mimeType = "application/smil";
				break;
				case "sol":
						mimeType = "application/solids";
				break;
				case "vda":
						mimeType = "application/vda";
				break;
				case "mif":
						mimeType = "application/vnd.mif";
				break;
				case "xlc":
						mimeType = "application/vnd.ms-excel";
				break;
				case "xll":
						mimeType = "application/vnd.ms-excel";
				break;
				case "xlm":
						mimeType = "application/vnd.ms-excel";
				break;
				case "xls":
						mimeType = "application/vnd.ms-excel";
				break;
				case "xlw":
						mimeType = "application/vnd.ms-excel";
				break;
				case "cod":
						mimeType = "application/vnd.rim.cod";
				break;
				case "arj":
						mimeType = "application/x-arj-compressed";
				break;
				case "bcpio":
						mimeType = "application/x-bcpio";
				break;
				case "vcd":
						mimeType = "application/x-cdlink";
				break;
				case "pgn":
						mimeType = "application/x-chess-pgn";
				break;
				case "cpio":
						mimeType = "application/x-cpio";
				break;
				case "csh":
						mimeType = "application/x-csh";
				break;
				case "deb":
						mimeType = "application/x-debian-package";
				break;
				case "dcr":
						mimeType = "application/x-director";
				break;
				case "dir":
						mimeType = "application/x-director";
				break;
				case "dxr":
						mimeType = "application/x-director";
				break;
				case "dvi":
						mimeType = "application/x-dvi";
				break;
				case "pre":
						mimeType = "application/x-freelance";
				break;
				case "spl":
						mimeType = "application/x-futuresplash";
				break;
				case "gtar":
						mimeType = "application/x-gtar";
				break;
				case "gz":
						mimeType = "application/x-gzip";
				break;
				case "hdf":
						mimeType = "application/x-hdf";
				break;
				case "ipx":
						mimeType = "application/x-ipix";
				break;
				case "ips":
						mimeType = "application/x-ipscript";
				break;
				case "js":
						mimeType = "application/x-javascript";
				break;
				case "skd":
						mimeType = "application/x-koan";
				break;
				case "skm":
						mimeType = "application/x-koan";
				break;
				case "skp":
						mimeType = "application/x-koan";
				break;
				case "skt":
						mimeType = "application/x-koan";
				break;
				case "latex":
						mimeType = "application/x-latex";
				break;
				case "lsp":
						mimeType = "application/x-lisp";
				break;
				case "scm":
						mimeType = "application/x-lotusscreencam";
				break;
				case "mif":
						mimeType = "application/x-mif";
				break;
				case "bat":
						mimeType = "application/x-msdos-program";
				break;
				case "com":
						mimeType = "application/x-msdos-program";
				break;
				case "exe":
						mimeType = "application/x-msdos-program";
				break;
				case "cdf":
						mimeType = "application/x-netcdf";
				break;
				case "nc":
						mimeType = "application/x-netcdf";
				break;
				case "pl":
						mimeType = "application/x-perl";
				break;
				case "pm":
						mimeType = "application/x-perl";
				break;
				case "rar":
						mimeType = "application/x-rar-compressed";
				break;
				case "sh":
						mimeType = "application/x-sh";
				break;
				case "shar":
						mimeType = "application/x-shar";
				break;
				case "swf":
						mimeType = "application/x-shockwave-flash";
				break;
				case "sit":
						mimeType = "application/x-stuffit";
				break;
				case "sv4cpio":
						mimeType = "application/x-sv4cpio";
				break;
				case "sv4crc":
						mimeType = "application/x-sv4crc";
				break;
				case "tar.gz":
						mimeType = "application/x-tar-gz";
				break;
				case "tgz":
						mimeType = "application/x-tar-gz";
				break;
				case "tar":
						mimeType = "application/x-tar";
				break;
				case "tcl":
						mimeType = "application/x-tcl";
				break;
				case "tex":
						mimeType = "application/x-tex";
				break;
				case "texi":
						mimeType = "application/x-texinfo";
				break;
				case "texinfo":
						mimeType = "application/x-texinfo";
				break;
				case "man":
						mimeType = "application/x-troff-man";
				break;
				case "me":
						mimeType = "application/x-troff-me";
				break;
				case "ms":
						mimeType = "application/x-troff-ms";
				break;
				case "roff":
						mimeType = "application/x-troff";
				break;
				case "t":
						mimeType = "application/x-troff";
				break;
				case "tr":
						mimeType = "application/x-troff";
				break;
				case "ustar":
						mimeType = "application/x-ustar";
				break;
				case "src":
						mimeType = "application/x-wais-source";
				break;
				case "zip":
						mimeType = "application/x-zip-compressed";
				break;
				case "zip":
						mimeType = "application/zip";
				break;
				case "tsi":
						mimeType = "audio/TSP-audio";
				break;
				case "au":
						mimeType = "audio/basic";
				break;
				case "snd":
						mimeType = "audio/basic";
				break;
				case "kar":
						mimeType = "audio/midi";
				break;
				case "mid":
						mimeType = "audio/midi";
				break;
				case "midi":
						mimeType = "audio/midi";
				break;
				case "mp2":
						mimeType = "audio/mpeg";
				break;
				case "mp3":
						mimeType = "audio/mpeg";
				break;
				case "mpga":
						mimeType = "audio/mpeg";
				break;
				case "au":
						mimeType = "audio/ulaw";
				break;
				case "aif":
						mimeType = "audio/x-aiff";
				break;
				case "aifc":
						mimeType = "audio/x-aiff";
				break;
				case "aiff":
						mimeType = "audio/x-aiff";
				break;
				case "m3u":
						mimeType = "audio/x-mpegurl";
				break;
				case "wax":
						mimeType = "audio/x-ms-wax";
				break;
				case "wma":
						mimeType = "audio/x-ms-wma";
				break;
				case "rpm":
						mimeType = "audio/x-pn-realaudio-plugin";
				break;
				case "ram":
						mimeType = "audio/x-pn-realaudio";
				break;
				case "rm":
						mimeType = "audio/x-pn-realaudio";
				break;
				case "ra":
						mimeType = "audio/x-realaudio";
				break;
				case "wav":
						mimeType = "audio/x-wav";
				break;
				case "pdb":
						mimeType = "chemical/x-pdb";
				break;
				case "xyz":
						mimeType = "chemical/x-pdb";
				break;
				case "ras":
						mimeType = "image/cmu-raster";
				break;
				case "gif":
						mimeType = "image/gif";
				break;
				case "ief":
						mimeType = "image/ief";
				break;
				case "jpe":
						mimeType = "image/jpeg";
				break;
				case "jpeg":
						mimeType = "image/jpeg";
				break;
				case "jpg":
						mimeType = "image/jpeg";
				break;
				case "png":
						mimeType = "image/png";
				break;
				case "tif":
						mimeType = "image/tiff";
				break;
				case "tiff":
						mimeType = "image/tiff";
				break;
				case "ras":
						mimeType = "image/x-cmu-raster";
				break;
				case "pnm":
						mimeType = "image/x-portable-anymap";
				break;
				case "pbm":
						mimeType = "image/x-portable-bitmap";
				break;
				case "pgm":
						mimeType = "image/x-portable-graymap";
				break;
				case "ppm":
						mimeType = "image/x-portable-pixmap";
				break;
				case "rgb":
						mimeType = "image/x-rgb";
				break;
				case "xbm":
						mimeType = "image/x-xbitmap";
				break;
				case "xpm":
						mimeType = "image/x-xpixmap";
				break;
				case "xwd":
						mimeType = "image/x-xwindowdump";
				break;
				case "iges":
						mimeType = "model/iges";
				break;
				case "igs":
						mimeType = "model/iges";
				break;
				case "mesh":
						mimeType = "model/mesh";
				break;
				case "":
						mimeType = "";
				break;
				case "msh":
						mimeType = "model/mesh";
				break;
				case "silo":
						mimeType = "model/mesh";
				break;
				case "vrml":
						mimeType = "model/vrml";
				break;
				case "wrl":
						mimeType = "model/vrml";
				break;
				case "css":
						mimeType = "text/css";
				break;
				case "htm":
						mimeType = "text/html";
				break;
				case "html":
						mimeType = "text/html";
				break;
				case "asc":
						mimeType = "text/plain";
				break;
				case "c":
						mimeType = "text/plain";
				break;
				case "cc":
						mimeType = "text/plain";
				break;
				case "f90":
						mimeType = "text/plain";
				break;
				case "f":
						mimeType = "text/plain";
				break;
				case "h":
						mimeType = "text/plain";
				break;
				case "hh":
						mimeType = "text/plain";
				break;
				case "m":
						mimeType = "text/plain";
				break;
				case "txt":
						mimeType = "text/plain";
				break;
				case "rtx":
						mimeType = "text/richtext";
				break;
				case "rtf":
						mimeType = "text/rtf";
				break;
				case "sgm":
						mimeType = "text/sgml";
				break;
				case "sgml":
						mimeType = "text/sgml";
				break;
				case "tsv":
						mimeType = "text/tab-separated-values";
				break;
				case "jad":
						mimeType = "text/vnd.sun.j2me.app-descriptor";
				break;
				case "etx":
						mimeType = "text/x-setext";
				break;
				case "xml":
						mimeType = "text/xml";
				break;
				case "dl":
						mimeType = "video/dl";
				break;
				case "fli":
						mimeType = "video/fli";
				break;
				case "flv":
						mimeType = "video/flv";
				break;
				case "gl":
						mimeType = "video/gl";
				break;
				case "mp2":
						mimeType = "video/mpeg";
				break;
				case "mp4":
						mimeType = "video/mp4";
				break;
				case "mpe":
						mimeType = "video/mpeg";
				break;
				case "mpeg":
						mimeType = "video/mpeg";
				break;
				case "mpg":
						mimeType = "video/mpeg";
				break;
				case "mov":
						mimeType = "video/quicktime";
				break;
				case "qt":
						mimeType = "video/quicktime";
				break;
				case "viv":
						mimeType = "video/vnd.vivo";
				break;
				case "vivo":
						mimeType = "video/vnd.vivo";
				break;
				case "fli":
						mimeType = "video/x-fli";
				break;
				case "asf":
						mimeType = "video/x-ms-asf";
				break;
				case "asx":
						mimeType = "video/x-ms-asx";
				break;
				case "wmv":
						mimeType = "video/x-ms-wmv";
				break;
				case "wmx":
						mimeType = "video/x-ms-wmx";
				break;
				case "wvx":
						mimeType = "video/x-ms-wvx";
				break;
				case "avi":
						mimeType = "video/x-msvideo";
				break;
				case "movie":
						mimeType = "video/x-sgi-movie";
				break;
				case "mime":
						mimeType = "www/mime";
				break;
				case "ice":
						mimeType = "x-conference/x-cooltalk";
				break;
				case "vrm":
						mimeType = "x-world/x-vrml";
				break;
				case "vrml":
						mimeType = "x-world/x-vrml";
				break;
				case "spx":
						mimeType = "audio/ogg";
				break;
				default:
					mimeType = "application/unknown";
				break;
			}
			return mimeType;
		}
	}
}