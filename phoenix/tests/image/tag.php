<?php
	class TestImageTag extends Testcase {
        protected $mAppliesTo = 'libs/image/tag';
        private $mImage;
        private $mUser;
        private $mUser2;
        private $mTag1;
        private $mTag2;

        public function SetUp() {
            $this->mUser = New User();
            $this->mUser->Name = 'testimagetags';
            $this->mUser->Subdomain = 'testimagetags';
            $this->mUser->Save();
            
            $this->mUser2 = New User();
            $this->mUser2->Name = 'testimagetags2';
            $this->mUser2->Subdomain = 'testimagetags2';
            $this->mUser2->Save();

            $this->mImage = New Image();
            $temp = tempnam( '/tmp', 'excalibur_' );
            $im = imagecreatetruecolor( 100, 100 );
            imagefill( $im, 50, 50, imagecolorallocate( $im, 255, 0, 0 ) );
            imagejpeg( $im, $temp );
            $this->mImage->LoadFromFile( $temp );
            $this->mImage->Name = 'test';
            $this->mImage->Userid = $this->mUser->Id;
            $this->mImage->Save();
        }
	    public function TestClassesExist() {
            $this->Assert( class_exists( 'ImageTag' ), 'ImageTag class does not exist' );
            $this->Assert( class_exists( 'ImageTagFinder' ), 'ImageTagFinder class does not exist' );
        }
        public function TestTagCreation() {
            $this->mTag1 = New ImageTag(); 
            $this->mTag1->X = 20;
            $this->mTag1->Y = 21;
            $this->mTag1->Imageid = $this->mImage->Id;
            $this->mTag1->Ownerid = $this->mUser->Id;
            $this->mTag1->Personid = $this->mUser->Id;
            $this->mTag1->Save();

            $this->mTag2 = New ImageTag(); 
            $this->mTag2->X = 50;
            $this->mTag2->Y = 60;
            $this->mTag2->Imageid = $this->mImage->Id;
            $this->mTag2->Ownerid = $this->mUser->Id;
            $this->mTag2->Personid = $this->mUser2->Id;
            $this->mTag2->Save();

            $this->Assert( $this->mTag1->Exists(), 'Failed to create tag 1' );
            $this->Assert( $this->mTag2->Exists(), 'Failed to create tag 2' );
        }
        public function TestFindByImage() {
            $finder = New ImageTagFinder();
            $tags = $finder->FindByImage( $this->mImage );
            $this->AssertEquals( 2, count( $tags ), 'Image must have two tags' );
            $i = 0;
            foreach ( $tags as $tag ) {
                switch ( $i ) {
                    case 0:
                        $this->Assert( $tag->Exists(), 'Tag1 found, but does not exist' );
                        $this->AssertEquals( $this->mUser->Id, $tag->Personid, 'Tag1 has wrong personid' );   
                        $this->AssertEquals( $this->mUser->Id, $tag->Ownerid, 'Tag1 has wrong ownerid' );   
                        $this->AssertEquals( $this->mImage->Id, $tag->Imageid, 'Tag1 has wrong imageid' );
                        $this->AssertEquals( 20, $tag->X, 'Tag1 X is wrong' );
                        $this->AssertEquals( 21, $tag->Y, 'Tag1 Y is wrong' );
                        break;
                    case 1:
                        $this->Assert( $tag->Exists(), 'Tag2 found, but does not exist' );
                        $this->AssertEquals( $this->mUser2->Id, $tag->Personid, 'Tag2 has wrong personid' );   
                        $this->AssertEquals( $this->mUser->Id, $tag->Ownerid, 'Tag2 has wrong ownerid' );   
                        $this->AssertEquals( $this->mImage->Id, $tag->Imageid, 'Tag2 has wrong imageid' );
                        $this->AssertEquals( 50, $tag->X, 'Tag1 X is wrong' );
                        $this->AssertEquals( 60, $tag->Y, 'Tag1 Y is wrong' );
                        break;
                }
                ++$i;
            }
        }
        public function TestTagDeletion() {
            $this->mTag1->Delete();
            $this->mTag2->Delete();

            $tag1 = New ImageTag( $this->mTag1->Id );
            $tag2 = New ImageTag( $this->mTag2->Id );

            $this->AssertFalse( $tag1->Exists(), 'Tag1 exists after deletion' );
            $this->AssertFalse( $tag2->Exists(), 'Tag2 exists after deletion' );
        }
        public function TearDown() {
            $this->mImage->Delete();
            $this->mUser->Delete();
            $this->mUser2->Delete();
        }
	}

    return New TestImageTag();
?>
