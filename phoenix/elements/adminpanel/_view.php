<?php    
    class ElementAdminpanelView extends Element {
        public function Render() {
	        global $page;
	        global $user;
	        
	        if( ! $user->HasPermission( PERMISSION_ADMINPANEL_VIEW ) ) {
	            ?> Permission Denied <?php
	            return;
	        }
	        
	        $page->setTitle( 'Κεντρική σελίδα διαχειριστών' );
	        
	        ?><h2>Κεντρική σελίδα διαχειριστών</h2><?php
	        
	        ?><ul><?php
		        ?><li><a href="?p=statistics" >Στατιστικά στοιχεία του Zino</a></li><?php
		        ?><li><a href="?p=banlist" >Αποκλεισμένοι χρήστες</a></li><?php
		        ?><li><a href="?p=adminlog" >Ενέργειες διαχειριστών</a></li><?php
	        ?></ul><?php    
	        
	        global $libs;
	        $libs->Load( 'user/user' );
	        //$libs->Load( 'bennu/bennu' );
	        $libs->Load( 'bennu/mybennu' );
	        
	        $userFinder = new UserFinder();
	        $input = $userFinder->FindLatest();
	        $target = $userFinder->FindByName( 'pagio91' );
	        
	        
	        
	        
	        /*
	        $bennu = new Bennu();
	        $bennu->SetData( $input, $target );
	        
	        $gender = new BennuRuleGender();
	        $gender->SetRule( $target->Gender, 'medium' ); 
	        
	        $sex = new BennuRuleSex();
	        $sex->SetRule( $target->Profile->Sexualorientation, 'medium' );
	        
	        $lastactive = new BennuRuleLastActive();
	        $lastactive->SetRule( strtotime( $target->Lastlogin ), 'medium' , 7*24*60*60 );

            $bennu->AddRule( $gender );
            $bennu->AddRule( $lastactive );
            $bennu->AddRule( $sex );
	        $res = $bennu->GetResult();
	        
	        ?><h3>Results</h3><?php
	        foreach ( $res as $key=>$val ) {
	            echo '<p>'.$key.' '.$val.'</p>';

            }*/            
	        
        }
    }
?>
