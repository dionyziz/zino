<?php
	function ElementMain() {
		global $user;
		global $water;
		global $page;
		global $libs;
		global $rabbit_settings;
		
		//attaching ALL css files
		//$page->AttachStylesheet( 'css/default.css' );
		//$page->AttachStylesheet( 'css/headlines.css' );
		//$page->AttachStylesheet( 'css/links.css' );
		//$page->AttachStylesheet( 'css/forms.css' );
		$page->AttachStylesheet( 'css/album/list.css' );
		$page->AttachStylesheet( 'css/album/small.css' );
		$page->AttachStylesheet( 'css/photo/list.css' );
		$page->AttachStylesheet( 'css/photo/small.css' );
		$page->AttachStylesheet( 'css/photo/view.css' );
		$page->AttachStylesheet( 'css/frontpage/comments.css' );
		$page->AttachStylesheet( 'css/frontpage/events.css' );
		$page->AttachStylesheet( 'css/frontpage/shoutbox.css' );
		$page->AttachStylesheet( 'css/frontpage/view.css' );
		$page->AttachStylesheet( 'css/journal/journalllist.css' );
		$page->AttachStylesheet( 'css/journal/list.css' );
		$page->AttachStylesheet( 'css/journal/small.css' );
		$page->AttachStylesheet( 'css/journal/view.css' );
		$page->AttachStylesheet( 'css/poll/list.css' );
		$page->AttachStylesheet( 'css/poll/small.css' );
		$page->AttachStylesheet( 'css/poll/view.css' );
		$page->AttachStylesheet( 'css/user/join.css' );
		$page->AttachStylesheet( 'css/user/joined.css' );
		$page->AttachStylesheet( 'css/user/list.css' );
		$page->AttachStylesheet( 'css/user/sections.css' );
		$page->AttachStylesheet( 'css/user/settings.css' );
		$page->AttachStylesheet( 'css/user/profile/view.css' );
		$page->AttachStylesheet( 'css/banner.css' );
		$page->AttachStylesheet( 'css/bubbles.css' );
		$page->AttachStylesheet( 'css/comment.css' );
		$page->AttachStylesheet( 'css/default.css' );
		$page->AttachStylesheet( 'css/favourites.css' );
		$page->AttachStylesheet( 'css/footer.css' );
		$page->AttachStylesheet( 'css/forms.css' );
		$page->AttachStylesheet( 'css/headlines.css' );
		$page->AttachStylesheet( 'css/links.css' );
		$page->AttachStylesheet( 'css/modal.css' );
		$page->AttachStylesheet( 'css/people.css' );
		$page->AttachStylesheet( 'css/search.css' );
		$page->AttachStylesheet( 'css/usersections.css' );
		
		
		//end of css attaching
		$page->AttachScript( 'js/jquery.js' );
		$page->AttachScript( 'js/IE8.js' , 'javascript' , false, '7' );  
		//$page->AttachScript( 'js/pngfix.js' , 'javascript', false, '7' );
        $page->AttachScript( 'js/main.js' );
		$page->AttachScript( 'js/trivial/dates.js' );
		$page->AttachScript( 'js/coala.js' );
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
            $page->SetTitle( $page->Title() . ' / ' . $rabbit_settings[ 'applicationname' ] );
        }
        else {
            $water->Notice( 'Title not defined for page' ); // Produce a notice at the php debugger
            $page->SetTitle( $rabbit_settings[ 'applicationname' ] );
        }
        
        // pass
        return $res;
    }
?>
