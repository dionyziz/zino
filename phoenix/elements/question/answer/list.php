<?php
    class ElementQuestionAnswerList extends Element {
        public function Render( tText $username , tText $subdomain , tInteger $pageno ) {
            global $page;
            global $user;
            global $rabbit_settings;
            global $xc_settings;
            
            Element( 'user/subdomainmatch' );
            
            $username = $username->Get();
            $subdomain = $subdomain->Get();
            $finder = New UserFinder();
            if ( $username != '' ) {
                if ( strtolower( $username ) == strtolower( $user->Name ) ) {
                    $theuser = $user;
                }
                else {
                    $theuser = $finder->FindByName( $username );
                }
            }
            else if ( $subdomain != '' ) {
                if ( strtolower( $subdomain ) == strtolower( $user->Subdomain ) ) {
                    $theuser = $user;
                }
                else {
                    $theuser = $finder->FindBySubdomain( $subdomain );
                }
            }    
            if ( !isset( $theuser ) || $theuser === false ) {
                return Element( '404' );
            }
            
            if ( strtoupper( substr( $theuser->Name, 0, 1 ) ) == substr( $theuser->Name, 0, 1 ) ) {
                $page->SetTitle( $theuser->Name . " Ερωτήσεις" );
            }
            else {
                $page->SetTitle( $theuser->Name . " ερωτήσεις" );
            }

            $answerfinder = New AnswerFinder();
            $answers = $answerfinder->FindByUser( $theuser );

            ?><div id="answers"><?php
            Element( 'user/sections', 'question' , $theuser );

            ?><div class="questions"><?php
            $owner = $theuser->Id == $user->Id;
            if ( $owner ) {
                $finder = New QuestionFinder();
                $question = $finder->FindNewQuestion( $theuser );
                ?><div class="newquestion"<?php
                if ( $question === false ) {
                    ?> style="display:none"<?php
                }
                ?>>
                <p class="question"><?php
                echo htmlspecialchars( $question->Text );
                ?></p>
                <p class="answer"><form id="newanswer" onsubmit="Questions.Answer();return false">
                    <input type="hidden" value="<?php
                    echo $question->Id;
                    ?>" />
                    <input type="text" /> <a href="" title="Απάντησε" onclick="Questions.Answer();return false"><img src="<?php
                    echo $xc_settings[ 'staticimagesurl' ];
                    ?>accept.png" alt="Απάντησε" title="Απάντησε" /></a> 
                    <a href="" title="Αλλαγή Ερώτησης" onclick="Coala.Cold( 'question/get', { 
                            'callback': Questions.Renew,
                            'excludeid' : <?php
                            echo $question->Id;
                            ?>} );return false"><img src="<?php
                    echo $xc_settings[ 'staticimagesurl' ];
                    ?>arrow_refresh.png" alt="Αλλαγή Ερώτησης" title="Αλλαγή Ερώτησης" /></a>
                </form></p>
                </div><?php
            }

            ?><ul class="questions"><?php
              
            foreach ( $answers as $answer ) {
                Element( 'question/answer/view', $answer, $owner );
            }
            ?></ul></div>
            
            </div><?php
            $page->AttachInlineScript( 'Questions.OnLoad();' );
        }
    }
?>
