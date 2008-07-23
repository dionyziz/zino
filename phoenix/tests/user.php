<?php

    // TODO: tags testing?

    final class TestUser extends Testcase {
        protected $mAppliesTo = 'libs/user/user';
        private $mUser;
        
        public function SetUp() {
            $finder = New UserFinder();
            $user = $finder->FindByName( 'usertest' );
            if ( is_object( $user ) ) {
                $user->Delete();
            }
        }
        public function TestClassesExist() {
            $this->Assert( class_exists( 'User' ), 'User class does not exist' );
            $this->Assert( class_exists( 'UserFinder' ), 'UserFinder class does not exist' );
        }
        public function TestFunctionsExist() {
            $this->Assert( function_exists( 'User_Valid' ), 'User_Valid function does not exist' );            
        }
        public function TestFindersExists() {
            $finder = New UserFinder();
            $this->Assert( method_exists( $finder, 'FindByNameAndPassword' ), 'UserFinder::FindByNameAndPassword does not exist' );
            $this->Assert( method_exists( $finder, 'FindByIdAndAuthtoken' ), 'UserFinder::FindByIdAndAuthtoken does not exist' );
            $this->Assert( method_exists( $finder, 'FindByName' ), 'UserFinder::FindByName does not exist' );
            $this->Assert( method_exists( $finder, 'FindBySubdomain' ), 'UserFinder::FindBySubdomain does not exist' );
            $this->Assert( method_exists( $finder, 'Count' ), 'UserFinder::Count does not exist' );
        }
        public function TestProperties() {
            $user = New User();
            $this->Assert( is_object( $user->Profile ), 'User::Profile is not an object' );
            $this->Assert( is_object( $user->Preferences ), 'User::Preferences is not an object' );
        }
        public function TestMethodsExist() {
            $user = New User();
            $this->Assert( method_exists( $user, 'Save' ), 'User::Save method does not exist' );
            $this->Assert( method_exists( $user, 'Delete' ), 'User::Delete method does not exist' );
            $this->Assert( method_exists( $user, 'Exists' ), 'User::Exists method does not exist' );
            $this->Assert( method_exists( $user, 'RenewAuthtoken' ), 'User::RenewAuthtoken method does not exist' );

            $profile = $user->Profile;
            $this->Assert( method_exists( $profile, 'Save' ), 'Profile::Save method does not exist' );

            $settings = $user->Preferences;
            $this->Assert( method_exists( $settings, 'Save' ), 'Settings::Save method does not exist' );
        }
        public function TestValidUser() {
            $this->AssertTrue( User_Valid( 'abresas' ), 'User_Valid did not accept a string of lowercase letters' );
            $this->AssertTrue( User_Valid( 'AbreSaS' ), 'User_Valid did not accept a string of lowercase and uppercase letters' );
            $this->AssertTrue( User_Valid( 'aBr3S4s' ), 'User_Valid did not accept an alphanumeric string' );
            $this->AssertFalse( User_Valid( '4Br3S4s' ), 'User_Valid should not accept a number in the beginning of the username' );
            $this->AssertFalse( User_Valid( 'abresas\finland\os' ), 'User_Valid must not accept an alphanumeric string with \ character' );
            $this->AssertFalse( User_Valid( '\abresas\finlandos' ), 'User_Valid should not accept a string starting with \ character' );
            $this->AssertTrue( User_Valid( 'abresas_finlandos_os' ), 'User_Valid did not accept an alphanumeric string with _ character' );
            $this->AssertFalse( User_Valid( '_abresas_finlandos' ), 'User_Valid should not accept a string starting with _ character' );
            $this->AssertTrue( User_Valid( 'abresas-finlandos-os' ), 'User_Valid did not accept an alphanumeric string with - character' );
            $this->AssertFalse( User_Valid( '-abresas-finlandos' ), 'User_Valid should not accept a string starting with - character' );
            $this->AssertTrue( User_Valid( 'aBr3S4sfin-laNd_oS' ), 'User_Valid did not accept an alphanumeric string with - and _ characters' );
        }
        public function TestCreation() {
            $this->mUser = New User();
            $finder = New UserFinder();
            
            $oldcount = $finder->Count();
            
            $this->mUser->Name = 'usertest';
            $this->mUser->Password = 'secret';

            $this->mUser->Profile->BirthYear = 1989;
            $this->mUser->Profile->BirthMonth = 11;
            $this->mUser->Profile->BirthDay = 17;
            
            $this->mUser->Profile->Email = 'test@kamibu.com';
            $this->mUser->Gender = 'male';
            $this->mUser->Rights = 10;
            $this->mUser->Avatarid = 1;
            
            $this->AssertEquals( '1989-11-17', $this->mUser->Profile->Dob, 'User dob changed prior to saving user' );

            $this->AssertFalse( $this->mUser->Exists(), 'User exists before creation' );
            
            $this->mUser->Save();

            $this->AssertEquals( $oldcount + 1, $finder->Count(), 'UserFinder->Count did not increase by 1 when a new user was created' );

            $this->AssertTrue( $this->mUser->Exists(), 'User created but does not seem to exist' );
            $this->AssertEquals( 'usertest', $this->mUser->Name, 'User name changed after saving user' );
            $this->AssertEquals( UserIp(), $this->mUser->Registerhost, 'User register host changed after saving user' );
            $this->AssertEquals( '1989-11-17', $this->mUser->Profile->Dob, 'User dob changed after saving user' );
            $this->AssertEquals( 'test@kamibu.com', $this->mUser->Profile->Email, 'test@kamibu.com', 'User email changed after saving user' );
            $this->AssertEquals( 'male', $this->mUser->Gender, 'User gender changed after saving user' );
            $this->AssertEquals( 10, $this->mUser->Rights, 'User rights changed after saving user' );
            $this->AssertEquals( 1, $this->mUser->Avatar->Id, 'User icon changed after saving user' );
            $this->AssertEquals( 32, strlen( $this->mUser->Authtoken ), 'User does not have a valid authtoken after creation' );
        }
        public function TestProfileDefaults() {
            $profile = $this->mUser->Profile;
            $this->AssertEquals( '-', $profile->Religion, 'Religion should be false by default' );
            $this->AssertEquals( '-', $profile->Education, 'Education should be false by default' );
            $this->AssertEquals( '-', $profile->Sexualorientation, 'Sexual orientation should be false by default' );
            $this->AssertEquals( '-', $profile->Smoker, 'Smoker should be false by default' );
            $this->AssertEquals( '-', $profile->Drinker, 'Drinker should be false by default' );
            $this->AssertEquals( false, $profile->Mood->Exists(), 'Mood should be a non-existing mood by default' );
        }
        public function TestSettingsDefaults() {
            $settings = $this->mUser->Preferences;
            $this->Assert( is_array( $settings->EmailNotify ), 'Settings::EmailNotify did not return an array' );
            $this->Assert( empty( $settings->EmailNotify ), 'Settings::EmailNotify should return an empty array by default' );
            $this->Assert( is_array( $settings->SiteNotify ), 'Settings::SiteNotify did not return an array' );
            $this->Assert( empty( $settings->SiteNotify ), 'Settings::SiteNotify should return an empty array by default' );
        }
        public function TestRenewAuthtoken() {
            $this->mUser = New User( 'usertest' );
            $oldAuth = $this->mUser->Authtoken;
            
            $this->mUser->RenewAuthtoken();
            $newAuth = $this->mUser->Authtoken;

            $this->AssertNotEquals( $oldAuth, $newAuth, 'User::RenewAuthtoken did not change the authtoken of the user' );
            $this->AssertEquals( strlen( $newAuth ), 32, 'User::RenewAuthtoken did not produce a valid authtoken' );
        }
        public function TestListUsers() {
            $list = User_List( 0, 20 );

            $this->Assert( is_array( $list ), 'User_List did not return an array' );
            $this->Assert( count( $list ) <= 20, 'User_List returned more users than requested' );

            $found = false;
            foreach ( $list as $item ) {
                if ( $item === $this->mUser ) {
                    $found = true;
                }
            }

            $this->AssertTrue( $found, 'User created but does not appear on the list of users' );
        }
        public function TestAuthentication() {
            $oldAuth = $this->mUser->Authtoken;

            $this->Assert( User_Authenticate( 'usertest', 'secret' ) instanceof User, 'User_Authenticate did not return a User instance after successful authentication' );
            $this->Assert( User_Authenticate( 'usertest', 'secret' ) == new User( 'usertest' ), 'User_Authenticate did not return the right User object after successful authentication' );

            $this->mUser = new User( 'usertest' );
            $newAuth = $this->mUser->Authtoken;
            $this->AssertNotEquals( $newAuth, $oldAuth, 'User_Authenticate did not change the authtoken of the user' );
            
            $this->AssertFalse( User_Authenticate( 'usertest', 'secret2' ), 'User_Authenticate did not return false after failed authentication' );
        }
        public function TestLogin() {
            User_Authenticate( 'usertest', 'secret' );
            $user = User_Login();
            $this->Assert( $user instanceof User, 'User_Login did not return a user object after successful Authentication' ); 
            $this->AssertTrue( $user->Exists(), 'User returned by User_Login after successful Authentication does not seem to exist' );
            $this->AssertEquals( $user, new User( 'usertest' ), 'User_Login did not return the logged in user' );

            User_Authenticate( 'donotcreatethisuser', 'atleastnotwiththispassword' );
            $user = User_Login();
            $this->Assert( $user instanceof User, 'User_Login did not return a user object after failed Authentication' ); 
            $this->AssertTrue( $user->Exists(), 'User returned by User_Login after failed Authentication does not seem to exist' );
            $this->AssertEquals( $user, new User( 'usertest' ), 'User_Login did not return the logged in user' );
        }
        public function TestLogout() {
            User_Logout();

            $user = User_Login();
            $this->Assert( $user instanceof User, 'User_Login did not return a user object after logging out' ); 
            $this->AssertFalse( $user->Exists(), 'User returned by User_Login after logging out should not exist' );
        }
        public function TestLastActive() {
            $this->mUser->LastActivity->Save();
            $this->Assert( abs( strtotime( $this->mUser->LastActive ) - now() ) < 60, 'User last active did not update correctly' );
        }
        public function TestEditUser() {
            $this->mUser->Name = 'testuser';
            $this->mUser->Password = 'password';
            $this->mUser->Profile->Dob = '1997-04-03 00:00:00';
            $this->mUser->Profile->Email = 'usertest@kamibu.com';
            $this->mUser->Gender = 'female';
            $this->mUser->Rights = 20;
            $this->mUser->Avatar = new Image( 2 );
            $this->mUser->Save();

            $this->AssertEquals( $this->mUser->Name, 'testuser', 'Name changed after saving changes' );
            $this->AssertNotEquals( $this->mUser->Password, 'password', 'Password was not encrypted with md5' );
            $this->AssertEquals( $this->mUser->Password, md5( 'password' ), 'Password changed after saving changes' );
            $this->AssertEquals( $this->mUser->Profile->Dob, '1997-04-03 00:00:00', 'Date of birthday changed after saving changes' );
            $this->AssertEquals( $this->mUser->Profile->Email, 'usertest@kamibu.com', 'Email changed after saving changes' );
            $this->AssertEquals( $this->mUser->Gender, 'female', 'Gender changed after saving changes' );
            $this->AssertEquals( $this->mUser->Rights, 20, 'Rights changed after saving changes' );
            $this->AssertEquals( $this->mUser->Avatar, new Image( 2 ) );
        }
        public function TestInvalidName() {
            $this->mUser->Name = 'bl4c_C123/z';
            $this->mUser->Name = 'usertest'; // valid!
            $this->mUser->Name = '4caer123z';
            $this->AssertEquals( $this->mUser->Name, 'usertest', 'Name should only change when a valid username is specified' );
        }
        public function TestInvalidDob() {
            $this->mUser->Profile->Dob = time();
            $this->mUser->Profile->Dob = 'yesterday';
            $this->mUser->Profile->Dob = '2008-01-03 00:00:00';
            $this->mUser->Profile->Dob = '1990-02-01'; // valid!
            $this->mUser->Profile->Dob = '1991-fo-ba 00:00:00';
            $this->AssertEquals( $this->mUser->Profile->Dob, '2001-02-01 00:00:00', 'UserProfile::Dob should ignore any invalid values' );
        }
        public function TestInvalidEmail() {
            $this->mUser->Profile->Email = 'lorem';
            $this->mUser->Profile->Email = 'foobar@foo';
            $this->mUser->Profile->Email = 'foo.org';
            $this->mUser->Profile->Email = 'foobar@foo.domainnn';
            $this->mUser->Profile->Email = '1#412fas@dsfo21.fr';
            $this->mUser->Profile->Email = 'qwe@ew@yahoo.com';
            $this->AssertEquals( 'usertest@kamibu.com', $this->mUser->Profile->Email, 'Email should not change when an invalid value is set' );
        }
        public function TestInvalidGender() {
            $this->mUser->Gender = 'MALE';
            $this->mUser->Gender = 'm4le';
            $this->AssertEquals( $this->mUser->Gender, 'female', 'Gender should not change when an invalid value is set' );
        }
        public function TestInvalidRights() {
            $this->mUser->Rights = 'fo';
            $this->mUser->Rights = '410';
            $this->AssertEquals( $this->mUser->Rights, 20, 'Rights should not change when an invalid value is set' );
        }
        public function TestInvalidAvatar() {
            $this->mUser->Avatar = 2;
            $this->mUser->Avatar = false;
            $this->AssertEquals( $this->mUser->Avatar, new Image( 2 ), 'Avatar should not change when an invalid value is set' );

            $this->mUser->Save();
        }
        public function TestEditProfile() {
            $profile = $this->mUser->Profile;
            $profile->AboutMe = 'Sexy script kiddie';
            $profile->Religion = 'atheism';
            $profile->Education = 'elementary';
            $profile->Politics = 'left';
            $profile->HairColor = 'blonde';
            $profile->EyeColor = 'blue';
            $profile->Weight = 60;
            $profile->Height = 170;
            $profile->SexualOrientation = 'bi';
            $profile->Smoker = 'no';
            $profile->Drinker = 'socially';
            $profile->Mood = New Mood( 'Happy' );
            $profile->Skype = 'usertest';
            $profile->MSN = 'usertest@hotmail.com';
            $profile->GTalk = 'usertest@gmail.com';
            $profile->YIM = 'usertest';

            $this->mUser->Save();

            $finder = New UserFinder();
            $this->mUser = $finder->FindByName( 'usertest' );
            $profile = $this->mUser->Profile;

            $this->AssertEquals( $profile->AboutMe, 'Sexy script kiddie', 'About me changed after saving changes' );
            $this->AssertEquals( $profile->Religion, 'atheism', 'Religion changed after saving changes' );
            $this->AssertEquals( $profile->Education, 'education', 'Education changed after saving changes' );
            $this->AssertEquals( $profile->Politics, 'left', 'Politics changed after saving changes' );
            $this->AssertEquals( $profile->HairColor, 'blonde', 'Hair color changed after saving changes' );
            $this->AssertEquals( $profile->EyeColor, 'blue', 'Eye color changed after saving changes' );
            $this->AssertEquals( $profile->Weight, 60, 'Weight changed after saving changes' );
            $this->AssertEquals( $profile->Height, 170, 'Height changed after saving changes' );
            $this->AssertEquals( $profile->SexualOrientation, 'bi', 'Sexual orientation changed after saving changes' );
            $this->AssertEquals( $profile->Smoker, 'no', 'Smoker changed after saving changes' );
            $this->AssertEquals( $profile->Drinker, 'no', 'Drinker changed after saving changes' );
            $this->AssertEquals( $profile->Mood, new Mood( 'Happy' ), 'Mood changed after saving changes' );
            $this->AssertEquals( $profile->Skype, 'usertest', 'Skype changed after saving changes' );
            $this->AssertEquals( $profile->MSN, 'usertest@hotmail.com', 'MSN changed after saving changes' );
            $this->AssertEquals( $profile->GTalk, 'usertest@gmail.com', 'GTalk changed after saving changes' );
            $this->AssertEquals( $profile->YIM, 'usertest', 'YIM changed after saving changes' );
        }
        public function TestInvalidReligion() {
            $profile = $this->mUser->Profile;

            $profile->Religion = 'C++';
            $profile->Religion = 'wicca';
            $profile->Religion = null;
            $profile->Save();
            $this->AssertEquals( $profile->Religion, 'atheism', 'Religion should not change when it is set to an invalid religion' );
        }
        public function TestInvalidEducation() {
            $profile = $this->mUser->Profile;

            $profile->Education = 'Kindergarden';
            $profile->Education = 'noeducation';
            $profile->Education = 0;
            $profile->Save();
            $this->AssertEquals( $profile->Education, 'Elementary', 'Education should not change when it is set to an invalid education' );
        }
        public function TestInvalidPolitics() {
            $profile = $this->mUser->Profile;

            $profile->Politics = 'rightleft';
            $profile->Politics = 'KKE';
            $profile->Politics = 2;
            $profile->Save();
            $this->AssertEquals( $profile->Politics, 'left', 'Politics should not change when it is set to an invalid value' );
        }
        public function TestInvalidHairColor() {
            $profile = $this->mUser->Profile;

            $profile->HairColor = 'Orange';
            $profile->Save();
            $this->AssertEquals( $profile->HairColor, 'blonde', 'Hair color should not change when it is set to an invalid value' );
        }
        public function TestInvalidWeight() {
            $profile = $this->mUser->Profile;

            $profile->Weight = '100Kg';
            $profile->Weight = 10;
            $profile->Save();
            $this->AssertEquals( $profile->Weight, '60', 'Weight should not change when it is set to an invalid value' );
        }
        public function TestInvalidHeight() {
            $profile = $this->mUser->Profile;

            $profile->Height = '100cm';
            $profile->Height = 10;
            $profile->Save();
            $this->AssertEquals( $profile->Height, '170', 'Height should not change when it is set to an invalid value' );
        }
        public function TestInvalidSexualOrientation() {
            $profile = $this->mUser->Profile;

            $profile->SexualOrientation = 'gaay';
            $profile->SexualOrientation = 'bii';
            $profile->SexualOrientation = 'trans';
            $profile->Save();
            $this->AssertEquals( $profile->SexualOrientation, 'bi', 'Sexual orientation changed after saving changes' );
        }
        public function TestInvalidSmoker() {
            $profile = $this->mUser->Profile;

            $profile->Smoker = 'never';
            $profile->Smoker = 'always';
            $profile->Save();
            $this->AssertEquals( $profile->Smoker, 'no', 'Smoker changed after saving changes' );
        }
        public function TestInvalidDrinker() {
            $profile = $this->mUser->Profile;

            $profile->Drinker = 'never';
            $profile->Drinker = 'always';
            $profile->Save();
            $this->AssertEquals( $profile->Drinker, 'no', 'Drinker changed after saving changes' );
        }
        public function TestInvalidMood() {
            $profile = $this->mUser->Profile;

            $profile->Mood = 'sad';
            $profile->Mood = new Mood( 'nomood' );
            $profile->Save();
            $this->AssertEquals( $profile->Mood, new Mood( 'Happy' ), 'Mood changed after saving changes' );
        }
        public function TestEditSettings() {
            $settings = $this->mUser->Preferences;
            $settings->EmailNotify = array( 'photos', 'friends' );
            $settings->SiteNotify = array( 'photos', 'friends', 'replies' );
            $this->mUser->Save();

            $this->AssertEquals( $settings->EmailNotify, array( 'photos', 'friends' ), 'Settings::EmailNotify did not return the correct array values' );
            $this->AssertEquals( $settings->SiteNotify, array( 'photos', 'friends', 'replies' ), 'Settings::SiteNotify did not return the correct array values' );
            
            $settings->EmailNotify = 'photos';
            $this->AssertEquals( $settings->EmailNotify, array( 'photos' ), 'Settings::EmailNotify should change a string to an array containing the string' );
            $settings->EmailNotify = 'nothing';
            $this->AssertEquals( $settings->EmailNotify, array( 'photos' ), 'Settings::EmailNotify should not change when it is set an invalid value' );

            $settings->EmailNotify = array( 'photos', 'replies', 'nothing' );
            $this->AssertEquals( $settings->EmailNotify, array( 'photos', 'replies' ), 'Settings::EmailNotify should ignore any invalid values' );

            $settings->SiteNotify = 'replies';
            $this->AssertEquals( $settings->EmailNotify, array( 'replies' ), 'Settings::EmailNotify should change a string to an array containing the string' );
            $settings->SiteNotify = 'everything';
            $this->AssertEquals( $settings->EmailNotify, array( 'replies' ), 'Settings::EmailNotify should not change when it is set an invalid value' );

            $settings->SiteNotify = array( 'friends', 'replies', 'everything' );
            $this->AssertEquals( $settings->EmailNotify, array( 'friends', 'replies' ), 'Settings::EmailNotify should ignore any invalid values' );
        }
        public function TestDeletion() {
            $this->AssertTrue( $this->mUser->Exists(), 'Created user does not seem to exist before deleting' );
            $this->mUser->Delete();
            $this->AssertFalse( $this->mUser->Exists(), 'User deleted but he still seems to exist' );

            $this->AssertFalse( $this->mUser->Exists(), 'User deleted but he still seems to exist after re-constructing the user object' );
        }
        public function TestSqlInjections() {
            $profile = $this->mUser->Profile;

            $profile->AboutMe = "Sexy'; DROP `table`; -- script kiddie";
            $profile->Skype = "user'; -- test";
            $profile->MSN = "usertest'; -- @hotmail.com";
            $profile->GTalk = "'; -- usertest@gmail.com";
            $profile->YIM = "'; -- usertest";
            $this->mUser->Save();

            $profile = $this->mUser->Profile;

            $this->AssertEquals( $profile->AboutMe, "Sexy'; DROP `table`; -- script kiddie", 'About me should not change even if it has special sql characters' );
            $this->AssertEquals( $profile->Skype, "user'; -- test" );
            $this->AssertEquals( $profile->MSN, "usertest'; -- @hotmail.com" );
            $this->AssertEquals( $profile->GTalk, "'; -- usertest@gmail.com" );
            $this->AssertEquals( $profile->YIM , "'; -- usertest" );
        }
    }

    return New TestUser();
?>
