<?php
   class ElementFrontpageView extends Element {
        public function Render() {
            $finder = New UserFinder();
		    $users = $finder->FindOnline( 0 , 50 );
            
            if ( count( $users ) > 0 ) {        
                ?><div class="onlineusers">
                    <h2<?php
                        if ( count( $users ) > 1 ) {
                            ?> title="<?php
                            echo count( $users );
                            ?> ����� ����� online"<?php
                        }
                        ?>>����� online ���� (<?php
                        echo count( $users );
                        ?>)</h2>
                        <div class="list"><?php
                            foreach( $users as $onuser ) {
                                ?><a href="<?php
                                Element( 'user/url', $onuser->Id , $onuser->Subdomain );
                                ?>"><?php
                                Element( 'user/avatar' , $onuser->Avatar->Id , $onuser->Id , $onuser->Avatar->Width , $onuser->Avatar->Height , $onuser->Name , 100 , '' , '' , false , 0 , 0 );
                                ?></a><?php
                            }    
                        ?></div><?php
                ?></div><?php
            }
        }
    }
?>