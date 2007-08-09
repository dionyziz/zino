/*
    Developer: Dionyziz
*/

var Drag = {
    Current: [],
    FindPos: function ( obj ) {
        if ( obj.offsetParent ) {
            x = 0;
            y = 0;
            while ( obj.offsetParent ) {
                x += obj.offsetLeft;
                y += obj.offsetTop;
                obj = obj.offsetParent;
            }
            return [ x, y ];
        }
        else if ( obj.x ) {
            return [ obj.x, obj.y ];
        }
    },
    Process: function ( e ) {
        for ( i in Drag.Current ) {
            me = Drag.Current[ i ];
            if ( me.Active ) {
                Drag.Do( e , i );
            }
        }
    },
    Start: function ( e, idx ) {
        mouse = Drag.GetMouseXY( e );
        Drag.Current[ idx ].Active = true;
        Drag.Current[ idx ].X = mouse[ 0 ];
        Drag.Current[ idx ].Y = mouse[ 1 ];
        Drag.Current[ idx ].InitX = Drag.Current[ idx ].Node.offsetLeft;
        Drag.Current[ idx ].InitY = Drag.Current[ idx ].Node.offsetTop;
        document.onmousemove = Drag.Process;
        
        if ( typeof Drag.Current[ idx ].Callback_OnStart == 'function' ) {
            Drag.Current[ idx ].Callback_OnStart( Drag.Current[ idx ].Node );
        }
        Drag.Do( e, idx );
    },
    Do: function ( e, idx ) {
        mouse = Drag.GetMouseXY( e );
        what = Drag.Current[ idx ].Node;
        x = mouse[ 0 ] - Drag.Current[ idx ].X + Drag.Current[ idx ].InitX;
        y = mouse[ 1 ] - Drag.Current[ idx ].Y + Drag.Current[ idx ].InitY;
        what.style.left = x + 'px';
        what.style.top = y + 'px';

        // hittest
        for ( i in Drag.Current[ idx ].Droppables ) {
            droppable = Drag.Current[ idx ].Droppables[ i ];
            xy = Drag.Current[ idx ].DropPositions[ i ];
            if ( Drag.HitTest( mouse, xy, [ droppable.offsetWidth, droppable.offsetHeight ] ) ) {
                if ( typeof Drag.Current[ idx ].Callback_OnOver == 'function' ) {
                    if ( !Drag.Current[ idx ].DropOver[ i ] ) {
                        Drag.Current[ idx ].DropOver[ i ] = true;
                        Drag.Current[ idx ].Callback_OnOver( Drag.Current[ idx ].Node, droppable );
                    }
                }
            }
            else {
                if ( typeof Drag.Current[ idx ].Callback_OnOut == 'function' ) {
                    if ( Drag.Current[ idx ].DropOver[ i ] ) {
                        Drag.Current[ idx ].DropOver[ i ] = false;
                        Drag.Current[ idx ].Callback_OnOut( Drag.Current[ idx ].Node, droppable );
                    }
                }
            }
        }
    },
    HitTest: function ( mouse, xy, wh ) {
        return mouse[ 0 ] > xy[ 0 ] && mouse[ 0 ] < xy[ 0 ] + wh[ 0 ] &&
               mouse[ 1 ] > xy[ 1 ] && mouse[ 1 ] < xy[ 1 ] + wh[ 1 ];
    },
    Stop: function ( e, idx ) {
        var droppable;
        var mouse = Drag.GetMouseXY( e );
        
        Drag.Current[ idx ].Active = false;
        Drag.Current[ idx ].Node.onmousemove = function() {};

        for ( i in Drag.Current[ idx ].Droppables ) {
            if ( Drag.Current[ idx ].DropOver[ i ] ) {
                if ( typeof Drag.Current[ idx ].Callback_OnOut == 'function' ) {
                    Drag.Current[ idx ].DropOver[ i ] = false;
                    Drag.Current[ idx ].Callback_OnOut( Drag.Current[ idx ].Ndoe, Drag.Current[ idx ].Droppables[ i ] );
                }
            }
        }
        
        if ( typeof Drag.Current[ idx ].Callback_OnEnd == 'function' ) {
            Drag.Current[ idx ].Callback_OnEnd( Drag.Current[ idx ].Node );
        }
        
        for ( i in Drag.Current[ idx ].Droppables ) {
            droppable = Drag.Current[ idx ].Droppables[ i ];
            xy = Drag.Current[ idx ].DropPositions[ i ];
            if ( Drag.HitTest( mouse, xy, [ droppable.offsetWidth, droppable.offsetHeight ] ) ) {
                if ( typeof Drag.Current[ idx ].Callback_OnDrop == 'function' ) {
                    Drag.Current[ idx ].Callback_OnDrop( Drag.Current[ idx ].Node, droppable );
                }
                return;
            }
        }
        
        // breach
        
        Animations.Create(
            Drag.Current[ idx ].Node, 'left', 500, Drag.Current[ idx ].Node.offsetLeft, Drag.Current[ idx ].InitX
        );
        Animations.Create(
            Drag.Current[ idx ].Node, 'top', 500, Drag.Current[ idx ].Node.offsetTop, Drag.Current[ idx ].InitY
        );
    },
    Create: function ( what, droppables, callback_ondrop, callback_onstart, callback_onend, callback_onover, callback_onout ) {
        what.onmousedown = (function ( idx ) {
            return function ( e ) {
                Drag.Start( e , idx );
            };
        })( Drag.Current.length );
        what.onmouseup   = function ( idx ) {
            return function ( e ) {
                Drag.Stop( e , idx );
            };
        }( Drag.Current.length );
        droppositions = [];
        for ( i in droppables ) {
            droppositions[ i ] = Drag.FindPos( droppables[ i ] );
        }
        dropover = [];
        for ( i in droppables ) {
            dropover[ i ] = false;
        }
        Drag.Current[ Drag.Current.length ] = {
            'Node'            : what            ,
            'X'               : 0               ,
            'Y'               : 0               ,
            'Active'          : false           ,
            'Enabled'         : true            ,
            'Droppables'      : droppables      ,
            'DropPositions'   : droppositions   ,
            'DropOver'        : dropover        ,
            'Callback_OnDrop' : callback_ondrop ,
            'Callback_OnOver' : callback_onover ,
            'Callback_OnOut'  : callback_onout  ,
            'Callback_OnStart': callback_onstart,
            'Callback_OnEnd'  : callback_onend
        };
        return Drag.Current.length - 1;
    },
    Destroy: function ( idx ) {
        
    },
    GetMouseXY: function( e ) {
        // this function gets current mouse position
        // and stores it in (mousex, mousey)
        if ( !e ) {
            e = window.event; // works on IE, but not NS (we rely on NS passing us the event)
        }
        
        if ( e ) { 
            if ( e.pageX || e.pageY ) { // this doesn't work on IE6!! (works on FF,Moz,Opera7)
                mousex = e.pageX;
                mousey = e.pageY;
            }
            else if ( e.clientX || e.clientY ) { // works on IE6,FF,Moz,Opera7
                mousex = e.clientX + document.body.scrollLeft;
                mousey = e.clientY + document.body.scrollTop;
            }
        }
        
        return [ mousex, mousey ];
    }
};
