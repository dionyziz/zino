package gr.zino {
	import flash.net.*;
	import flash.events.Event;
	
	public class FileHandler {
		private var filelist;
		private var callback;
		private var file;
		private var ret:Array;
		
		public function FileHandler( callback:Function ){ 
			this.callback = callback;
			filelist = new FileReferenceList;
			file = new FileReference;
			ret = new Array();
			filelist.addEventListener( Event.SELECT, onSelect );
		}
		
		public function BrowseFiles( event ){
			filelist.browse();
		}
		
		public function onSelect( event = null ){
			file = filelist.fileList.pop();
			file.addEventListener( Event.COMPLETE, onComplete );
			if( file != undefined ){
			 file.load();
			}
		}
		
		public function onComplete( event ){
			ret.push( file );
			if( filelist.fileList.length == 0 ){
				callback( ret );
				return;
			}
			onSelect();
		}
	}
}