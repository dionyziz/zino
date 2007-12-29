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
			<div class="edge">
				<a href=""><img src="http://static.zino.gr/phoenix/mockups/img10.jpg" alt="img10" title="img10" /></a>
			</div>
			<a href=""><img src="http://static.zino.gr/phoenix/mockups/img11.jpg" alt="img11" title="img11" /></a>
			<a href=""><img src="http://static.zino.gr/phoenix/mockups/img12.jpg" alt="img12" title="img12" /></a>
			<a href=""><img src="http://static.zino.gr/phoenix/mockups/img13.jpg" alt="img13" title="img13" /></a>
			<a href=""><img src="http://static.zino.gr/phoenix/mockups/img14.jpg" alt="img14" title="img14" /></a>
		</div>
		<div style="clear:both"></div>
		<div class="upperslide">
			<a href=""><img src="http://static.zino.gr/phoenix/mockups/img1.jpg" alt="img1" title="img1" /></a>
			<a href=""><img src="http://static.zino.gr/phoenix/mockups/img2.jpg" alt="img2" title="img2" /></a>
			<a href=""><img src="http://static.zino.gr/phoenix/mockups/img3.jpg" alt="img3" title="img3" /></a>
			<a href=""><img src="http://static.zino.gr/phoenix/mockups/img4.jpg" alt="img4" title="img4" /></a>
			<a href=""><img src="http://static.zino.gr/phoenix/mockups/img5.jpg" alt="img5" title="img5" /></a>
			<a href=""><img src="http://static.zino.gr/phoenix/mockups/img6.jpg" alt="img6" title="img6" /></a>
			<a href=""><img src="http://static.zino.gr/phoenix/mockups/img7.jpg" alt="img7" title="img7" /></a>
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
