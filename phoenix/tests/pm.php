<?php
    
    class TestPMs extends Testcase {
        protected $mAppliesTo = 'libs/pm/pm';
        private $mUser;
        private $mUser2;
        
        public function SetUp() {
            global $libs;

            $libs->Load( 'pm/pm' );

            $ufinder = New UserFinder();
            $user = $ufinder->FindByName( 'testpms' );
            if ( is_object( $user ) ) {
                $user->Delete();
            }
            $user = $ufinder->FindByName( 'testpms2' );
            if ( is_object( $user ) ) {
                $user->Delete();
            }

            $this->mUser = New User();
            $this->mUser->Name = 'testpms';
            $this->mUser->Email = 'bitbucket@kamibu.com';
            $this->mUser->Subdomain = 'testpms';
            $this->mUser->Save();

            $this->mUser2 = New User();
            $this->mUser2->Name = 'testpms2';
            $this->mUser->Email = 'bitbucket@kamibu.com';
            $this->mUser2->Subdomain = 'testpms2';
            $this->mUser2->Save();
        }
        public function TestCreateFolder() {
            $folder = New PMFolder();
            $folder->Userid = $this->mUser->Id;
            $folder->Name = 'feedback';
            $folder->Save();

            $f = New PMFolder( $folder->Id );
            $this->AssertEquals( 'feedback', $f->Name, 'Wrong folder name' );
            $this->AssertEquals( $this->mUser->Id, $f->Userid, 'Wrong folder userid' );

            $f->Delete();
        }
        public function TestFindFoldersByUser() {
            $folder1 = New PMFolder();
            $folder1->Userid = $this->mUser->Id;
            $folder1->Name = 'funny';
            $folder1->Save();

            $folder2 = New PMFolder();
            $folder2->Userid = $this->mUser->Id;
            $folder2->Name = 'phoenix';
            $folder2->Typeid = PMFOLDER_USER; // not needed
            $folder2->Save();

            $finder = New PMFolderFinder();
            $folders = $finder->FindByUserAndType( $this->mUser, PMFOLDER_USER );
            $this->AssertEquals( 2, count( $folders ), 'Wrong number of folders' );

            $names = array( 'funny', 'phoenix' );
            foreach ( $folders as $i => $folder ) {
                $this->AssertEquals( $this->mUser->Id, $folder->Userid, 'Wrong folder userid' );
                $this->AssertEquals( $names[ $i ], $folder->Name, 'Wrong folder name' );
                $this->AssertFalse( $folder->IsDeleted(), 'Folder seems to be deleted' );
                $this->AssertEquals( PMFOLDER_USER, $folder->Typeid, 'Wrong folder typeid' );
            }

            $folder1->Delete();
            $folder2->Delete();
        }
        public function TestSendPM() {
            $pm = New PM();
            $pm->Senderid = $this->mUser->Id;
            $pm->AddReceiver( $this->mUser );
            $pm->AddReceiver( $this->mUser2 );
            $pm->Text = 'foo bar blah';
            $this->AssertEquals( 'foo bar blah', $pm->Text, 'Wrong pm text before creating' );
            $pm->Save();

            $this->AssertEquals( 'foo bar blah', $pm->Text, 'Wrong pm text after creating' );

            $p = New PM( $pm->Id );
            $this->AssertEquals( 'foo bar blah', $pm->Text, 'Wrong pm text' );
            $this->AssertEquals( $this->mUser->Id, $pm->Senderid, 'Wrong pm senderid' );
            $this->AssertEquals( 2, count( $pm->Receivers ), 'Wrong receivers count' );
            $this->AssertEquals( $this->mUser2->Id, $pm->Receivers[ 1 ]->Id, 'Wrong second receiver' );

            $folderfinder = New PMFolderFinder();
            $senderoutbox = $folderfinder->FindByUserAndType( $this->mUser, PMFOLDER_OUTBOX );

            $senderpms = $senderoutbox->UserPMs;
            $this->AssertEquals( 1, count( $senderpms ), 'Wrong number of pms in sender outbox' );

            $pm = $senderpms[ 0 ];
            $this->AssertEquals( $senderoutbox->Id, $pm->Folderid, 'Wrong userpm folderid' );
            $this->AssertEquals( 'foo bar blah', $pm->Text, 'Wrong userpm text' );
            $this->Assert( $pm->IsRead(), 'Sent pm does not seem to be read' );
            $this->AssertFalse( $pm->IsDeleted(), 'Sent pm seems to be deleted' );
            $this->AssertEquals( $this->mUser->Name, $pm->Sender->Name, 'Wrong userpm sender name' );
            $this->AssertEquals( 2, count( $pm->Receivers ), 'Wrong number of userpm receivers' );
            $this->AssertEquals( $this->mUser->Id, $pm->Receivers[ 0 ]->Id, 'Wrong userid on first userpm receiver' );
            $this->AssertEquals( $this->mUser2->Id, $pm->Receivers[ 1 ]->Id, 'Wrong userid on second userpm receiver' );

            $receiverinbox = $folderfinder->FindByUserAndType( $this->mUser2, PMFOLDER_INBOX );
            $receiverpms = $receiverinbox->UserPMs;
            $this->AssertEquals( 1, count( $receiverpms ), 'Wrong number of pms in receiver inbox' );

            $pm = $receiverpms[ 0 ];
            $this->AssertEquals( $receiverinbox->Id, $pm->Folderid, 'Wrong userpm folderid' );
            $this->AssertEquals( 'foo bar blah', $pm->Text, 'Wrong userpm text' );
            $this->AssertFalse( $pm->IsRead(), 'Received pms seems to be already read' );
            $this->AssertFalse( $pm->IsDeleted(), 'Received pm seems to be deleted' );
            $this->AssertEquals( $this->mUser->Name, $pm->Sender->Name, 'Wrong userpm sender name' );
            $this->AssertEquals( $this->mUser2->Name, $pm->User->Name, 'Wrong userpm user name' );

            $p->Delete();
        }
        public function TearDown() {
            if ( is_object( $this->mUser ) ) {
                $this->mUser->Delete();
            }
            if ( is_object( $this->mUser2 ) ) {
                $this->mUser2->Delete();
            }
        }
    }

    return New TestPMs();

?>
