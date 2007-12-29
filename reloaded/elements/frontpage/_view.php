<?php
    function ElementFrontpageView() {
        global $page;
        global $rabbit_settings;
        
        $page->SetTitle( "Είσαι μέσα?" );
        $page->AttachStylesheet( 'css/sidebar.css' );
		$page->AttachStyleSheet( 'css/profileview.css' );
        $page->AttachScript( 'js/animations.js' );
        
        //Element( "photo/latest" );
		?><div class="rightslide">
		</div>
		<div style="upperslide">
		</div><?php
        Element( "frontpage/leftbar" );

        $latestids = Element( "article/latest" );
        
        ?><div style="clear:both"></div><?php
        
        //Element( "frontpage/rightbar" );
        Element( "article/popular", $latestids );
        
        ?><br /><?php
		Element( "user/birthdays" );
        ?><br /><br /><?php
        Element( "notify/frontpage" );
        ?><br />
		<a href="index.php?p=advertise">&#187;Διαφημιστείτε στο <?php
        echo $rabbit_settings[ 'applicationname' ];
        ?></a><br /><br /><?php
        Element( "ad/leaderboardgameplanet" );
    }
?>
