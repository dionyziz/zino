<?php
	function ElementMain() {
		global $user;
		global $water;
		global $page;
		global $libs;
		global $rabbit_settings;
		
		//attaching ALL css files
		$page->AttachStylesheet( 'css/style.css.php' );
		
		//start javascript attaching
		$page->AttachScript( 'js/jquery.js' );
		//$page->AttachScript( 'js/script.js.php' );
		$page->AttachScript( 'js/IE8.js' , 'javascript' , false, '7' );  
		$page->AttachScript( 'js/modal.js' );
		/*$page->AttachScript( 'js/main.js' );*/
		$page->AttachScript( 'js/trivial/dates.js' );
		$page->AttachScript( 'js/coala.js' );
		$page->AttachScript( 'js/album/list.js' );
		$page->AttachScript( 'js/album/photo/list.js' );
		$page->AttachScript( 'js/album/photo/view.js' );
		$page->AttachScript( 'js/poll/list.js' );
		$page->AttachScript( 'js/poll/view.js' );
		$page->AttachScript( 'js/journal/list.js' );
		$page->AttachScript( 'js/journal/view.js' );
        $page->AttachScript( 'js/journal/new.js' );
		$page->AttachScript( 'js/user/join.js' );
		$page->AttachScript( 'js/user/joined.js' );
		$page->AttachScript( 'js/user/settings.js' );
		$page->AttachScript( 'js/banner.js' );
		$page->AttachScript( 'js/settings.js' );
        $page->AttachScript( 'js/wysiwyg.js' );
		$page->AttachScript( 'js/frontpage.js' );
		
        $page->AddMeta( 'author', 'Kamibu Development Team' );
        $page->AddMeta( 'keywords', 'greek friends chat community greece meet people' );
        $page->AddMeta( 'description', 'Το ' . $rabbit_settings[ 'applicationname' ] . ' είναι μία ελληνική κοινότητα φίλων - είσαι μέσα;' );
        
		ob_start();
		$res = MasterElement();
		$master = ob_get_clean();
		
		if ( $res === false ) { //If the page requested is not in the pages available
			Element( 'banner' );
			?><div class="content" id="content"><?php
			Element( '404' );
			?></div><?php
			Element( 'footer' );
		}
		else {
			if ( !is_array( $res ) || !isset( $res[ 'tiny' ] ) ) {
                Element( 'banner' );
            }
			?><div class="content" id="content"><?php	
            echo $master;
			?></div><?php
            if ( !is_array( $res ) || !isset( $res[ 'tiny' ] ) ) {
                Element( 'footer' );
            }
        }
        Element( 'tracking/analytics' ); // Google-Analytics, for stats
        if ( $page->Title() != '' ) { // If the title's page is not blank
            $page->SetTitle( $page->Title() . ' | ' . $rabbit_settings[ 'applicationname' ] );
        }
        else {
            $water->Notice( 'Title not defined for page' ); // Produce a notice at the php debugger
            $page->SetTitle( $rabbit_settings[ 'applicationname' ] );
        }
        
        // pass
        return $res;
    }
?>
