<?php
    class ControllerSession {
        public static function View() {
            if ( isset( $_SESSION[ 'user' ] ) ) {
                $user = $_SESSION[ 'user' ];
            }
            else {
                $user = false;
            }
            include 'views/session/view.php';
        }
        public static function Create( $username = '', $password = '') {
            clude( 'models/db.php' );
            clude( 'models/user.php' );
            $data = User::Login( $username, $password );
            $success = $data !== false;
            if ( $success ) {
                global $settings;
                $eofw = 2147483646;
                if ( $data[ 'authtoken' ] == '' ) {
                    $data[ 'authtoken' ] = User::RenewAuthtoken( $data[ 'id' ] );
                }
                $cookie = $data[ 'id' ] . ':' . $data[ 'authtoken' ];
                setcookie( $settings[ 'cookiename' ], $cookie, $eofw, '/', $settings[ 'cookiedomain' ], false, true );
                $_SESSION[ 'user' ] = $data;
            }
			else {
				$data2 = User::ItemByName( $username );
				if ( !empty( $data2 ) ) {
					$cause = "PASS";
				}
				else {
					$cause = "NAME";
				}
			}
			$name = ( string ) $username;
            include 'views/session/create.php';
        }
        public static function Delete() {
            global $settings;
            setcookie( $settings[ 'cookiename' ], '', time() - 86400, '/', $settings[ 'cookiedomain' ], false, true );
            clude( 'models/user.php' );
            User::ClearAuthtoken( $_SESSION[ 'user' ][ 'id' ] );
            unset( $_SESSION[ 'user' ] );
            $success = true;
            include 'views/session/delete.php';
        }
    }
?>
