<?php
	function ElementBanner() {
		global $page;
		
		$page->AttachStylesheet( 'css/banner.css' );
		$page->AttachScript( 'js/banner.js' );
		$page->AttachScript( 'js/animations.js' );
		
		$loggedin = true;
		?><div class="header" id="banner">
		<h1><a href="http://www.zino.gr/" onclick="return false"><img src="images/zino.png" alt="Zino" /></a></h1>
	    <a href="#content" class="novisual">Πλοήγηση στο περιεχόμενο</a>
		<ul><?php   
	        if ( !isset( $loggedin ) ) {
	            ?><li><a href="register" onclick="return false" class="register icon">Δημιούργησε λογαριασμό</a></li>
	            <li>·</li>
	            <li><a href="?#login" onclick="Banner.Login();return false" class="login icon">Είσοδος</a></li>
	            <li style="display:none">·</li>
	            <li style="display:none">Όνομα: <input type="text" /> Κωδικός: <input type="password" /></li>
	            <li style="display:none"><input type="button" value="Είσοδος" class="button" /></li><?php
	        }
	        else {
	    		?><li><a href="user/dionyziz" class="self icon" style="background-image: url('images/avatars/dionyziz.25.jpg')" onclick="return false">dionyziz</a></li>
	    		<li>·</li>
	    		<li><a href="messages" class="messages icon" onclick="return false">2 νέα μηνύματα</a></li>
	    		<li>·</li>
	    		<li><a href="profile" class="profile icon" onclick="return false">Προφίλ</a></li><?php
	        }
	        ?>
		</ul><?php
	    if ( isset( $loggedin ) ) {
	        ?><a href="logout" class="logout" onclick="return false">Έξοδος</a><?php
		}
	    ?><div class="search">
			<form action="" method="get">
				<input type="text" class="text" onfocus="" value="αναζήτησε φίλους" />
				<input type="submit" class="submit" value="ψάξε" />
			</form>
		</div>
	    <div class="eof"></div>
		</div><?php
	}
?>