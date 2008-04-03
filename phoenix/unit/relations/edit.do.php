<?php
	function UnitRelationsEdit( tInteger $rid, tString $rtype ) {
		global $user;
		global $libs;
		
		if ( !$user->CanModifyCategories() ) {
			return;
		}
		
		$rid = $rid->Get();
		$rtype = $rtype->Get();
		
		if( $rtype == '' ) {
			?>alert( "Δεν μπορείς να δημιουργήσεις κενή σχέση" );<?php
			return;
		}
		else if( strlen( $rtype ) > 20 ) {
			?>alert( "Δεν μπορεί μια σχέση να έχει όνομα μεγαλύτερο των 20 χαρακτήρων" );<?php
			return;
		}
		
		$libs->Load( 'relations' );
		
		$change = New Relation( $rid );
		$change->Type = $rtype;
		$change->Created = NowDate();
		$change->CreatorId = $user->Id();
		$change->CreatorIp	= UserIp();
		$change->Save();
	}
		
