/*
    Developer: Dionysis Zindros <dionyziz@kamibu.com>
    
    Copyright (c) 2009, Kamibu
    
    All rights reserved.

    Redistribution and use in source and binary forms, with or without
    modification, are permitted provided that the following conditions are met:
    1. Redistributions of source code must retain the above copyright
       notice, this list of conditions and the following disclaimer.
    2. Redistributions in binary form must reproduce the above copyright
       notice, this list of conditions and the following disclaimer in the
       documentation and/or other materials provided with the distribution.
    3. All advertising materials mentioning features or use of this software
       must display the following acknowledgement:
       This product includes software developed by Kamibu.
    4. Neither the name of Kamibu, nor the
       names of its contributors may be used to endorse or promote products
       derived from this software without specific prior written permission.

    THIS SOFTWARE IS PROVIDED BY KAMIBU ''AS IS'' AND ANY
    EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
    WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
    DISCLAIMED. IN NO EVENT SHALL <COPYRIGHT HOLDER> BE LIABLE FOR ANY
    DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
    (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
    LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
    ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
    (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
    SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/

var Puffin = {
    clickable: function ( element ) {
        Puffin.attachEvent( element, 'mousedown', function ( e ) {
            if ( !e ) {
                var e = window.event;
            }
            e.throughClickableElement = true;
        }, false );
    },
    currentZ: 100, // starting Z-index
    clientWidth: function () {
        if ( typeof window.innerWidth != 'undefined' ) {
            return window.innerWidth;
        }
        if ( document.documentElement.clientWidth == 0 ) {
            return document.body.clientWidth;
        }
        return document.documentElement.clientWidth;
    },
    clientHeight: function () {
        if ( typeof window.innerHeight != 'undefined' ) {
            return window.innerHeight;
        }
        if ( document.documentElement.clientHeight == 0 ) {
            return document.body.clientHeight;
        }
        return document.documentElement.clientHeight;
    },
    c: {
        RESIZE_N: 'N',
        RESIZE_NE: 'NE',
        RESIZE_E: 'E',
        RESIZE_SE: 'SE',
        RESIZE_S: 'S',
        RESIZE_SW: 'SW',
        RESIZE_W: 'W',
        RESIZE_NW: 'NW',
        RESIZE_BORDER: 6, // in pixels
        DIMENSION_HORIZONTAL: 1,
        DIMENSION_VERTICAL: 2,
        MIN_W: 20,
        MIN_H: 20,
    },
    attachEvent: function ( obj, onwhat, dowhat ) {
        var f;
        if ( typeof obj.addEventListener == 'undefined' ) {
            if ( typeof obj[ 'on' + onwhat ] == 'undefined' ) {
                f = obj[ 'on' + onwhat ];
            }
            else {
                f = function () {};
            }
            obj[ 'on' + onwhat ] = function() {
                f();
                dowhat();
            };
            return;
        }
        obj.addEventListener( onwhat, dowhat, false );
    },
    create: function () {
        var obj = {
            div: null,
            x: 0, y: 0, w: 0, h: 0, visible: false,
            movable: true, moving: false, resizing: false,
            minw: Puffin.c.MIN_W, minh: Puffin.c.MIN_H,
            minSize: function ( minw, minh ) {
                this.minw = minw;
                this.minh = minh;
            },
            move: function ( x, y ) {
                // check window is within boundaries
                if ( x < 0 ) {
                    x = 0;
                }
                if ( y < 0 ) {
                    y = 0;
                }
                if ( x > this.maxx - this.w ) {
                    x = this.maxx - this.w;
                }
                if ( y > this.maxy - this.h ) {
                    y = this.maxy - this.h;
                }
                
                var ret = 0;
                if ( this.x != x ) {
                    ret |= Puffin.c.DIMENSION_HORIZONTAL;
                }
                if ( this.y != y ) {
                    ret |= Puffin.c.DIMENSION_VERTICAL;
                }
                
                this.x = x;
                this.y = y;
                this.div.style.left = x + 'px';
                this.div.style.top = y + 'px';

                return ret;
            },
            resize: function ( w, h ) {
                if ( w < this.minw ) {
                    w = this.minw;
                }
                if ( h < this.minh ) {
                    h = this.minh;
                }
                if ( w > this.maxx ) {
                    w = this.maxx;
                }
                if ( h > this.maxy ) {
                    h = this.maxy;
                }
                
                var ret = 0;
                if ( this.w != w ) {
                    ret |= Puffin.c.DIMENSION_HORIZONTAL;
                }
                if ( this.h != h ) {
                    ret |= Puffin.c.DIMENSION_VERTICAL;
                }
                
                this.w = w;
                this.h = h;
                this.div.style.width = w + 'px';
                this.div.style.height = h + 'px';
                
                return ret;
            },
            focus: function () {
                this.div.style.zIndex = Puffin.currentZ;
                ++Puffin.currentZ;
            },
            setContent: function ( node ) {
                if ( typeof node == 'string' ) {
                    this.div.innerHTML = node;
                }
                else {
                    var content = node.cloneNode( true );
                    content.style.display = 'block';
                    this.div.appendChild( content );
                }

                // return the winow div node; can be used to manipulate particular window contents
                // that require the returned puffin window object (e.g. a close link)
                return this.div;
            },
            hide: function () {
                this.visible = false;
                this.div.style.display = 'none';
            },
            show: function () {
                this.visible = true;
                this.div.style.display = 'block';
            },
            onmove: function ( x, y ) {
                // overwrite me
            },
            onresize: function ( w, h ) {
                // overwrite me
            },
            __construct: function () {
                this.div = document.createElement( 'div' );
                this.div.className = 'puffin';
                this.focus();
                Puffin.attachEvent( this.div, 'mousedown', ( function( me ) {
                    return function( e ) {
                        if ( !e ) {
                            e = window.event;
                        }
                        if ( e.throughClickableElement ) {
                            // do not allow window move initiation when user clicks on "clickable" in-window
                            // elements such as textboxes or links
                            return;
                        }
                        me.focus()

                        // position of mouse within window
                        var xpos = e.clientX - me.x;
                        var ypos = e.clientY - me.y;
                        var resizeType = Puffin.getResizeType( me, { x: e.clientX, y: e.clientY } );
                        if ( resizeType !== false ) {
                            me.resizing = resizeType;
                            document.body.style.cursor = resizeType + '-resize';
                        }
                        else {
                            me.moving = true;
                            document.body.style.cursor = 'move';
                            me.div.style.cursor = 'move';
                        }
                        me.capture = {
                            clientX: e.clientX,
                            clientY: e.clientY,
                            x: me.x, y: me.y,
                            h: me.h, w: me.w
                        };
                        return false;
                    };
                } )( this ), false );
                var f = function () {};
                if ( typeof document.onmouseup == 'function' ) {
                    var f = document.onmouseup;
                }
                document.onmouseup = ( function( me, f ) {
                    return function() {
                        if ( me.moving ) {
                            me.onmove( me.x, me.y );
                        }
                        if ( me.resizing ) {
                            me.onresize( me.w, me.h );
                        }
                        me.moving = false;
                        me.resizing = false;
                        document.body.style.cursor = 'default';
                        me.div.style.cursor = 'default';
                        f();
                        return false;
                    };
                } )( this, f );
                Puffin.attachEvent( document, 'mousemove', ( function( me, f ) {
                    return function( e ) {
                        if ( !e ) {
                            e = window.event;
                        }
                        if ( me.moving ) {
                            me.move( e.clientX - me.capture.clientX + me.capture.x, e.clientY - me.capture.clientY + me.capture.y );
                        }
                        if ( me.resizing !== false ) {
                            var cx = e.clientX;
                            var cy = e.clientY;

                            if ( cx < 0 ) {
                                cx = 0;
                            }
                            if ( cy < 0 ) {
                                cy = 0;
                            }
                            if ( cx > me.maxx ) {
                                cx = me.maxx;
                            }
                            if ( cy > me.maxy ) {
                                cy = me.maxy;
                            }

                            var coords = Puffin.resizeEval( me, me.resizing, me.capture, { x: cx, y: cy } );
                            var resized = me.resize( coords[ 2 ], coords[ 3 ] );
                            var x = coords[ 0 ];
                            var y = coords[ 1 ];

                            if ( ( resized & Puffin.c.DIMENSION_HORIZONTAL ) == 0 ) {
                                // do not move window horizontally if it was not resized horizontally
                                x = me.x;
                            }
                            if ( ( resized & Puffin.c.DIMENSION_VERTICAL ) == 0 ) {
                                // do not move window vertically if it was not resized vertically
                                y = me.y;
                            }
                            me.move( x, y );
                        }
                        return false;
                    };
                } )( this, f ), false );
                this.div.onmousemove = ( function ( me ) {
                    return function ( e ) {
                        if ( !e ) {
                            e = window.event;
                        }
                        if ( me.moving || me.resizing !== false ) {
                            // move/resize system will take care of mouse cursor; do not override
                            return false;
                        }
                        // change mouse to current resize cursor
                        var resizeType = Puffin.getResizeType( me, { x: e.clientX, y: e.clientY } );
                        if ( resizeType === false ) {
                            me.div.style.cursor = 'default';
                        }
                        else {
                            me.div.style.cursor = resizeType + '-resize';
                        }
                        return false;
                    }
                } )( this );
                var f = function () {};
                if ( typeof window.onresize == 'function' ) {
                    f = window.onresize;
                }
                window.onresize = ( function ( me, f ) {
                    return function () {
                        me.maxx = Puffin.clientWidth();
                        me.maxy = Puffin.clientHeight();
                        // fix window position
                        me.resize( me.w, me.h );
                        me.move( me.x, me.y );
                        f();
                    }
                } )( this, f );
                this.hide();
                document.body.appendChild( this.div );
                window.onresize();
            }
        };
        obj.__construct();
        return obj;
    },
    resizeEval: function ( obj, direction, capture, mouse ) {
        var combine = function ( a, b ) {
            var c = a;
            
            if ( a[ 0 ] == obj.x ) {
                c[ 0 ] = b[ 0 ];
            }
            if ( a[ 1 ] == obj.y ) {
                c[ 1 ] = b[ 1 ];
            }
            if ( a[ 2 ] == obj.w ) {
                c[ 2 ] = b[ 2 ];
            }
            if ( a[ 3 ] == obj.h ) {
                c[ 3 ] = b[ 3 ];
            }
            
            return c;
        }
        var get = function ( dir ) {
            switch ( dir ) {
                case Puffin.c.RESIZE_N:
                    return [
                        obj.x, capture.y - capture.clientY + mouse.y,
                        obj.w, capture.clientY + capture.h - mouse.y 
                    ];
                case Puffin.c.RESIZE_W:
                    return [
                        capture.x - capture.clientX + mouse.x, obj.y,
                        capture.clientX + capture.w - mouse.x, obj.h
                    ];
                case Puffin.c.RESIZE_S:
                    return [
                        obj.x, obj.y,
                        obj.w, capture.h + mouse.y - capture.clientY
                    ];
                case Puffin.c.RESIZE_E:
                    return [
                        obj.x, obj.y,
                        capture.w + mouse.x - capture.clientX, obj.h
                    ];
                case Puffin.c.RESIZE_NW:
                    return combine( get( Puffin.c.RESIZE_N ), get( Puffin.c.RESIZE_W ) );
                case Puffin.c.RESIZE_NE:
                    return combine( get( Puffin.c.RESIZE_N ), get( Puffin.c.RESIZE_E ) );
                case Puffin.c.RESIZE_SE:
                    return combine( get( Puffin.c.RESIZE_S ), get( Puffin.c.RESIZE_E ) );
                case Puffin.c.RESIZE_SW:
                    return combine( get( Puffin.c.RESIZE_S ), get( Puffin.c.RESIZE_W ) );
            }
        };
        return get( direction );
    },
    getResizeType: function ( obj, mouse ) {
        var xx = 0;
        var yy = 0;
        
        if ( mouse.x >= obj.x && mouse.x < obj.x + Puffin.c.RESIZE_BORDER ) {
            xx = -1;
        }
        if ( mouse.x <= obj.x + obj.w && mouse.x > obj.x + obj.w - Puffin.c.RESIZE_BORDER ) {
            xx = 1;
        }
        if ( mouse.y >= obj.y && mouse.y < obj.y + Puffin.c.RESIZE_BORDER ) {
            yy = -1;
        }
        if ( mouse.y <= obj.y + obj.h && mouse.y > obj.y + obj.h - Puffin.c.RESIZE_BORDER ) {
            yy = 1;
        }
        
        switch ( xx ) {
            case -1:
                switch ( yy ) {
                    case -1:
                        return Puffin.c.RESIZE_NW;
                    case 1:
                        return Puffin.c.RESIZE_SW;
                }
                return Puffin.c.RESIZE_W;
            case 1:
                switch ( yy ) {
                    case -1:
                        return Puffin.c.RESIZE_NE;
                    case 1:
                        return Puffin.c.RESIZE_SE;
                }
                return Puffin.c.RESIZE_E;
        }
        switch ( yy ) {
            case -1:
                return Puffin.c.RESIZE_N;
            case 1:
                return Puffin.c.RESIZE_S;
        }
        return false;
    }
};
