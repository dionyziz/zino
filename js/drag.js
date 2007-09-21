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
        Drag.Current[ idx ].Informed = false;
        Drag.Current[ idx ].X = mouse[ 0 ];
        Drag.Current[ idx ].Y = mouse[ 1 ];
        Drag.Current[ idx ].InitX = Drag.Current[ idx ].Node.offsetLeft;
        Drag.Current[ idx ].InitY = Drag.Current[ idx ].Node.offsetTop;
        document.onmousemove = Drag.Process;
        
        Drag.Do( e, idx );
    },
    Inform: function ( idx ) {
        if ( !Drag.Current[ idx ].Informed ) {
            Drag.Current[ idx ].Informed = true;
            if ( typeof Drag.Current[ idx ].Callback_OnStart == 'function' ) {
                Drag.Current[ idx ].Callback_OnStart( Drag.Current[ idx ].Node );
            }
        }
    },
    Uninform: function ( idx ) {
        if ( Drag.Current[ idx ].Informed ) {
            if ( typeof Drag.Current[ idx ].Callback_OnEnd == 'function' ) {
                Drag.Current[ idx ].Callback_OnEnd( Drag.Current[ idx ].Node );
            }
        }
    }
    Do: function ( e, idx ) {
        mouse = Drag.GetMouseXY( e );
        what = Drag.Current[ idx ].Node;
        if ( what.style.position == 'relative' ) {
            x = mouse[ 0 ] - Drag.Current[ idx ].X;
            y = mouse[ 1 ] - Drag.Current[ idx ].Y;
        }
        else {
            x = mouse[ 0 ] - Drag.Current[ idx ].X + Drag.Current[ idx ].InitX;
            y = mouse[ 1 ] - Drag.Current[ idx ].Y + Drag.Current[ idx ].InitY;
        }
        if ( x + y > 3 ) {
            Drag.Inform( idx );
        }
        if ( Drag.Current[ idx ].Informed ) {
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
        
        Drag.Uninform( idx );
        
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
        if ( Drag.Current[ idx ].Node.style.position == 'relative' ) {
            var startx = Drag.Current[ idx ].Node.style.left.substr( 0, Drag.Current[ idx ].Node.style.left.length - 2 );
            var starty = Drag.Current[ idx ].Node.style.top.substr( 0, Drag.Current[ idx ].Node.style.top.length - 2 );
            var endx = 0;
            var endy = 0;
        }
        else {
            var startx = Drag.Current[ idx ].Node.offsetLeft;
            var starty = Drag.Current[ idx ].Node.offsetTop;
            var endx = Drag.Current[ idx ].InitX;
            var endy = Drag.Current[ idx ].InitY;
        }
        Animations.Create(
            Drag.Current[ idx ].Node, 'left', 500, startx, endx
        );
        Animations.Create(
            Drag.Current[ idx ].Node, 'top', 500, starty, endy
        );
    },
    _AddDroppable: function ( idx, droppable ) {
        Drag.Current[ idx ].Droppables.push( droppable );
        Drag.Current[ idx ].DropPositions.push( Drag.FindPos( droppable ) );
        Drag.Current[ idx ].DropOver.push( false );
    },
    _SetCallbackOnDrop: function ( idx, callback_ondrop ) {
        Drag.Current[ idx ].Callback_OnDrop = callback_ondrop;
    },
    _SetCallbackOnStart: function ( idx, callback_onstart ) {
        Drag.Current[ idx ].Callback_OnStart = callback_onstart;
    },
    _SetCallbackOnEnd: function ( idx, callback_onend ) {
        Drag.Current[ idx ].Callback_OnEnd = callback_onend;
    },
    _SetCallbackOnOver: function ( idx, callback_onover ) {
        Drag.Current[ idx ].Callback_OnOver = callback_onover;
    },
    _SetCallbackOnOut: function ( idx, callback_onout ) {
        Drag.Current[ idx ].Callback_OnOut = callback_onout;
    },
    Create: function ( what ) { 
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
        dropover = [];
        idx = Drag.Current.push( {
            'Node'            : what           ,
            'X'               : 0              ,
            'Y'               : 0              ,
            'Active'          : false          ,
            'Enabled'         : true           ,
            'Droppables'      : []             ,
            'DropPositions'   : []             ,
            'DropOver'        : []             ,
            'Callback_OnDrop' : function () {} ,
            'Callback_OnOver' : function () {} ,
            'Callback_OnOut'  : function () {} ,
            'Callback_OnStart': function () {} ,
            'Callback_OnEnd'  : function () {} ,
            'Informed'        : false
        } ) - 1;
        return {
            'AddDroppable': function ( droppable ) {
                Drag._AddDroppable( idx, droppable );
            },
            'SetOnDrop': function ( callback_ondrop ) {
                Drag._SetCallbackOnDrop( idx, callback_ondrop );
            },
            'SetOnOver': function ( callback_onover ) {
                Drag._SetCallbackOnOver( idx, callback_onover );
            },
            'SetOnOut': function ( callback_onout ) {
                Drag._SetCallbackOnOut( idx, callback_onout );
            },
            'SetOnStart': function ( callback_onstart ) {
                Drag._SetCallbackOnStart( idx, callback_onstart );
            },
            'SetOnEnd': function ( callback_onend ) {
                Drag._SetCallbackOnEnd( idx, callback_onend );
            },
            'Destroy': function () {
                Drag._Destroy( idx );
            }
        };
    },
    _Destroy: function ( idx ) {
        
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
