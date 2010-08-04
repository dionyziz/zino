<?php

    class TestUrlMaker extends ModelTestcase {
		protected $mUsers;
		protected $mData;


		public function SetUp() {
            clude( 'models/url.php' );
            clude( 'models/types.php' );
            clude( 'models/user.php' );

			$this->mData = array( 
				array( "ασκηασφ askfhasf ασκakjsfασφaf", "askiasf_askfhasf_askakjsfasfaf" ),
				array( "Mono agglika prepei na epistrefei to idio me underscores","Mono_agglika_prepei_na_epistrefei_to_idio me_underscores" )
			);
        }
        public function TearDown() {

        }
		public function PreConditions() {
        }
        /**
         * @dataProvider GetData
         */
        public function TestUrl_Format( $input, $output ) {		
			$out = URL_Format( $input );
			$this->AssertEquals( $out, $output, "Wrong Conversion" );
		}
		public function GetData() {
			return $this->mData;	
		}
	}
	
	return New TestUrlMaker();
?>
