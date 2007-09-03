<?php
	function ElementUserProfileFrelations( $relations ) {
		global $user;
		global $libs;
		global $page;
		
		$libs->Load( 'relations' );
		
		$page->AttachStylesheet( 'css/frelations.css' );
    	$page->AttachScript( 'js/animations.js' );
    	$page->AttachScript( 'js/_friends.js' );
    	
    	?><div style="position: absolute;left: 11px;top: 220px;">
                <map id="close" name="close">
				<area shape="rect" coords="94,20,105,30" onclick="alert('Klino');return false;" alt="Κλείσιμο" title="Κλείσιμο" href=''/>
				</map>
				
				<img src="https://beta.chit-chat.gr/etc/mockups/frelations/frelations_htmled/top_close.png" usemap="#close" style="border: none;position: absolute" /><br /><br />
                <div class="frelations"><?php
                foreach( $relations as $relation ) {
                	?><div id="frel_<?php
                	echo $relation->Id;
                	?>" class="<?php
                	if( $relid == $relation->Id ) {
                		?>relselected<?php
                	}
                	else {
                		?>frelation<?php
                	}
                	?>" onmouseover="g( 'frel_<?php
                	echo $relation->Id;
                	?>' ).style.color='#5c60bb';" onmouseout="g( 'frel_<?php
                	echo $relation->Id;
                	?>' ).style.color='#757bee';"><?php
                	echo $relation->Type;
                	?></div><?php
                }
                ?><div id="frel_-1" class="<?php
                if( $relid == -1 ) {
                	?>relselected<?php
                }
                else {
                	?>frelation<?php
                }
                ?>" onmouseover="g( 'frel_-1' ).style.color='#5c60bb';" onmouseout="g( 'frel_-1' ).style.color='#757bee';">Καμία</div>
                </div>
                <img src="https://beta.chit-chat.gr/etc/mockups/frelations/frelations_htmled/bottom.png" style="margin-left:6px;position:absolute;z-index: 1;" />
         </div><?php
     }
?>
