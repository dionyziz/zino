<?php
    class TestNotificationEmailReply extends Testcase {
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
        public function TestParse() {
            $data = Notify_EmailReplyParse(
                'MIME-Version: 1.0
                Received: by 10.216.46.135 with HTTP; Mon, 27 Jul 2009 16:04:35 -0700 (PDT)
                In-Reply-To: <E1MVZ19-000853-5t@iris.kamibu.com>
                References: <E1MVZ19-000853-5t@iris.kamibu.com>
                Date: Tue, 28 Jul 2009 02:04:35 +0300
                Delivered-To: dionyziz@gmail.com
                Message-ID: <8b8555a70907271604j6938434ay38267b5336f9fe25@mail.gmail.com>
                Subject: =?UTF-8?B?UmU6IM6fIFBldHJvc2FnZzE4IM+Dz4fOv867zq/Osc+DzrUgz4PPhM6/IM+Az4HOv8+G?=
                    =?UTF-8?B?zq/OuyDPg86/z4U=?=
                From: Dionysis Zindros <dionyziz@gmail.com>
                To: teras3430000-b0fc6dcdd9@zino.gr
                Content-Type: text/plain; charset=UTF-8
                Content-Transfer-Encoding: base64

                VGVyYXMgdGVzdGluZy4KCjIwMDkvNy8yOCAgPHRlcmFzMzQzMDAwMC1iMGZjNmRjZGQ5QHppbm8u
                Z3I+Ogo+IM6fIFBldHJvc2FnZzE4IM+Dz4fOv867zq/Osc+DzrUgz4PPhM6/IM+Az4HOv8+Gzq/O
                uyDPg86/z4UgzrrOsc65IM6tzrPPgc6xz4jOtToKPgo+ICJiZWFzdGllIgo+Cj4KPiDOk865zrEg
                zr3OsSDOsc+AzrHOvc+Ezq7Pg861zrnPgiDPg8+Ezr8gz4PPh8+MzrvOuc+MIM+Ezr/PhSBQZXRy
                b3NhZ2cxOCDOus6szr3OtSDOus67zrnOuiDPg8+Ezr/OvSDPgM6xz4HOsc66zqzPhM+JIM+Dz43O
                vc60zrXPg868zr86Cj4gaHR0cDovL2Rpb255eml6Lnppbm8uZ3IvP2NvbW1lbnRpZD0zNDMwMDAw
                Cj4KPiDOlc+Fz4fOsc+BzrnPg8+Ezr/Pjc68zrUsCj4gzpcgzp/OvM6szrTOsSDPhM6/z4UgWmlu
                bwo+Cj4gX19fX19fCj4gzpHOvSDOuM6tzrvOtc65z4Igzr3OsSDOv8+Bzq/Pg861zrnPgiDPhM65
                IGUtbWFpbCDOu86xzrzOss6szr3Otc65z4IgzrHPgM+MIM+Ezr8gWmlubywgz4DOrs6zzrHOuc69
                zrUgz4PPhM6/Ogo+IGh0dHA6Ly93d3cuemluby5nci9zZXR0aW5ncyNzZXR0aW5ncwo+Cg=='
            );
            $this->Assert( strpos( $data[ 'body' ], 'Teras testing.' ) !== false );
            $this->AssertEquals( 'teras3430000-b0fc6dcdd9', $data[ 'target' ] );
        }
        public function TearDown() {
        }
    }
    
    return New TestNotificationEmailReply();
?>
