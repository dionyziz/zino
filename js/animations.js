/*
	Developer: Dionyziz
*/

var Tween = {
	Interval: 25,
	LastUpdate: 0,
	Current: [],
	Init: function() {
		Tween.LastUpdate = ( new Date() ).valueOf();
		setInterval( Tween.Update, Tween.Interval );
	},
	Create: function( time, start, end, interpolator, callback_onprogress /* function (value) */ , callback_ondone /* function () */ ) {
		Tween.Current[ Tween.Current.length ] = {
			'Start': start,
			'End': end,
			'Time': time,
			'Callback_OnProgress': callback_onprogress,
			'Callback_OnDone': callback_ondone,
			'Position': 0,
			'Enabled': true,
			'Interpolator': interpolator
		};
		return Tween.Current.length - 1;
	},
	Break: function ( id ) {
		Tween.Current[ id ].Enabled = false;
		Tween.Current[ id ].Callback_OnDone();
	},
	Update: function() {
		now = ( new Date() ).valueOf();
		interval = now - Tween.LastUpdate;
		Tween.LastUpdate += interval;
		for ( i in Tween.Current ) {
			thing = Tween.Current[ i ];
			if ( thing.Enabled ) {
				thing.Position += interval / thing.Time;
				if ( thing.Position >= 1 ) {
					thing.Position = 1;
					thing.Enabled = false;
				}
				value = thing.Start + thing.Interpolator( thing.Position ) * ( thing.End - thing.Start );
				thing.Callback_OnProgress( value );
				if ( thing.Position == 1 ) {
					thing.Callback_OnDone();
				}
			}
		}
	}
};

var Interpolators = {
	Identity: function ( x ) {
		return x;
	},
	Factor: function ( factor , interpolator ) {
		return function ( x ) {
			return factor * interpolator( x );
		};
	},
	Multiply: function ( a , b ) {
		return function ( x ) {
			return a( b( x ) );
		};
	},
	PulseReal: function ( x ) { // Pulse interpolator from http://stereopsis.com/stopping/
		var PULSE_SCALE = 8;
		
		x *= PULSE_SCALE;
		if ( x < 1 ) {
			ret = x - ( 1 - Math.exp( -x ) );
		}
		else {
			start = Math.exp( -1 );
			x -= 1;
			expx = 1 - Math.exp( -x );
			ret = start + ( expx * ( 1 - start ) );
		}
		return ret;
	},
	PulseNormalize: 1,
	Pulse: function ( x ) {
		if ( Interpolators.PulseNormalize == 1 ) {
			Interpolators.PulseNormalize = 1 / Interpolators.PulseReal( 1 );
		}
		
		return Interpolators.PulseReal( x ) * Interpolators.PulseNormalize;
	},
	Sin: function ( x ) {
		return Math.sin( x * Math.PI / 2 );
	}
};

var Animations = {
	Current: [],
	Create: function( node, attribute, time, start, end, callback_ondone, interpolator ) {
		if ( interpolator === undefined ) {
			interpolator = Interpolators.Pulse;
		}
		if ( start === false ) {
			start = Animations.GetAttribute( node , attribute );
		}
		index = Animations.Current.length;
		Animations.Current[ index ] = {
			'Object': node,
			'Attribute': attribute,
			'Callback_OnDone': callback_ondone,
			'Tween': Tween.Create( time, start, end, interpolator, function ( id ) {
						return function ( value ) {
							Animations.Callback_OnProgress( id, value );
						};
					}( index ), function ( id ) {
						return function () {
							Animations.Callback_OnDone( id );
						};
					}( index ) )
		};
        return index;
	},
	Break: function ( id ) {
		return Tween.Break( Animations.Current[ id ].Tween );
	},
	Callback_OnProgress: function ( id, value ) {
		Animations.SetAttribute( Animations.Current[ id ].Object , Animations.Current[ id ].Attribute , value );
	},
	SetAttribute: function ( object , attribute , value ) {
		switch ( attribute ) {
			case 'left':
			case 'top':
            case 'bottom':
            case 'right':
			case 'width':
			case 'height':
				object.style[ attribute ] = value + 'px';
				break;
			case 'opacity':
				object.style[ attribute ] = value;
                if (object.style.filter !== null) {
                    object.style.filter = 'progid:DXImageTransform.Microsoft.Alpha(opacity=' + value * 100 + ')';
                }
				break;
            case 'fontsize':
                object.style.fontSize = value;
		}
	},
	GetAttribute: function ( object , attribute ) {
		switch ( attribute ) {
			case 'left':
			case 'top':
			case 'width':
			case 'height':
				return object.style[ attribute ].substr( 0 , object.style[ attribute ].length - 2 ); // -px
			case 'opacity':
				return object.style[ attribute ];
            case 'fontsize':
                return object.style.fontSize;
			default:
		}
	},
	Callback_OnDone: function ( id ) {
        if ( typeof Animations.Current[ id ].Callback_OnDone == 'function' ) {
            Animations.Current[ id ].Callback_OnDone();
        }
	},
	Init: function () {
		// pass
	}
};

Tween.Init();
Animations.Init();
