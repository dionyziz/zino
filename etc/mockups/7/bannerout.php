<script type="text/javascript" src="../../../js/animations.js"></script>
<script type="text/javascript">
function User_Login() {
	var isanimating = false;
	var banner = document.getElementById( 'banner' );
	var menu = banner.getElementsByTagName( 'ul' )[ 0 ];
	var options = menu.getElementsByTagName( 'li' );
	if ( options[ 0 ].style.display == '' ) {
		Animations.Create( menu, 'opacity', 500, 1, 0, function () {
			if ( !isanimating ) {
				isanimating = true;
				options[ 0 ].style.display = 'none';
				options[ 1 ].style.display = 'none';
				options[ 3 ].style.display = '';
				options[ 4 ].style.display = '';
				options[ 5 ].style.display = '';
				Animations.Create( menu, 'opacity', 500, 0, 1 );
				menu.getElementsByTagName( 'input' )[ 0 ].value = '';
				menu.getElementsByTagName( 'input' )[ 0 ].focus();
				isanimating = false;
			}
		} );
	}
	else {
		Animations.Create( menu, 'opacity', 500, 1, 0, function () {
			if ( !isanimating ) {
				isanimating = true;
				options[ 0 ].style.display = '';
				options[ 1 ].style.display = '';
				//options[ 2 ].style.display = 'none';
				options[ 3 ].style.display = 'none';
				options[ 4 ].style.display = 'none';
				options[ 5 ].style.display = 'none';
				Animations.Create( menu, 'opacity', 500, 0, 1 );
				isanimating = false;
			}
		} );
	}
}
</script>
<div class="header" id="banner">
<h1><a href="http://www.chit-chat.gr/" onclick="return false"><img src="images/logo.png" alt="Chit-Chat" /></a></h1>
<ul>
	<li><a href="register" onclick="return false" class="register icon">Δημιούργησε λογαριασμό</a></li>
	<li class="dot">·</li>
	<li><a href="login" onclick="User_Login();return false" class="login icon">Είσοδος</a></li>
	<li class="dot" style="display:none">·</li>
	<li style="display:none">Όνομα: <input type="text" /> Κωδικός: <input type="password" /></li>
	<li class="dot" style="display:none"><input type="button" value="Είσοδος" class="button" /></li>
</ul>
<div class="search">
	<form action="" method="get">
		<input type="text" class="text" value="αναζήτησε φίλους" />
		<input type="submit" class="submit" value="ψάξε" />
	</form>
</div>
</div>
