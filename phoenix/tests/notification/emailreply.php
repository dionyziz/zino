<?php
    class TestNotification extends Testcase {
        protected $mAppliesTo = 'libs/notify/emailreplyhandler';
        public function SetUp() {
        }
        public function TestFunctionsExist() {
            $this->Assert( function_exists( 'Notify_EmailReplyHandler' ) );
            $this->Assert( function_exists( 'Notify_EmailReplyFilterRecipients' ) );
            $this->Assert( function_exists( 'Notify_EmailReplyParse' ) );
        }
        public function TestFilterRecipients() {
            $this->AssertEquals(
                'test',
                Notify_EmailReplyFilterRecipients( 'test@zino.gr' )
            );
            $this->AssertEquals(
                'foo.bar.123',
                Notify_EmailReplyFilterRecipients( 'foo.bar.123@zino.gr' )
            );
            $this->AssertEquals(
                'beast1357-29bcd12',
                Notify_EmailReplyFilterRecipients( 'beast1357-29bcd12@zino.gr' )
            );
            $this->AssertEquals(
                false,
                Notify_EmailReplyFilterRecipients( 'test@example.org' )
            );
            $this->AssertEquals(
                'dionyziz',
                Notify_EmailReplyFilterRecipients( 'Dionysis Zindros <dionyziz@zino.gr>' )
            );
            $this->AssertEquals(
                'petros',
                Notify_EmailReplyFilterRecipients( 'Petros Aggelatos <petros@zino.gr>, Dionysis Zindros <dionyziz@zino.gr>' )
            );
            $this->AssertEquals(
                'dionyziz',
                Notify_EmailReplyFilterRecipients( 'Petros Aggelatos <petros@kamibu.com>, Dionysis Zindros <dionyziz@zino.gr>' )
            );
        }
        public function TearDown() {
        }
    }
    
    return New TestNotification();
?>
