<?php
    abstract class Element {
        public final function __construct() {
        }
    }

    final class Elemental {
        private $mLastElement;
        private $mIncluded;
        private $mSettings;
        private $mWater;
        private $mMainReq;
        private $mMasterCall;
        private $mMasterElementAlias;
        
        public function Elemental() {
            global $water;
            
            $this->mWater = $water;
            $this->mMasterCall = false;
            $this->SetSetting( 'production', true );
        }
        public function SetSetting( $setting, $value ) {
            switch ( $setting ) {
                case 'production':
                    w_assert( is_bool( $value ) );
                    $this->mSettings[ $setting ] = $value;
                    break;
                default:
                    throw New Exception( 'Invalid Elemental setting' , $setting );
            }
        }
        public function IncludeFile( $elementpath ) {
            w_assert( is_string( $elementpath ) && strlen( $elementpath ) );
            $elementpath = strtolower( $elementpath );
            $this->mLastElement = $elementpath;
            if ( !isset( $this->mIncluded[ $elementpath ] ) ) {
                ob_start();
                $ret = Rabbit_Include( 'elements/' . $elementpath ); // throws RabbitIncludeException
                $out = ob_get_clean();
                
                if ( strlen( $out ) ) {
                    throw New Exception( 'Non-functional element output: ' . $elementpath );
                }
                
                $this->mIncluded[ $elementpath ] = true;
            }
            if ( !$this->mIncluded[ $elementpath ] ) {
                throw New Exception( 'Element doesn\'t exist: ' . $elementpath );
            }
            return true;
        }
        public function GetClass( $elementpath ) {
            $this->IncludeFile( $elementpath );
            $classname = 'Element' . str_replace( '/' , '' , $elementpath );
            if ( class_exists( $classname ) ) {
                return $classname;
            }
            $classes = get_declared_classes();
            throw New Exception( 'Element class not defined for element ' . $elementpath . '; expected class "' . $classname . '" (last defined: "' . $classes[ count( $classes ) - 1 ] . '")' );
        }
        public function Element( /* $elementpath , $arg1 , $arg2 , $arg3 , ... , $argN */ ) {
            w_assert( func_num_args() );
            $args = func_get_args();
            $elementpath = array_shift( $args );
            $classname = $this->GetClass( $elementpath );
            if ( $classname === false ) {
                return false;
            }
            $this->mWater->Profile( 'Render Element ' . $elementpath );
            $element = New $classname();
            $ret = call_user_func_array( array( $element, 'Render' ), $args );
            $this->mWater->ProfileEnd();
            
            return $ret;
        }
        public function MasterElement() {
            $this->mMasterCall = true;
            
            $pagesmap = Project_PagesMap(); // Gets an array with the actual filenames on the server
            
            if ( !isset( $pagesmap[ $this->mMasterElementAlias ] ) ) {
                throw New Exception( 'Requested master element alias is not defined in pagesmap: ' . $this->mMasterElementAlias );
            }
            
            $master = $pagesmap[ $this->mMasterElementAlias ];
            if ( is_array( $master ) ) {
                w_assert( isset( $master[ 'master' ] ) );
                $elementid = $master[ 'master' ];
            }
            else {
                $elementid = $master;
            }
            
            $classname = $this->GetClass( $elementid );
            $element = New $classname();
            $this->mWater->Profile( 'Render Master Element ' . $elementid );
            $ret = Rabbit_TypeSafe_Call( array( $element, 'Render' ), $this->mMainReq );
            $this->mWater->ProfileEnd();
            
            if ( $ret === false ) { // boolean `false' should only be returned when the element does not exist
                return 0;
            }
            
            return $ret;
        }
        public function MainElement( $which, $req ) {
            if ( !isset( $req[ 'p' ] ) ) {
                $req[ 'p' ] = '';
            }
            
            $this->mMasterElementAlias = $req[ 'p' ];

            unset( $req[ 'p' ] );

            $this->mMainReq = $req;
            $ret = $this->Element( $which ); // this function call should set ->mMasterCall to true
            if ( !$this->mMasterCall ) {
                $this->mWater->Warning( 'Main element did not invoke a MasterElement call; please modify your main element to invoke MasterElement() at your desired location' );
            }
            
            return $ret;
        }
    }

    function MasterElement() { // MAGICal
        global $elemental;
        
        return $elemental->MasterElement();
    }
    
    function Element( /* $elementpath , $arg1 , $arg2 , $arg3 , ... , $argN */ ) {
        global $elemental;
        
        $args = func_get_args();
        return call_user_func_array( array( $elemental , 'Element' ) , $args );
    }
    
    return new Elemental();
?>
