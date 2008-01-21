<?php

    // TODO: tags testing?

    global $libs;
    $libs->Load( 'user' );

    final class TestUser extends Testcase {
        protected $mAppliesTo = 'libs/user';
        
        public function TestClassesExist() {
            $this->Assert( class_exists( 'User' ), 'User class does not exist' );
        }
        public function TestFunctionsExist() {
            $this->Assert( function_exists( 'User_List' ), 'User_List function does not exist' );
            $this->Assert( function_exists( 'User_Authenticate' ), 'User_Authenticate function does not exist' );
            $this->Assert( function_exists( 'User_Login' ), 'User_Login function does not exist' );
            $this->Assert( function_exists( 'User_Logout' ), 'User_Logout function does not exist' );
            $this->Assert( function_exists( 'User_Valid' ), 'User_Valid function does not exist' );
            $this->Assert( function_exists( 'User_Count' ), 'User_Count function does not exist' );
        }
        public function TestProperties() {
            $user = New User();
            $this->Assert( is_object( $user->Profile ), 'User::Profile is not an object' );
            $this->Assert( is_object( $user->Settings ), 'User:Settings is not an object' );
        }
        public function TestMethodsExist() {
            $user = New User();
            $this->Assert( method_exists( $user, 'Save' ), 'User::Save method does not exist' );
            $this->Assert( method_exists( $user, 'Delete' ), 'User::Delete method does not exist' );
            $this->Assert( method_exists( $user, 'Exists' ), 'User::Exists method does not exist' );
            $this->Assert( method_exists( $user, 'ScrambleAuthtoken' ), 'User::ScrambleAuthtoken method does not exist' );

            $profile = $user->Profile;
            $this->Assert( method_exists( $profile, 'Save' ), 'Profile::Save method does not exist' );

            $settings = $user->Settings;
            $this->Assert( method_exists( $settings, 'Save' ), 'Settings::Save method does not exist' );
        }
        public function TestValidUser() {
            $this->AssertTrue( User_Valid( 'abresas' ), 'User_Valid did not accept a string of lowercase letters' );
            $this->AssertTrue( User_Valid( 'AbreSaS' ), 'User_Valid did not accept a string of lowercase and uppercase letters' );
            $this->AssertTrue( User_Valid( 'aBr3S4s' ), 'User_Valid did not accept an alphanumeric string' );
            $this->AssertFalse( User_Valid( '4Br3S4s' ), 'User_Valid should not accept a number in the beginning of the username' );
            $this->AssertTrue( User_Valid( 'abresas\finland\os' ), 'User_Valid did not accept an alphanumeric string with \ character' );
            $this->AssertFalse( User_Valid( '\abresas\finlandos' ), 'User_Valid should not accept a string starting with \ character' );
            $this->AssertTrue( User_Valid( 'abresas_finlandos_os' ), 'User_Valid did not accept an alphanumeric string with _ character' );
            $this->AssertFalse( User_Valid( '_abresas_finlandos' ), 'User_Valid should not accept a string starting with _ character' );
            $this->AssertTrue( User_Valid( 'abresas-finlandos-os' ), 'User_Valid did not accept an alphanumeric string with - character' );
            $this->AssertFalse( User_Valid( '-abresas-finlandos' ), 'User_Valid should not accept a string starting with - character' );
            $this->AssertTrue( User_Valid( 'aBr\3S4s\fin-laNd_oS' ), 'User_Valid did not accept an alphanumeric string with \, - and _ characters' );
        }
        public function TestCreation() {
            $user = New User();
    
            $oldcount = User_Count();

            $user->Name = 'usertest';
            $user->Password = 'secret';
            $user->Dob = '1989-17-11 00:00:00';
            $user->Email = 'test@kamibu.com';
            $user->Gender = 'male';
            $user->Signature = 'Foo bar blah';
            $user->Rights = 10;
            $user->Avatar = new Image( 1 );
            
            $this->AssertFalse( $user->Exists(), 'User exists before creation' );
            $this->AssertFalse( $user->Locked, 'User should not be locked by default' );
            $this->AssertEquals( $user->Password, md5( 'secret' ), 'User password not encrypted with md5 before saving' );

            $user->Save();

            $this->AssertEquals( User_Count() + 1, $oldcount, 'User_Count did not increase by 1 when a new user was created' );

            $this->AssertTrue( $user->Exists(), 'User created but does not seem to exist' );
            $this->AssertEquals( $user->Name, 'usertest', 'User name changed after saving user' );
            $this->AssertNotEquals( $user->Password, 'secret', 'User password not encrypted with md5' );
            $this->AssertEquals( $user->Password, md5( 'secret' ), 'User password changed after saving user' );
            $this->AssertEquals( $user->RegisterHost, UserIp(), 'User register host changed after saving user' );
            $this->AssertEquals( $user->Dob, '1989-17-11 00:00:00', 'User dob changed after saving user' );
            $this->AssertEquals( $user->Email, 'test@kamibu.com', 'User email changed after saving user' );
            $this->AssertEquals( $user->Gender, 'male', 'User gender changed after saving user' );
            $this->AssertEquals( $user->Signature, 'Foo bar blah', 'User signature changed after saving user' );
            $this->AssertEquals( $user->Rights, 10, 'User rights changed after saving user' );
            $this->AssertEquals( $user->Avatar, new Image( 1 ), 'User icon changed after saving user' );
            $this->AssertEquals( strlen( $user->Authtoken ), 32, 'User does not have a valid authtoken after creation' );
            $this->AssertEquals( $user->RegiterHost, UserIp(), 'User register host should be set to the current user\'s IP by default' );
        }
        public function TestProfileDefaults() {
            $user = New User( 'usertest' );

            $profile = $user->Profile;
            $this->AssertFalse( $profile->Religion, 'Religion should be false by default' );
            $this->AssertFalse( $profile->Education, 'Education should be false by default' );
            $this->AssertFalse( $profile->SexualOrientation, 'Sexual orientation should be false by default' );
            $this->AssertFalse( $profile->Smoker, 'Smoker should be false by default' );
            $this->AssertFalse( $profile->Drinker, 'Drinker should be false by default' );
            $this->AssertFalse( $profile->Mood->Exists(), 'Mood should be a non-existing mood by default' );
        }
        public function TestSettingsDefaults() {
            $user = New User( 'usertest' );
           
            $settings = $user->Settings;
            $this->Assert( is_array( $settings->EmailNotify ), 'Settings::EmailNotify did not return an array' );
            $this->Assert( empty( $settings->EmailNotify ), 'Settings::EmailNotify should return an empty array by default' );
            $this->Assert( is_array( $settings->SiteNotify ), 'Settings::SiteNotify did not return an array' );
            $this->Assert( empty( $settings->SiteNotify ), 'Settings::SiteNotify should return an empty array by default' );
        }
        public function TestScrambleAuthtoken() {
            $user = New User( 'usertest' );
            $oldAuth = $user->Authtoken;
            
            $user->ScrambleAuthtoken();
            $newAuth = $user->Authtoken;

            $this->AssertNotEquals( $oldAuth, $newAuth, 'User::ScrambleAuthtoken did not change the authtoken of the user' );
            $this->AssertEquals( strlen( $newAuth ), 32, 'User::ScrambleAuthtoken did not produce a valid authtoken' );
        }
        public function TestListUsers() {
            $user = New User( 'usertest' );
            $list = User_List( 0, 20 );

            $this->Assert( is_array( $list ), 'User_List did not return an array' );
            $this->Assert( count( $list ) <= 20, 'User_List returned more users than requested' );

            $found = false;
            foreach ( $list as $item ) {
                if ( $item === $user ) {
                    $found = true;
                }
            }

            $this->AssertTrue( $found, 'User created but does not appear on the list of users' );
        }
        public function TestAuthentication() {
            $user = new User( 'usertest' );
            $oldAuth = $user->Authtoken;

            $this->Assert( User_Authenticate( 'usertest', 'secret' ) instanceof User, 'User_Authenticate did not return a User instance after successful authentication' );
            $this->Assert( User_Authenticate( 'usertest', 'secret' ) == new User( 'usertest' ), 'User_Authenticate did not return the right User object after successful authentication' );

            $user = new User( 'usertest' );
            $newAuth = $user->Authtoken;
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
        public function TestEditUser() {
            $user = New User( 'usertest' );
            
            $user->Name = 'testuser';
            $user->Password = 'password';
            $user->Dob = '1997-04-03 00:00:00';
            $user->Email = 'usertest@kamibu.com';
            $user->Gender = 'female';
            $user->Signature = 'Foo bar';
            $user->Rights = 20;
            $user->Avatar = new Image( 2 );
            $user->Save();

            $this->AssertEquals( $user->Name, 'testuser', 'Name changed after saving changes' );
            $this->AssertNotEquals( $user->Password, 'password', 'Password was not encrypted with md5' );
            $this->AssertEquals( $user->Password, md5( 'password' ), 'Password changed after saving changes' );
            $this->AssertEquals( $user->Dob, '1997-04-03 00:00:00', 'Date of birthday changed after saving changes' );
            $this->AssertEquals( $user->Email, 'usertest@kamibu.com', 'Email changed after saving changes' );
            $this->AssertEquals( $user->Gender, 'female', 'Gender changed after saving changes' );
            $this->AssertEquals( $user->Signature, 'Foo bar', 'Signature changed after saving changes' );
            $this->AssertEquals( $user->Rights, 20, 'Rights changed after saving changes' );
            $this->AssertEquals( $user->Avatar, new Image( 2 ) );
        }
        public function TestInvalidName() {
            $user = New User( 'usertest' );

            $user->Name = 'bl4c_C123/z';
            $user->Name = 'usertest'; // valid!
            $user->Name = '4caer123z';
            $this->AssertEquals( $user->Name, 'usertest', 'Name should only change when a valid username is specified' );
        }
        public function TestInvalidDob() {
            $user = New User( 'usertest' );
            
            $user->Dob = time();
            $user->Dob = 'yesterday';
            $user->Dob = '2008-01-03 00:00:00';
            $user->Dob = '1990-02-01'; // valid!
            $user->Dob = '1991-fo-ba 00:00:00';
            $this->AssertEquals( $user->Dob, '2001-02-01 00:00:00', 'User::Dob should ignore any invalid values' );
        }
        public function TestInvalidEmail() {
            $user = New User( 'usertest' );

            $user->Email = 'lorem';
            $user->Email = 'foobar@foo';
            $user->Email = 'foo.org';
            $user->Email = 'foobar@foo.domainnn';
            $user->Email = '1#412fas@dsfo21.fr';
            $user->Email = 'qwe@ew@yahoo.com';
            $this->AssertEquals( $user->Email, 'usertest@kamibu.com', 'Email should not change when an invalid value is set' );
        }
        public function TestInvalidGender() {
            $user = New User( 'usertest' );

            $user->Gender = 'MALE';
            $user->Gender = 'm4le';
            $this->AssertEquals( $user->Gender, 'female', 'Gender should not change when an invalid value is set' );
        }
        public function TestInvalidRights() {
            $user = New User( 'usertest' );

            $user->Rights = 'fo';
            $user->Rights = '410';
            $this->AssertEquals( $user->Rights, 20, 'Rights should not change when an invalid value is set' );
        }
        public function TestInvalidAvatar() {
            $user = New User( 'usertest' );

            $user->Avatar = 2;
            $user->Avatar = false;
            $this->AssertEquals( $user->Avatar, new Image( 2 ), 'Avatar should not change when an invalid value is set' );

            $user->Save();
        }
        public function TestEditProfile() {
            $user = New User( 'usertest' );

            $profile = $user->Profile;
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

            $user->Save();

            $user = New User( 'usertest' );
            $profile = $user->Profile;

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
            $user = New User( 'usertest' );
            $profile = $user->Profile;

            $profile->Religion = 'C++';
            $profile->Religion = 'wicca';
            $profile->Religion = null;
            $profile->Save();
            $this->AssertEquals( $profile->Religion, 'atheism', 'Religion should not change when it is set to an invalid religion' );
        }
        public function TestInvalidEducation() {
            $user = New User( 'usertest' );
            $profile = $user->Profile;

            $profile->Education = 'Kindergarden';
            $profile->Education = 'noeducation';
            $profile->Education = 0;
            $profile->Save();
            $this->AssertEquals( $profile->Education, 'Elementary', 'Education should not change when it is set to an invalid education' );
        }
        public function TestInvalidPolitics() {
            $user = New User( 'usertest' );
            $profile = $user->Profile;

            $profile->Politics = 'rightleft';
            $profile->Politics = 'KKE';
            $profile->Politics = 2;
            $profile->Save();
            $this->AssertEquals( $profile->Politics, 'left', 'Politics should not change when it is set to an invalid value' );
        }
        public function TestInvalidHairColor() {
            $user = New User( 'usertest' );
            $profile = $user->Profile;

            $profile->HairColor = 'Orange';
            $profile->Save();
            $this->AssertEquals( $profile->HairColor, 'blonde', 'Hair color should not change when it is set to an invalid value' );
        }
        public function TestInvalidWeight() {
            $user = New User( 'usertest' );
            $profile = $user->Profile;

            $profile->Weight = '100Kg';
            $profile->Weight = 10;
            $profile->Save();
            $this->AssertEquals( $profile->Weight, '60', 'Weight should not change when it is set to an invalid value' );
        }
        public function TestInvalidHeight() {
            $user = New User( 'usertest' );
            $profile = $user->Profile;

            $profile->Height = '100cm';
            $profile->Height = 10;
            $profile->Save();
            $this->AssertEquals( $profile->Height, '170', 'Height should not change when it is set to an invalid value' );
        }
        public function TestInvalidSexualOrientation() {
            $user = New User( 'usertest' );
            $profile = $user->Profile;

            $profile->SexualOrientation = 'gaay';
            $profile->SexualOrientation = 'bii';
            $profile->SexualOrientation = 'trans';
            $profile->Save();
            $this->AssertEquals( $profile->SexualOrientation, 'bi', 'Sexual orientation changed after saving changes' );
        }
        public function TestInvalidSmoker() {
            $user = New User( 'usertest' );
            $profile = $user->Profile;

            $profile->Smoker = 'never';
            $profile->Smoker = 'always';
            $profile->Save();
            $this->AssertEquals( $profile->Smoker, 'no', 'Smoker changed after saving changes' );
        }
        public function TestInvalidDrinker() {
            $user = New User( 'usertest' );
            $profile = $user->Profile;

            $profile->Drinker = 'never';
            $profile->Drinker = 'always';
            $profile->Save();
            $this->AssertEquals( $profile->Drinker, 'no', 'Drinker changed after saving changes' );
        }
        public function TestInvalidMood() {
            $user = New User( 'usertest' );
            $profile = $user->Profile;

            $profile->Mood = 'sad';
            $profile->Mood = new Mood( 'nomood' );
            $profile->Save();
            $this->AssertEquals( $profile->Mood, new Mood( 'Happy' ), 'Mood changed after saving changes' );
        }
        public function TestEditSettings() {
            $user = New User( 'usertest' );

            $settings = $user->Settings;
            $settings->EmailNotify = array( 'photos', 'friends' );
            $settings->SiteNotify = array( 'photos', 'friends', 'replies' );
            $user->Save();

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
            $user = New User( 'usertest' );
            
            $this->AssertTrue( $user->Exists(), 'Created user does not seem to exist before deleting' );
            $user->Delete();
            $this->AssertFalse( $user->Exists(), 'User deleted but he still seems to exist' );

            $user = new User( 'usertest' );
            $this->AssertFalse( $user->Exists(), 'User deleted but he still seems to exist after re-constructing the user object' );
        }
        public function TestSqlInjections() {
            $user = New User( 'usertest' );
            $profile = $user->Profile;

            $user->Signature = "foo'; -- Foo bar";
            $profile->AboutMe = "Sexy'; DROP `table`; -- script kiddie";
            $profile->Skype = "user'; -- test";
            $profile->MSN = "usertest'; -- @hotmail.com";
            $profile->GTalk = "'; -- usertest@gmail.com";
            $profile->YIM = "'; -- usertest";
            $user->Save();

            $user = New User( 'usertest' );
            $profile = $user->Profile;

            $this->AssertEquals( $user->Signature, "foo'; -- Foo bar", 'User signature should not change even if it has special sql characters' );
            $this->AssertEquals( $profile->AboutMe, "Sexy'; DROP `table`; -- script kiddie", 'About me should not change even if it has special sql characters' );
            $this->AssertEquals( $profile->Skype, "user'; -- test" );
            $this->AssertEquals( $profile->MSN, "usertest'; -- @hotmail.com" );
            $this->AssertEquals( $profile->GTalk, "'; -- usertest@gmail.com" );
            $this->AssertEquals( $profile->YIM , "'; -- usertest" );
        }
    }

    return New TestUser();
?>
