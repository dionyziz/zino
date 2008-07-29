<?php
	class TestPhotoTag extends Testcase {
        protected $mAppliesTo = 'libs/image/tag';
        private $mImage;

        public function SetUp() {
            $this->mImage = New Image();
        }
	    public function TestClassesExist() {
            $this->Assert( class_exists( 'ImageTag' ), 'ImageTag class does not exist' );
        }
        public function TestTagCreation() {
        }
	}	
?>
