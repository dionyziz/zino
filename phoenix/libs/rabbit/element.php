<?php
    abstract class Element {
        // static members
        private static $mIncluded;
        private static $mSettings = array( 'production' => true );
        private static $mMainReq;
        private static $mMasterCall = false;
        private static $mMasterElementAlias;
        private static $mPersistentElements = false; // array of persistent element paths => array of significant argument positions
        // non-static members
        protected $mPersistent = false;
        protected $mPath = '';
        
        // static methods
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
            global $rabbit_settings;

            if ( self::$mPersistentElements === false ) {
                self::$mPersistentElements = $mc->get( 'persistentelements:' . $rabbit_settings[ 'elementschemaversion' ] );
                if ( !is_array( self::$mPersistentElements ) ) {
                    self::$mPersistentElements = array();
                }
            }
            if ( isset( self::$mPersistentElements[ $path ] ) ) {
                return self::$mPersistentElements[ $path ][ 'args' ];
            }
            // else fallthrough
            return false;
        }
        static public function GetPersistentElementMtime( $path = false ) {
            global $mc;
            global $rabbit_settings;

            if ( self::$mPersistentElements === false ) {
                self::$mPersistentElements = $mc->get( 'persistentelements:' . $rabbit_settings[ 'elementschemaversion' ] );
                if ( !is_array( self::$mPersistentElements ) ) {
                    self::$mPersistentElements = array();
                }
            }
            if ( isset( self::$mPersistentElements[ $path ] ) ) {
                return self::$mPersistentElements[ $path ][ 'mtime' ];
            }
            // else fallthrough
            return false;
        }
        static public function SetPersistentElementSignificantArgs( $path, $args ) {
            global $mc;
            global $rabbit_settings;

            self::$mPersistentElements[ $path ][ 'args' ] = $args;
            $mc->set( 'persistentelements:' . $rabbit_settings[ 'elementschemaversion' ], self::$mPersistentElements );
        }
        static public function SetPersistentElementMtime( $path, $mtime ) {
            global $mc;
            global $rabbit_settings;

            self::$mPersistentElements[ $path ][ 'mtime' ] = $mtime;
            $mc->set( 'persistentelements:' . $rabbit_settings[ 'elementschemaversion' ], self::$mPersistentElements );
        }
        static public function EncodeArguments( $args ) {
            return md5( serialize( $args ) );
        }
        static public function LoadFromCache( $elementpath, $args ) {
            global $mc;
            global $water;

            // retrieve positions of significant arguments
            $significant = self::GetPersistentElementSignificantArgs( $elementpath );
            if ( $significant === false ) { // not a persistent element
                return false;
            }
            $mtime = self::GetPersistentElementMtime( $elementpath );
            // it's a persistent element, check cache
            $params = array(); // a list of the values of the significant arguments, in order
            foreach ( $significant as $pos ) {
                w_assert( is_int( $pos ) );
                w_assert( isset( $args[ $pos ] ), 'Persistent element significant argument must be defined; not passed for argument ' . $pos . ' of element `' . $elementpath . '\'' );
                $params[] = $args[ $pos ];
                w_assert( is_scalar( $args[ $pos ] ), 'Persistent element significant argument must be scalar; ' . gettype( $args[ $pos ] ) . ' given for argument ' . $pos . ' of element `' . $elementpath . '\'' );
            }
            $sig = self::EncodeArguments( $params ); // retrieve invokation signature (string)
            $ret = $mc->get( 'persistent:' . $elementpath . ':' . $sig . ':' . $mtime );
            if ( $ret === false ) {
                $water->Trace( 'Persistent element MISS: ' . $elementpath . ' ( "' . implode( '", "', $params ) . '" )' );
                // not cached
                return false;
            }
            $water->Trace( 'Persistent element HIT: ' . $elementpath . ' ( "' . implode( '", "', $params ) . '" )' );
            w_assert( is_array( $ret ) );
            w_assert( count( $ret ) == 2 ); // echoed value + return value
            // cached, echo its cached data
            echo $ret[ 0 ];
            // return its cached data (usually empty)
            return $ret[ 1 ];
        }
        static public function ClearFromCache( /* $elementpath, $param1, $param2, ... */ ) {
            global $mc;
            global $water;

            $args = func_get_args();
            $elementpath = array_shift( $args );

            $mtime = self::GetPersistentElementMtime( $elementpath );
            // it's a persistent element, check cache
            foreach ( $args as $i => $arg ) {
                w_assert( is_scalar( $arg ), 'Persistent element significant argument must be scalar; ' . gettype( $arg ) . ' given for significant argument ' . $pos . ' of element `' . $elementpath . '\' when clearing cache' );
            }
            $sig = self::EncodeArguments( $args ); // retrieve invokation signature (string)
            $mc->delete( 'persistent:' . $elementpath . ':' . $sig . ':' . $mtime );
            $water->Trace( 'Persistent element CLEAR: ' . $elementpath . ' ( "' . implode( '", "', $args ) . '" )' );
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
        // fires an element
        static public function Fire( /* $elementpath , $arg1 , $arg2 , $arg3 , ... , $argN */ ) {
            global $water;
            global $mc;

            w_assert( func_num_args() );
            $args = func_get_args();
            $elementpath = array_shift( $args );
            if ( ( $ret = self::LoadFromCache( $elementpath, $args ) ) !== false ) {
                return $ret;
            }
            self::IncludeFile( $elementpath );
            $classname = self::GetClass( $elementpath );
            if ( $classname === false ) {
                return false;
            }
            $water->Profile( 'Render Element ' . $elementpath );
            $element = New $classname( $elementpath );
            ob_start();
            $ret = call_user_func_array( array( $element, 'Render' ), $args );
            $echo = ob_get_clean();
            if ( $element->IsPersistent() ) {
                // cache the result
                $sig = self::EncodeArguments( $element->GetSignificantArgs( $args ) );
                $mtime = self::GetPersistentElementMtime( $elementpath );
                $mc->set( 'persistent:' . $elementpath . ':' . $sig . ':' . $mtime, array( $echo, $ret ) );
            }
            echo $echo;
            $water->ProfileEnd();
            
            return $ret;
        }
        static public function MasterElement() {
            global $water;

            self::$mMasterCall = true;
            
            $pagesmap = Project_PagesMap(); // Gets an array with the actual filenames on the server
            
            if ( !isset( $pagesmap[ self::$mMasterElementAlias ] ) ) {
                //throw New Exception( 'Requested master element alias is not defined in pagesmap: ' . self::$mMasterElementAlias );
                return array( false, false );
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
            $element = New $classname( $elementid );
            $water->Profile( 'Render Master Element ' . $elementid );
            $ret = Rabbit_TypeSafe_Call( array( $element, 'Render' ), self::$mMainReq );
            $water->ProfileEnd();
            
            if ( $ret === false ) { // boolean `false' should only be returned when the element does not exist
                return array( 0, $elementid );
            }
            
            return array( $ret, $elementid );
        }
        static public function MainElement( $which, $req ) {
            global $water;

            if ( !isset( $req[ 'p' ] ) ) {
                $req[ 'p' ] = '';
            }
            
            self::$mMasterElementAlias = $req[ 'p' ];

            $water->SetPageURL( $_SERVER[ 'PHP_SELF' ] . ' - ' . self::$mMasterElementAlias );

            unset( $req[ 'p' ] );

            self::$mMainReq = $req;
            $ret = self::Fire( $which ); // this function call should set ->mMasterCall to true
            if ( !self::$mMasterCall ) {
                $water->Warning( 'Main element did not invoke a MasterElement call; please modify your main element to invoke MasterElement() at your desired location' );
            }
            
            return $ret;
        }
        // 
        // non-static methods...
        //
        public function IsPersistent() {
            return $this->mPersistent !== false;
        }
        public function GetSignificantArgs( $args ) {
            global $mc;
            global $rabbit_settings;

            w_assert( is_array( $this->mPersistent ) );

            $me = New ReflectionClass( get_class( $this ) );
            $render = $me->getMethod( 'Render' );
            $params = $render->getParameters();

            $significant = Element::GetPersistentElementSignificantArgs( $this->mPath );
            if ( $significant === false ) {
                // this part is called only once for each persistent element
                // to cache the function signature and file mtime
                // (it is ~not~ called again for a different permutation of the significant arguments)
                $i = 0;
                $j = 0;
                $ret = array();
                if ( count( $this->mPersistent ) ) {
                    foreach ( $params as $param ) {
                        if ( $this->mPersistent[ $i ] == $param->getName() ) {
                            $ret[] = $j;
                            ++$i;
                            if ( $i == count( $this->mPersistent ) ) {
                                break;
                            }
                        }
                        ++$j;
                    }
                }
                // w_assert( $i == count( $this->mPersistent ), 'Arguments in mPersistent do not match the element\'s argument list' );
                if ( $i != count( $this->mPersistent ) ) {
                    throw New Exception( 'Persistent element significant arguments do not match the arguments of the element: ' . $this->mPath );
                }
                Element::SetPersistentElementSignificantArgs( $this->mPath, $ret );
                
                $maskinfo = Mask( 'elements/' . $this->mPath, !$rabbit_settings[ 'production' ] );

                Element::SetPersistentElementMtime( $this->mPath, filemtime( $maskinfo[ 'realpath' ] ) );
                w_assert( strlen( $this->mPath ) < 73, 'Persistent element paths must be less than 73 characters long; "' . $this->mPath . '" exceeds this limit' );
                $significant = Element::GetPersistentElementSignificantArgs( $this->mPath );
            }
            $ret = array();
            foreach ( $significant as $i ) {
                $ret[] = $args[ $i ];
            }

            return $ret;
        }
        public final function __construct( $path ) {
            $this->mPath = $path;
        }
    }

    function Element( /* $elementpath , $arg1 , $arg2 , $arg3 , ... , $argN */ ) {
        $args = func_get_args(); // can't pass func_get_args() as a parameter without priorly assigning it to a variable
        return call_user_func_array( array( 'Element', 'Fire' ), $args );
    }
?>
