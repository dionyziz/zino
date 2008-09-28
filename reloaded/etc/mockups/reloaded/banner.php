<div class="roxas">
	<div style="float:right"><img src="roxasend.jpg" /></div>
	<a href="" onclick=""><img src="logo.jpg" class="logo" /></a>
	<ul><?php
        if ( $loggedin ) {
            ?><li><a class="logout" href="?p=frontpageloggedout">Έξοδος</a></li><?php
        }
        else {
            ?><li><a href="" onclick="return false" class="register">Νέος χρήστης</a></li><?php
        }
	?></ul>
</div>
<div class="roku">
	<div class="aku<?php
        if ( !$loggedin ) {
            ?> akuloggedout<?
        }
		 if ( isset( $collapse ) && $collapse ) {
			?> akucollapse<?php
		}
        ?>">
		<div class="leftcorner"></div>
		<div class="rightcorner"></div>
		<div class="content">
			<div>
				<?php
				if ( isset( $allowtoggle ) && $allowtoggle ) {
					if ( isset( $collapse ) && collapse ) {
						?><a href="" class="arrow" onclick="return false" title="Εμφάνιση κάρτας χρήστη"></a><?php
					}
					else {
						?><a href="" class="arrow" onclick="return false" title="Απόκρυψη κάρτας χρήστη"></a><?php
					}
				}
                if ( $loggedin ) {
					if ( isset( $collapse ) && !$collapse ) {
	    				?><h2><a href="" class="operator" onclick="return false"><img src="images/blink.jpg" class="avatar" />Blink</a></h2>
	    				<br />
						<div class="sigshadow">
						 &nbsp;
						</div>
	    				<small class="motto">
	    					Άντε μην τα πάρω με την αργοπορία σας! Γαμώ την τρέλα μου και την δική σας γαμώ!
	    				</small><?php
					}
                }
                else {
    				?><br />
    				<small>
    					<div class="label">Όνομα:</div>
    					<div class="field"><input type="text" /></div>
    					<div style="clear:both;height:5px;"></div>
    					<div class="label">Κωδικός:</div>
    					<div class="field"><input type="password" /></div>
    				</small>
    				<ul>
    					<li><a class="submit" href="?p=frontpage">Είσοδος</a></li>
    				</ul><?php
                }
			?></div>
			<?php
			if ( $loggedin ) {
				?><ul>
					<li><a class="options" href="" onclick="return false">Επιλογές</a></li>
					<li><a class="profile" href="" onclick="return false">Προφίλ</a></li>
					<li><a class="chat" href="" onclick="return false">Συνομιλία</a></li>
				</ul><?php
			}
			?>
		</div>
	</div>
</div>
<br />
