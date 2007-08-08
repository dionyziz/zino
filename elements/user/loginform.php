<?php
function ElementUserLoginform() {
	global $page;
	
	$page->AttachScript( 'js/user.js' );
	?><form onkeypress="return submitenter(this,event)" id="loginform" action="do/user/logon" method="post">
		<div style="display:inline">
		<small>
			<label for="username">Όνομα:</label>
			<div class="field"><input type="text" id="username" name="username" /></div>
			<div style="clear:both;height:5px;"></div>
			<label for="password">Κωδικός:</label>
			<div class="field"><input type="password" id="password" name="password" /></div>
		</small>
		</div>
		<div style="font-size: 90%; margin-top: 5px; margin-right: 5px; _position:relative; _top:-50px">
			<a href="" onclick="user.LoginUser( this.parentNode.parentNode.getElementsByTagName( 'input' )[ 0 ].value , this.parentNode.parentNode.getElementsByTagName( 'input' )[ 1 ].value , this.parentNode.parentNode , this.parentNode.parentNode );return false;" class="submit">Είσοδος</a>
			<a href="?p=faqc&amp;id=6">Πληροφορίες</a>
		</div>
	</form><?php
}