<?php
	function ElementBanner() {
		global $page;
		global $user;
		
		$page->AttachStylesheet( 'css/banner.css' );
		$page->AttachScript( 'js/banner.js' );
		$page->AttachScript( 'js/animations.js' );
		
		$loggedin = false;
		?><div class="header" id="banner">
		<h1><a href="http://www.zino.gr/" onclick="return false"><img src="images/zino.png" alt="Zino" /></a></h1>
	    <a href="#content" class="novisual">Πλοήγηση στο περιεχόμενο</a>
		<ul><?php   
	        if ( !$user->Exists() ) {
	            ?><form action="do/user/login" method="post">
					<li><a href="?p=join" class="register icon">Δημιούργησε λογαριασμό</a></li>
		            <li>·</li>
		            <li><a href="?#login" onclick="Banner.Login();return false" class="login icon">Είσοδος</a></li>
		            <li style="display:none;">·</li>
		            <li style="display:none;">Όνομα: <input type="text" name="username" /> Κωδικός: <input type="password" name="password" /></li>
		            <li style="display:none;"><input type="button" value="Είσοδος" class="button" /></li>
				</form><?php
	        }
	        else {
	    		?><li><a href="user/dionyziz" class="self icon" style="background-image: url('../images/avatars/dionyziz.25.jpg');" onclick="return false"><?php
				echo $user->Name;
				?></a></li>
	    		<li>·</li>
	    		<li><a href="messages" class="messages icon" onclick="return false">2 νέα μηνύματα</a></li>
	    		<li>·</li>
	    		<li><a href="profile" class="profile icon" onclick="return false">Προφίλ</a></li><?php
	        }
	        ?>
		</ul><?php
	    if ( $user->Exists() ) {
	        ?><a href="logout" class="logout" onclick="return false;">Έξοδος</a><?php
		}
	    ?><div class="search">
			<form action="" method="get">
				<input type="text" class="text" value="αναζήτησε φίλους" />
				<input type="submit" class="submit" value="ψάξε" />
			</form>
		</div>
	    <div class="eof"></div>
		</div><?php
	}
?>