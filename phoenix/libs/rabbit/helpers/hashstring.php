<?php    
    function GenerateRandomHash() {
        // first generate 16 random bytes
        // generate 8 pseurandom 2-byte sequences 
        // (that's bad but generally conventional pseudorandom generation algorithms do not allow very high limits
        // unless they repeatedly generate random numbers, so we'll have to go this way)
        $bytes = array(); // the array of all our 16 bytes
        for ( $i = 0; $i < 8 ; ++$i ) {
            $bytesequence = rand( 0, 65535 ); // generate a 2-bytes sequence
            // split the two bytes
            // lower-order byte
            $a = $bytesequence & 255; // a will be 0...255
            // higher-order byte
            $b = $bytesequence >> 8; // b will also be 0...255
            // append the bytes
            $bytes[] = $a;
            $bytes[] = $b;
        }
        // now that we have 16 "random" bytes, create a string of 32 characters,
        // each of which will be a hex digit 0...f
        $hash = ''; // start with an empty string
        foreach ( $bytes as $byte ) {
            // each byte is two authtoken digits
            // split them up
            $first = $byte & 15; // this will be 0...15
            $second = $byte >> 4; // this will be 0...15 again
            // convert decimal to hex and append
            // order doesn't really matter, it's all random after all
            $hash .= dechex($first) . dechex($second);
        }
        
        return $hash;
    } 
?>
