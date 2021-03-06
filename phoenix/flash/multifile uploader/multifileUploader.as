﻿package {
	import flash.display.*;
	import flash.events.*
	import flash.net.*;
	
	import flash.external.ExternalInterface;
	import flash.geom.Matrix;
	import flash.system.Security;
	import flash.utils.ByteArray;
	
	import gr.zino.FileHandler;
	import gr.zino.lib.PostRequest;
	import com.adobe.images.JPGEncoder;
	import com.dynamicflash.util.Base64;
	
	public class multifileUploader extends MovieClip {
		
		private var filehandler:FileHandler;
		private var req:URLRequest;
		private var variables:String = new String;
		private var urlvars:URLVariables = new URLVariables();
		private var loader:Loader = new Loader;
		private var files:Array;
		
		public function multifileUploader() {
			Security.allowDomain( "*" );
			filehandler = new FileHandler( onFileSelect );
			req = new URLRequest( MovieClip(this.root).loaderInfo.parameters.posturl );
			req.method = URLRequestMethod.POST;
			button.addEventListener( MouseEvent.CLICK, filehandler.BrowseFiles );
			loader.contentLoaderInfo.addEventListener( Event.COMPLETE, PrepareForUpload );
			ExternalInterface.addCallback( "AppendPostVar", AppendPostVar );
		}
		
		public function AppendPostVar( name, value ){
			urlvars[ name ]  = value;
		}
		
		public function onFileSelect( files:Array ){
			this.files = files;
			ExternalInterface.call( "document.body.style.cursor = 'wait'" );			
			LoadImage();
		}
		
		public function LoadImage( event = null ){
			var file = files.pop();
			if( file != undefined ){
				loader.loadBytes( file.data );
				return;
			}
			ExternalInterface.call( "document.location.reload" );
		}
		
		public function PrepareForUpload( event ){
			var resize:Number = 1;
			
			if( loader.width > 700 || loader.height > 600 ) {
				if( loader.width / loader.height >= 7 / 6 ){
					resize = 700 / loader.width;
				}
				else {
					resize = 600 / loader.height;					
				}
			}
			var image = new BitmapData( Math.floor( loader.width * resize ), Math.floor( loader.height * resize ) );
			var trans = new Matrix();
			trans.scale( resize, resize );
			image.draw( loader, trans, null, null, null, true );
			var jpgencoder = new JPGEncoder( 100 );
			Upload( jpgencoder.encode( image ) );
		}
		
		public function Upload( file:ByteArray ){
			urlvars.fileencoded = Base64.encodeByteArray( file );
			req.data = urlvars;
			var urlloader = new URLLoader( req );
			trace("uploading" );
			urlloader.addEventListener( Event.COMPLETE, LoadImage );
		}
	}
}