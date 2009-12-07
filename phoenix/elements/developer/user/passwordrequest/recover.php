<?php
    class ElementDeveloperUserPasswordRequestRecover extends Element {
        public function Render( tInteger $requestid, tText $hash ) {
            global $libs;
            global $page;
            
            $page->SetTitle( 'Επαναφορά κωδικού' );
            
            $requestid = $requestid->Get();
            $hash = $hash->Get();
            
            $libs->Load( 'passwordrequest' );
            $libs->Load( 'rabbit/helpers/http' );
            
            $request = New PasswordRequest( $requestid );
            $myuser = New User( $request->Userid );
            if ( $request->Used || $request->Hash != $hash || !$myuser->Exists() ) {
                return Redirect( 'forgot/failure' );
            }
            
            $myuser->UpdateLastLogin();
            $myuser->Save();
            $_SESSION[ 's_userid' ] = $myuser->Id;
            $_SESSION[ 's_authtoken' ] = $myuser->Authtoken;
            User_SetCookie( $myuser->Id, $myuser->Authtoken );
            
            ?><h2>Αλλαγή κωδικού πρόσβασης</h2>
            
            <form action="do/user/password/recover" method="post">
                <div>
                    <label>Πληκτρολόγησε τον νέο σου κωδικό:</label>
                    <input type="password" name="password" value="" />
                </div>
                <input type="hidden" value="<?php
                echo htmlspecialchars( $hash );
                ?>" name="hash" />
                <input type="hidden" value="<?php
                echo htmlspecialchars( $requestid );
                ?>" name="requestid" />
                <input type="submit" value="Αλλαγή κωδικού" />
            </form><?php
        }
    }
?>
