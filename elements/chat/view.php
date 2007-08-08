 <?php
	function ElementChatView() {
		global $user;
		global $water;
		global $page;
		global $libs;
		global $xc_settings;
		
		if ( !$xc_settings['chatavailable'] ) {
			return;
		}
		
		$libs->Load( 'chat' );
		$page->SetTitle( 'Συνομιλία' );
		$page->AttachScript( 'js/coala.js' );
		$page->AttachScript( 'js/chat.js' );
		$page->AttachStyleSheet( 'css/chat.css' );
		
		?>
		<br /><br /><br />
		<div class="chat">
			<div class="userlist" id="userlist">
				<h3>Συνομιλούν Τώρα</h3><?php
				/*
				<div>
					<a href="" class="operator" onclick="return false">
						<img src="images/blink.jpg" class="avatar" />Blink
					</a>
					Συνδεδεμένος: 5 λεπτά<br />
					Ανενεργός
				</div>
				<div>
					<a href="" class="developer" onclick="return false">
						<img src="images/dionyziz.jpg" class="avatar" />Dionyziz
					</a>
					Συνδεδεμένος: 1 λεπτό<br />
				</div>
				<div>
					<a href="" class="developer" onclick="return false">
						<img src="images/finlandos.jpg" class="avatar" />Abresas
					</a>
					Συνδεδεμένος: 39 λεπτά<br />
				</div>
				*/
			?></div>
			<div class="history" id="chathistory"><?php
				/*
				<!--
				<div><span><a href="" class="developer" onclick="return false"><img src="images/finlandos.jpg" class="avatar" />Abresas</a> λέει:</span> Γεια!</div>
				<div><span><a href="" class="developer" onclick="return false"><img src="images/finlandos.jpg" class="avatar" />Abresas</a> λέει:</span> Ε πώς το έχεις κάνει έτσι..</div>
				-->
				*/
			?></div>
			<div>
				<form onsubmit="Chat.Release();return false;" method="post" action="">
					<input type="text" class="msg" value="" id="chatmessage" style="background-image:url('image.php?id=<?php
					echo $user->Icon();
					?>');" />
					<input type="submit" class="send" value="Αποστολή" />
				</form>
			</div>
		</div>
		<div style="display: none;" id="chat_last_id"><?php
			echo ChatLastId() - 30;
		?></div>
		
		<script type="text/javascript">
		</script><?php
	}
?>
