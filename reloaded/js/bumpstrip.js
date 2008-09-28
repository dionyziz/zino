var Bumpstrip = {
    Do: function () {
        var bumper = document.getElementById( 'bumpstrip' );
        stripie = bumper.getElementsByTagName( 'div' )[ 0 ];
        firstimage = stripie.getElementsByTagName( 'div' )[ 0 ];
        Animations.Create( firstimage , 'opacity' , 2000 , 1 , 0 , function () {
            var bumper = document.getElementById( 'bumpstrip' );
            stripie = bumper.getElementsByTagName('div')[ 0 ];
            firstimage = stripie.getElementsByTagName( 'div' )[ 0 ];
            actualimg = firstimage.getElementsByTagName( 'img' )[ 0 ];
            Animations.Create( actualimg , 'width' , 2000 , actualimg.offsetWidth , 0 ); // callback on next animation
            Animations.Create( firstimage , 'width' , 2000 , firstimage.offsetWidth , 0 , function () {
                var bumper = document.getElementById( 'bumpstrip' );
                stripie = bumper.getElementsByTagName( 'div' )[ 0 ];
                firstimage = stripie.getElementsByTagName( 'div' )[ 0 ];
                respawn = firstimage.cloneNode( true );
                ractualimg = respawn.getElementsByTagName( 'img' )[ 0 ];
                stripie.removeChild( firstimage );
                Animations.SetAttribute( respawn , 'opacity' , 1 );
                respawn.style.width = '';
                ractualimg.style.width = '';
                stripie.appendChild( respawn );
            } );
        } , Interpolators.Sin );
        setTimeout( function () {
            Bumpstrip.Do();
        } , 5000 );
    }
};

setTimeout( function () {
    Bumpstrip.Do();
} , 5000 );
