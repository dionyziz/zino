<script type="text/javascript" src="../../js/animations.js"></script>
<script type="text/javascript" src="js/banner.js"></script>
<div class="header" id="banner">
	<h1><a href="http://www.chit-chat.gr/" onclick="return false"><img src="images/zino-trans.png" alt="Zino" /></a></h1>
    <a href="#content" class="novisual">Πλοήγηση στο περιεχόμενο</a>
	<ul><?php   
        if ( !isset( $_GET[ 'loggedin' ] ) ) {
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
    if ( isset( $_GET[ 'loggedin' ] ) ) {
        ?><a href="logout" class="logout" onclick="return false">Έξοδος</a><?php
	}
    ?><div class="search">
		<form action="" method="get">
			<div><input type="text" class="text" onfocus="" value="αναζήτησε φίλους" /></div>
			<div><input type="submit" class="submit" value="ψάξε" /></div>
		</form>
	</div>
    <div class="eof"></div>
</div>
