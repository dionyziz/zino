<div class="join">
	<div class="bubble">
	    <i class="tl"></i><i class="tr"></i>
		<h2>Γίνε μέλος!</h2>
		<form class="joinform">
			<div>
				<label for="join_name">Όνομα χρήστη:</label>
				<input type="text" id="join_name" value="" onfocus="Join_Focusinput( this );" onblur="Join_Unfocusinput( this);" />
				<p>Το όνομα με το οποίο θα εμφανίζεσαι, δεν μπορείς να το αλλάξεις αργότερα.</p>
			</div>
			<div>
				<label for="join_pwd">Κωδικός πρόσβασης:</label>
				<input type="password" id="join_pwd" value="" style="margin-bottom:5px;" onfocus="Join_Focusinput( this );" onblur="Join_Unfocusinput( this );" />
				<div>
					<label for="join_repwd">Πληκτρολόγησε τον ξανά:</label>
					<input type="password"  id="join_repwd" value="" style="vertical-align:top;" onfocus="Join_Focusinput( this );" onblur="Join_Unfocusinput( this );" onkeyup="Join_Checkpwd( this );" />
				</div>
			</div>
			<div>
				<label for="join_email">E-mail (προαιρετικά):</label>
				<input type="text" id="join_email" value="" style="width:200px;" onfocus="Join_Focusinput( this );" onblur="Join_Unfocusinput( this );" />
				<p>Αν συμπληρώσεις το e-mail σου θα μπορείς να επαναφέρεις τον κωδικό σου σε περίπτωση που τον ξεχάσεις.</p>
			</div>
			
			<p>Η δημιουργία λογαριασμού συνεπάγεται την ανεπιφύλακτη αποδοχή των <a href="">όρων χρήσης</a>.</p>
		
			<div style="text-align:center;">
				<a href="" class="button">Δημιουργία &raquo;</a>
			</div>
		</form>    
	    <i class="qleft"></i><i class="qright"></i>
	    <i class="qbottom"></i>
	    <i class="bl"></i><i class="br"></i>
	</div>
	<img src="images/button_ok_16.png" alt="Σωστή επαλήθευση" title="Σωστή επαλήθευση" style="display:none;" />
</div>
<script type="text/javascript">//<![CDATA[

setTimeout( function () {
	document.getElementById( 'join_name' ).focus();
}, 20 );
function Join_Focusinput( node ) {
	node.style.border = '1px solid #bdbdff';
}
function Join_Unfocusinput( node ) {
	node.style.border = '1px solid #999';
}
var hadcorrect = false;
var timervar;
function Join_Checkpwd( node ) {
	var parent = node.parentNode.parentNode;
	var divlist = parent.getElementsByTagName( 'div' );
	var div = divlist[ 0 ];
	var inputlist = parent.getElementsByTagName( 'input' );
	var pwd = inputlist[ 0 ];

	//alert( okpwd );
	clearTimeout( timervar );
	timervar = setTimeout( function() {
		if ( node.value == pwd.value && node.value !== '' && !hadcorrect ) {
			//alert( 'OK' );
			hadcorrect = true;
			node.style.display = 'inline';
			var okpwd = document.createElement( 'img' );
			okpwd.src = 'images/button_ok_16.png';
			okpwd.alt = 'Σωστή επαλήθευση';
			okpwd.title = 'Σωστή επαλήθευση';
			okpwd.style.paddingLeft = '5px';
			if ( typeof okpwd.style.opacity != 'undefined' ) {
				Animations.SetAttribute( okpwd, 'opacity', 0 );
				div.appendChild( okpwd );
				Animations.Create( okpwd, 'opacity', 2000, 0, 1 );
			}
			else {
				div.appendChild( okpwd );
			}
		}
		else {
			var imglist = parent.getElementsByTagName( 'img' );
			var okpwd = imglist[ 0 ];
			//alert( okpwd );
			if ( node.value != pwd.value && okpwd ) {
				div.removeChild( okpwd );
				hadcorrect = false;
			}
		}
	}, 200 );
}

//]]>
</script>