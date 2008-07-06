<?php
    class TestImage extends TestCase {
        protected $mAppliesTo = 'libs/image/image';
        private $mImages;
        private $mCount;
        private $mFinder;
        private $mUser;
        private $mAlbum;

        public function SetUp() {
            $this->mUser = New User();
            $this->mUser->Name = 'testimage';
            $this->mUser->Subdomain = 'testimage';
            $this->mUser->Save();

            $this->mImages = array();

            $this->mAlbum = New Album();
            $this->mAlbum->Userid = $this->mUser->Id;
            $this->mAlbum->Save();
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
            $this->Assert( method_exists( 'ImageFinder', 'FindFrontpageLatest' ), 'Method ImageFinder->FindFrontpageLatest does not exist' );
        }
        public function TestCount() {
            $this->mFinder = New ImageFinder();

            $this->mCount = $this->mFinder->Count();
            $this->Assert( is_int( $this->mCount ), 'Count images must be an integer' );
            $this->Assert( $this->mCount >= 0, 'Count images must be non-negative' );
        }
        public function TestUpload() {
            $temp = tempnam( '/tmp', 'excalibur_' );
            
            $im = imagecreatetruecolor( 100, 100 );
            imagefill( $im, 50, 50, imagecolorallocate( $im, 255, 0, 0 ) );

            imagejpeg( $im, $temp );
            w_assert( file_exists( $temp ), 'Failed to create temporary file `' . $temp . '\'' );
            
            $image = New Image();
            $image->LoadFromFile( $temp );
            $image->Name = 'test';
            $image->Userid = $this->mUser->Id;

            $this->AssertFalse( $image->Exists(), 'Image must not exist prior to saving it!' );
            try {
                $image->Save();
                $error = false;
                $message = ''; 
            }
            catch ( ImageException $e ) {
                $error = true;
                $message = $e->getMessage();
            }
            $this->AssertFalse( $error, 'Cannot upload photo due to exception: ' . $message );

            $imageid = $image->Id;
            $this->Assert( $imageid > 0, 'A new image must have a positive id' );
            $this->Assert( $image->Exists(), 'Image does not exist after upload' );

            $image = New Image( $imageid );
            $this->RequireSuccess( 
                $this->Assert( $image->Exists(), 'Could not find the newly created image' )
            );
            $this->AssertEquals( 'test', $image->Name, 'Could not retrieve the name of the uploaded image' );
            $this->AssertEquals( $this->mUser->Id, $image->Userid, 'Could not retrieve the userid of the uploaded image' );

            $this->mImages[] = $image;

            for ( $i = 0; $i < 5; ++$i ) {
                $image = New Image();
                $image->LoadFromFile( $temp );
                $image->Name = 'test' . $i;
                $image->Userid = $this->mUser->Id;
                if ( $i == 0 ) {
                    $image->Albumid = $this->mUser->Egoalbumid;
                }
                else {
                    $image->Albumid = $this->mAlbum->Id;
                }
                $image->Save();

                $this->mImages[] = $image;
            }

            unlink( $temp );
        }
        public function TestCountInc() {
            $this->AssertEquals( $this->mCount + 6, $this->mFinder->Count(), 'Count of images must increment when a new images are uploaded' );
        }
        public function TestRename() {
            $this->mImages[ 0 ]->Name = 'hohoho';
            $this->AssertEquals( 'hohoho', $this->mImages[ 0 ]->Name, 'Was not able to rename image' );
        }
        public function TestFindByUser() {
            $results = $this->mFinder->FindByUser( $this->mUser, 0, 1 );

            $this->AssertEquals( 1, count( $results ), 'Finder FindByUser must return the image just uploaded, returned nothing' );
            $this->AssertEquals( $this->mImages[ 5 ]->Id, $results[ 0 ]->Id, 'Finder FindByUser must return the image just uploaded (in decreasing order by creation time), returned something else' );

        }
        public function TestFindByIds() {
            $results = $this->mFinder->FindByIds( array( -1, $this->mImages[ 0 ]->Id, -129 ) );

            $this->AssertEquals( 1, count( $results ), 'Finder FindById must return the image referred to, returned nothing' );
            $this->AssertEquals( $this->mImages[ 0 ]->Id, $results[ 0 ]->Id, 'Finder FindByIds must return the image referred, returned something else' );
        }
        public function TestFindByAlbum() {
            $results = $this->mFinder->FindByAlbum( $this->mAlbum );

            $this->AssertEquals( 4, count( $results ), '4 images exists in this album, FindByAlbum says something else' );
            $this->AssertEquals( $this->mImages[ 5 ]->Id, $results[ 0 ]->Id, 'Incorrect Id returned by FindByAlbum' );
        }
        public function TestFindAround() {
            $results = $this->mFinder->FindAround( $this->mImages[ 3 ], 3 );

            $this->AssertEquals( 3, count( $results ), 'FindAround did not return 3 results as expected' );
            $this->Assert( $this->mImages[ 2 ]->Id, $results[ 0 ]->Id, 'FindAround did not return the expected images (0)' );
            $this->Assert( $this->mImages[ 3 ]->Id, $results[ 1 ]->Id, 'FindAround did not return the expected images (0)' );
            $this->Assert( $this->mImages[ 4 ]->Id, $results[ 2 ]->Id, 'FindAround did not return the expected images (0)' );
        }
        public function TsetFindFrontpage() {
            $results = $this->mFinder->FindFrontpageLatest( 0, 1 );

            $this->AssertEquals( 1, count( $results ), 'FindFrontpageLatest did not return 1 result as expected' );
            $this->Assert( $this->mImages[ 1 ]->Id, $results[ 0 ]->Id, 'FindFrontpageLatest did not return the expected image' );

            $this->mImages[ 1 ]->Delete();
        
            $results = $this->mFinder->FindFrontpageLatest( 0, 1 );
            $this->Assert( empty( $results ) || $results[ 0 ]->Id != $this->mImages[ 1 ]->Id, 'FindFrontpageLatest did not remove image when it was deleted' );
        }
        public function TestDelete() {
            $this->AssertFalse( $this->mImages[ 0 ]->IsDeleted(), 'Image must not be marked as deleted prior to deleting it' );
            $this->mImages[ 0 ]->Delete();
            $this->Assert( $this->mImages[ 0 ]->IsDeleted(), 'Image could not be deleted' );
            $image = New Image( $this->mImages[ 0 ]->Id );

            $this->Assert( $image->IsDeleted(), 'Was able to lookup deleted image and it is marked as non-deleted' );
            $this->Assert( $image->Exists(), 'Image must be marked as existing but deleted after deletion' );
        }
        public function TestCountDec() {
            $this->AssertEquals( $this->mCount + 5, $this->mFinder->Count(), 'Count of images must decrease when an image is deleted' );
        }
        public function TestUndelete() {
            $image = New Image( $this->mImages[ 0 ]->Id );
            $this->Assert( $image->Exists(), 'Image must be marked as existing although deleted, prior to undeleting' );
            $this->Assert( $image->IsDeleted(), 'Image must be deleted prior to undeleting' );
            $image->Undelete();

            $image = New Image( $this->mImages[ 0 ]->Id );
            $this->AssertFalse( $image->IsDeleted(), 'Image must not be deleted after undeleting' );
        }
        public function TearDown() {
            $this->mUser->Delete();

            foreach ( $this->mImages as $image ) {
                if ( !$image->IsDeleted() ) {
                    $image->Delete();
                }
            }
        }
    }

    return New TestImage();
?>
