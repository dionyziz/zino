<?php
    function ElementCommentImport() {
        // perform the necessary imports/loads to be able to display and handle comments
        global $page;
        
        $page->AttachStyleSheet( 'css/comment.css' ); 
        $page->AttachStyleSheet( 'css/modal.css' );
		$page->AttachScript( 'js/coala.js' );
		$page->AttachScript( 'js/comments.js' );
		$page->AttachScript( 'js/modal.js' );
    }
    
?>
