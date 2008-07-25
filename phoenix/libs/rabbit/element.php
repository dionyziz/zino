<?php
    abstract class Element {
        public final function __construct() {
        }
    }

    final class Elemental {
        private static $mIncluded;
        private static $mSettings = array( 'production' => true );
        private static $mMainReq;
        private static $mMasterCall = false;
        private static $mMasterElementAlias;
        
        static public function SetSetting( $setting, $value ) {
            switch ( $setting ) {
                case 'production':
                    w_assert( is_bool( $value ) );
                    self::$mSettings[ $setting ] = $value;
                    break;
                default:
                    throw New Exception( 'Invalid Elemental setting' , $setting );
            }
        }
        static public function GetPersistentElementSignificantArgs( $path ) {
            global $mc;
            global $water;

            if ( $path != 'main' ) {
                die( $path );
            }
            $persistent = $mc->get( 'persistentelements' );
            if ( is_array( $persistent ) ) {
                if ( isset( $persistent[ $path ] ) ) {
                    return $persistent[ $path ];
                }
                // else fallthrough to include the file
            }
            self::IncludeFile( $path );
        }
        static public function EncodeArguments( $args ) {
            return md5( serialize( $args ) );
        }
        static public function LoadFromCache( $elementpath, $args ) {
            global $mc;

            // retrieve positions of significant arguments
            $significant = self::GetPersistentElementSignificantArgs( $elementpath );
            if ( $significant === false ) { // not a persistent element
                return false;
            }
            // it's a persistent element, check cache
            $params = array(); // a list of the values of the significant arguments, in order
            foreach ( $significant as $pos ) {
                w_assert( is_int( $pos ) );
                w_assert( isset( $args[ $pos ] ) );
                $params[] = $args[ $pos ];
            }
            $sig = self::EncodeArguments( $params ); // retrieve invokation signature (string)
            $ret = $mc->get( $elementpath . ':' . $sig );
            if ( $ret === false ) {
                // not cached
                return false;
            }
            w_assert( is_array( $ret ) );
            w_assert( count( $ret ) == 2 ); // echoed value + return value
            // cached, echo its cached data
            echo $ret[ 0 ];
            // return its cached data (usually empty)
            return $ret[ 1 ];
        }
        static public function IncludeFile( $elementpath ) {
            w_assert( is_string( $elementpath ) && strlen( $elementpath ) );
            $elementpath = strtolower( $elementpath );
            if ( !isset( self::$mIncluded[ $elementpath ] ) ) {
                ob_start();
                $ret = Rabbit_Include( 'elements/' . $elementpath ); // throws RabbitIncludeException
                $out = ob_get_clean();
                
                if ( strlen( $out ) ) {
                    throw New Exception( 'Non-functional element output: ' . $elementpath );
                }
                
                self::$mIncluded[ $elementpath ] = true;
            }
            if ( !self::$mIncluded[ $elementpath ] ) {
                throw New Exception( 'Element doesn\'t exist: ' . $elementpath );
            }
            return true;
        }
        static public function GetClass( $elementpath ) {
            $classname = 'Element' . str_replace( '/' , '' , $elementpath );
            if ( class_exists( $classname ) ) {
                return $classname;
            }
            $classes = get_declared_classes();
            throw New Exception( 'Element class not defined for element ' . $elementpath . '; expected class "' . $classname . '" (last defined: "' . $classes[ count( $classes ) - 1 ] . '")' );
        }
        static public function Element( /* $elementpath , $arg1 , $arg2 , $arg3 , ... , $argN */ ) {
            global $water;

            w_assert( func_num_args() );
            $args = func_get_args();
            $elementpath = array_shift( $args );
            if ( $elementpath != 'main' ) {
                die( 'Element::' . $elementpath );
            }
            if ( ( $ret = self::LoadFromCache( $elementpath, $args ) ) !== false ) {
                return $ret;
            }
            $classname = self::GetClass( $elementpath );
            if ( $classname === false ) {
                return false;
            }
            $water->Profile( 'Render Element ' . $elementpath );
            $element = New $classname();
            $ret = call_user_func_array( array( $element, 'Render' ), $args );
            $water->ProfileEnd();
            
            return $ret;
        }
        static public function MasterElement() {
            global $water;

            self::$mMasterCall = true;
            
            $pagesmap = Project_PagesMap(); // Gets an array with the actual filenames on the server
            
            if ( !isset( $pagesmap[ self::$mMasterElementAlias ] ) ) {
                throw New Exception( 'Requested master element alias is not defined in pagesmap: ' . self::$mMasterElementAlias );
            }
            
            $master = $pagesmap[ self::$mMasterElementAlias ];
            if ( is_array( $master ) ) {
                w_assert( isset( $master[ 'master' ] ) );
                $elementid = $master[ 'master' ];
            }
            else {
                $elementid = $master;
            }
            
            self::IncludeFile( $elementid );
            $classname = self::GetClass( $elementid );
            $element = New $classname();
            $water->Profile( 'Render Master Element ' . $elementid );
            $ret = Rabbit_TypeSafe_Call( array( $element, 'Render' ), self::$mMainReq );
            $water->ProfileEnd();
            
            if ( $ret === false ) { // boolean `false' should only be returned when the element does not exist
                return 0;
            }
            
            return $ret;
        }
        static public function MainElement( $which, $req ) {
            global $water;

            if ( !isset( $req[ 'p' ] ) ) {
                $req[ 'p' ] = '';
            }
            
            self::$mMasterElementAlias = $req[ 'p' ];

            unset( $req[ 'p' ] );

            self::$mMainReq = $req;
            $ret = self::Element( $which ); // this function call should set ->mMasterCall to true
            if ( !self::$mMasterCall ) {
                $water->Warning( 'Main element did not invoke a MasterElement call; please modify your main element to invoke MasterElement() at your desired location' );
            }
            
            return $ret;
        }
    }

    function MasterElement() { // MAGICal
        Elemental::MasterElement();
    }
    
    function Element( /* $elementpath , $arg1 , $arg2 , $arg3 , ... , $argN */ ) {
        $args = func_get_args(); // can't pass func_get_args() as a parameter without priorly assigning it to a variable
        return call_user_func_array( array( 'Elemental', 'Element' ), $args );
    }
?>
