<?php
    function ElementUserChangePassword( tInteger $uid, tString $oldpass, tString $error ) {
        global $user;
        global $page;
        
        $page->AttachStylesheet( 'css/rounded.css' );
        $page->SetTitle( "Αλλαγή Κωδικού" );

        $uid = $uid->Get();
        $oldpassmd5 = $oldpass->Get();

        if( $user->Exists() ) {
            return Redirect();
        }

        $error = $error->Get();
        $theuser = New User( $uid );
        if( $error != 'none' ) {
            if ( !$theuser->Exists() || $theuser->Password() != $oldpassmd5 ) {
                ?>Δεν υπάρχεις στην βάση δεδομένων!<?php
                   return;
            }
        ?><br /><br /><br />
        	<div class="content">
	            <form method='post' id='chp' action='do/user/changepassword'>
	            <div class="register">
	            	<div class="opties">
	            		<div class="upperline">
							<div class="leftupcorner"></div>
							<div class="rightupcorner"></div>
							<div class="middle"></div>
						</div>
						<div class="rectanglesopts">
				            <span class="directions" style="padding-left:20px">Επέλεξε έναν κωδικό πρόσβασης</span><br />
				            <span class="tip" style="padding-left:20px">(θα τον πληκτρολογείς για να επιβεβαιώνεις την ταυτότητά σου)</span><br />
				            <input type='password' tabindex='0' name='newpass1' /><br /><br /><br />
				            
				            <span class="directions" style="padding-left:20px">Ξαναγράψε τον κωδικό πρόσβασης</span><br />
				            <span class="tip" style="padding-left:20px">(για να βεβαιωθείς ότι δεν έκανες λάθος)</span><br />
				            <input type='password' tabindex='0' name='newpass2' /><br/>
				            <input type='hidden' name='uid' value='<?php
				            echo $uid;
				            ?>' />
				            <input type='hidden' name='oldpassmd5' value='<?php
				            echo $oldpassmd5;
				            ?>' />
				        </div>
				        <div class="downline">
							<div class="leftdowncorner"></div>
							<div class="rightdowncorner"></div>
							<div class="middledowncss"></div>
						</div>
					</div>
				</div>
			   <div id="nextlink" style="text-align:center"><a href="" onclick="g('chp').submit();return false" class="next">Αλλαγή &gt;&gt;</a></div>
			   </form>
			</div><br /><br /><?php
        }
        switch( $error ) {
            case 'passwords':
                ?><b>Οι νέοι κωδικοί που πληκτρολόγησες δεν είναι ίδιοι μεταξύ τους</b><?php
                break;
            case 'oldpass':
                ?><b>Έχεις ήδη αλλάξει τον κωδικό σου</b><?php
                break;
            case 'none':
                $_SESSION[ 's_username' ] = $theuser->Username();
                $_SESSION[ 's_password' ] = $theuser->Password();
                CheckLogon( "session" , $_SESSION[ 's_username' ] , $_SESSION[ 's_password' ] );
                $user->UpdateLastLogon();
				$user->RenewAuthtoken();
				$user->SetCookie();
				return Redirect();
        }
    }
?>
