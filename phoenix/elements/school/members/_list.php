<?php
	class ElementSchoolMembersList extends Element {
		public function Render( tInteger $id , tInteger $pageno ) {
            $id = $id->Get();
			
            $school = New School( $id );

            if ( !$school->Exists() ) {
                die( 'Το σχολείο που προσπαθείς να δεις δεν υπάρχει.' );
                return Element( '404' );
            }

            if ( !$institution->Exists() ) {
                die( 'Το σχολείο που προσπαθείς να δεις δεν εντάσσεται σε κάποιο ίδρυμα.' );
                return Element( '404' );
            }
			
			$pageno = $pageno->Get();
            if ( $pageno <= 0 ) {
                $pageno = 1;
            }
			$userfinder = New UserFinder();
			$students = $userfinder->FindBySchool( $school , ( $pageno - 1 )*24 , 24 );
			?><div id="#schview"><?php
				Element( 'school/info' , $school , true );
				?><div class="members">
					<h4>Λίστα μελών</h4><?php
					Element( 'user/list' , $students );
					?><div class="pagifymembers"><?php
					    $link = str_replace( '*', urlencode( $theuser->Subdomain ), $xc_settings[ 'usersubdomains' ] ) . 'friends?pageno=';
		                $total_friends = $theuser->Count->Relations;
		                $total_pages = ceil( $total_friends / 24 );
		                Element( 'pagify', $pageno, $link, $total_pages, "( " . $total_friends . " Φίλοι )" );
					
					
					?></div>
				</div>
				<div class="eof"></div><?php
			?></div><?php
		
		
		}
	}
?>