<?php
    class TestAlbum extends TestCase {
        protected $mAppliesTo = 'libs/album';
        private $mAlbums;
        private $mUser;
        private $mUser2;
        private $mImage;

        public function SetUp() {
            $this->mUser = New User();
            $this->mUser->Username = 'testalbum';
            $this->mUser->Subdomain = 'testalbum';
            $this->mUser->Save();

            $this->mUser2 = New User();
            $this->mUser2->Username = 'testalbum2';
            $this->mUser2->Subdomain = 'testalbum2';
            $this->mUser2->Save();

            $this->mAlbums = array();

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
            $this->RequireSuccess(
                $this->Assert( class_exists( 'Album' ), 'Class Album does not exist' )
            );
            $this->RequireSuccess(
                $this->Assert( class_exists( 'AlbumFinder' ), 'Class AlbumFinder does not exist' )
            );
        }
        public function TestCreation() {
            $album = New Album();
            $album->Name = 'Example';
            $album->Userid = $this->mUser->Id;
            $album->Save();
            
            $album = New Album( $album->Id );
            
            $this->Assert( $album->Exists, 'Could not look up the newly created album' );
            $this->AssertEquals( 'Example', $album->Name, 'Could not create an album with the given name' );

            $this->mAlbums[] = $album;

            $album = New Album();
            $album->Name = 'Forsaken';
            $album->Userid = $this->mUser->Id;
            $album->Save();

            $this->mAlbums[] = $album;
        }
        public function TestFinder() {
            $finder = New AlbumFinder();

            $albums = $finder->FindByUser( $this->mUser );

            $this->Assert( is_array( $albums ), 'AlbumFinder did not return an array' );
            $this->AssertEquals( 2, count( $albums ), 'This user has two albums' );
            $i = 0;
            foreach ( $albums as $album ) {
                $this->Assert( $album instanceof Album, 'AlbumFinder did not return an array of Album instances' );

                switch ( $i ) {
                    case 0:
                        $this->Assert( $this->mAlbums[ 1 ]->Id, $album->Id, 'Albums must be returned in reverse order (0)' );
                        $this->Assert( 'Forsaken', $album->Name, 'Album found title does not match (0) ' );
                        break;
                    case 1:
                        $this->Assert( $this->mAlbums[ 0 ]->Id, $album->Id, 'Albums must be returned in reverse order (1)' );
                        $this->Assert( 'Example', $album->Name, 'Album found title does not match (1) ' );
                }

                ++$i;
            }
        }
        public function TestOwner() {
            $this->Assert( $this->mAlbums[ 0 ]->User->Id, $this->mUser->Id, 'Owner of album is not what we expected' );
        }
        public function TestDescription() {
            $this->mAlbums[ 0 ]->Description = 'Hello';
            $this->mAlbums[ 0 ]->Save();

            $album = New Album( $this->mAlbums[ 0 ]->Id );
            $this->Assert( 'Hello', $album->Description, 'Description of album was not saved/restored correctly' );
        }
        public function TestImages() {
            $this->mImage->Albumid = $this->mAlbums[ 0 ]->Id;

            $album = New Album( $this->mAlbums[ 0 ]->Id );

            $this->AssertEquals( 1, count( $album->Images ), 'Number of images must be 1 in album' );
            $this->AssertEquals( $this->mImage->Id, $album->Images[ 0 ]->Id, 'Image mismatch in album' );
        }
        public function TestDelete() {
            $imageid = $this->mAlbums[ 0 ]->Images[ 0 ]->Id;

            foreach ( $this->mAlbums as $album ) {
                $album->Delete();
                $this->Assert( $album->IsDeleted(), 'Album must not exist after deletion' );
            }

            if ( $imageid > 0 ) { // case imageid = 0 covered by above test method
                $this->mImage = New Image( $imageid );
                $this->Assert( $this->mImage->IsDeleted(), 'Album deletion should result to image deletion as well' );
            }
        }
        public function TearDown() {
            $this->mUser->Delete();
            $this->mUser2->Delete();

            if ( !$this->mImage->IsDeleted() ) {
                $this->mImage->Delete();
            }
        }
    }

    return New TestAlbum();
?>
