<?
    class ControllerPassword {
        public static function Update( $oldpassword, $newpassword ) {
            $success = false;
            if ( isset( $_SESSION[ 'user' ] ) ) {                
                clude( 'models/user.php' );
                $userid = $_SESSION[ 'user' ][ 'id' ];
                $success = User::SetPassword( $id, $oldpassword, $newpassword );
            }            
            include 'views/password/update';
        }
    }
?>
