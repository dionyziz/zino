Security.allowDomain("*");


var nc1:NetConnection = new NetConnection();
var nc2:NetConnection = new NetConnection();

var ns1:NetStream;
var ns2:NetStream;

var cam:Camera;
var mic:Microphone;
var channel1:String;
var channel2:String;


myStreamBorder.addEventListener( MouseEvent.MOUSE_DOWN, drag );
myStreamBorder.addEventListener( MouseEvent.MOUSE_UP, stopdrag );
myStreamBorder.stage.addEventListener( Event.MOUSE_LEAVE, stopdrag );
function drag( event ){
	myStreamBorder.startDrag( false, new Rectangle( 3, 3, this.stage.stageWidth - myStreamBorder.width, this.stage.stageHeight - myStreamBorder.height ) );
}

function stopdrag( event ) {
	myStreamBorder.stopDrag();
}

function publish( channel:String ){
	setupCameraMic();
	nc1.connect( "rtmp://europa.kamibu.com/oflaDemo" );
	nc1.addEventListener( NetStatusEvent.NET_STATUS, publish2 );
	channel1 = channel;
}

function publish2( event:NetStatusEvent ){
	if ( event.info.code == "NetConnection.Connect.Success") {
		ns1 = new NetStream( nc1 );
		ns1.attachCamera( cam );
		ns1.attachAudio( mic );
		ns1.publish( channel1 );
		myStreamBorder.myStream.attachCamera( cam );
	}
}

function watch( channel:String ){
	nc2.connect( "rtmp://europa.kamibu.com/oflaDemo" );
	nc2.addEventListener( NetStatusEvent.NET_STATUS, watch2 );
	channel2 = channel;
}

function watch2( event:NetStatusEvent ){
	if ( event.info.code == "NetConnection.Connect.Success") {
		ns2 = new NetStream( nc2 );
		ns2.play( channel2, -1);
		othersStream.attachNetStream( ns2 );
	}
}

function stopPublishing(){
	ns1.close();
}

function stopWatching(){
	ns2.close();
}

function setupCameraMic() {
	cam = Camera.getCamera();
	cam.setMode(320, 240, 30);
	cam.setQuality(0,90);
	mic = Microphone.getMicrophone();
}

ExternalInterface.addCallback( "publish", publish);
ExternalInterface.addCallback( "watch", watch);
ExternalInterface.addCallback( "stopWatching", stopWatching);
ExternalInterface.addCallback( "stopPublishing", stopPublishing);