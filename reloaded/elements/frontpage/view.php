<?php
    function ElementFrontpageView() {
        global $page;
        global $rabbit_settings;
        
        $page->SetTitle( "Είσαι μέσα?" );
        $page->AttachStylesheet( 'css/sidebar.css' );
        $page->AttachScript( 'js/animations.js' );
        
        Element( "frontpage/leftbar" );

        $latestids = Element( "article/latest" );
        
        ?><div style="clear:both"></div><?php
        
        //Element( "frontpage/rightbar" );
        Element( "article/popular", $latestids );
        
        ?><br /><?php
		Element( "user/birthdays" );
        ?><br /><br /><?php
        Element( "photo/latest" );
        Element( "notify/frontpage" );
        ?><br />
		<a href="index.php?p=advertise">&#187;Διαφημιστείτε στο <?php
			echo $rabbit_settings[ 'applicationname' ];
			?></a><br /><br /><?php
        Element( "ad/leaderboardgameplanet" );
    }
?>
