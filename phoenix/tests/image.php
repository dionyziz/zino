<?php
    class TestImage extends TestCase {
        protected $mAppliesTo = 'libs/image/image';
        private $mImage;
        private $mCount;
        private $mFinder;
        private $mUser;

        public function SetUp() {
            $this->mUser = New User();
            $this->mUser->Name = 'testimage';
            $this->mUser->Subdomain = 'testimage';
            $this->mUser->Save();
        }
        public function TestClassesExist() {
            $this->Assert( class_exists( 'ImageException' ), 'ImageException class does not exist' );
            $this->Assert( class_exists( 'ImageFinder' ), 'ImageFinder class does not exist' );
            $this->Assert( class_exists( 'Image' ), 'Image class does not exist' );
        }
        public function TestMethodsExist() {
            $this->Assert( method_exists( 'Image', 'LoadFromFile' ), 'Method Image->LoadFromFile does not exist' );
            $this->Assert( method_exists( 'Image', 'IsDeleted' ), 'Method Image->IsDeleted does not exist' );
            $this->Assert( method_exists( 'ImageFinder', 'Count' ), 'Method ImageFinder->Count does not exist' );
            $this->Assert( method_exists( 'ImageFinder', 'FindByIds' ), 'Method ImageFinder->FindByIds does not exist' );
            $this->Assert( method_exists( 'ImageFinder', 'FindByAlbum' ), 'Method ImageFinder->FindByAlbum does not exist' );
            $this->Assert( method_exists( 'ImageFinder', 'FindAround' ), 'Method ImageFinder->FindAround does not exist' );
            $this->Assert( method_exists( 'ImageFinder', 'FindFrontpage' ), 'Method ImageFinder->FindFrontpage does not exist' );
        }
        public function TestCount() {
            $this->mFinder = New ImageFinder();

            $this->mCount = $this->mFinder->Count();
            $this->Assert( is_int( $this->mCount ), 'Count images must be an integer' );
            $this->Assert( $this->mCount >= 0, 'Count images must be non-negative' );
        }
        /*
        public function TestUpload() {
            $image = New Image();

            $temp = tempnam( '/tmp', 'excalibur_' );
            
            $im = imagecreatetruecolor( 100, 100 );
            imagefill( $im, 50, 50, imagecolorallocate( $im, 255, 0, 0 ) );

            imagejpeg( $im, $temp );
            
            $image->LoadFromFile( $temp );
            $image->Name = 'test';
            $image->Userid = $this->mUser->Id;

            $this->AssertFalse( $image->Exists(), 'Image must not exist prior to saving it!' );
            try {
                $image->Save();
                $error = false;
            }
            catch ( ImageException $e ) {
                $error = true;
                $message = $e->getMessage();
            }
            $this->AssertFalse( $error, 'Cannot upload photo due to exception: ' . $message );

            unlink( $temp );

            $imageid = $image->Id;
            $this->Assert( $imageid > 0, 'A new image must have a positive id' );
            $this->Assert( $image->Exists(), 'Image does not exist after upload' );

            $image = New Image( $imageid );
            $this->RequireSuccess( 
                $this->Assert( $image->Exists(), 'Could not find the newly created image' )
            );
            $this->AssertEquals( 'test', $image->Name, 'Could not retrieve the name of the uploaded image' );
            $this->AssertEquals( $this->mUser->Id, $image->Userid, 'Could not retrieve the userid of the uploaded image' );

            $this->mImage = $image;
        }
        public function TestCountInc() {
            $this->AssertEquals( $this->mCount + 1, $this->mFinder->Count(), 'Count of images must increment by one when a new image is uploaded' );
        }
        public function TestRename() {
            $this->mImage->Name = 'hohoho';
            $this->AssertEquals( 'hohoho', $this->mImage->Name, 'Was not able to rename image' );
        }
        public function TestFindByUser() {
            $results = $this->mFinder->FindByUser( New User( 1 ), 0, 1 );

            $this->AssertEquals( 1, count( $results ), 'Finder FindByUser must return the image just uploaded, returned nothing' );
            $this->AssertEquals( $this->mImage->Id, $results[ 0 ]->Id, 'Finder FindByUser must return the image just uploaded (in decreasing order by creation time), returned something else' );

        }
        public function TestDelete() {
            $this->AssertFalse( $this->mImage->IsDeleted(), 'Image must not be marked as deleted prior to deleting it' );
            $this->mImage->Delete();
            $this->Assert( $this->mImage->IsDeleted(), 'Image could not be deleted' );
            $image = New Image( $this->mImage->Id );

            $this->Assert( $image->IsDeleted(), 'Was able to lookup deleted image and it is marked as non-deleted' );
        }
        public function TestUndelete() {
            $image = New Image( $this->mImage->Id );
            $this->Assert( $this->mImage->IsDeleted(), 'Image must be deleted prior to undeleting' );
            $image->Undelete();

            $image = New Image( $this->mImage->Id );
            $this->AssertFalse( $this->mImage->IsDeleted(), 'Image must not be deleted after undeleting' );
        }
        */
        public function TestCountDec() {
            $this->AssertEquals( $this->mCount, $this->mFinder->Count(), 'Count of images must decrease by one when an image is deleted' );
        }
        public function TearDown() {
            $this->mUser->Delete();
        }
    }

    return New TestImage();
?>
