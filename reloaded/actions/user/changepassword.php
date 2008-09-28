<?php
    function ActionUserChangePassword( tString $newpass1, tString $newpass2, tInteger $uid, tString $oldpassmd5 ) {
        $newpass1 = $newpass1->Get();
        $newpass2 = $newpass2->Get();
        $uid = $uid->Get();
        $oldpassmd5 = $oldpassmd5->Get();

        $user = new User( $uid );
        if( $user->Exists() && $user->Password() == $oldpassmd5 ) {
            if( $newpass1 == $newpass2 ) {
                $user->SetPassword( $newpass1 );
                return Redirect( '?p=chpasswd&uid=' . $uid . '&oldpass=' . $oldpassmd5 . '&error=none' );
            }
            return Redirect( '?p=chpasswd&uid=' . $uid . '&oldpass=' . $oldpassmd5 . '&error=passwords' );
        }
        return Redirect( '?p=chpasswd&uid=' . $uid . '&oldpass=' . $oldpassmd5 . '&error=oldpass' );
    }
?>
