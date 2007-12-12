<?php
function UnitUsersLogin( tString $username , tString $password , tCoalaPointer $form , tCoalaPointer $parentdiv , tCoalaPointer $timer ) {
	global $user;
	global $rabbit_settings;
	global $libs;
	
	$libs->Load( 'loginattempt' );
	
	$s_username = $username->Get();
	$s_password = $password->Get();
	$s_password = md5( $s_password );
	
	$_SESSION[ 's_password' ] = $s_password;
	$_SESSION[ 's_username' ] = $s_username;
	
	CheckLogon( "session" , $_SESSION[ 's_username' ] , $_SESSION[ 's_password' ] );
	?>parentdiv = <?php
	echo $parentdiv;
	?>;<?php
	if ( $user->IsAnonymous() ) {
		$login = New LoginAttempt();
		$login->SetDefaults();
		$login->UserName = $s_username;
		$login->Password = $password->Get();
		$login->Save();
	
		?>Animations.Break ( <?php
		echo $timer;
		?> );
        window.location.href = '?p=a';
		/* inputs = <?php
		echo $form;
		?>.getElementsByTagName( 'input' );
		parentdiv.parentNode.parentNode.style.opacity = 1;
		inputs[ 0 ].style.backgroundColor = inputs[ 1 ].style.backgroundColor = 'lightyellow';
		inputs[ 0 ].style.color = inputs[ 1 ].style.color = 'red';
		inputs[ 0 ].style.fontWeight = inputs[ 1 ].style.fontWeight = 'bold';
		inputs[ 1 ].focus();
		inputs[ 1 ].select(); */<?php
	}
	else {
		$login = New LoginAttempt();
		$login->SetDefaults();
		$login->UserName = $s_username;
		$login->Success = 1;
		$login->Save();
	
		$user->UpdateLastLogon();
		$user->RenewAuthtoken();
		$user->SetCookie();
		?>roku = parentdiv = parentdiv.parentNode.parentNode.parentNode.parentNode;
        while ( roku = roku.nextSibling ) {
            if ( roku.className == 'roxas' ) {
                break;
            }
        }
        roxas = roku;
        ul = roxas.getElementsByTagName( 'ul' )[ 0 ];
        for ( i in ul.childNodes ) {
            li = ul.childNodes[ i ];
            if ( li.nodeType == 1 && li.nodeName.toLowerCase() == 'li' ) {
                lli = li;
            }
        }
        a = lli.getElementsByTagName( 'a' )[ 0 ];
        with ( a ) {
            className = 'logout';
            href = 'do/user/logout';
            childNodes[ 0 ].nodeValue = 'Έξοδος';
        }
		parentdiv.innerHTML += <?php
		ob_start();
		Element( 'user/box' , true );
		echo w_json_encode( ob_get_clean() );
		?>;
		divs = parentdiv.childNodes;
		for ( i in divs ) {
			if ( divs[ i ].nodeType == 1 && divs[ i ].nodeName.toLowerCase() == 'div' ) {
				k = divs[ i ];
			}
		}
		Animations.Create( k , 'opacity' , 3000 , 0 , 1 );
		window.location.reload();<?php
	}
}
?>
