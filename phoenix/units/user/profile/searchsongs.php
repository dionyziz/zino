<?php
    function UnitUserProfileSearchsongs( tText $query ) {
        global $libs;
        
		$libs->Load( 'music/grooveshark' );
		
        $query = $query->Get();
		?>Profile.Player.Addsongs( [{"songID":2779456,"artistID":668,"artistName":"Foo Fighters","albumID":623163,"albumName":"Echoes, Silence, Patience & Gr","isLowBitrateAvailable":true,"flags":0,"songName":"The Pretender","image":{"tiny":"http:\/\/beta.grooveshark.com\/static\/amazonart\/t2907915.jpg","small":"http:\/\/beta.grooveshark.com\/static\/amazonart\/s2907915.jpg","medium":"http:\/\/beta.grooveshark.com\/static\/amazonart\/m2907915.jpg"},"estDurationSecs":269,"genreID":0,"genreName":"unknown","isStreamable":true,"isFavorite":false,"liteUrl":""}] );<?php
	} 
?>
