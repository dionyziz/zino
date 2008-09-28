<?php
	final class Libs {
		private $mLoaded;
		
		public function CountLoaded() {
			return count( $this->mLoaded );
		}
		public function Load( $which ) {
			global $water;
			
			if ( !isset( $this->mLoaded[ $which ] ) || !$this->mLoaded[ $which ] ) {
				if ( $this->ValidatePath( $which ) ) {
					$this->mLoaded[ $which ] = true;
					$this->LoadNotify( $which );
					$this->Profile( $which );
                    ob_start();
					$ret = Rabbit_Include( "libs/$which" );
                    $out = ob_get_clean();
                    if ( strlen( $out ) ) {
                        $water->Warning( 'Non-functional library output: ' . $which );
                    }
					$this->ProfileEnd();
					return $ret;
				}
			}
			return false;
		}
		private function ValidatePath( $path ) {
			w_assert( strpos( $path , '..' ) === false );
			w_assert( strtolower( substr( $path , -4 ) ) != '.php' );

			return true;
		}
		private function Profile( $path ) {
			global $water;
			
			$water->Profile( "Loading library $path" );
		}
		private function ProfileEnd() {
			global $water;
			
			$water->ProfileEnd();
		}
		private function LoadNotify( $path ) {
			global $water;
			
			$water->Trace( "Loading library $path" );
		}
		public function Libs() {
			$this->mLoaded = array();
		}
	}
	
	return New Libs(); // singleton
?>
