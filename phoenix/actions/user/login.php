<?php
    function ActionUserLogin( tText $username, tText $password ) {
        global $user;
        global $rabbit_settings;
        global $water;
        global $libs;
        
        $username = $username->Get();
        $password = $password->Get();
        $finder = New UserFinder();
        $user = $finder->FindByNameAndPassword( $username, $password );
        
        $libs->Load( 'loginattempt' );
        $libs->Load( 'adminpanel/ban' );
        $libs->Load( 'user/profile' );
        
        $loginattempt = New LoginAttempt();
        $loginattempt->Username = $username;
        if ( $user === false ) {
            $loginattempt->Password = $password;
            $loginattempt->Save();
            
            /*if ( LoginAttempt_checkBot( UserIp() ) ) {
              Ban::BanIp( UserIp(), 15*60 );
            }*///TODO<--reconsider this

            return Redirect( '?p=a' );
        }
        $validate = $user->Profile->Emailvalidated;
		$timecreated = strtotime( $user->Created );
		$datecheck = strtotime( '2009-03-21 04:30:00');
        if ( !$validate && $timecreated > $datecheck ) {
            return Redirect( '?p=notvalidated&userid=' . $user->Id  );
        }
        // don't store the password for security reasons
        $loginattempt->Success = 'yes';
        $loginattempt->Save();
        // else...
        $user->UpdateLastLogin();
        $user->RenewAuthtokenIfNeeded();
        $user->Save();
        
        $_SESSION[ 's_userid' ] = $user->Id;
        $_SESSION[ 's_authtoken' ] = $user->Authtoken;
        User_SetCookie( $user->Id, $user->Authtoken );

        if ( isset( $_SESSION[ 'teaser_comment' ] ) ) { // user wrote comment as logged out
            $libs->Load( 'comment' );
            $libs->Load( 'wysiwyg' );

            w_assert( is_array( $_SESSION[ 'teaser_comment' ] ) );
            w_assert( isset( $_SESSION[ 'teaser_comment' ][ 'text' ] ) );
            w_assert( isset( $_SESSION[ 'teaser_comment' ][ 'parentid' ] ) );
            w_assert( isset( $_SESSION[ 'teaser_comment' ][ 'typeid' ] ) );
            w_assert( isset( $_SESSION[ 'teaser_comment' ][ 'itemid' ] ) );

            $text = $_SESSION[ 'teaser_comment' ][ 'text' ];
            $parentid = $_SESSION[ 'teaser_comment' ][ 'parentid' ];
            $typeid = $_SESSION[ 'teaser_comment' ][ 'typeid' ];
            $itemid = $_SESSION[ 'teaser_comment' ][ 'itemid' ];

            $comment = New Comment();
            $text = nl2br( htmlspecialchars( $text ) );
            $text = WYSIWYG_PostProcess( $text );
            $comment->Text = $text;
            $comment->Userid = $user->Id;
            $comment->Parentid = $parent;
            $comment->Typeid = $type;
            $comment->Itemid = $compage;
            $comment->Save();

            unset( $_SESSION[ 'teaser_comment' ] );

            ob_start();
            Element( 'url', $comment );
            $url = ob_get_clean();
            return Redirect( $url );
        }

        return Redirect( $_SERVER[ 'HTTP_REFERER' ]  );
    }
?>
