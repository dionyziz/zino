<?php
	function ElementMain() {
		global $user;
		global $water;
		global $page;
		global $libs;
		global $rabbit_settings;
		
		//$page->AttachStylesheet( 'css/main.css' );
		//attaching all the default styling files
		$page->AttachStylesheet( 'css/default.css' );
		$page->AttachStylesheet( 'css/headlines.css' );
		$page->AttachStylesheet( 'css/links.css' );
		$page->AttachStylesheet( 'css/forms.css' );
        $page->AttachScript( 'js/pngfix.js' , 'javascript', false, '7' );
        $page->AttachScript( 'js/main.js' );
        $page->AddMeta( 'author', 'Kamibu Development Team' );
        $page->AddMeta( 'keywords', 'greek friends chat community greece meet people' );
        $page->AddMeta( 'description', '�� ' . $rabbit_settings[ 'applicationname' ] . ' ����� ��� �������� ��������� ����� - ����� ����;' );
        
		?><div class="content" id="content"><?php
		ob_start();
		$res = MasterElement();
		$master = ob_get_clean();
		
		if ( $res === false ) { //If the page requested is not in the pages available
			Element( 'banner' );
			Element( '404' );
			Element( 'copyright' );
		}
		else {	
			if ( !is_array( $res ) || !isset( $res[ 'tiny' ] ) ) {
                Element( 'banner' );
            }
            echo $master;
            if ( !is_array( $res ) || !isset( $res[ 'tiny' ] ) ) {
                Element( 'footer' );
            }
        }
        ?></div><?php
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