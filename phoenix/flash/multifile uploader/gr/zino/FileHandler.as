package gr.zino {
	import flash.net.*;
	import flash.events.Event;
	
	public class FileHandler {
		private var filelist;
		private var callback;
		
		public function FileHandler( callback:Function ){ 
			this.callback = callback;
			filelist = new FileReferenceList;
			filelist.addEventListener( Event.SELECT, onSelect );
		}
		
		public function BrowseFiles( event ){
			filelist.browse();
		}
		
		public function onSelect( event ){
			filelist.fileList[ 0 ].load();
			filelist.fileList[ 0 ].addEventListener( Event.COMPLETE, onComplete );
		}
		
		public function onComplete( event ){
			callback( filelist.fileList );
		}
	}
}