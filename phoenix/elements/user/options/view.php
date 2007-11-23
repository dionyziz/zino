<?php
	function ElementUserOptionsView( tBoolean $saved, tBoolean $match, tBoolean $invalid, tBoolean $newpassword, tBoolean $sizeok, tBoolean $extok ) {
		global $user;
		global $page;
		
        $saved = $saved->Get();
    	$match = $match->Get();
    	$invalid = $invalid->Get();
    	$newpassword = $newpassword->Get();
    	$sizeok = $sizeok->Get();
    	$extok = $extok->Get();
		
		$page->SetTitle( "Επιλογές" );
		$page->AttachStyleSheet( 'css/settings.css' );
		$page->AttachScript( 'js/options.js' );
		
		if ( $user->IsAnonymous() ) { 
			?><br /><br /><br /><br /><b>Αυτή η σελίδα είναι διαθέσιμη μόνο αφού εισέλθεις!<br /> Για να εισέλθεις δώσε το όνομα χρήστη σου και τον κωδικό πρόσβασης στη φόρμα πάνω δεξιά.</b><?php
			return;
		}
		
		else if ( $saved ) {
			?><b>Οι επιλογές σου έχουν αποθηκευτεί επιτυχώς!</b><br /><br /><?php
		}
		
		$showchange = $match || $invalid || $newpassword;

		?><div id="settings" class="settings">
			<form action="do/user/options" id="theuseropties" enctype="multipart/form-data" method="post"><?php
			
				Element( "user/options/personal" );
				Element( "user/options/password", $match, $invalid, $newpassword );
				Element( "user/options/icon", $sizeok, $extok );
				Element( "user/options/contact" );		
				Element( "user/options/slogan" );				
				
				?><a href="javascript:SetCat.submitchanges();" class="next">Αποθήκευση&#187;</a>
			</form>
		</div><?php
	}
?>
