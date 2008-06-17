<?php
    class TestValidation extends Testcase {
        protected $mAppliesTo = 'libs/rabbit/helpers/validate';

        public function TestFunctionsExist() {
            $this->RequireSuccess(
                $this->Assert( function_exists( 'ValidEmail' ), 'ValidEmail function does not exist' )
            );
            $this->RequireSuccess(
                $this->Assert( function_exists( 'ValidURL' ), 'ValidURL function does not exist' )
            );
            $this->RequireSuccess(
                $this->Assert( function_exists( 'ValidEmail' ), 'ValidEmail function does not exist' )
            );
        }
        public function TestEmail() {
            $this->Assert( ValidEmail( 'dionyziz@gmail.com' ), 'ValidEmail failed for "dionyziz@gmail.com"' );
            $this->AssertFalse( ValidEmail( 'hello' ), 'ValidEmail succeeded for "hello"' );
            $this->Assert( ValidEmail( 'dionysis.zindros@dionyziz.com' ), 'ValidEmail failed for "dionysis.zindros@dionyziz.com"' );
            $this->Assert( ValidEmail( 'a@b.gr' ), 'ValidEmail failed for "a@b.gr"' );
            $this->Assert( ValidEmail( 'admin-foo_bar@baz.aero' ), 'ValidEmail failed for "admin-foo_bar@baz.aero' );
            $this->Assert( ValidEmail( 'admin@help.a123456789012345678901234567890123456789012345678901234567890b.com' ), 'ValidEmail failed for valid long domain name' );
            $this->AssertFalse( ValidEmail( 'admin@help.a123456789012345678901234567890123456789012345678901234567890abcdefghijk.com' ), 'ValidEmail succeeded for too long domain name' );
            $this->Assert( ValidEmail( 'bob@virtual.museum' ), 'ValidEmail doesn\'t allow .museum domains' );
            $this->AssertFalse( ValidEmail( '@kamibu.com' ), 'ValidEmail succeeded with an empty username' );
            $this->AssertFalse( ValidEmail( 'bob@' ), 'ValidEmail succeeded with an empty username' );
            $this->AssertFalse( ValidEmail( 'bob@alice@kamibu.com' ), 'ValidEmail succeeded with a double @' );
            $this->AssertFalse( ValidEmail( 'bob.brown@fools..kamibu.com' ), 'ValidEmail succeeded with a double .' );
            $this->AssertFalse( ValidEmail( 'bob@-kamibu.com' ), 'ValidEmail matched a domain starting with -' );
            $this->AssertFalse( ValidEmail( 'bob@kamibu-.com' ), 'ValidEmail matched a domain ending in a -' );
            $this->AssertFalse( ValidEmail( 'bob@rabbit.-kamibu-.com' ), 'ValidEmail matched a domain wrapped in -' );
        }
    }

    return New TestValidation();
?>
