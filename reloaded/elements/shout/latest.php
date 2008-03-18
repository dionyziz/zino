<?php
	function ElementShoutLatest( tInteger $offset ) {
		global $page;
		global $libs;
		global $user;
		global $water;
		global $xc_settings;
		
        $offset = $offset->Get();
        
		$libs->Load( 'shoutbox' );
		$page->SetTitle( "Συζήτηση" );
		$page->AttachStyleSheet( 'css/allshouts.css' );
		
		if ( $offset < 1 ) {
			$offset = 1;
		}
		$shoutsnumber = CountShouts();
		$pages = $shoutsnumber / 20;
		if ( $pages % 20 != 0 ) {
			++$pages;
		}
		if ( $offset < 0 || $offset > $pages ) {
			return;
		}
		if ( $offset == 0 ) {
			$offset = 1 ;
		}
		$shouts = LatestShouts( $offset , 20 );
		
		?><br /><br /><br /><br />
		<span class="heading">Συζήτηση</span><br /><br /><div class="allshouts"><?php
        $shouttexts = array();
        foreach ( $shouts as $i => $thisshout ) {
            $shouttexts[ $i ] = $thisshout->Text();
        }
        $shouttexts = mformatshouts( $shouttexts );
		foreach ( $shouts as $i => $thisshout ) {
			?><div class="thisshout" style="clear:both;min-height:50px"><?php
			?><span style="float:left;margin-right:3px;"><?php
				Element( 'user/icon' , $thisshout->User() );
			?></span>
			<span style="float:left;font-weight:bold;"><?php
				Element( 'user/static' , $thisshout->User() );
			?></span><br /><span><?php
			echo $shouttexts[ $i ];
			?></span><?php
			if ( $user->CanModifyCategories() || ( $user->CanModifyStories() && $thisshout->UserId() == $user->Id() ) ) {
				?><a style="cursor: pointer;" onclick="Shoutbox.Edit( <?php
				echo $thisshout->Id();
				?> );return false;" href="" title="Επεξεργασία"><img src="<?php
				echo $xc_settings[ 'staticimagesurl' ];
				?>icons/icon_wand.gif" width="16" height="16" alt="Επεξεργασία" /></a><?php
			}
			?></div>
			<div style="display: none;" id="shoutedit_<?php
			echo $thisshout->Id();
			?>"><?php
			echo $shouttexts[ $i ];
			?></div><?php
		}
		Element( 'pagify' , $offset , 'allshouts' , $shoutsnumber , 20 );
        ?></div><?php
	}
?>
