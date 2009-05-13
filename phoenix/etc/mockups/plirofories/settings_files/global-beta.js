/*!
 * jQuery JavaScript Library v1.3.2
 * http://jquery.com/
 *
 * Copyright (c) 2009 John Resig
 * Dual licensed under the MIT and GPL licenses.
 * http://docs.jquery.com/License
 *
 * Date: 2009-02-19 17:34:21 -0500 (Thu, 19 Feb 2009)
 * Revision: 6246
 */
(function(){

var 
	// Will speed up references to window, and allows munging its name.
	window = this,
	// Will speed up references to undefined, and allows munging its name.
	undefined,
	// Map over jQuery in case of overwrite
	_jQuery = window.jQuery,
	// Map over the $ in case of overwrite
	_$ = window.$,

	jQuery = window.jQuery = window.$ = function( selector, context ) {
		// The jQuery object is actually just the init constructor 'enhanced'
		return new jQuery.fn.init( selector, context );
	},

	// A simple way to check for HTML strings or ID strings
	// (both of which we optimize for)
	quickExpr = /^[^<]*(<(.|\s)+>)[^>]*$|^#([\w-]+)$/,
	// Is it a simple selector
	isSimple = /^.[^:#\[\.,]*$/;

jQuery.fn = jQuery.prototype = {
	init: function( selector, context ) {
		// Make sure that a selection was provided
		selector = selector || document;

		// Handle $(DOMElement)
		if ( selector.nodeType ) {
			this[0] = selector;
			this.length = 1;
			this.context = selector;
			return this;
		}
		// Handle HTML strings
		if ( typeof selector === "string" ) {
			// Are we dealing with HTML string or an ID?
			var match = quickExpr.exec( selector );

			// Verify a match, and that no context was specified for #id
			if ( match && (match[1] || !context) ) {

				// HANDLE: $(html) -> $(array)
				if ( match[1] )
					selector = jQuery.clean( [ match[1] ], context );

				// HANDLE: $("#id")
				else {
					var elem = document.getElementById( match[3] );

					// Handle the case where IE and Opera return items
					// by name instead of ID
					if ( elem && elem.id != match[3] )
						return jQuery().find( selector );

					// Otherwise, we inject the element directly into the jQuery object
					var ret = jQuery( elem || [] );
					ret.context = document;
					ret.selector = selector;
					return ret;
				}

			// HANDLE: $(expr, [context])
			// (which is just equivalent to: $(content).find(expr)
			} else
				return jQuery( context ).find( selector );

		// HANDLE: $(function)
		// Shortcut for document ready
		} else if ( jQuery.isFunction( selector ) )
			return jQuery( document ).ready( selector );

		// Make sure that old selector state is passed along
		if ( selector.selector && selector.context ) {
			this.selector = selector.selector;
			this.context = selector.context;
		}

		return this.setArray(jQuery.isArray( selector ) ?
			selector :
			jQuery.makeArray(selector));
	},

	// Start with an empty selector
	selector: "",

	// The current version of jQuery being used
	jquery: "1.3.2",

	// The number of elements contained in the matched element set
	size: function() {
		return this.length;
	},

	// Get the Nth element in the matched element set OR
	// Get the whole matched element set as a clean array
	get: function( num ) {
		return num === undefined ?

			// Return a 'clean' array
			Array.prototype.slice.call( this ) :

			// Return just the object
			this[ num ];
	},

	// Take an array of elements and push it onto the stack
	// (returning the new matched element set)
	pushStack: function( elems, name, selector ) {
		// Build a new jQuery matched element set
		var ret = jQuery( elems );

		// Add the old object onto the stack (as a reference)
		ret.prevObject = this;

		ret.context = this.context;

		if ( name === "find" )
			ret.selector = this.selector + (this.selector ? " " : "") + selector;
		else if ( name )
			ret.selector = this.selector + "." + name + "(" + selector + ")";

		// Return the newly-formed element set
		return ret;
	},

	// Force the current matched set of elements to become
	// the specified array of elements (destroying the stack in the process)
	// You should use pushStack() in order to do this, but maintain the stack
	setArray: function( elems ) {
		// Resetting the length to 0, then using the native Array push
		// is a super-fast way to populate an object with array-like properties
		this.length = 0;
		Array.prototype.push.apply( this, elems );

		return this;
	},

	// Execute a callback for every element in the matched set.
	// (You can seed the arguments with an array of args, but this is
	// only used internally.)
	each: function( callback, args ) {
		return jQuery.each( this, callback, args );
	},

	// Determine the position of an element within
	// the matched set of elements
	index: function( elem ) {
		// Locate the position of the desired element
		return jQuery.inArray(
			// If it receives a jQuery object, the first element is used
			elem && elem.jquery ? elem[0] : elem
		, this );
	},

	attr: function( name, value, type ) {
		var options = name;

		// Look for the case where we're accessing a style value
		if ( typeof name === "string" )
			if ( value === undefined )
				return this[0] && jQuery[ type || "attr" ]( this[0], name );

			else {
				options = {};
				options[ name ] = value;
			}

		// Check to see if we're setting style values
		return this.each(function(i){
			// Set all the styles
			for ( name in options )
				jQuery.attr(
					type ?
						this.style :
						this,
					name, jQuery.prop( this, options[ name ], type, i, name )
				);
		});
	},

	css: function( key, value ) {
		// ignore negative width and height values
		if ( (key == 'width' || key == 'height') && parseFloat(value) < 0 )
			value = undefined;
		return this.attr( key, value, "curCSS" );
	},

	text: function( text ) {
		if ( typeof text !== "object" && text != null )
			return this.empty().append( (this[0] && this[0].ownerDocument || document).createTextNode( text ) );

		var ret = "";

		jQuery.each( text || this, function(){
			jQuery.each( this.childNodes, function(){
				if ( this.nodeType != 8 )
					ret += this.nodeType != 1 ?
						this.nodeValue :
						jQuery.fn.text( [ this ] );
			});
		});

		return ret;
	},

	wrapAll: function( html ) {
		if ( this[0] ) {
			// The elements to wrap the target around
			var wrap = jQuery( html, this[0].ownerDocument ).clone();

			if ( this[0].parentNode )
				wrap.insertBefore( this[0] );

			wrap.map(function(){
				var elem = this;

				while ( elem.firstChild )
					elem = elem.firstChild;

				return elem;
			}).append(this);
		}

		return this;
	},

	wrapInner: function( html ) {
		return this.each(function(){
			jQuery( this ).contents().wrapAll( html );
		});
	},

	wrap: function( html ) {
		return this.each(function(){
			jQuery( this ).wrapAll( html );
		});
	},

	append: function() {
		return this.domManip(arguments, true, function(elem){
			if (this.nodeType == 1)
				this.appendChild( elem );
		});
	},

	prepend: function() {
		return this.domManip(arguments, true, function(elem){
			if (this.nodeType == 1)
				this.insertBefore( elem, this.firstChild );
		});
	},

	before: function() {
		return this.domManip(arguments, false, function(elem){
			this.parentNode.insertBefore( elem, this );
		});
	},

	after: function() {
		return this.domManip(arguments, false, function(elem){
			this.parentNode.insertBefore( elem, this.nextSibling );
		});
	},

	end: function() {
		return this.prevObject || jQuery( [] );
	},

	// For internal use only.
	// Behaves like an Array's method, not like a jQuery method.
	push: [].push,
	sort: [].sort,
	splice: [].splice,

	find: function( selector ) {
		if ( this.length === 1 ) {
			var ret = this.pushStack( [], "find", selector );
			ret.length = 0;
			jQuery.find( selector, this[0], ret );
			return ret;
		} else {
			return this.pushStack( jQuery.unique(jQuery.map(this, function(elem){
				return jQuery.find( selector, elem );
			})), "find", selector );
		}
	},

	clone: function( events ) {
		// Do the clone
		var ret = this.map(function(){
			if ( !jQuery.support.noCloneEvent && !jQuery.isXMLDoc(this) ) {
				// IE copies events bound via attachEvent when
				// using cloneNode. Calling detachEvent on the
				// clone will also remove the events from the orignal
				// In order to get around this, we use innerHTML.
				// Unfortunately, this means some modifications to
				// attributes in IE that are actually only stored
				// as properties will not be copied (such as the
				// the name attribute on an input).
				var html = this.outerHTML;
				if ( !html ) {
					var div = this.ownerDocument.createElement("div");
					div.appendChild( this.cloneNode(true) );
					html = div.innerHTML;
				}

				return jQuery.clean([html.replace(/ jQuery\d+="(?:\d+|null)"/g, "").replace(/^\s*/, "")])[0];
			} else
				return this.cloneNode(true);
		});

		// Copy the events from the original to the clone
		if ( events === true ) {
			var orig = this.find("*").andSelf(), i = 0;

			ret.find("*").andSelf().each(function(){
				if ( this.nodeName !== orig[i].nodeName )
					return;

				var events = jQuery.data( orig[i], "events" );

				for ( var type in events ) {
					for ( var handler in events[ type ] ) {
						jQuery.event.add( this, type, events[ type ][ handler ], events[ type ][ handler ].data );
					}
				}

				i++;
			});
		}

		// Return the cloned set
		return ret;
	},

	filter: function( selector ) {
		return this.pushStack(
			jQuery.isFunction( selector ) &&
			jQuery.grep(this, function(elem, i){
				return selector.call( elem, i );
			}) ||

			jQuery.multiFilter( selector, jQuery.grep(this, function(elem){
				return elem.nodeType === 1;
			}) ), "filter", selector );
	},

	closest: function( selector ) {
		var pos = jQuery.expr.match.POS.test( selector ) ? jQuery(selector) : null,
			closer = 0;

		return this.map(function(){
			var cur = this;
			while ( cur && cur.ownerDocument ) {
				if ( pos ? pos.index(cur) > -1 : jQuery(cur).is(selector) ) {
					jQuery.data(cur, "closest", closer);
					return cur;
				}
				cur = cur.parentNode;
				closer++;
			}
		});
	},

	not: function( selector ) {
		if ( typeof selector === "string" )
			// test special case where just one selector is passed in
			if ( isSimple.test( selector ) )
				return this.pushStack( jQuery.multiFilter( selector, this, true ), "not", selector );
			else
				selector = jQuery.multiFilter( selector, this );

		var isArrayLike = selector.length && selector[selector.length - 1] !== undefined && !selector.nodeType;
		return this.filter(function() {
			return isArrayLike ? jQuery.inArray( this, selector ) < 0 : this != selector;
		});
	},

	add: function( selector ) {
		return this.pushStack( jQuery.unique( jQuery.merge(
			this.get(),
			typeof selector === "string" ?
				jQuery( selector ) :
				jQuery.makeArray( selector )
		)));
	},

	is: function( selector ) {
		return !!selector && jQuery.multiFilter( selector, this ).length > 0;
	},

	hasClass: function( selector ) {
		return !!selector && this.is( "." + selector );
	},

	val: function( value ) {
		if ( value === undefined ) {			
			var elem = this[0];

			if ( elem ) {
				if( jQuery.nodeName( elem, 'option' ) )
					return (elem.attributes.value || {}).specified ? elem.value : elem.text;
				
				// We need to handle select boxes special
				if ( jQuery.nodeName( elem, "select" ) ) {
					var index = elem.selectedIndex,
						values = [],
						options = elem.options,
						one = elem.type == "select-one";

					// Nothing was selected
					if ( index < 0 )
						return null;

					// Loop through all the selected options
					for ( var i = one ? index : 0, max = one ? index + 1 : options.length; i < max; i++ ) {
						var option = options[ i ];

						if ( option.selected ) {
							// Get the specifc value for the option
							value = jQuery(option).val();

							// We don't need an array for one selects
							if ( one )
								return value;

							// Multi-Selects return an array
							values.push( value );
						}
					}

					return values;				
				}

				// Everything else, we just grab the value
				return (elem.value || "").replace(/\r/g, "");

			}

			return undefined;
		}

		if ( typeof value === "number" )
			value += '';

		return this.each(function(){
			if ( this.nodeType != 1 )
				return;

			if ( jQuery.isArray(value) && /radio|checkbox/.test( this.type ) )
				this.checked = (jQuery.inArray(this.value, value) >= 0 ||
					jQuery.inArray(this.name, value) >= 0);

			else if ( jQuery.nodeName( this, "select" ) ) {
				var values = jQuery.makeArray(value);

				jQuery( "option", this ).each(function(){
					this.selected = (jQuery.inArray( this.value, values ) >= 0 ||
						jQuery.inArray( this.text, values ) >= 0);
				});

				if ( !values.length )
					this.selectedIndex = -1;

			} else
				this.value = value;
		});
	},

	html: function( value ) {
		return value === undefined ?
			(this[0] ?
				this[0].innerHTML.replace(/ jQuery\d+="(?:\d+|null)"/g, "") :
				null) :
			this.empty().append( value );
	},

	replaceWith: function( value ) {
		return this.after( value ).remove();
	},

	eq: function( i ) {
		return this.slice( i, +i + 1 );
	},

	slice: function() {
		return this.pushStack( Array.prototype.slice.apply( this, arguments ),
			"slice", Array.prototype.slice.call(arguments).join(",") );
	},

	map: function( callback ) {
		return this.pushStack( jQuery.map(this, function(elem, i){
			return callback.call( elem, i, elem );
		}));
	},

	andSelf: function() {
		return this.add( this.prevObject );
	},

	domManip: function( args, table, callback ) {
		if ( this[0] ) {
			var fragment = (this[0].ownerDocument || this[0]).createDocumentFragment(),
				scripts = jQuery.clean( args, (this[0].ownerDocument || this[0]), fragment ),
				first = fragment.firstChild;

			if ( first )
				for ( var i = 0, l = this.length; i < l; i++ )
					callback.call( root(this[i], first), this.length > 1 || i > 0 ?
							fragment.cloneNode(true) : fragment );
		
			if ( scripts )
				jQuery.each( scripts, evalScript );
		}

		return this;
		
		function root( elem, cur ) {
			return table && jQuery.nodeName(elem, "table") && jQuery.nodeName(cur, "tr") ?
				(elem.getElementsByTagName("tbody")[0] ||
				elem.appendChild(elem.ownerDocument.createElement("tbody"))) :
				elem;
		}
	}
};

// Give the init function the jQuery prototype for later instantiation
jQuery.fn.init.prototype = jQuery.fn;

function evalScript( i, elem ) {
	if ( elem.src )
		jQuery.ajax({
			url: elem.src,
			async: false,
			dataType: "script"
		});

	else
		jQuery.globalEval( elem.text || elem.textContent || elem.innerHTML || "" );

	if ( elem.parentNode )
		elem.parentNode.removeChild( elem );
}

function now(){
	return +new Date;
}

jQuery.extend = jQuery.fn.extend = function() {
	// copy reference to target object
	var target = arguments[0] || {}, i = 1, length = arguments.length, deep = false, options;

	// Handle a deep copy situation
	if ( typeof target === "boolean" ) {
		deep = target;
		target = arguments[1] || {};
		// skip the boolean and the target
		i = 2;
	}

	// Handle case when target is a string or something (possible in deep copy)
	if ( typeof target !== "object" && !jQuery.isFunction(target) )
		target = {};

	// extend jQuery itself if only one argument is passed
	if ( length == i ) {
		target = this;
		--i;
	}

	for ( ; i < length; i++ )
		// Only deal with non-null/undefined values
		if ( (options = arguments[ i ]) != null )
			// Extend the base object
			for ( var name in options ) {
				var src = target[ name ], copy = options[ name ];

				// Prevent never-ending loop
				if ( target === copy )
					continue;

				// Recurse if we're merging object values
				if ( deep && copy && typeof copy === "object" && !copy.nodeType )
					target[ name ] = jQuery.extend( deep, 
						// Never move original objects, clone them
						src || ( copy.length != null ? [ ] : { } )
					, copy );

				// Don't bring in undefined values
				else if ( copy !== undefined )
					target[ name ] = copy;

			}

	// Return the modified object
	return target;
};

// exclude the following css properties to add px
var	exclude = /z-?index|font-?weight|opacity|zoom|line-?height/i,
	// cache defaultView
	defaultView = document.defaultView || {},
	toString = Object.prototype.toString;

jQuery.extend({
	noConflict: function( deep ) {
		window.$ = _$;

		if ( deep )
			window.jQuery = _jQuery;

		return jQuery;
	},

	// See test/unit/core.js for details concerning isFunction.
	// Since version 1.3, DOM methods and functions like alert
	// aren't supported. They return false on IE (#2968).
	isFunction: function( obj ) {
		return toString.call(obj) === "[object Function]";
	},

	isArray: function( obj ) {
		return toString.call(obj) === "[object Array]";
	},

	// check if an element is in a (or is an) XML document
	isXMLDoc: function( elem ) {
		return elem.nodeType === 9 && elem.documentElement.nodeName !== "HTML" ||
			!!elem.ownerDocument && jQuery.isXMLDoc( elem.ownerDocument );
	},

	// Evalulates a script in a global context
	globalEval: function( data ) {
		if ( data && /\S/.test(data) ) {
			// Inspired by code by Andrea Giammarchi
			// http://webreflection.blogspot.com/2007/08/global-scope-evaluation-and-dom.html
			var head = document.getElementsByTagName("head")[0] || document.documentElement,
				script = document.createElement("script");

			script.type = "text/javascript";
			if ( jQuery.support.scriptEval )
				script.appendChild( document.createTextNode( data ) );
			else
				script.text = data;

			// Use insertBefore instead of appendChild  to circumvent an IE6 bug.
			// This arises when a base node is used (#2709).
			head.insertBefore( script, head.firstChild );
			head.removeChild( script );
		}
	},

	nodeName: function( elem, name ) {
		return elem.nodeName && elem.nodeName.toUpperCase() == name.toUpperCase();
	},

	// args is for internal usage only
	each: function( object, callback, args ) {
		var name, i = 0, length = object.length;

		if ( args ) {
			if ( length === undefined ) {
				for ( name in object )
					if ( callback.apply( object[ name ], args ) === false )
						break;
			} else
				for ( ; i < length; )
					if ( callback.apply( object[ i++ ], args ) === false )
						break;

		// A special, fast, case for the most common use of each
		} else {
			if ( length === undefined ) {
				for ( name in object )
					if ( callback.call( object[ name ], name, object[ name ] ) === false )
						break;
			} else
				for ( var value = object[0];
					i < length && callback.call( value, i, value ) !== false; value = object[++i] ){}
		}

		return object;
	},

	prop: function( elem, value, type, i, name ) {
		// Handle executable functions
		if ( jQuery.isFunction( value ) )
			value = value.call( elem, i );

		// Handle passing in a number to a CSS property
		return typeof value === "number" && type == "curCSS" && !exclude.test( name ) ?
			value + "px" :
			value;
	},

	className: {
		// internal only, use addClass("class")
		add: function( elem, classNames ) {
			jQuery.each((classNames || "").split(/\s+/), function(i, className){
				if ( elem.nodeType == 1 && !jQuery.className.has( elem.className, className ) )
					elem.className += (elem.className ? " " : "") + className;
			});
		},

		// internal only, use removeClass("class")
		remove: function( elem, classNames ) {
			if (elem.nodeType == 1)
				elem.className = classNames !== undefined ?
					jQuery.grep(elem.className.split(/\s+/), function(className){
						return !jQuery.className.has( classNames, className );
					}).join(" ") :
					"";
		},

		// internal only, use hasClass("class")
		has: function( elem, className ) {
			return elem && jQuery.inArray( className, (elem.className || elem).toString().split(/\s+/) ) > -1;
		}
	},

	// A method for quickly swapping in/out CSS properties to get correct calculations
	swap: function( elem, options, callback ) {
		var old = {};
		// Remember the old values, and insert the new ones
		for ( var name in options ) {
			old[ name ] = elem.style[ name ];
			elem.style[ name ] = options[ name ];
		}

		callback.call( elem );

		// Revert the old values
		for ( var name in options )
			elem.style[ name ] = old[ name ];
	},

	css: function( elem, name, force, extra ) {
		if ( name == "width" || name == "height" ) {
			var val, props = { position: "absolute", visibility: "hidden", display:"block" }, which = name == "width" ? [ "Left", "Right" ] : [ "Top", "Bottom" ];

			function getWH() {
				val = name == "width" ? elem.offsetWidth : elem.offsetHeight;

				if ( extra === "border" )
					return;

				jQuery.each( which, function() {
					if ( !extra )
						val -= parseFloat(jQuery.curCSS( elem, "padding" + this, true)) || 0;
					if ( extra === "margin" )
						val += parseFloat(jQuery.curCSS( elem, "margin" + this, true)) || 0;
					else
						val -= parseFloat(jQuery.curCSS( elem, "border" + this + "Width", true)) || 0;
				});
			}

			if ( elem.offsetWidth !== 0 )
				getWH();
			else
				jQuery.swap( elem, props, getWH );

			return Math.max(0, Math.round(val));
		}

		return jQuery.curCSS( elem, name, force );
	},

	curCSS: function( elem, name, force ) {
		var ret, style = elem.style;

		// We need to handle opacity special in IE
		if ( name == "opacity" && !jQuery.support.opacity ) {
			ret = jQuery.attr( style, "opacity" );

			return ret == "" ?
				"1" :
				ret;
		}

		// Make sure we're using the right name for getting the float value
		if ( name.match( /float/i ) )
			name = styleFloat;

		if ( !force && style && style[ name ] )
			ret = style[ name ];

		else if ( defaultView.getComputedStyle ) {

			// Only "float" is needed here
			if ( name.match( /float/i ) )
				name = "float";

			name = name.replace( /([A-Z])/g, "-$1" ).toLowerCase();

			var computedStyle = defaultView.getComputedStyle( elem, null );

			if ( computedStyle )
				ret = computedStyle.getPropertyValue( name );

			// We should always get a number back from opacity
			if ( name == "opacity" && ret == "" )
				ret = "1";

		} else if ( elem.currentStyle ) {
			var camelCase = name.replace(/\-(\w)/g, function(all, letter){
				return letter.toUpperCase();
			});

			ret = elem.currentStyle[ name ] || elem.currentStyle[ camelCase ];

			// From the awesome hack by Dean Edwards
			// http://erik.eae.net/archives/2007/07/27/18.54.15/#comment-102291

			// If we're not dealing with a regular pixel number
			// but a number that has a weird ending, we need to convert it to pixels
			if ( !/^\d+(px)?$/i.test( ret ) && /^\d/.test( ret ) ) {
				// Remember the original values
				var left = style.left, rsLeft = elem.runtimeStyle.left;

				// Put in the new values to get a computed value out
				elem.runtimeStyle.left = elem.currentStyle.left;
				style.left = ret || 0;
				ret = style.pixelLeft + "px";

				// Revert the changed values
				style.left = left;
				elem.runtimeStyle.left = rsLeft;
			}
		}

		return ret;
	},

	clean: function( elems, context, fragment ) {
		context = context || document;

		// !context.createElement fails in IE with an error but returns typeof 'object'
		if ( typeof context.createElement === "undefined" )
			context = context.ownerDocument || context[0] && context[0].ownerDocument || document;

		// If a single string is passed in and it's a single tag
		// just do a createElement and skip the rest
		if ( !fragment && elems.length === 1 && typeof elems[0] === "string" ) {
			var match = /^<(\w+)\s*\/?>$/.exec(elems[0]);
			if ( match )
				return [ context.createElement( match[1] ) ];
		}

		var ret = [], scripts = [], div = context.createElement("div");

		jQuery.each(elems, function(i, elem){
			if ( typeof elem === "number" )
				elem += '';

			if ( !elem )
				return;

			// Convert html string into DOM nodes
			if ( typeof elem === "string" ) {
				// Fix "XHTML"-style tags in all browsers
				elem = elem.replace(/(<(\w+)[^>]*?)\/>/g, function(all, front, tag){
					return tag.match(/^(abbr|br|col|img|input|link|meta|param|hr|area|embed)$/i) ?
						all :
						front + "></" + tag + ">";
				});

				// Trim whitespace, otherwise indexOf won't work as expected
				var tags = elem.replace(/^\s+/, "").substring(0, 10).toLowerCase();

				var wrap =
					// option or optgroup
					!tags.indexOf("<opt") &&
					[ 1, "<select multiple='multiple'>", "</select>" ] ||

					!tags.indexOf("<leg") &&
					[ 1, "<fieldset>", "</fieldset>" ] ||

					tags.match(/^<(thead|tbody|tfoot|colg|cap)/) &&
					[ 1, "<table>", "</table>" ] ||

					!tags.indexOf("<tr") &&
					[ 2, "<table><tbody>", "</tbody></table>" ] ||

				 	// <thead> matched above
					(!tags.indexOf("<td") || !tags.indexOf("<th")) &&
					[ 3, "<table><tbody><tr>", "</tr></tbody></table>" ] ||

					!tags.indexOf("<col") &&
					[ 2, "<table><tbody></tbody><colgroup>", "</colgroup></table>" ] ||

					// IE can't serialize <link> and <script> tags normally
					!jQuery.support.htmlSerialize &&
					[ 1, "div<div>", "</div>" ] ||

					[ 0, "", "" ];

				// Go to html and back, then peel off extra wrappers
				div.innerHTML = wrap[1] + elem + wrap[2];

				// Move to the right depth
				while ( wrap[0]-- )
					div = div.lastChild;

				// Remove IE's autoinserted <tbody> from table fragments
				if ( !jQuery.support.tbody ) {

					// String was a <table>, *may* have spurious <tbody>
					var hasBody = /<tbody/i.test(elem),
						tbody = !tags.indexOf("<table") && !hasBody ?
							div.firstChild && div.firstChild.childNodes :

						// String was a bare <thead> or <tfoot>
						wrap[1] == "<table>" && !hasBody ?
							div.childNodes :
							[];

					for ( var j = tbody.length - 1; j >= 0 ; --j )
						if ( jQuery.nodeName( tbody[ j ], "tbody" ) && !tbody[ j ].childNodes.length )
							tbody[ j ].parentNode.removeChild( tbody[ j ] );

					}

				// IE completely kills leading whitespace when innerHTML is used
				if ( !jQuery.support.leadingWhitespace && /^\s/.test( elem ) )
					div.insertBefore( context.createTextNode( elem.match(/^\s*/)[0] ), div.firstChild );
				
				elem = jQuery.makeArray( div.childNodes );
			}

			if ( elem.nodeType )
				ret.push( elem );
			else
				ret = jQuery.merge( ret, elem );

		});

		if ( fragment ) {
			for ( var i = 0; ret[i]; i++ ) {
				if ( jQuery.nodeName( ret[i], "script" ) && (!ret[i].type || ret[i].type.toLowerCase() === "text/javascript") ) {
					scripts.push( ret[i].parentNode ? ret[i].parentNode.removeChild( ret[i] ) : ret[i] );
				} else {
					if ( ret[i].nodeType === 1 )
						ret.splice.apply( ret, [i + 1, 0].concat(jQuery.makeArray(ret[i].getElementsByTagName("script"))) );
					fragment.appendChild( ret[i] );
				}
			}
			
			return scripts;
		}

		return ret;
	},

	attr: function( elem, name, value ) {
		// don't set attributes on text and comment nodes
		if (!elem || elem.nodeType == 3 || elem.nodeType == 8)
			return undefined;

		var notxml = !jQuery.isXMLDoc( elem ),
			// Whether we are setting (or getting)
			set = value !== undefined;

		// Try to normalize/fix the name
		name = notxml && jQuery.props[ name ] || name;

		// Only do all the following if this is a node (faster for style)
		// IE elem.getAttribute passes even for style
		if ( elem.tagName ) {

			// These attributes require special treatment
			var special = /href|src|style/.test( name );

			// Safari mis-reports the default selected property of a hidden option
			// Accessing the parent's selectedIndex property fixes it
			if ( name == "selected" && elem.parentNode )
				elem.parentNode.selectedIndex;

			// If applicable, access the attribute via the DOM 0 way
			if ( name in elem && notxml && !special ) {
				if ( set ){
					// We can't allow the type property to be changed (since it causes problems in IE)
					if ( name == "type" && jQuery.nodeName( elem, "input" ) && elem.parentNode )
						throw "type property can't be changed";

					elem[ name ] = value;
				}

				// browsers index elements by id/name on forms, give priority to attributes.
				if( jQuery.nodeName( elem, "form" ) && elem.getAttributeNode(name) )
					return elem.getAttributeNode( name ).nodeValue;

				// elem.tabIndex doesn't always return the correct value when it hasn't been explicitly set
				// http://fluidproject.org/blog/2008/01/09/getting-setting-and-removing-tabindex-values-with-javascript/
				if ( name == "tabIndex" ) {
					var attributeNode = elem.getAttributeNode( "tabIndex" );
					return attributeNode && attributeNode.specified
						? attributeNode.value
						: elem.nodeName.match(/(button|input|object|select|textarea)/i)
							? 0
							: elem.nodeName.match(/^(a|area)$/i) && elem.href
								? 0
								: undefined;
				}

				return elem[ name ];
			}

			if ( !jQuery.support.style && notxml &&  name == "style" )
				return jQuery.attr( elem.style, "cssText", value );

			if ( set )
				// convert the value to a string (all browsers do this but IE) see #1070
				elem.setAttribute( name, "" + value );

			var attr = !jQuery.support.hrefNormalized && notxml && special
					// Some attributes require a special call on IE
					? elem.getAttribute( name, 2 )
					: elem.getAttribute( name );

			// Non-existent attributes return null, we normalize to undefined
			return attr === null ? undefined : attr;
		}

		// elem is actually elem.style ... set the style

		// IE uses filters for opacity
		if ( !jQuery.support.opacity && name == "opacity" ) {
			if ( set ) {
				// IE has trouble with opacity if it does not have layout
				// Force it by setting the zoom level
				elem.zoom = 1;

				// Set the alpha filter to set the opacity
				elem.filter = (elem.filter || "").replace( /alpha\([^)]*\)/, "" ) +
					(parseInt( value ) + '' == "NaN" ? "" : "alpha(opacity=" + value * 100 + ")");
			}

			return elem.filter && elem.filter.indexOf("opacity=") >= 0 ?
				(parseFloat( elem.filter.match(/opacity=([^)]*)/)[1] ) / 100) + '':
				"";
		}

		name = name.replace(/-([a-z])/ig, function(all, letter){
			return letter.toUpperCase();
		});

		if ( set )
			elem[ name ] = value;

		return elem[ name ];
	},

	trim: function( text ) {
		return (text || "").replace( /^\s+|\s+$/g, "" );
	},

	makeArray: function( array ) {
		var ret = [];

		if( array != null ){
			var i = array.length;
			// The window, strings (and functions) also have 'length'
			if( i == null || typeof array === "string" || jQuery.isFunction(array) || array.setInterval )
				ret[0] = array;
			else
				while( i )
					ret[--i] = array[i];
		}

		return ret;
	},

	inArray: function( elem, array ) {
		for ( var i = 0, length = array.length; i < length; i++ )
		// Use === because on IE, window == document
			if ( array[ i ] === elem )
				return i;

		return -1;
	},

	merge: function( first, second ) {
		// We have to loop this way because IE & Opera overwrite the length
		// expando of getElementsByTagName
		var i = 0, elem, pos = first.length;
		// Also, we need to make sure that the correct elements are being returned
		// (IE returns comment nodes in a '*' query)
		if ( !jQuery.support.getAll ) {
			while ( (elem = second[ i++ ]) != null )
				if ( elem.nodeType != 8 )
					first[ pos++ ] = elem;

		} else
			while ( (elem = second[ i++ ]) != null )
				first[ pos++ ] = elem;

		return first;
	},

	unique: function( array ) {
		var ret = [], done = {};

		try {

			for ( var i = 0, length = array.length; i < length; i++ ) {
				var id = jQuery.data( array[ i ] );

				if ( !done[ id ] ) {
					done[ id ] = true;
					ret.push( array[ i ] );
				}
			}

		} catch( e ) {
			ret = array;
		}

		return ret;
	},

	grep: function( elems, callback, inv ) {
		var ret = [];

		// Go through the array, only saving the items
		// that pass the validator function
		for ( var i = 0, length = elems.length; i < length; i++ )
			if ( !inv != !callback( elems[ i ], i ) )
				ret.push( elems[ i ] );

		return ret;
	},

	map: function( elems, callback ) {
		var ret = [];

		// Go through the array, translating each of the items to their
		// new value (or values).
		for ( var i = 0, length = elems.length; i < length; i++ ) {
			var value = callback( elems[ i ], i );

			if ( value != null )
				ret[ ret.length ] = value;
		}

		return ret.concat.apply( [], ret );
	}
});

// Use of jQuery.browser is deprecated.
// It's included for backwards compatibility and plugins,
// although they should work to migrate away.

var userAgent = navigator.userAgent.toLowerCase();

// Figure out what browser is being used
jQuery.browser = {
	version: (userAgent.match( /.+(?:rv|it|ra|ie)[\/: ]([\d.]+)/ ) || [0,'0'])[1],
	safari: /webkit/.test( userAgent ),
	opera: /opera/.test( userAgent ),
	msie: /msie/.test( userAgent ) && !/opera/.test( userAgent ),
	mozilla: /mozilla/.test( userAgent ) && !/(compatible|webkit)/.test( userAgent )
};

jQuery.each({
	parent: function(elem){return elem.parentNode;},
	parents: function(elem){return jQuery.dir(elem,"parentNode");},
	next: function(elem){return jQuery.nth(elem,2,"nextSibling");},
	prev: function(elem){return jQuery.nth(elem,2,"previousSibling");},
	nextAll: function(elem){return jQuery.dir(elem,"nextSibling");},
	prevAll: function(elem){return jQuery.dir(elem,"previousSibling");},
	siblings: function(elem){return jQuery.sibling(elem.parentNode.firstChild,elem);},
	children: function(elem){return jQuery.sibling(elem.firstChild);},
	contents: function(elem){return jQuery.nodeName(elem,"iframe")?elem.contentDocument||elem.contentWindow.document:jQuery.makeArray(elem.childNodes);}
}, function(name, fn){
	jQuery.fn[ name ] = function( selector ) {
		var ret = jQuery.map( this, fn );

		if ( selector && typeof selector == "string" )
			ret = jQuery.multiFilter( selector, ret );

		return this.pushStack( jQuery.unique( ret ), name, selector );
	};
});

jQuery.each({
	appendTo: "append",
	prependTo: "prepend",
	insertBefore: "before",
	insertAfter: "after",
	replaceAll: "replaceWith"
}, function(name, original){
	jQuery.fn[ name ] = function( selector ) {
		var ret = [], insert = jQuery( selector );

		for ( var i = 0, l = insert.length; i < l; i++ ) {
			var elems = (i > 0 ? this.clone(true) : this).get();
			jQuery.fn[ original ].apply( jQuery(insert[i]), elems );
			ret = ret.concat( elems );
		}

		return this.pushStack( ret, name, selector );
	};
});

jQuery.each({
	removeAttr: function( name ) {
		jQuery.attr( this, name, "" );
		if (this.nodeType == 1)
			this.removeAttribute( name );
	},

	addClass: function( classNames ) {
		jQuery.className.add( this, classNames );
	},

	removeClass: function( classNames ) {
		jQuery.className.remove( this, classNames );
	},

	toggleClass: function( classNames, state ) {
		if( typeof state !== "boolean" )
			state = !jQuery.className.has( this, classNames );
		jQuery.className[ state ? "add" : "remove" ]( this, classNames );
	},

	remove: function( selector ) {
		if ( !selector || jQuery.filter( selector, [ this ] ).length ) {
			// Prevent memory leaks
			jQuery( "*", this ).add([this]).each(function(){
				jQuery.event.remove(this);
				jQuery.removeData(this);
			});
			if (this.parentNode)
				this.parentNode.removeChild( this );
		}
	},

	empty: function() {
		// Remove element nodes and prevent memory leaks
		jQuery(this).children().remove();

		// Remove any remaining nodes
		while ( this.firstChild )
			this.removeChild( this.firstChild );
	}
}, function(name, fn){
	jQuery.fn[ name ] = function(){
		return this.each( fn, arguments );
	};
});

// Helper function used by the dimensions and offset modules
function num(elem, prop) {
	return elem[0] && parseInt( jQuery.curCSS(elem[0], prop, true), 10 ) || 0;
}
var expando = "jQuery" + now(), uuid = 0, windowData = {};

jQuery.extend({
	cache: {},

	data: function( elem, name, data ) {
		elem = elem == window ?
			windowData :
			elem;

		var id = elem[ expando ];

		// Compute a unique ID for the element
		if ( !id )
			id = elem[ expando ] = ++uuid;

		// Only generate the data cache if we're
		// trying to access or manipulate it
		if ( name && !jQuery.cache[ id ] )
			jQuery.cache[ id ] = {};

		// Prevent overriding the named cache with undefined values
		if ( data !== undefined )
			jQuery.cache[ id ][ name ] = data;

		// Return the named cache data, or the ID for the element
		return name ?
			jQuery.cache[ id ][ name ] :
			id;
	},

	removeData: function( elem, name ) {
		elem = elem == window ?
			windowData :
			elem;

		var id = elem[ expando ];

		// If we want to remove a specific section of the element's data
		if ( name ) {
			if ( jQuery.cache[ id ] ) {
				// Remove the section of cache data
				delete jQuery.cache[ id ][ name ];

				// If we've removed all the data, remove the element's cache
				name = "";

				for ( name in jQuery.cache[ id ] )
					break;

				if ( !name )
					jQuery.removeData( elem );
			}

		// Otherwise, we want to remove all of the element's data
		} else {
			// Clean up the element expando
			try {
				delete elem[ expando ];
			} catch(e){
				// IE has trouble directly removing the expando
				// but it's ok with using removeAttribute
				if ( elem.removeAttribute )
					elem.removeAttribute( expando );
			}

			// Completely remove the data cache
			delete jQuery.cache[ id ];
		}
	},
	queue: function( elem, type, data ) {
		if ( elem ){
	
			type = (type || "fx") + "queue";
	
			var q = jQuery.data( elem, type );
	
			if ( !q || jQuery.isArray(data) )
				q = jQuery.data( elem, type, jQuery.makeArray(data) );
			else if( data )
				q.push( data );
	
		}
		return q;
	},

	dequeue: function( elem, type ){
		var queue = jQuery.queue( elem, type ),
			fn = queue.shift();
		
		if( !type || type === "fx" )
			fn = queue[0];
			
		if( fn !== undefined )
			fn.call(elem);
	}
});

jQuery.fn.extend({
	data: function( key, value ){
		var parts = key.split(".");
		parts[1] = parts[1] ? "." + parts[1] : "";

		if ( value === undefined ) {
			var data = this.triggerHandler("getData" + parts[1] + "!", [parts[0]]);

			if ( data === undefined && this.length )
				data = jQuery.data( this[0], key );

			return data === undefined && parts[1] ?
				this.data( parts[0] ) :
				data;
		} else
			return this.trigger("setData" + parts[1] + "!", [parts[0], value]).each(function(){
				jQuery.data( this, key, value );
			});
	},

	removeData: function( key ){
		return this.each(function(){
			jQuery.removeData( this, key );
		});
	},
	queue: function(type, data){
		if ( typeof type !== "string" ) {
			data = type;
			type = "fx";
		}

		if ( data === undefined )
			return jQuery.queue( this[0], type );

		return this.each(function(){
			var queue = jQuery.queue( this, type, data );
			
			 if( type == "fx" && queue.length == 1 )
				queue[0].call(this);
		});
	},
	dequeue: function(type){
		return this.each(function(){
			jQuery.dequeue( this, type );
		});
	}
});/*!
 * Sizzle CSS Selector Engine - v0.9.3
 *  Copyright 2009, The Dojo Foundation
 *  Released under the MIT, BSD, and GPL Licenses.
 *  More information: http://sizzlejs.com/
 */
(function(){

var chunker = /((?:\((?:\([^()]+\)|[^()]+)+\)|\[(?:\[[^[\]]*\]|['"][^'"]*['"]|[^[\]'"]+)+\]|\\.|[^ >+~,(\[\\]+)+|[>+~])(\s*,\s*)?/g,
	done = 0,
	toString = Object.prototype.toString;

var Sizzle = function(selector, context, results, seed) {
	results = results || [];
	context = context || document;

	if ( context.nodeType !== 1 && context.nodeType !== 9 )
		return [];
	
	if ( !selector || typeof selector !== "string" ) {
		return results;
	}

	var parts = [], m, set, checkSet, check, mode, extra, prune = true;
	
	// Reset the position of the chunker regexp (start from head)
	chunker.lastIndex = 0;
	
	while ( (m = chunker.exec(selector)) !== null ) {
		parts.push( m[1] );
		
		if ( m[2] ) {
			extra = RegExp.rightContext;
			break;
		}
	}

	if ( parts.length > 1 && origPOS.exec( selector ) ) {
		if ( parts.length === 2 && Expr.relative[ parts[0] ] ) {
			set = posProcess( parts[0] + parts[1], context );
		} else {
			set = Expr.relative[ parts[0] ] ?
				[ context ] :
				Sizzle( parts.shift(), context );

			while ( parts.length ) {
				selector = parts.shift();

				if ( Expr.relative[ selector ] )
					selector += parts.shift();

				set = posProcess( selector, set );
			}
		}
	} else {
		var ret = seed ?
			{ expr: parts.pop(), set: makeArray(seed) } :
			Sizzle.find( parts.pop(), parts.length === 1 && context.parentNode ? context.parentNode : context, isXML(context) );
		set = Sizzle.filter( ret.expr, ret.set );

		if ( parts.length > 0 ) {
			checkSet = makeArray(set);
		} else {
			prune = false;
		}

		while ( parts.length ) {
			var cur = parts.pop(), pop = cur;

			if ( !Expr.relative[ cur ] ) {
				cur = "";
			} else {
				pop = parts.pop();
			}

			if ( pop == null ) {
				pop = context;
			}

			Expr.relative[ cur ]( checkSet, pop, isXML(context) );
		}
	}

	if ( !checkSet ) {
		checkSet = set;
	}

	if ( !checkSet ) {
		throw "Syntax error, unrecognized expression: " + (cur || selector);
	}

	if ( toString.call(checkSet) === "[object Array]" ) {
		if ( !prune ) {
			results.push.apply( results, checkSet );
		} else if ( context.nodeType === 1 ) {
			for ( var i = 0; checkSet[i] != null; i++ ) {
				if ( checkSet[i] && (checkSet[i] === true || checkSet[i].nodeType === 1 && contains(context, checkSet[i])) ) {
					results.push( set[i] );
				}
			}
		} else {
			for ( var i = 0; checkSet[i] != null; i++ ) {
				if ( checkSet[i] && checkSet[i].nodeType === 1 ) {
					results.push( set[i] );
				}
			}
		}
	} else {
		makeArray( checkSet, results );
	}

	if ( extra ) {
		Sizzle( extra, context, results, seed );

		if ( sortOrder ) {
			hasDuplicate = false;
			results.sort(sortOrder);

			if ( hasDuplicate ) {
				for ( var i = 1; i < results.length; i++ ) {
					if ( results[i] === results[i-1] ) {
						results.splice(i--, 1);
					}
				}
			}
		}
	}

	return results;
};

Sizzle.matches = function(expr, set){
	return Sizzle(expr, null, null, set);
};

Sizzle.find = function(expr, context, isXML){
	var set, match;

	if ( !expr ) {
		return [];
	}

	for ( var i = 0, l = Expr.order.length; i < l; i++ ) {
		var type = Expr.order[i], match;
		
		if ( (match = Expr.match[ type ].exec( expr )) ) {
			var left = RegExp.leftContext;

			if ( left.substr( left.length - 1 ) !== "\\" ) {
				match[1] = (match[1] || "").replace(/\\/g, "");
				set = Expr.find[ type ]( match, context, isXML );
				if ( set != null ) {
					expr = expr.replace( Expr.match[ type ], "" );
					break;
				}
			}
		}
	}

	if ( !set ) {
		set = context.getElementsByTagName("*");
	}

	return {set: set, expr: expr};
};

Sizzle.filter = function(expr, set, inplace, not){
	var old = expr, result = [], curLoop = set, match, anyFound,
		isXMLFilter = set && set[0] && isXML(set[0]);

	while ( expr && set.length ) {
		for ( var type in Expr.filter ) {
			if ( (match = Expr.match[ type ].exec( expr )) != null ) {
				var filter = Expr.filter[ type ], found, item;
				anyFound = false;

				if ( curLoop == result ) {
					result = [];
				}

				if ( Expr.preFilter[ type ] ) {
					match = Expr.preFilter[ type ]( match, curLoop, inplace, result, not, isXMLFilter );

					if ( !match ) {
						anyFound = found = true;
					} else if ( match === true ) {
						continue;
					}
				}

				if ( match ) {
					for ( var i = 0; (item = curLoop[i]) != null; i++ ) {
						if ( item ) {
							found = filter( item, match, i, curLoop );
							var pass = not ^ !!found;

							if ( inplace && found != null ) {
								if ( pass ) {
									anyFound = true;
								} else {
									curLoop[i] = false;
								}
							} else if ( pass ) {
								result.push( item );
								anyFound = true;
							}
						}
					}
				}

				if ( found !== undefined ) {
					if ( !inplace ) {
						curLoop = result;
					}

					expr = expr.replace( Expr.match[ type ], "" );

					if ( !anyFound ) {
						return [];
					}

					break;
				}
			}
		}

		// Improper expression
		if ( expr == old ) {
			if ( anyFound == null ) {
				throw "Syntax error, unrecognized expression: " + expr;
			} else {
				break;
			}
		}

		old = expr;
	}

	return curLoop;
};

var Expr = Sizzle.selectors = {
	order: [ "ID", "NAME", "TAG" ],
	match: {
		ID: /#((?:[\w\u00c0-\uFFFF_-]|\\.)+)/,
		CLASS: /\.((?:[\w\u00c0-\uFFFF_-]|\\.)+)/,
		NAME: /\[name=['"]*((?:[\w\u00c0-\uFFFF_-]|\\.)+)['"]*\]/,
		ATTR: /\[\s*((?:[\w\u00c0-\uFFFF_-]|\\.)+)\s*(?:(\S?=)\s*(['"]*)(.*?)\3|)\s*\]/,
		TAG: /^((?:[\w\u00c0-\uFFFF\*_-]|\\.)+)/,
		CHILD: /:(only|nth|last|first)-child(?:\((even|odd|[\dn+-]*)\))?/,
		POS: /:(nth|eq|gt|lt|first|last|even|odd)(?:\((\d*)\))?(?=[^-]|$)/,
		PSEUDO: /:((?:[\w\u00c0-\uFFFF_-]|\\.)+)(?:\((['"]*)((?:\([^\)]+\)|[^\2\(\)]*)+)\2\))?/
	},
	attrMap: {
		"class": "className",
		"for": "htmlFor"
	},
	attrHandle: {
		href: function(elem){
			return elem.getAttribute("href");
		}
	},
	relative: {
		"+": function(checkSet, part, isXML){
			var isPartStr = typeof part === "string",
				isTag = isPartStr && !/\W/.test(part),
				isPartStrNotTag = isPartStr && !isTag;

			if ( isTag && !isXML ) {
				part = part.toUpperCase();
			}

			for ( var i = 0, l = checkSet.length, elem; i < l; i++ ) {
				if ( (elem = checkSet[i]) ) {
					while ( (elem = elem.previousSibling) && elem.nodeType !== 1 ) {}

					checkSet[i] = isPartStrNotTag || elem && elem.nodeName === part ?
						elem || false :
						elem === part;
				}
			}

			if ( isPartStrNotTag ) {
				Sizzle.filter( part, checkSet, true );
			}
		},
		">": function(checkSet, part, isXML){
			var isPartStr = typeof part === "string";

			if ( isPartStr && !/\W/.test(part) ) {
				part = isXML ? part : part.toUpperCase();

				for ( var i = 0, l = checkSet.length; i < l; i++ ) {
					var elem = checkSet[i];
					if ( elem ) {
						var parent = elem.parentNode;
						checkSet[i] = parent.nodeName === part ? parent : false;
					}
				}
			} else {
				for ( var i = 0, l = checkSet.length; i < l; i++ ) {
					var elem = checkSet[i];
					if ( elem ) {
						checkSet[i] = isPartStr ?
							elem.parentNode :
							elem.parentNode === part;
					}
				}

				if ( isPartStr ) {
					Sizzle.filter( part, checkSet, true );
				}
			}
		},
		"": function(checkSet, part, isXML){
			var doneName = done++, checkFn = dirCheck;

			if ( !part.match(/\W/) ) {
				var nodeCheck = part = isXML ? part : part.toUpperCase();
				checkFn = dirNodeCheck;
			}

			checkFn("parentNode", part, doneName, checkSet, nodeCheck, isXML);
		},
		"~": function(checkSet, part, isXML){
			var doneName = done++, checkFn = dirCheck;

			if ( typeof part === "string" && !part.match(/\W/) ) {
				var nodeCheck = part = isXML ? part : part.toUpperCase();
				checkFn = dirNodeCheck;
			}

			checkFn("previousSibling", part, doneName, checkSet, nodeCheck, isXML);
		}
	},
	find: {
		ID: function(match, context, isXML){
			if ( typeof context.getElementById !== "undefined" && !isXML ) {
				var m = context.getElementById(match[1]);
				return m ? [m] : [];
			}
		},
		NAME: function(match, context, isXML){
			if ( typeof context.getElementsByName !== "undefined" ) {
				var ret = [], results = context.getElementsByName(match[1]);

				for ( var i = 0, l = results.length; i < l; i++ ) {
					if ( results[i].getAttribute("name") === match[1] ) {
						ret.push( results[i] );
					}
				}

				return ret.length === 0 ? null : ret;
			}
		},
		TAG: function(match, context){
			return context.getElementsByTagName(match[1]);
		}
	},
	preFilter: {
		CLASS: function(match, curLoop, inplace, result, not, isXML){
			match = " " + match[1].replace(/\\/g, "") + " ";

			if ( isXML ) {
				return match;
			}

			for ( var i = 0, elem; (elem = curLoop[i]) != null; i++ ) {
				if ( elem ) {
					if ( not ^ (elem.className && (" " + elem.className + " ").indexOf(match) >= 0) ) {
						if ( !inplace )
							result.push( elem );
					} else if ( inplace ) {
						curLoop[i] = false;
					}
				}
			}

			return false;
		},
		ID: function(match){
			return match[1].replace(/\\/g, "");
		},
		TAG: function(match, curLoop){
			for ( var i = 0; curLoop[i] === false; i++ ){}
			return curLoop[i] && isXML(curLoop[i]) ? match[1] : match[1].toUpperCase();
		},
		CHILD: function(match){
			if ( match[1] == "nth" ) {
				// parse equations like 'even', 'odd', '5', '2n', '3n+2', '4n-1', '-n+6'
				var test = /(-?)(\d*)n((?:\+|-)?\d*)/.exec(
					match[2] == "even" && "2n" || match[2] == "odd" && "2n+1" ||
					!/\D/.test( match[2] ) && "0n+" + match[2] || match[2]);

				// calculate the numbers (first)n+(last) including if they are negative
				match[2] = (test[1] + (test[2] || 1)) - 0;
				match[3] = test[3] - 0;
			}

			// TODO: Move to normal caching system
			match[0] = done++;

			return match;
		},
		ATTR: function(match, curLoop, inplace, result, not, isXML){
			var name = match[1].replace(/\\/g, "");
			
			if ( !isXML && Expr.attrMap[name] ) {
				match[1] = Expr.attrMap[name];
			}

			if ( match[2] === "~=" ) {
				match[4] = " " + match[4] + " ";
			}

			return match;
		},
		PSEUDO: function(match, curLoop, inplace, result, not){
			if ( match[1] === "not" ) {
				// If we're dealing with a complex expression, or a simple one
				if ( match[3].match(chunker).length > 1 || /^\w/.test(match[3]) ) {
					match[3] = Sizzle(match[3], null, null, curLoop);
				} else {
					var ret = Sizzle.filter(match[3], curLoop, inplace, true ^ not);
					if ( !inplace ) {
						result.push.apply( result, ret );
					}
					return false;
				}
			} else if ( Expr.match.POS.test( match[0] ) || Expr.match.CHILD.test( match[0] ) ) {
				return true;
			}
			
			return match;
		},
		POS: function(match){
			match.unshift( true );
			return match;
		}
	},
	filters: {
		enabled: function(elem){
			return elem.disabled === false && elem.type !== "hidden";
		},
		disabled: function(elem){
			return elem.disabled === true;
		},
		checked: function(elem){
			return elem.checked === true;
		},
		selected: function(elem){
			// Accessing this property makes selected-by-default
			// options in Safari work properly
			elem.parentNode.selectedIndex;
			return elem.selected === true;
		},
		parent: function(elem){
			return !!elem.firstChild;
		},
		empty: function(elem){
			return !elem.firstChild;
		},
		has: function(elem, i, match){
			return !!Sizzle( match[3], elem ).length;
		},
		header: function(elem){
			return /h\d/i.test( elem.nodeName );
		},
		text: function(elem){
			return "text" === elem.type;
		},
		radio: function(elem){
			return "radio" === elem.type;
		},
		checkbox: function(elem){
			return "checkbox" === elem.type;
		},
		file: function(elem){
			return "file" === elem.type;
		},
		password: function(elem){
			return "password" === elem.type;
		},
		submit: function(elem){
			return "submit" === elem.type;
		},
		image: function(elem){
			return "image" === elem.type;
		},
		reset: function(elem){
			return "reset" === elem.type;
		},
		button: function(elem){
			return "button" === elem.type || elem.nodeName.toUpperCase() === "BUTTON";
		},
		input: function(elem){
			return /input|select|textarea|button/i.test(elem.nodeName);
		}
	},
	setFilters: {
		first: function(elem, i){
			return i === 0;
		},
		last: function(elem, i, match, array){
			return i === array.length - 1;
		},
		even: function(elem, i){
			return i % 2 === 0;
		},
		odd: function(elem, i){
			return i % 2 === 1;
		},
		lt: function(elem, i, match){
			return i < match[3] - 0;
		},
		gt: function(elem, i, match){
			return i > match[3] - 0;
		},
		nth: function(elem, i, match){
			return match[3] - 0 == i;
		},
		eq: function(elem, i, match){
			return match[3] - 0 == i;
		}
	},
	filter: {
		PSEUDO: function(elem, match, i, array){
			var name = match[1], filter = Expr.filters[ name ];

			if ( filter ) {
				return filter( elem, i, match, array );
			} else if ( name === "contains" ) {
				return (elem.textContent || elem.innerText || "").indexOf(match[3]) >= 0;
			} else if ( name === "not" ) {
				var not = match[3];

				for ( var i = 0, l = not.length; i < l; i++ ) {
					if ( not[i] === elem ) {
						return false;
					}
				}

				return true;
			}
		},
		CHILD: function(elem, match){
			var type = match[1], node = elem;
			switch (type) {
				case 'only':
				case 'first':
					while (node = node.previousSibling)  {
						if ( node.nodeType === 1 ) return false;
					}
					if ( type == 'first') return true;
					node = elem;
				case 'last':
					while (node = node.nextSibling)  {
						if ( node.nodeType === 1 ) return false;
					}
					return true;
				case 'nth':
					var first = match[2], last = match[3];

					if ( first == 1 && last == 0 ) {
						return true;
					}
					
					var doneName = match[0],
						parent = elem.parentNode;
	
					if ( parent && (parent.sizcache !== doneName || !elem.nodeIndex) ) {
						var count = 0;
						for ( node = parent.firstChild; node; node = node.nextSibling ) {
							if ( node.nodeType === 1 ) {
								node.nodeIndex = ++count;
							}
						} 
						parent.sizcache = doneName;
					}
					
					var diff = elem.nodeIndex - last;
					if ( first == 0 ) {
						return diff == 0;
					} else {
						return ( diff % first == 0 && diff / first >= 0 );
					}
			}
		},
		ID: function(elem, match){
			return elem.nodeType === 1 && elem.getAttribute("id") === match;
		},
		TAG: function(elem, match){
			return (match === "*" && elem.nodeType === 1) || elem.nodeName === match;
		},
		CLASS: function(elem, match){
			return (" " + (elem.className || elem.getAttribute("class")) + " ")
				.indexOf( match ) > -1;
		},
		ATTR: function(elem, match){
			var name = match[1],
				result = Expr.attrHandle[ name ] ?
					Expr.attrHandle[ name ]( elem ) :
					elem[ name ] != null ?
						elem[ name ] :
						elem.getAttribute( name ),
				value = result + "",
				type = match[2],
				check = match[4];

			return result == null ?
				type === "!=" :
				type === "=" ?
				value === check :
				type === "*=" ?
				value.indexOf(check) >= 0 :
				type === "~=" ?
				(" " + value + " ").indexOf(check) >= 0 :
				!check ?
				value && result !== false :
				type === "!=" ?
				value != check :
				type === "^=" ?
				value.indexOf(check) === 0 :
				type === "$=" ?
				value.substr(value.length - check.length) === check :
				type === "|=" ?
				value === check || value.substr(0, check.length + 1) === check + "-" :
				false;
		},
		POS: function(elem, match, i, array){
			var name = match[2], filter = Expr.setFilters[ name ];

			if ( filter ) {
				return filter( elem, i, match, array );
			}
		}
	}
};

var origPOS = Expr.match.POS;

for ( var type in Expr.match ) {
	Expr.match[ type ] = RegExp( Expr.match[ type ].source + /(?![^\[]*\])(?![^\(]*\))/.source );
}

var makeArray = function(array, results) {
	array = Array.prototype.slice.call( array );

	if ( results ) {
		results.push.apply( results, array );
		return results;
	}
	
	return array;
};

// Perform a simple check to determine if the browser is capable of
// converting a NodeList to an array using builtin methods.
try {
	Array.prototype.slice.call( document.documentElement.childNodes );

// Provide a fallback method if it does not work
} catch(e){
	makeArray = function(array, results) {
		var ret = results || [];

		if ( toString.call(array) === "[object Array]" ) {
			Array.prototype.push.apply( ret, array );
		} else {
			if ( typeof array.length === "number" ) {
				for ( var i = 0, l = array.length; i < l; i++ ) {
					ret.push( array[i] );
				}
			} else {
				for ( var i = 0; array[i]; i++ ) {
					ret.push( array[i] );
				}
			}
		}

		return ret;
	};
}

var sortOrder;

if ( document.documentElement.compareDocumentPosition ) {
	sortOrder = function( a, b ) {
		var ret = a.compareDocumentPosition(b) & 4 ? -1 : a === b ? 0 : 1;
		if ( ret === 0 ) {
			hasDuplicate = true;
		}
		return ret;
	};
} else if ( "sourceIndex" in document.documentElement ) {
	sortOrder = function( a, b ) {
		var ret = a.sourceIndex - b.sourceIndex;
		if ( ret === 0 ) {
			hasDuplicate = true;
		}
		return ret;
	};
} else if ( document.createRange ) {
	sortOrder = function( a, b ) {
		var aRange = a.ownerDocument.createRange(), bRange = b.ownerDocument.createRange();
		aRange.selectNode(a);
		aRange.collapse(true);
		bRange.selectNode(b);
		bRange.collapse(true);
		var ret = aRange.compareBoundaryPoints(Range.START_TO_END, bRange);
		if ( ret === 0 ) {
			hasDuplicate = true;
		}
		return ret;
	};
}

// Check to see if the browser returns elements by name when
// querying by getElementById (and provide a workaround)
(function(){
	// We're going to inject a fake input element with a specified name
	var form = document.createElement("form"),
		id = "script" + (new Date).getTime();
	form.innerHTML = "<input name='" + id + "'/>";

	// Inject it into the root element, check its status, and remove it quickly
	var root = document.documentElement;
	root.insertBefore( form, root.firstChild );

	// The workaround has to do additional checks after a getElementById
	// Which slows things down for other browsers (hence the branching)
	if ( !!document.getElementById( id ) ) {
		Expr.find.ID = function(match, context, isXML){
			if ( typeof context.getElementById !== "undefined" && !isXML ) {
				var m = context.getElementById(match[1]);
				return m ? m.id === match[1] || typeof m.getAttributeNode !== "undefined" && m.getAttributeNode("id").nodeValue === match[1] ? [m] : undefined : [];
			}
		};

		Expr.filter.ID = function(elem, match){
			var node = typeof elem.getAttributeNode !== "undefined" && elem.getAttributeNode("id");
			return elem.nodeType === 1 && node && node.nodeValue === match;
		};
	}

	root.removeChild( form );
})();

(function(){
	// Check to see if the browser returns only elements
	// when doing getElementsByTagName("*")

	// Create a fake element
	var div = document.createElement("div");
	div.appendChild( document.createComment("") );

	// Make sure no comments are found
	if ( div.getElementsByTagName("*").length > 0 ) {
		Expr.find.TAG = function(match, context){
			var results = context.getElementsByTagName(match[1]);

			// Filter out possible comments
			if ( match[1] === "*" ) {
				var tmp = [];

				for ( var i = 0; results[i]; i++ ) {
					if ( results[i].nodeType === 1 ) {
						tmp.push( results[i] );
					}
				}

				results = tmp;
			}

			return results;
		};
	}

	// Check to see if an attribute returns normalized href attributes
	div.innerHTML = "<a href='#'></a>";
	if ( div.firstChild && typeof div.firstChild.getAttribute !== "undefined" &&
			div.firstChild.getAttribute("href") !== "#" ) {
		Expr.attrHandle.href = function(elem){
			return elem.getAttribute("href", 2);
		};
	}
})();

if ( document.querySelectorAll ) (function(){
	var oldSizzle = Sizzle, div = document.createElement("div");
	div.innerHTML = "<p class='TEST'></p>";

	// Safari can't handle uppercase or unicode characters when
	// in quirks mode.
	if ( div.querySelectorAll && div.querySelectorAll(".TEST").length === 0 ) {
		return;
	}
	
	Sizzle = function(query, context, extra, seed){
		context = context || document;

		// Only use querySelectorAll on non-XML documents
		// (ID selectors don't work in non-HTML documents)
		if ( !seed && context.nodeType === 9 && !isXML(context) ) {
			try {
				return makeArray( context.querySelectorAll(query), extra );
			} catch(e){}
		}
		
		return oldSizzle(query, context, extra, seed);
	};

	Sizzle.find = oldSizzle.find;
	Sizzle.filter = oldSizzle.filter;
	Sizzle.selectors = oldSizzle.selectors;
	Sizzle.matches = oldSizzle.matches;
})();

if ( document.getElementsByClassName && document.documentElement.getElementsByClassName ) (function(){
	var div = document.createElement("div");
	div.innerHTML = "<div class='test e'></div><div class='test'></div>";

	// Opera can't find a second classname (in 9.6)
	if ( div.getElementsByClassName("e").length === 0 )
		return;

	// Safari caches class attributes, doesn't catch changes (in 3.2)
	div.lastChild.className = "e";

	if ( div.getElementsByClassName("e").length === 1 )
		return;

	Expr.order.splice(1, 0, "CLASS");
	Expr.find.CLASS = function(match, context, isXML) {
		if ( typeof context.getElementsByClassName !== "undefined" && !isXML ) {
			return context.getElementsByClassName(match[1]);
		}
	};
})();

function dirNodeCheck( dir, cur, doneName, checkSet, nodeCheck, isXML ) {
	var sibDir = dir == "previousSibling" && !isXML;
	for ( var i = 0, l = checkSet.length; i < l; i++ ) {
		var elem = checkSet[i];
		if ( elem ) {
			if ( sibDir && elem.nodeType === 1 ){
				elem.sizcache = doneName;
				elem.sizset = i;
			}
			elem = elem[dir];
			var match = false;

			while ( elem ) {
				if ( elem.sizcache === doneName ) {
					match = checkSet[elem.sizset];
					break;
				}

				if ( elem.nodeType === 1 && !isXML ){
					elem.sizcache = doneName;
					elem.sizset = i;
				}

				if ( elem.nodeName === cur ) {
					match = elem;
					break;
				}

				elem = elem[dir];
			}

			checkSet[i] = match;
		}
	}
}

function dirCheck( dir, cur, doneName, checkSet, nodeCheck, isXML ) {
	var sibDir = dir == "previousSibling" && !isXML;
	for ( var i = 0, l = checkSet.length; i < l; i++ ) {
		var elem = checkSet[i];
		if ( elem ) {
			if ( sibDir && elem.nodeType === 1 ) {
				elem.sizcache = doneName;
				elem.sizset = i;
			}
			elem = elem[dir];
			var match = false;

			while ( elem ) {
				if ( elem.sizcache === doneName ) {
					match = checkSet[elem.sizset];
					break;
				}

				if ( elem.nodeType === 1 ) {
					if ( !isXML ) {
						elem.sizcache = doneName;
						elem.sizset = i;
					}
					if ( typeof cur !== "string" ) {
						if ( elem === cur ) {
							match = true;
							break;
						}

					} else if ( Sizzle.filter( cur, [elem] ).length > 0 ) {
						match = elem;
						break;
					}
				}

				elem = elem[dir];
			}

			checkSet[i] = match;
		}
	}
}

var contains = document.compareDocumentPosition ?  function(a, b){
	return a.compareDocumentPosition(b) & 16;
} : function(a, b){
	return a !== b && (a.contains ? a.contains(b) : true);
};

var isXML = function(elem){
	return elem.nodeType === 9 && elem.documentElement.nodeName !== "HTML" ||
		!!elem.ownerDocument && isXML( elem.ownerDocument );
};

var posProcess = function(selector, context){
	var tmpSet = [], later = "", match,
		root = context.nodeType ? [context] : context;

	// Position selectors must be done after the filter
	// And so must :not(positional) so we move all PSEUDOs to the end
	while ( (match = Expr.match.PSEUDO.exec( selector )) ) {
		later += match[0];
		selector = selector.replace( Expr.match.PSEUDO, "" );
	}

	selector = Expr.relative[selector] ? selector + "*" : selector;

	for ( var i = 0, l = root.length; i < l; i++ ) {
		Sizzle( selector, root[i], tmpSet );
	}

	return Sizzle.filter( later, tmpSet );
};

// EXPOSE
jQuery.find = Sizzle;
jQuery.filter = Sizzle.filter;
jQuery.expr = Sizzle.selectors;
jQuery.expr[":"] = jQuery.expr.filters;

Sizzle.selectors.filters.hidden = function(elem){
	return elem.offsetWidth === 0 || elem.offsetHeight === 0;
};

Sizzle.selectors.filters.visible = function(elem){
	return elem.offsetWidth > 0 || elem.offsetHeight > 0;
};

Sizzle.selectors.filters.animated = function(elem){
	return jQuery.grep(jQuery.timers, function(fn){
		return elem === fn.elem;
	}).length;
};

jQuery.multiFilter = function( expr, elems, not ) {
	if ( not ) {
		expr = ":not(" + expr + ")";
	}

	return Sizzle.matches(expr, elems);
};

jQuery.dir = function( elem, dir ){
	var matched = [], cur = elem[dir];
	while ( cur && cur != document ) {
		if ( cur.nodeType == 1 )
			matched.push( cur );
		cur = cur[dir];
	}
	return matched;
};

jQuery.nth = function(cur, result, dir, elem){
	result = result || 1;
	var num = 0;

	for ( ; cur; cur = cur[dir] )
		if ( cur.nodeType == 1 && ++num == result )
			break;

	return cur;
};

jQuery.sibling = function(n, elem){
	var r = [];

	for ( ; n; n = n.nextSibling ) {
		if ( n.nodeType == 1 && n != elem )
			r.push( n );
	}

	return r;
};

return;

window.Sizzle = Sizzle;

})();
/*
 * A number of helper functions used for managing events.
 * Many of the ideas behind this code originated from
 * Dean Edwards' addEvent library.
 */
jQuery.event = {

	// Bind an event to an element
	// Original by Dean Edwards
	add: function(elem, types, handler, data) {
		if ( elem.nodeType == 3 || elem.nodeType == 8 )
			return;

		// For whatever reason, IE has trouble passing the window object
		// around, causing it to be cloned in the process
		if ( elem.setInterval && elem != window )
			elem = window;

		// Make sure that the function being executed has a unique ID
		if ( !handler.guid )
			handler.guid = this.guid++;

		// if data is passed, bind to handler
		if ( data !== undefined ) {
			// Create temporary function pointer to original handler
			var fn = handler;

			// Create unique handler function, wrapped around original handler
			handler = this.proxy( fn );

			// Store data in unique handler
			handler.data = data;
		}

		// Init the element's event structure
		var events = jQuery.data(elem, "events") || jQuery.data(elem, "events", {}),
			handle = jQuery.data(elem, "handle") || jQuery.data(elem, "handle", function(){
				// Handle the second event of a trigger and when
				// an event is called after a page has unloaded
				return typeof jQuery !== "undefined" && !jQuery.event.triggered ?
					jQuery.event.handle.apply(arguments.callee.elem, arguments) :
					undefined;
			});
		// Add elem as a property of the handle function
		// This is to prevent a memory leak with non-native
		// event in IE.
		handle.elem = elem;

		// Handle multiple events separated by a space
		// jQuery(...).bind("mouseover mouseout", fn);
		jQuery.each(types.split(/\s+/), function(index, type) {
			// Namespaced event handlers
			var namespaces = type.split(".");
			type = namespaces.shift();
			handler.type = namespaces.slice().sort().join(".");

			// Get the current list of functions bound to this event
			var handlers = events[type];
			
			if ( jQuery.event.specialAll[type] )
				jQuery.event.specialAll[type].setup.call(elem, data, namespaces);

			// Init the event handler queue
			if (!handlers) {
				handlers = events[type] = {};

				// Check for a special event handler
				// Only use addEventListener/attachEvent if the special
				// events handler returns false
				if ( !jQuery.event.special[type] || jQuery.event.special[type].setup.call(elem, data, namespaces) === false ) {
					// Bind the global event handler to the element
					if (elem.addEventListener)
						elem.addEventListener(type, handle, false);
					else if (elem.attachEvent)
						elem.attachEvent("on" + type, handle);
				}
			}

			// Add the function to the element's handler list
			handlers[handler.guid] = handler;

			// Keep track of which events have been used, for global triggering
			jQuery.event.global[type] = true;
		});

		// Nullify elem to prevent memory leaks in IE
		elem = null;
	},

	guid: 1,
	global: {},

	// Detach an event or set of events from an element
	remove: function(elem, types, handler) {
		// don't do events on text and comment nodes
		if ( elem.nodeType == 3 || elem.nodeType == 8 )
			return;

		var events = jQuery.data(elem, "events"), ret, index;

		if ( events ) {
			// Unbind all events for the element
			if ( types === undefined || (typeof types === "string" && types.charAt(0) == ".") )
				for ( var type in events )
					this.remove( elem, type + (types || "") );
			else {
				// types is actually an event object here
				if ( types.type ) {
					handler = types.handler;
					types = types.type;
				}

				// Handle multiple events seperated by a space
				// jQuery(...).unbind("mouseover mouseout", fn);
				jQuery.each(types.split(/\s+/), function(index, type){
					// Namespaced event handlers
					var namespaces = type.split(".");
					type = namespaces.shift();
					var namespace = RegExp("(^|\\.)" + namespaces.slice().sort().join(".*\\.") + "(\\.|$)");

					if ( events[type] ) {
						// remove the given handler for the given type
						if ( handler )
							delete events[type][handler.guid];

						// remove all handlers for the given type
						else
							for ( var handle in events[type] )
								// Handle the removal of namespaced events
								if ( namespace.test(events[type][handle].type) )
									delete events[type][handle];
									
						if ( jQuery.event.specialAll[type] )
							jQuery.event.specialAll[type].teardown.call(elem, namespaces);

						// remove generic event handler if no more handlers exist
						for ( ret in events[type] ) break;
						if ( !ret ) {
							if ( !jQuery.event.special[type] || jQuery.event.special[type].teardown.call(elem, namespaces) === false ) {
								if (elem.removeEventListener)
									elem.removeEventListener(type, jQuery.data(elem, "handle"), false);
								else if (elem.detachEvent)
									elem.detachEvent("on" + type, jQuery.data(elem, "handle"));
							}
							ret = null;
							delete events[type];
						}
					}
				});
			}

			// Remove the expando if it's no longer used
			for ( ret in events ) break;
			if ( !ret ) {
				var handle = jQuery.data( elem, "handle" );
				if ( handle ) handle.elem = null;
				jQuery.removeData( elem, "events" );
				jQuery.removeData( elem, "handle" );
			}
		}
	},

	// bubbling is internal
	trigger: function( event, data, elem, bubbling ) {
		// Event object or event type
		var type = event.type || event;

		if( !bubbling ){
			event = typeof event === "object" ?
				// jQuery.Event object
				event[expando] ? event :
				// Object literal
				jQuery.extend( jQuery.Event(type), event ) :
				// Just the event type (string)
				jQuery.Event(type);

			if ( type.indexOf("!") >= 0 ) {
				event.type = type = type.slice(0, -1);
				event.exclusive = true;
			}

			// Handle a global trigger
			if ( !elem ) {
				// Don't bubble custom events when global (to avoid too much overhead)
				event.stopPropagation();
				// Only trigger if we've ever bound an event for it
				if ( this.global[type] )
					jQuery.each( jQuery.cache, function(){
						if ( this.events && this.events[type] )
							jQuery.event.trigger( event, data, this.handle.elem );
					});
			}

			// Handle triggering a single element

			// don't do events on text and comment nodes
			if ( !elem || elem.nodeType == 3 || elem.nodeType == 8 )
				return undefined;
			
			// Clean up in case it is reused
			event.result = undefined;
			event.target = elem;
			
			// Clone the incoming data, if any
			data = jQuery.makeArray(data);
			data.unshift( event );
		}

		event.currentTarget = elem;

		// Trigger the event, it is assumed that "handle" is a function
		var handle = jQuery.data(elem, "handle");
		if ( handle )
			handle.apply( elem, data );

		// Handle triggering native .onfoo handlers (and on links since we don't call .click() for links)
		if ( (!elem[type] || (jQuery.nodeName(elem, 'a') && type == "click")) && elem["on"+type] && elem["on"+type].apply( elem, data ) === false )
			event.result = false;

		// Trigger the native events (except for clicks on links)
		if ( !bubbling && elem[type] && !event.isDefaultPrevented() && !(jQuery.nodeName(elem, 'a') && type == "click") ) {
			this.triggered = true;
			try {
				elem[ type ]();
			// prevent IE from throwing an error for some hidden elements
			} catch (e) {}
		}

		this.triggered = false;

		if ( !event.isPropagationStopped() ) {
			var parent = elem.parentNode || elem.ownerDocument;
			if ( parent )
				jQuery.event.trigger(event, data, parent, true);
		}
	},

	handle: function(event) {
		// returned undefined or false
		var all, handlers;

		event = arguments[0] = jQuery.event.fix( event || window.event );
		event.currentTarget = this;
		
		// Namespaced event handlers
		var namespaces = event.type.split(".");
		event.type = namespaces.shift();

		// Cache this now, all = true means, any handler
		all = !namespaces.length && !event.exclusive;
		
		var namespace = RegExp("(^|\\.)" + namespaces.slice().sort().join(".*\\.") + "(\\.|$)");

		handlers = ( jQuery.data(this, "events") || {} )[event.type];

		for ( var j in handlers ) {
			var handler = handlers[j];

			// Filter the functions by class
			if ( all || namespace.test(handler.type) ) {
				// Pass in a reference to the handler function itself
				// So that we can later remove it
				event.handler = handler;
				event.data = handler.data;

				var ret = handler.apply(this, arguments);

				if( ret !== undefined ){
					event.result = ret;
					if ( ret === false ) {
						event.preventDefault();
						event.stopPropagation();
					}
				}

				if( event.isImmediatePropagationStopped() )
					break;

			}
		}
	},

	props: "altKey attrChange attrName bubbles button cancelable charCode clientX clientY ctrlKey currentTarget data detail eventPhase fromElement handler keyCode metaKey newValue originalTarget pageX pageY prevValue relatedNode relatedTarget screenX screenY shiftKey srcElement target toElement view wheelDelta which".split(" "),

	fix: function(event) {
		if ( event[expando] )
			return event;

		// store a copy of the original event object
		// and "clone" to set read-only properties
		var originalEvent = event;
		event = jQuery.Event( originalEvent );

		for ( var i = this.props.length, prop; i; ){
			prop = this.props[ --i ];
			event[ prop ] = originalEvent[ prop ];
		}

		// Fix target property, if necessary
		if ( !event.target )
			event.target = event.srcElement || document; // Fixes #1925 where srcElement might not be defined either

		// check if target is a textnode (safari)
		if ( event.target.nodeType == 3 )
			event.target = event.target.parentNode;

		// Add relatedTarget, if necessary
		if ( !event.relatedTarget && event.fromElement )
			event.relatedTarget = event.fromElement == event.target ? event.toElement : event.fromElement;

		// Calculate pageX/Y if missing and clientX/Y available
		if ( event.pageX == null && event.clientX != null ) {
			var doc = document.documentElement, body = document.body;
			event.pageX = event.clientX + (doc && doc.scrollLeft || body && body.scrollLeft || 0) - (doc.clientLeft || 0);
			event.pageY = event.clientY + (doc && doc.scrollTop || body && body.scrollTop || 0) - (doc.clientTop || 0);
		}

		// Add which for key events
		if ( !event.which && ((event.charCode || event.charCode === 0) ? event.charCode : event.keyCode) )
			event.which = event.charCode || event.keyCode;

		// Add metaKey to non-Mac browsers (use ctrl for PC's and Meta for Macs)
		if ( !event.metaKey && event.ctrlKey )
			event.metaKey = event.ctrlKey;

		// Add which for click: 1 == left; 2 == middle; 3 == right
		// Note: button is not normalized, so don't use it
		if ( !event.which && event.button )
			event.which = (event.button & 1 ? 1 : ( event.button & 2 ? 3 : ( event.button & 4 ? 2 : 0 ) ));

		return event;
	},

	proxy: function( fn, proxy ){
		proxy = proxy || function(){ return fn.apply(this, arguments); };
		// Set the guid of unique handler to the same of original handler, so it can be removed
		proxy.guid = fn.guid = fn.guid || proxy.guid || this.guid++;
		// So proxy can be declared as an argument
		return proxy;
	},

	special: {
		ready: {
			// Make sure the ready event is setup
			setup: bindReady,
			teardown: function() {}
		}
	},
	
	specialAll: {
		live: {
			setup: function( selector, namespaces ){
				jQuery.event.add( this, namespaces[0], liveHandler );
			},
			teardown:  function( namespaces ){
				if ( namespaces.length ) {
					var remove = 0, name = RegExp("(^|\\.)" + namespaces[0] + "(\\.|$)");
					
					jQuery.each( (jQuery.data(this, "events").live || {}), function(){
						if ( name.test(this.type) )
							remove++;
					});
					
					if ( remove < 1 )
						jQuery.event.remove( this, namespaces[0], liveHandler );
				}
			}
		}
	}
};

jQuery.Event = function( src ){
	// Allow instantiation without the 'new' keyword
	if( !this.preventDefault )
		return new jQuery.Event(src);
	
	// Event object
	if( src && src.type ){
		this.originalEvent = src;
		this.type = src.type;
	// Event type
	}else
		this.type = src;

	// timeStamp is buggy for some events on Firefox(#3843)
	// So we won't rely on the native value
	this.timeStamp = now();
	
	// Mark it as fixed
	this[expando] = true;
};

function returnFalse(){
	return false;
}
function returnTrue(){
	return true;
}

// jQuery.Event is based on DOM3 Events as specified by the ECMAScript Language Binding
// http://www.w3.org/TR/2003/WD-DOM-Level-3-Events-20030331/ecma-script-binding.html
jQuery.Event.prototype = {
	preventDefault: function() {
		this.isDefaultPrevented = returnTrue;

		var e = this.originalEvent;
		if( !e )
			return;
		// if preventDefault exists run it on the original event
		if (e.preventDefault)
			e.preventDefault();
		// otherwise set the returnValue property of the original event to false (IE)
		e.returnValue = false;
	},
	stopPropagation: function() {
		this.isPropagationStopped = returnTrue;

		var e = this.originalEvent;
		if( !e )
			return;
		// if stopPropagation exists run it on the original event
		if (e.stopPropagation)
			e.stopPropagation();
		// otherwise set the cancelBubble property of the original event to true (IE)
		e.cancelBubble = true;
	},
	stopImmediatePropagation:function(){
		this.isImmediatePropagationStopped = returnTrue;
		this.stopPropagation();
	},
	isDefaultPrevented: returnFalse,
	isPropagationStopped: returnFalse,
	isImmediatePropagationStopped: returnFalse
};
// Checks if an event happened on an element within another element
// Used in jQuery.event.special.mouseenter and mouseleave handlers
var withinElement = function(event) {
	// Check if mouse(over|out) are still within the same parent element
	var parent = event.relatedTarget;
	// Traverse up the tree
	while ( parent && parent != this )
		try { parent = parent.parentNode; }
		catch(e) { parent = this; }
	
	if( parent != this ){
		// set the correct event type
		event.type = event.data;
		// handle event if we actually just moused on to a non sub-element
		jQuery.event.handle.apply( this, arguments );
	}
};
	
jQuery.each({ 
	mouseover: 'mouseenter', 
	mouseout: 'mouseleave'
}, function( orig, fix ){
	jQuery.event.special[ fix ] = {
		setup: function(){
			jQuery.event.add( this, orig, withinElement, fix );
		},
		teardown: function(){
			jQuery.event.remove( this, orig, withinElement );
		}
	};			   
});

jQuery.fn.extend({
	bind: function( type, data, fn ) {
		return type == "unload" ? this.one(type, data, fn) : this.each(function(){
			jQuery.event.add( this, type, fn || data, fn && data );
		});
	},

	one: function( type, data, fn ) {
		var one = jQuery.event.proxy( fn || data, function(event) {
			jQuery(this).unbind(event, one);
			return (fn || data).apply( this, arguments );
		});
		return this.each(function(){
			jQuery.event.add( this, type, one, fn && data);
		});
	},

	unbind: function( type, fn ) {
		return this.each(function(){
			jQuery.event.remove( this, type, fn );
		});
	},

	trigger: function( type, data ) {
		return this.each(function(){
			jQuery.event.trigger( type, data, this );
		});
	},

	triggerHandler: function( type, data ) {
		if( this[0] ){
			var event = jQuery.Event(type);
			event.preventDefault();
			event.stopPropagation();
			jQuery.event.trigger( event, data, this[0] );
			return event.result;
		}		
	},

	toggle: function( fn ) {
		// Save reference to arguments for access in closure
		var args = arguments, i = 1;

		// link all the functions, so any of them can unbind this click handler
		while( i < args.length )
			jQuery.event.proxy( fn, args[i++] );

		return this.click( jQuery.event.proxy( fn, function(event) {
			// Figure out which function to execute
			this.lastToggle = ( this.lastToggle || 0 ) % i;

			// Make sure that clicks stop
			event.preventDefault();

			// and execute the function
			return args[ this.lastToggle++ ].apply( this, arguments ) || false;
		}));
	},

	hover: function(fnOver, fnOut) {
		return this.mouseenter(fnOver).mouseleave(fnOut);
	},

	ready: function(fn) {
		// Attach the listeners
		bindReady();

		// If the DOM is already ready
		if ( jQuery.isReady )
			// Execute the function immediately
			fn.call( document, jQuery );

		// Otherwise, remember the function for later
		else
			// Add the function to the wait list
			jQuery.readyList.push( fn );

		return this;
	},
	
	live: function( type, fn ){
		var proxy = jQuery.event.proxy( fn );
		proxy.guid += this.selector + type;

		jQuery(document).bind( liveConvert(type, this.selector), this.selector, proxy );

		return this;
	},
	
	die: function( type, fn ){
		jQuery(document).unbind( liveConvert(type, this.selector), fn ? { guid: fn.guid + this.selector + type } : null );
		return this;
	}
});

function liveHandler( event ){
	var check = RegExp("(^|\\.)" + event.type + "(\\.|$)"),
		stop = true,
		elems = [];

	jQuery.each(jQuery.data(this, "events").live || [], function(i, fn){
		if ( check.test(fn.type) ) {
			var elem = jQuery(event.target).closest(fn.data)[0];
			if ( elem )
				elems.push({ elem: elem, fn: fn });
		}
	});

	elems.sort(function(a,b) {
		return jQuery.data(a.elem, "closest") - jQuery.data(b.elem, "closest");
	});
	
	jQuery.each(elems, function(){
		if ( this.fn.call(this.elem, event, this.fn.data) === false )
			return (stop = false);
	});

	return stop;
}

function liveConvert(type, selector){
	return ["live", type, selector.replace(/\./g, "`").replace(/ /g, "|")].join(".");
}

jQuery.extend({
	isReady: false,
	readyList: [],
	// Handle when the DOM is ready
	ready: function() {
		// Make sure that the DOM is not already loaded
		if ( !jQuery.isReady ) {
			// Remember that the DOM is ready
			jQuery.isReady = true;

			// If there are functions bound, to execute
			if ( jQuery.readyList ) {
				// Execute all of them
				jQuery.each( jQuery.readyList, function(){
					this.call( document, jQuery );
				});

				// Reset the list of functions
				jQuery.readyList = null;
			}

			// Trigger any bound ready events
			jQuery(document).triggerHandler("ready");
		}
	}
});

var readyBound = false;

function bindReady(){
	if ( readyBound ) return;
	readyBound = true;

	// Mozilla, Opera and webkit nightlies currently support this event
	if ( document.addEventListener ) {
		// Use the handy event callback
		document.addEventListener( "DOMContentLoaded", function(){
			document.removeEventListener( "DOMContentLoaded", arguments.callee, false );
			jQuery.ready();
		}, false );

	// If IE event model is used
	} else if ( document.attachEvent ) {
		// ensure firing before onload,
		// maybe late but safe also for iframes
		document.attachEvent("onreadystatechange", function(){
			if ( document.readyState === "complete" ) {
				document.detachEvent( "onreadystatechange", arguments.callee );
				jQuery.ready();
			}
		});

		// If IE and not an iframe
		// continually check to see if the document is ready
		if ( document.documentElement.doScroll && window == window.top ) (function(){
			if ( jQuery.isReady ) return;

			try {
				// If IE is used, use the trick by Diego Perini
				// http://javascript.nwbox.com/IEContentLoaded/
				document.documentElement.doScroll("left");
			} catch( error ) {
				setTimeout( arguments.callee, 0 );
				return;
			}

			// and execute any waiting functions
			jQuery.ready();
		})();
	}

	// A fallback to window.onload, that will always work
	jQuery.event.add( window, "load", jQuery.ready );
}

jQuery.each( ("blur,focus,load,resize,scroll,unload,click,dblclick," +
	"mousedown,mouseup,mousemove,mouseover,mouseout,mouseenter,mouseleave," +
	"change,select,submit,keydown,keypress,keyup,error").split(","), function(i, name){

	// Handle event binding
	jQuery.fn[name] = function(fn){
		return fn ? this.bind(name, fn) : this.trigger(name);
	};
});

// Prevent memory leaks in IE
// And prevent errors on refresh with events like mouseover in other browsers
// Window isn't included so as not to unbind existing unload events
jQuery( window ).bind( 'unload', function(){ 
	for ( var id in jQuery.cache )
		// Skip the window
		if ( id != 1 && jQuery.cache[ id ].handle )
			jQuery.event.remove( jQuery.cache[ id ].handle.elem );
}); 
(function(){

	jQuery.support = {};

	var root = document.documentElement,
		script = document.createElement("script"),
		div = document.createElement("div"),
		id = "script" + (new Date).getTime();

	div.style.display = "none";
	div.innerHTML = '   <link/><table></table><a href="/a" style="color:red;float:left;opacity:.5;">a</a><select><option>text</option></select><object><param/></object>';

	var all = div.getElementsByTagName("*"),
		a = div.getElementsByTagName("a")[0];

	// Can't get basic test support
	if ( !all || !all.length || !a ) {
		return;
	}

	jQuery.support = {
		// IE strips leading whitespace when .innerHTML is used
		leadingWhitespace: div.firstChild.nodeType == 3,
		
		// Make sure that tbody elements aren't automatically inserted
		// IE will insert them into empty tables
		tbody: !div.getElementsByTagName("tbody").length,
		
		// Make sure that you can get all elements in an <object> element
		// IE 7 always returns no results
		objectAll: !!div.getElementsByTagName("object")[0]
			.getElementsByTagName("*").length,
		
		// Make sure that link elements get serialized correctly by innerHTML
		// This requires a wrapper element in IE
		htmlSerialize: !!div.getElementsByTagName("link").length,
		
		// Get the style information from getAttribute
		// (IE uses .cssText insted)
		style: /red/.test( a.getAttribute("style") ),
		
		// Make sure that URLs aren't manipulated
		// (IE normalizes it by default)
		hrefNormalized: a.getAttribute("href") === "/a",
		
		// Make sure that element opacity exists
		// (IE uses filter instead)
		opacity: a.style.opacity === "0.5",
		
		// Verify style float existence
		// (IE uses styleFloat instead of cssFloat)
		cssFloat: !!a.style.cssFloat,

		// Will be defined later
		scriptEval: false,
		noCloneEvent: true,
		boxModel: null
	};
	
	script.type = "text/javascript";
	try {
		script.appendChild( document.createTextNode( "window." + id + "=1;" ) );
	} catch(e){}

	root.insertBefore( script, root.firstChild );
	
	// Make sure that the execution of code works by injecting a script
	// tag with appendChild/createTextNode
	// (IE doesn't support this, fails, and uses .text instead)
	if ( window[ id ] ) {
		jQuery.support.scriptEval = true;
		delete window[ id ];
	}

	root.removeChild( script );

	if ( div.attachEvent && div.fireEvent ) {
		div.attachEvent("onclick", function(){
			// Cloning a node shouldn't copy over any
			// bound event handlers (IE does this)
			jQuery.support.noCloneEvent = false;
			div.detachEvent("onclick", arguments.callee);
		});
		div.cloneNode(true).fireEvent("onclick");
	}

	// Figure out if the W3C box model works as expected
	// document.body must exist before we can do this
	jQuery(function(){
		var div = document.createElement("div");
		div.style.width = div.style.paddingLeft = "1px";

		document.body.appendChild( div );
		jQuery.boxModel = jQuery.support.boxModel = div.offsetWidth === 2;
		document.body.removeChild( div ).style.display = 'none';
	});
})();

var styleFloat = jQuery.support.cssFloat ? "cssFloat" : "styleFloat";

jQuery.props = {
	"for": "htmlFor",
	"class": "className",
	"float": styleFloat,
	cssFloat: styleFloat,
	styleFloat: styleFloat,
	readonly: "readOnly",
	maxlength: "maxLength",
	cellspacing: "cellSpacing",
	rowspan: "rowSpan",
	tabindex: "tabIndex"
};
jQuery.fn.extend({
	// Keep a copy of the old load
	_load: jQuery.fn.load,

	load: function( url, params, callback ) {
		if ( typeof url !== "string" )
			return this._load( url );

		var off = url.indexOf(" ");
		if ( off >= 0 ) {
			var selector = url.slice(off, url.length);
			url = url.slice(0, off);
		}

		// Default to a GET request
		var type = "GET";

		// If the second parameter was provided
		if ( params )
			// If it's a function
			if ( jQuery.isFunction( params ) ) {
				// We assume that it's the callback
				callback = params;
				params = null;

			// Otherwise, build a param string
			} else if( typeof params === "object" ) {
				params = jQuery.param( params );
				type = "POST";
			}

		var self = this;

		// Request the remote document
		jQuery.ajax({
			url: url,
			type: type,
			dataType: "html",
			data: params,
			complete: function(res, status){
				// If successful, inject the HTML into all the matched elements
				if ( status == "success" || status == "notmodified" )
					// See if a selector was specified
					self.html( selector ?
						// Create a dummy div to hold the results
						jQuery("<div/>")
							// inject the contents of the document in, removing the scripts
							// to avoid any 'Permission Denied' errors in IE
							.append(res.responseText.replace(/<script(.|\s)*?\/script>/g, ""))

							// Locate the specified elements
							.find(selector) :

						// If not, just inject the full result
						res.responseText );

				if( callback )
					self.each( callback, [res.responseText, status, res] );
			}
		});
		return this;
	},

	serialize: function() {
		return jQuery.param(this.serializeArray());
	},
	serializeArray: function() {
		return this.map(function(){
			return this.elements ? jQuery.makeArray(this.elements) : this;
		})
		.filter(function(){
			return this.name && !this.disabled &&
				(this.checked || /select|textarea/i.test(this.nodeName) ||
					/text|hidden|password|search/i.test(this.type));
		})
		.map(function(i, elem){
			var val = jQuery(this).val();
			return val == null ? null :
				jQuery.isArray(val) ?
					jQuery.map( val, function(val, i){
						return {name: elem.name, value: val};
					}) :
					{name: elem.name, value: val};
		}).get();
	}
});

// Attach a bunch of functions for handling common AJAX events
jQuery.each( "ajaxStart,ajaxStop,ajaxComplete,ajaxError,ajaxSuccess,ajaxSend".split(","), function(i,o){
	jQuery.fn[o] = function(f){
		return this.bind(o, f);
	};
});

var jsc = now();

jQuery.extend({
  
	get: function( url, data, callback, type ) {
		// shift arguments if data argument was ommited
		if ( jQuery.isFunction( data ) ) {
			callback = data;
			data = null;
		}

		return jQuery.ajax({
			type: "GET",
			url: url,
			data: data,
			success: callback,
			dataType: type
		});
	},

	getScript: function( url, callback ) {
		return jQuery.get(url, null, callback, "script");
	},

	getJSON: function( url, data, callback ) {
		return jQuery.get(url, data, callback, "json");
	},

	post: function( url, data, callback, type ) {
		if ( jQuery.isFunction( data ) ) {
			callback = data;
			data = {};
		}

		return jQuery.ajax({
			type: "POST",
			url: url,
			data: data,
			success: callback,
			dataType: type
		});
	},

	ajaxSetup: function( settings ) {
		jQuery.extend( jQuery.ajaxSettings, settings );
	},

	ajaxSettings: {
		url: location.href,
		global: true,
		type: "GET",
		contentType: "application/x-www-form-urlencoded",
		processData: true,
		async: true,
		/*
		timeout: 0,
		data: null,
		username: null,
		password: null,
		*/
		// Create the request object; Microsoft failed to properly
		// implement the XMLHttpRequest in IE7, so we use the ActiveXObject when it is available
		// This function can be overriden by calling jQuery.ajaxSetup
		xhr:function(){
			return window.ActiveXObject ? new ActiveXObject("Microsoft.XMLHTTP") : new XMLHttpRequest();
		},
		accepts: {
			xml: "application/xml, text/xml",
			html: "text/html",
			script: "text/javascript, application/javascript",
			json: "application/json, text/javascript",
			text: "text/plain",
			_default: "*/*"
		}
	},

	// Last-Modified header cache for next request
	lastModified: {},

	ajax: function( s ) {
		// Extend the settings, but re-extend 's' so that it can be
		// checked again later (in the test suite, specifically)
		s = jQuery.extend(true, s, jQuery.extend(true, {}, jQuery.ajaxSettings, s));

		var jsonp, jsre = /=\?(&|$)/g, status, data,
			type = s.type.toUpperCase();

		// convert data if not already a string
		if ( s.data && s.processData && typeof s.data !== "string" )
			s.data = jQuery.param(s.data);

		// Handle JSONP Parameter Callbacks
		if ( s.dataType == "jsonp" ) {
			if ( type == "GET" ) {
				if ( !s.url.match(jsre) )
					s.url += (s.url.match(/\?/) ? "&" : "?") + (s.jsonp || "callback") + "=?";
			} else if ( !s.data || !s.data.match(jsre) )
				s.data = (s.data ? s.data + "&" : "") + (s.jsonp || "callback") + "=?";
			s.dataType = "json";
		}

		// Build temporary JSONP function
		if ( s.dataType == "json" && (s.data && s.data.match(jsre) || s.url.match(jsre)) ) {
			jsonp = "jsonp" + jsc++;

			// Replace the =? sequence both in the query string and the data
			if ( s.data )
				s.data = (s.data + "").replace(jsre, "=" + jsonp + "$1");
			s.url = s.url.replace(jsre, "=" + jsonp + "$1");

			// We need to make sure
			// that a JSONP style response is executed properly
			s.dataType = "script";

			// Handle JSONP-style loading
			window[ jsonp ] = function(tmp){
				data = tmp;
				success();
				complete();
				// Garbage collect
				window[ jsonp ] = undefined;
				try{ delete window[ jsonp ]; } catch(e){}
				if ( head )
					head.removeChild( script );
			};
		}

		if ( s.dataType == "script" && s.cache == null )
			s.cache = false;

		if ( s.cache === false && type == "GET" ) {
			var ts = now();
			// try replacing _= if it is there
			var ret = s.url.replace(/(\?|&)_=.*?(&|$)/, "$1_=" + ts + "$2");
			// if nothing was replaced, add timestamp to the end
			s.url = ret + ((ret == s.url) ? (s.url.match(/\?/) ? "&" : "?") + "_=" + ts : "");
		}

		// If data is available, append data to url for get requests
		if ( s.data && type == "GET" ) {
			s.url += (s.url.match(/\?/) ? "&" : "?") + s.data;

			// IE likes to send both get and post data, prevent this
			s.data = null;
		}

		// Watch for a new set of requests
		if ( s.global && ! jQuery.active++ )
			jQuery.event.trigger( "ajaxStart" );

		// Matches an absolute URL, and saves the domain
		var parts = /^(\w+:)?\/\/([^\/?#]+)/.exec( s.url );

		// If we're requesting a remote document
		// and trying to load JSON or Script with a GET
		if ( s.dataType == "script" && type == "GET" && parts
			&& ( parts[1] && parts[1] != location.protocol || parts[2] != location.host )){

			var head = document.getElementsByTagName("head")[0];
			var script = document.createElement("script");
			script.src = s.url;
			if (s.scriptCharset)
				script.charset = s.scriptCharset;

			// Handle Script loading
			if ( !jsonp ) {
				var done = false;

				// Attach handlers for all browsers
				script.onload = script.onreadystatechange = function(){
					if ( !done && (!this.readyState ||
							this.readyState == "loaded" || this.readyState == "complete") ) {
						done = true;
						success();
						complete();

						// Handle memory leak in IE
						script.onload = script.onreadystatechange = null;
						head.removeChild( script );
					}
				};
			}

			head.appendChild(script);

			// We handle everything using the script element injection
			return undefined;
		}

		var requestDone = false;

		// Create the request object
		var xhr = s.xhr();

		// Open the socket
		// Passing null username, generates a login popup on Opera (#2865)
		if( s.username )
			xhr.open(type, s.url, s.async, s.username, s.password);
		else
			xhr.open(type, s.url, s.async);

		// Need an extra try/catch for cross domain requests in Firefox 3
		try {
			// Set the correct header, if data is being sent
			if ( s.data )
				xhr.setRequestHeader("Content-Type", s.contentType);

			// Set the If-Modified-Since header, if ifModified mode.
			if ( s.ifModified )
				xhr.setRequestHeader("If-Modified-Since",
					jQuery.lastModified[s.url] || "Thu, 01 Jan 1970 00:00:00 GMT" );

			// Set header so the called script knows that it's an XMLHttpRequest
			xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");

			// Set the Accepts header for the server, depending on the dataType
			xhr.setRequestHeader("Accept", s.dataType && s.accepts[ s.dataType ] ?
				s.accepts[ s.dataType ] + ", */*" :
				s.accepts._default );
		} catch(e){}

		// Allow custom headers/mimetypes and early abort
		if ( s.beforeSend && s.beforeSend(xhr, s) === false ) {
			// Handle the global AJAX counter
			if ( s.global && ! --jQuery.active )
				jQuery.event.trigger( "ajaxStop" );
			// close opended socket
			xhr.abort();
			return false;
		}

		if ( s.global )
			jQuery.event.trigger("ajaxSend", [xhr, s]);

		// Wait for a response to come back
		var onreadystatechange = function(isTimeout){
			// The request was aborted, clear the interval and decrement jQuery.active
			if (xhr.readyState == 0) {
				if (ival) {
					// clear poll interval
					clearInterval(ival);
					ival = null;
					// Handle the global AJAX counter
					if ( s.global && ! --jQuery.active )
						jQuery.event.trigger( "ajaxStop" );
				}
			// The transfer is complete and the data is available, or the request timed out
			} else if ( !requestDone && xhr && (xhr.readyState == 4 || isTimeout == "timeout") ) {
				requestDone = true;

				// clear poll interval
				if (ival) {
					clearInterval(ival);
					ival = null;
				}

				status = isTimeout == "timeout" ? "timeout" :
					!jQuery.httpSuccess( xhr ) ? "error" :
					s.ifModified && jQuery.httpNotModified( xhr, s.url ) ? "notmodified" :
					"success";

				if ( status == "success" ) {
					// Watch for, and catch, XML document parse errors
					try {
						// process the data (runs the xml through httpData regardless of callback)
						data = jQuery.httpData( xhr, s.dataType, s );
					} catch(e) {
						status = "parsererror";
					}
				}

				// Make sure that the request was successful or notmodified
				if ( status == "success" ) {
					// Cache Last-Modified header, if ifModified mode.
					var modRes;
					try {
						modRes = xhr.getResponseHeader("Last-Modified");
					} catch(e) {} // swallow exception thrown by FF if header is not available

					if ( s.ifModified && modRes )
						jQuery.lastModified[s.url] = modRes;

					// JSONP handles its own success callback
					if ( !jsonp )
						success();
				} else
					jQuery.handleError(s, xhr, status);

				// Fire the complete handlers
				complete();

				if ( isTimeout )
					xhr.abort();

				// Stop memory leaks
				if ( s.async )
					xhr = null;
			}
		};

		if ( s.async ) {
			// don't attach the handler to the request, just poll it instead
			var ival = setInterval(onreadystatechange, 13);

			// Timeout checker
			if ( s.timeout > 0 )
				setTimeout(function(){
					// Check to see if the request is still happening
					if ( xhr && !requestDone )
						onreadystatechange( "timeout" );
				}, s.timeout);
		}

		// Send the data
		try {
			xhr.send(s.data);
		} catch(e) {
			jQuery.handleError(s, xhr, null, e);
		}

		// firefox 1.5 doesn't fire statechange for sync requests
		if ( !s.async )
			onreadystatechange();

		function success(){
			// If a local callback was specified, fire it and pass it the data
			if ( s.success )
				s.success( data, status );

			// Fire the global callback
			if ( s.global )
				jQuery.event.trigger( "ajaxSuccess", [xhr, s] );
		}

		function complete(){
			// Process result
			if ( s.complete )
				s.complete(xhr, status);

			// The request was completed
			if ( s.global )
				jQuery.event.trigger( "ajaxComplete", [xhr, s] );

			// Handle the global AJAX counter
			if ( s.global && ! --jQuery.active )
				jQuery.event.trigger( "ajaxStop" );
		}

		// return XMLHttpRequest to allow aborting the request etc.
		return xhr;
	},

	handleError: function( s, xhr, status, e ) {
		// If a local callback was specified, fire it
		if ( s.error ) s.error( xhr, status, e );

		// Fire the global callback
		if ( s.global )
			jQuery.event.trigger( "ajaxError", [xhr, s, e] );
	},

	// Counter for holding the number of active queries
	active: 0,

	// Determines if an XMLHttpRequest was successful or not
	httpSuccess: function( xhr ) {
		try {
			// IE error sometimes returns 1223 when it should be 204 so treat it as success, see #1450
			return !xhr.status && location.protocol == "file:" ||
				( xhr.status >= 200 && xhr.status < 300 ) || xhr.status == 304 || xhr.status == 1223;
		} catch(e){}
		return false;
	},

	// Determines if an XMLHttpRequest returns NotModified
	httpNotModified: function( xhr, url ) {
		try {
			var xhrRes = xhr.getResponseHeader("Last-Modified");

			// Firefox always returns 200. check Last-Modified date
			return xhr.status == 304 || xhrRes == jQuery.lastModified[url];
		} catch(e){}
		return false;
	},

	httpData: function( xhr, type, s ) {
		var ct = xhr.getResponseHeader("content-type"),
			xml = type == "xml" || !type && ct && ct.indexOf("xml") >= 0,
			data = xml ? xhr.responseXML : xhr.responseText;

		if ( xml && data.documentElement.tagName == "parsererror" )
			throw "parsererror";
			
		// Allow a pre-filtering function to sanitize the response
		// s != null is checked to keep backwards compatibility
		if( s && s.dataFilter )
			data = s.dataFilter( data, type );

		// The filter can actually parse the response
		if( typeof data === "string" ){

			// If the type is "script", eval it in global context
			if ( type == "script" )
				jQuery.globalEval( data );

			// Get the JavaScript object, if JSON is used.
			if ( type == "json" )
				data = window["eval"]("(" + data + ")");
		}
		
		return data;
	},

	// Serialize an array of form elements or a set of
	// key/values into a query string
	param: function( a ) {
		var s = [ ];

		function add( key, value ){
			s[ s.length ] = encodeURIComponent(key) + '=' + encodeURIComponent(value);
		};

		// If an array was passed in, assume that it is an array
		// of form elements
		if ( jQuery.isArray(a) || a.jquery )
			// Serialize the form elements
			jQuery.each( a, function(){
				add( this.name, this.value );
			});

		// Otherwise, assume that it's an object of key/value pairs
		else
			// Serialize the key/values
			for ( var j in a )
				// If the value is an array then the key names need to be repeated
				if ( jQuery.isArray(a[j]) )
					jQuery.each( a[j], function(){
						add( j, this );
					});
				else
					add( j, jQuery.isFunction(a[j]) ? a[j]() : a[j] );

		// Return the resulting serialization
		return s.join("&").replace(/%20/g, "+");
	}

});
var elemdisplay = {},
	timerId,
	fxAttrs = [
		// height animations
		[ "height", "marginTop", "marginBottom", "paddingTop", "paddingBottom" ],
		// width animations
		[ "width", "marginLeft", "marginRight", "paddingLeft", "paddingRight" ],
		// opacity animations
		[ "opacity" ]
	];

function genFx( type, num ){
	var obj = {};
	jQuery.each( fxAttrs.concat.apply([], fxAttrs.slice(0,num)), function(){
		obj[ this ] = type;
	});
	return obj;
}

jQuery.fn.extend({
	show: function(speed,callback){
		if ( speed ) {
			return this.animate( genFx("show", 3), speed, callback);
		} else {
			for ( var i = 0, l = this.length; i < l; i++ ){
				var old = jQuery.data(this[i], "olddisplay");
				
				this[i].style.display = old || "";
				
				if ( jQuery.css(this[i], "display") === "none" ) {
					var tagName = this[i].tagName, display;
					
					if ( elemdisplay[ tagName ] ) {
						display = elemdisplay[ tagName ];
					} else {
						var elem = jQuery("<" + tagName + " />").appendTo("body");
						
						display = elem.css("display");
						if ( display === "none" )
							display = "block";
						
						elem.remove();
						
						elemdisplay[ tagName ] = display;
					}
					
					jQuery.data(this[i], "olddisplay", display);
				}
			}

			// Set the display of the elements in a second loop
			// to avoid the constant reflow
			for ( var i = 0, l = this.length; i < l; i++ ){
				this[i].style.display = jQuery.data(this[i], "olddisplay") || "";
			}
			
			return this;
		}
	},

	hide: function(speed,callback){
		if ( speed ) {
			return this.animate( genFx("hide", 3), speed, callback);
		} else {
			for ( var i = 0, l = this.length; i < l; i++ ){
				var old = jQuery.data(this[i], "olddisplay");
				if ( !old && old !== "none" )
					jQuery.data(this[i], "olddisplay", jQuery.css(this[i], "display"));
			}

			// Set the display of the elements in a second loop
			// to avoid the constant reflow
			for ( var i = 0, l = this.length; i < l; i++ ){
				this[i].style.display = "none";
			}

			return this;
		}
	},

	// Save the old toggle function
	_toggle: jQuery.fn.toggle,

	toggle: function( fn, fn2 ){
		var bool = typeof fn === "boolean";

		return jQuery.isFunction(fn) && jQuery.isFunction(fn2) ?
			this._toggle.apply( this, arguments ) :
			fn == null || bool ?
				this.each(function(){
					var state = bool ? fn : jQuery(this).is(":hidden");
					jQuery(this)[ state ? "show" : "hide" ]();
				}) :
				this.animate(genFx("toggle", 3), fn, fn2);
	},

	fadeTo: function(speed,to,callback){
		return this.animate({opacity: to}, speed, callback);
	},

	animate: function( prop, speed, easing, callback ) {
		var optall = jQuery.speed(speed, easing, callback);

		return this[ optall.queue === false ? "each" : "queue" ](function(){
		
			var opt = jQuery.extend({}, optall), p,
				hidden = this.nodeType == 1 && jQuery(this).is(":hidden"),
				self = this;
	
			for ( p in prop ) {
				if ( prop[p] == "hide" && hidden || prop[p] == "show" && !hidden )
					return opt.complete.call(this);

				if ( ( p == "height" || p == "width" ) && this.style ) {
					// Store display property
					opt.display = jQuery.css(this, "display");

					// Make sure that nothing sneaks out
					opt.overflow = this.style.overflow;
				}
			}

			if ( opt.overflow != null )
				this.style.overflow = "hidden";

			opt.curAnim = jQuery.extend({}, prop);

			jQuery.each( prop, function(name, val){
				var e = new jQuery.fx( self, opt, name );

				if ( /toggle|show|hide/.test(val) )
					e[ val == "toggle" ? hidden ? "show" : "hide" : val ]( prop );
				else {
					var parts = val.toString().match(/^([+-]=)?([\d+-.]+)(.*)$/),
						start = e.cur(true) || 0;

					if ( parts ) {
						var end = parseFloat(parts[2]),
							unit = parts[3] || "px";

						// We need to compute starting value
						if ( unit != "px" ) {
							self.style[ name ] = (end || 1) + unit;
							start = ((end || 1) / e.cur(true)) * start;
							self.style[ name ] = start + unit;
						}

						// If a +=/-= token was provided, we're doing a relative animation
						if ( parts[1] )
							end = ((parts[1] == "-=" ? -1 : 1) * end) + start;

						e.custom( start, end, unit );
					} else
						e.custom( start, val, "" );
				}
			});

			// For JS strict compliance
			return true;
		});
	},

	stop: function(clearQueue, gotoEnd){
		var timers = jQuery.timers;

		if (clearQueue)
			this.queue([]);

		this.each(function(){
			// go in reverse order so anything added to the queue during the loop is ignored
			for ( var i = timers.length - 1; i >= 0; i-- )
				if ( timers[i].elem == this ) {
					if (gotoEnd)
						// force the next step to be the last
						timers[i](true);
					timers.splice(i, 1);
				}
		});

		// start the next in the queue if the last step wasn't forced
		if (!gotoEnd)
			this.dequeue();

		return this;
	}

});

// Generate shortcuts for custom animations
jQuery.each({
	slideDown: genFx("show", 1),
	slideUp: genFx("hide", 1),
	slideToggle: genFx("toggle", 1),
	fadeIn: { opacity: "show" },
	fadeOut: { opacity: "hide" }
}, function( name, props ){
	jQuery.fn[ name ] = function( speed, callback ){
		return this.animate( props, speed, callback );
	};
});

jQuery.extend({

	speed: function(speed, easing, fn) {
		var opt = typeof speed === "object" ? speed : {
			complete: fn || !fn && easing ||
				jQuery.isFunction( speed ) && speed,
			duration: speed,
			easing: fn && easing || easing && !jQuery.isFunction(easing) && easing
		};

		opt.duration = jQuery.fx.off ? 0 : typeof opt.duration === "number" ? opt.duration :
			jQuery.fx.speeds[opt.duration] || jQuery.fx.speeds._default;

		// Queueing
		opt.old = opt.complete;
		opt.complete = function(){
			if ( opt.queue !== false )
				jQuery(this).dequeue();
			if ( jQuery.isFunction( opt.old ) )
				opt.old.call( this );
		};

		return opt;
	},

	easing: {
		linear: function( p, n, firstNum, diff ) {
			return firstNum + diff * p;
		},
		swing: function( p, n, firstNum, diff ) {
			return ((-Math.cos(p*Math.PI)/2) + 0.5) * diff + firstNum;
		}
	},

	timers: [],

	fx: function( elem, options, prop ){
		this.options = options;
		this.elem = elem;
		this.prop = prop;

		if ( !options.orig )
			options.orig = {};
	}

});

jQuery.fx.prototype = {

	// Simple function for setting a style value
	update: function(){
		if ( this.options.step )
			this.options.step.call( this.elem, this.now, this );

		(jQuery.fx.step[this.prop] || jQuery.fx.step._default)( this );

		// Set display property to block for height/width animations
		if ( ( this.prop == "height" || this.prop == "width" ) && this.elem.style )
			this.elem.style.display = "block";
	},

	// Get the current size
	cur: function(force){
		if ( this.elem[this.prop] != null && (!this.elem.style || this.elem.style[this.prop] == null) )
			return this.elem[ this.prop ];

		var r = parseFloat(jQuery.css(this.elem, this.prop, force));
		return r && r > -10000 ? r : parseFloat(jQuery.curCSS(this.elem, this.prop)) || 0;
	},

	// Start an animation from one number to another
	custom: function(from, to, unit){
		this.startTime = now();
		this.start = from;
		this.end = to;
		this.unit = unit || this.unit || "px";
		this.now = this.start;
		this.pos = this.state = 0;

		var self = this;
		function t(gotoEnd){
			return self.step(gotoEnd);
		}

		t.elem = this.elem;

		if ( t() && jQuery.timers.push(t) && !timerId ) {
			timerId = setInterval(function(){
				var timers = jQuery.timers;

				for ( var i = 0; i < timers.length; i++ )
					if ( !timers[i]() )
						timers.splice(i--, 1);

				if ( !timers.length ) {
					clearInterval( timerId );
					timerId = undefined;
				}
			}, 13);
		}
	},

	// Simple 'show' function
	show: function(){
		// Remember where we started, so that we can go back to it later
		this.options.orig[this.prop] = jQuery.attr( this.elem.style, this.prop );
		this.options.show = true;

		// Begin the animation
		// Make sure that we start at a small width/height to avoid any
		// flash of content
		this.custom(this.prop == "width" || this.prop == "height" ? 1 : 0, this.cur());

		// Start by showing the element
		jQuery(this.elem).show();
	},

	// Simple 'hide' function
	hide: function(){
		// Remember where we started, so that we can go back to it later
		this.options.orig[this.prop] = jQuery.attr( this.elem.style, this.prop );
		this.options.hide = true;

		// Begin the animation
		this.custom(this.cur(), 0);
	},

	// Each step of an animation
	step: function(gotoEnd){
		var t = now();

		if ( gotoEnd || t >= this.options.duration + this.startTime ) {
			this.now = this.end;
			this.pos = this.state = 1;
			this.update();

			this.options.curAnim[ this.prop ] = true;

			var done = true;
			for ( var i in this.options.curAnim )
				if ( this.options.curAnim[i] !== true )
					done = false;

			if ( done ) {
				if ( this.options.display != null ) {
					// Reset the overflow
					this.elem.style.overflow = this.options.overflow;

					// Reset the display
					this.elem.style.display = this.options.display;
					if ( jQuery.css(this.elem, "display") == "none" )
						this.elem.style.display = "block";
				}

				// Hide the element if the "hide" operation was done
				if ( this.options.hide )
					jQuery(this.elem).hide();

				// Reset the properties, if the item has been hidden or shown
				if ( this.options.hide || this.options.show )
					for ( var p in this.options.curAnim )
						jQuery.attr(this.elem.style, p, this.options.orig[p]);
					
				// Execute the complete function
				this.options.complete.call( this.elem );
			}

			return false;
		} else {
			var n = t - this.startTime;
			this.state = n / this.options.duration;

			// Perform the easing function, defaults to swing
			this.pos = jQuery.easing[this.options.easing || (jQuery.easing.swing ? "swing" : "linear")](this.state, n, 0, 1, this.options.duration);
			this.now = this.start + ((this.end - this.start) * this.pos);

			// Perform the next step of the animation
			this.update();
		}

		return true;
	}

};

jQuery.extend( jQuery.fx, {
	speeds:{
		slow: 600,
 		fast: 200,
 		// Default speed
 		_default: 400
	},
	step: {

		opacity: function(fx){
			jQuery.attr(fx.elem.style, "opacity", fx.now);
		},

		_default: function(fx){
			if ( fx.elem.style && fx.elem.style[ fx.prop ] != null )
				fx.elem.style[ fx.prop ] = fx.now + fx.unit;
			else
				fx.elem[ fx.prop ] = fx.now;
		}
	}
});
if ( document.documentElement["getBoundingClientRect"] )
	jQuery.fn.offset = function() {
		if ( !this[0] ) return { top: 0, left: 0 };
		if ( this[0] === this[0].ownerDocument.body ) return jQuery.offset.bodyOffset( this[0] );
		var box  = this[0].getBoundingClientRect(), doc = this[0].ownerDocument, body = doc.body, docElem = doc.documentElement,
			clientTop = docElem.clientTop || body.clientTop || 0, clientLeft = docElem.clientLeft || body.clientLeft || 0,
			top  = box.top  + (self.pageYOffset || jQuery.boxModel && docElem.scrollTop  || body.scrollTop ) - clientTop,
			left = box.left + (self.pageXOffset || jQuery.boxModel && docElem.scrollLeft || body.scrollLeft) - clientLeft;
		return { top: top, left: left };
	};
else 
	jQuery.fn.offset = function() {
		if ( !this[0] ) return { top: 0, left: 0 };
		if ( this[0] === this[0].ownerDocument.body ) return jQuery.offset.bodyOffset( this[0] );
		jQuery.offset.initialized || jQuery.offset.initialize();

		var elem = this[0], offsetParent = elem.offsetParent, prevOffsetParent = elem,
			doc = elem.ownerDocument, computedStyle, docElem = doc.documentElement,
			body = doc.body, defaultView = doc.defaultView,
			prevComputedStyle = defaultView.getComputedStyle(elem, null),
			top = elem.offsetTop, left = elem.offsetLeft;

		while ( (elem = elem.parentNode) && elem !== body && elem !== docElem ) {
			computedStyle = defaultView.getComputedStyle(elem, null);
			top -= elem.scrollTop, left -= elem.scrollLeft;
			if ( elem === offsetParent ) {
				top += elem.offsetTop, left += elem.offsetLeft;
				if ( jQuery.offset.doesNotAddBorder && !(jQuery.offset.doesAddBorderForTableAndCells && /^t(able|d|h)$/i.test(elem.tagName)) )
					top  += parseInt( computedStyle.borderTopWidth,  10) || 0,
					left += parseInt( computedStyle.borderLeftWidth, 10) || 0;
				prevOffsetParent = offsetParent, offsetParent = elem.offsetParent;
			}
			if ( jQuery.offset.subtractsBorderForOverflowNotVisible && computedStyle.overflow !== "visible" )
				top  += parseInt( computedStyle.borderTopWidth,  10) || 0,
				left += parseInt( computedStyle.borderLeftWidth, 10) || 0;
			prevComputedStyle = computedStyle;
		}

		if ( prevComputedStyle.position === "relative" || prevComputedStyle.position === "static" )
			top  += body.offsetTop,
			left += body.offsetLeft;

		if ( prevComputedStyle.position === "fixed" )
			top  += Math.max(docElem.scrollTop, body.scrollTop),
			left += Math.max(docElem.scrollLeft, body.scrollLeft);

		return { top: top, left: left };
	};

jQuery.offset = {
	initialize: function() {
		if ( this.initialized ) return;
		var body = document.body, container = document.createElement('div'), innerDiv, checkDiv, table, td, rules, prop, bodyMarginTop = body.style.marginTop,
			html = '<div style="position:absolute;top:0;left:0;margin:0;border:5px solid #000;padding:0;width:1px;height:1px;"><div></div></div><table style="position:absolute;top:0;left:0;margin:0;border:5px solid #000;padding:0;width:1px;height:1px;" cellpadding="0" cellspacing="0"><tr><td></td></tr></table>';

		rules = { position: 'absolute', top: 0, left: 0, margin: 0, border: 0, width: '1px', height: '1px', visibility: 'hidden' };
		for ( prop in rules ) container.style[prop] = rules[prop];

		container.innerHTML = html;
		body.insertBefore(container, body.firstChild);
		innerDiv = container.firstChild, checkDiv = innerDiv.firstChild, td = innerDiv.nextSibling.firstChild.firstChild;

		this.doesNotAddBorder = (checkDiv.offsetTop !== 5);
		this.doesAddBorderForTableAndCells = (td.offsetTop === 5);

		innerDiv.style.overflow = 'hidden', innerDiv.style.position = 'relative';
		this.subtractsBorderForOverflowNotVisible = (checkDiv.offsetTop === -5);

		body.style.marginTop = '1px';
		this.doesNotIncludeMarginInBodyOffset = (body.offsetTop === 0);
		body.style.marginTop = bodyMarginTop;

		body.removeChild(container);
		this.initialized = true;
	},

	bodyOffset: function(body) {
		jQuery.offset.initialized || jQuery.offset.initialize();
		var top = body.offsetTop, left = body.offsetLeft;
		if ( jQuery.offset.doesNotIncludeMarginInBodyOffset )
			top  += parseInt( jQuery.curCSS(body, 'marginTop',  true), 10 ) || 0,
			left += parseInt( jQuery.curCSS(body, 'marginLeft', true), 10 ) || 0;
		return { top: top, left: left };
	}
};


jQuery.fn.extend({
	position: function() {
		var left = 0, top = 0, results;

		if ( this[0] ) {
			// Get *real* offsetParent
			var offsetParent = this.offsetParent(),

			// Get correct offsets
			offset       = this.offset(),
			parentOffset = /^body|html$/i.test(offsetParent[0].tagName) ? { top: 0, left: 0 } : offsetParent.offset();

			// Subtract element margins
			// note: when an element has margin: auto the offsetLeft and marginLeft 
			// are the same in Safari causing offset.left to incorrectly be 0
			offset.top  -= num( this, 'marginTop'  );
			offset.left -= num( this, 'marginLeft' );

			// Add offsetParent borders
			parentOffset.top  += num( offsetParent, 'borderTopWidth'  );
			parentOffset.left += num( offsetParent, 'borderLeftWidth' );

			// Subtract the two offsets
			results = {
				top:  offset.top  - parentOffset.top,
				left: offset.left - parentOffset.left
			};
		}

		return results;
	},

	offsetParent: function() {
		var offsetParent = this[0].offsetParent || document.body;
		while ( offsetParent && (!/^body|html$/i.test(offsetParent.tagName) && jQuery.css(offsetParent, 'position') == 'static') )
			offsetParent = offsetParent.offsetParent;
		return jQuery(offsetParent);
	}
});


// Create scrollLeft and scrollTop methods
jQuery.each( ['Left', 'Top'], function(i, name) {
	var method = 'scroll' + name;
	
	jQuery.fn[ method ] = function(val) {
		if (!this[0]) return null;

		return val !== undefined ?

			// Set the scroll offset
			this.each(function() {
				this == window || this == document ?
					window.scrollTo(
						!i ? val : jQuery(window).scrollLeft(),
						 i ? val : jQuery(window).scrollTop()
					) :
					this[ method ] = val;
			}) :

			// Return the scroll offset
			this[0] == window || this[0] == document ?
				self[ i ? 'pageYOffset' : 'pageXOffset' ] ||
					jQuery.boxModel && document.documentElement[ method ] ||
					document.body[ method ] :
				this[0][ method ];
	};
});
// Create innerHeight, innerWidth, outerHeight and outerWidth methods
jQuery.each([ "Height", "Width" ], function(i, name){

	var tl = i ? "Left"  : "Top",  // top or left
		br = i ? "Right" : "Bottom", // bottom or right
		lower = name.toLowerCase();

	// innerHeight and innerWidth
	jQuery.fn["inner" + name] = function(){
		return this[0] ?
			jQuery.css( this[0], lower, false, "padding" ) :
			null;
	};

	// outerHeight and outerWidth
	jQuery.fn["outer" + name] = function(margin) {
		return this[0] ?
			jQuery.css( this[0], lower, false, margin ? "margin" : "border" ) :
			null;
	};
	
	var type = name.toLowerCase();

	jQuery.fn[ type ] = function( size ) {
		// Get window width or height
		return this[0] == window ?
			// Everyone else use document.documentElement or document.body depending on Quirks vs Standards mode
			document.compatMode == "CSS1Compat" && document.documentElement[ "client" + name ] ||
			document.body[ "client" + name ] :

			// Get document width or height
			this[0] == document ?
				// Either scroll[Width/Height] or offset[Width/Height], whichever is greater
				Math.max(
					document.documentElement["client" + name],
					document.body["scroll" + name], document.documentElement["scroll" + name],
					document.body["offset" + name], document.documentElement["offset" + name]
				) :

				// Get or set width or height on the element
				size === undefined ?
					// Get width or height on the element
					(this.length ? jQuery.css( this[0], type ) : null) :

					// Set the width or height on the element (default to pixels if value is unitless)
					this.css( type, typeof size === "string" ? size : size + "px" );
	};

});
})();
/*
 * jqModal - Minimalist Modaling with jQuery
 *   (http://dev.iceburg.net/jquery/jqModal/)
 *
 * Copyright (c) 2007,2008 Brice Burgess <bhb@iceburg.net>
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 * 
 * $Version: 07/06/2008 +r13
 */
(function($) {
$.fn.jqm=function(o){
var p={
overlay: 50,
overlayClass: 'jqmOverlay',
closeClass: 'jqmClose',
trigger: '.jqModal',
ajax: F,
ajaxText: '',
target: F,
modal: F,
toTop: F,
onShow: F,
onHide: F,
onLoad: F
};
return this.each(function(){if(this._jqm)return H[this._jqm].c=$.extend({},H[this._jqm].c,o);s++;this._jqm=s;
H[s]={c:$.extend(p,$.jqm.params,o),a:F,w:$(this).addClass('jqmID'+s),s:s};
if(p.trigger)$(this).jqmAddTrigger(p.trigger);
});};

$.fn.jqmAddClose=function(e){return hs(this,e,'jqmHide');};
$.fn.jqmAddTrigger=function(e){return hs(this,e,'jqmShow');};
$.fn.jqmShow=function(t){return this.each(function(){$.jqm.open(this._jqm,t);});};
$.fn.jqmHide=function(t){return this.each(function(){$.jqm.close(this._jqm,t)});};

$.jqm = {
hash:{},
open:function(s,t){var h=H[s],c=h.c,cc='.'+c.closeClass,z=(parseInt(h.w.css('z-index'))),z=(z>0)?z:3000,o=$('<div></div>').css({height:'100%',width:'100%',position:'fixed',left:0,top:0,'z-index':z-1,opacity:c.overlay/100});if(h.a)return F;h.t=t;h.a=true;h.w.css('z-index',z);
 if(c.modal) {if(!A[0])L('bind');A.push(s);}
 else if(c.overlay > 0)h.w.jqmAddClose(o);
 else o=F;

 h.o=(o)?o.addClass(c.overlayClass).prependTo('body'):F;
 if(ie6){$('html,body').css({height:'100%',width:'100%'});if(o){o=o.css({position:'absolute'})[0];for(var y in {Top:1,Left:1})o.style.setExpression(y.toLowerCase(),"(_=(document.documentElement.scroll"+y+" || document.body.scroll"+y+"))+'px'");}}

 if(c.ajax) {var r=c.target||h.w,u=c.ajax,r=(typeof r == 'string')?$(r,h.w):$(r),u=(u.substr(0,1) == '@')?$(t).attr(u.substring(1)):u;
  r.html(c.ajaxText).load(u,function(){if(c.onLoad)c.onLoad.call(this,h);if(cc)h.w.jqmAddClose($(cc,h.w));e(h);});}
 else if(cc)h.w.jqmAddClose($(cc,h.w));

 if(c.toTop&&h.o)h.w.before('<span id="jqmP'+h.w[0]._jqm+'"></span>').insertAfter(h.o);	
 (c.onShow)?c.onShow(h):h.w.show();e(h);return F;
},
close:function(s){var h=H[s];if(!h.a)return F;h.a=F;
 if(A[0]){A.pop();if(!A[0])L('unbind');}
 if(h.c.toTop&&h.o)$('#jqmP'+h.w[0]._jqm).after(h.w).remove();
 if(h.c.onHide)h.c.onHide(h);else{h.w.hide();if(h.o)h.o.remove();} return F;
},
params:{}};
var s=0,H=$.jqm.hash,A=[],ie6=$.browser.msie&&($.browser.version == "6.0"),F=false,
i=$('<iframe src="javascript:false;document.write(\'\');" class="jqm"></iframe>').css({opacity:0}),
e=function(h){if(ie6)if(h.o)h.o.html('<p style="width:100%;height:100%"/>').prepend(i);else if(!$('iframe.jqm',h.w)[0])h.w.prepend(i); f(h);},
f=function(h){try{$(':input:visible',h.w)[0].focus();}catch(_){}},
L=function(t){$()[t]("keypress",m)[t]("keydown",m)[t]("mousedown",m);},
m=function(e){var h=H[A[A.length-1]],r=(!$(e.target).parents('.jqmID'+h.s)[0]);if(r)f(h);return !r;},
hs=function(w,t,c){return w.each(function(){var s=this._jqm;$(t).each(function() {
 if(!this[c]){this[c]=[];$(this).click(function(){for(var i in {jqmShow:1,jqmHide:1})for(var s in this[i])if(H[this[i][s]])H[this[i][s]].w[i](this);return F;});}this[c].push(s);});});};
})(jQuery);var Modals = {
    ModalBG: false,
    CurrentWindow: false,
    CurrentWidth: 0,
    CurrentHeight: 0,
    Confirm: function ( question, action ) {
        Modals.Question( question, [
            { Text: 'Ναι' , Action: action },
            { Text: 'Όχι' }
        ] );
    },
    Question: function ( question, answers ) {
        qq = document.createElement( 'div' );
        qq.appendChild( document.createElement( 'br' ) );
        qq.appendChild( document.createTextNode( question ) );
        options = document.createElement( 'ul' );
        options.style.display = 'inline';
        options.style.margin = '0px';
        options.style.padding = '0px';
        for ( i in answers ) {
            answer = answers[ i ];
            li = document.createElement( 'li' );
            li.style.display = 'inline';
            li.style.padding = '5px';
            btn = document.createElement( 'input' );
            btn.type = 'button';
            btn.value = answer.Text;
            btn.onclick = function ( act ) {
                return function () {
                    if ( act ) {
                        act();
                    }
                    Modals.Destroy();
                };
            }( answer.Action );
            li.appendChild( btn );
            options.appendChild( li );
        }
        qq.appendChild( document.createElement( 'br' ) );
        qq.appendChild( document.createElement( 'br' ) );
        qq.appendChild( options );
        Modals.Create( qq , 250 , 100 );
    },
    Create: function ( node, width, height ) {
        if ( !width ) {
            width = 500;
        }
        if ( !height ) {
            height = 300;
        }
        Modals.ModalBG = bg = document.createElement( 'div' );
        bg.className = 'modalbg';
        Modals.CurrentWindow = modal = document.createElement( 'div' );
        modal.className = 'modal';
        modal.appendChild( node );
        modal.style.width  = width + 'px';
        modal.style.height = height + 'px';
        document.body.appendChild( bg );
        document.body.appendChild( modal );
        document.body.onscroll = Modals.Scrolled;
        Modals.CurrentWidth = width;
        Modals.CurrentHeight = height;
        Modals.Scrolled();
    },
    Destroy: function () {
        document.body.removeChild( Modals.CurrentWindow );
        document.body.removeChild( Modals.ModalBG );
    },
    Scrolled: function () {
        Modals.ModalBG.style.top = document.body.scrollTop + 'px';
        Modals.ModalBG.style.left = document.body.scrollLeft + 'px';
        Modals.CurrentWindow.style.marginLeft = document.body.scrollLeft - Modals.CurrentWidth / 2 + 'px'; // document.body.scrollTop + 'px';
        Modals.CurrentWindow.style.marginTop  = document.body.scrollTop - Modals.CurrentHeight / 2 + 'px';
    }
};
var Dates = {
	LeapYear : function( year ) {
		if ( year % 100 ) {
			if ( year % 400 ) {
				return true;
			}
		}
		else if ( year % 4 ) {
			return true;
		}
		return false;
	},
	DaysInMonth : function( month , year ) {
		switch ( month ) {
			case '01':
			case '03':
			case '05':
			case '07':
			case '08':
			case '10':
			case '12':
				return 31;
			case '02':
				if ( Dates.LeapYear( year ) ) {
					return 29;
				}
				return 28;
		}
		return 30;
	},
	ValidDate : function( day , month , year ) {
		var daysinmonth = Dates.DaysInMonth( month , year );
		if ( day < 0 || day > daysinmonth ) {
			return false;
		}
		if ( month < 0 || month >12 ) {
			return false;
		}
		return true;
	}
};/*
    Developer: dionyziz
*/

var Coala = {
	StoredObjects: [],
	ThreadedRequests: [],
	LazyCommit: null,
    BaseURL: '',
    Frozen: function ( unitid, parameters, failurecallback ) { // get, cacheable client-side (doesn't have to be public -- not necessarily squidable)
        if ( Coala.ThreadedRequests.length ) {
            // force commit of any queued requests
            Coala.Commit();
        }
        // send frozen call separately
        this._AppendRequest( unitid, parameters, 'frozen', failurecallback );
        Coala.Commit();
    },
	Cold: function ( unitid, parameters, failurecallback ) { // get, non-cacheable
        this._AppendRequest( unitid, parameters, 'cold', failurecallback );
        clearTimeout( this.LazyCommit );
		this.LazyCommit = setTimeout( function () {
            Coala.Commit();
        }, 50 );
	},
	Warm: function ( unitid, parameters, failurecallback ) { // post
		this._AppendRequest( unitid, parameters, 'warm', failurecallback );
        clearTimeout( this.LazyCommit );
		this.LazyCommit = setTimeout( function () {
            Coala.Commit();
        }, 50 );
	},
	_AppendRequest: function ( unitid, parameters, type, failurecallback ) {
        if ( typeof unitid === 'undefined' ) {
            alert( 'No coala call unitid specified; aborting call' );
            return;
        }
        if ( typeof parameters === 'undefined' ) {
            alert( 'No coala call parameters specified; aborting call' );
            return;
        }
		Coala.ThreadedRequests.push( 
			{ 
				'unitid'          : unitid, 
				'parameters'      : parameters, 
				'type'            : type,
                'failurecallback' : failurecallback
			}
		);
	},
	Commit: function () {
		if ( Coala.ThreadedRequests.length === 0 ) {
			// nothing to commit
			return;
		}
		
		request = { 'ids' : '' };
		ids = [];
		warm = false;
        failurecallbacks = [];
		for ( i in Coala.ThreadedRequests ) {
			args = [];
            if ( typeof Coala.ThreadedRequests[ i ].failurecallback !== 'undefined' ) {
                failurecallbacks.push(
                    Coala.ThreadedRequests[ i ].failurecallback
                );
            }
			for ( j in Coala.ThreadedRequests[ i ].parameters ) {
                switch ( typeof( Coala.ThreadedRequests[ i ].parameters[ j ] ) ) {
                    case 'object': // object or array
                    case 'function': // function
                        // create coala pointer
    					Coala.StoredObjects[ Coala.StoredObjects.length ] = Coala.ThreadedRequests[ i ].parameters[ j ];
    					arg = 'Coala.StoredObjects[' + ( Coala.StoredObjects.length - 1 ) + ']';
                        break;
                    case 'boolean':
                        if ( Coala.ThreadedRequests[ i ].parameters[ j ] ) {
                            arg = 1;
                        }
                        else {
                            arg = 0;
                        }
                        break;
                    default: // scalar type
                        arg = Coala.ThreadedRequests[ i ].parameters[ j ];
                        break;
				}
				args.push( encodeURIComponent( j ) + '=' + encodeURIComponent( arg ) );
			}
			request[ 'p' + i ] = args.join( '&' );
			switch ( Coala.ThreadedRequests[ i ].type ) {
				case 'warm':
					symbol = '!';
					warm = true;
					break;
				case 'cold':
					symbol = '~';
					break;
                case 'frozen':
                    symbol = '_';
                    break;
				default:
					alert( 'Invalid coala call type' );
			}
			ids.push( symbol + Coala.ThreadedRequests[ i ].unitid );
		}
		if ( warm ) {
			method = 'post';
		}
		else {
			method = 'get';
		}
		request.ids = ids.join( ':' );
		this._PlaceRequest( request, method, failurecallbacks );
		Coala.ThreadedRequests = [];
	},
	_PlaceRequest: function ( request, method, failurecallbacks ) {
		if ( request === null ) {
			request = {};
		}
		Socket = new this._AJAXSocket(); // instanciate new socket object
		if ( Socket === null ) {
			// this shouldn't happen; browser is not XMLHTTP-compatible
			return false;
		}
		realparameters = [];
		for ( parameter in request ) {
			realparameters.push( encodeURIComponent( parameter ) + '=' + encodeURIComponent( request[ parameter ] ) );
		}
		Socket.connect( this.BaseURL + "coala.php" , method , realparameters.join( '&' ) , function ( xh ) {
            Coala._Callback( xh, failurecallbacks );
        } );
		return true; // successfully pushed request
	},
	_Callback: function ( xh, failurecallbacks ) {
		if ( xh.readyState != 4 ) {
            for ( i = 0; i < failurecallbacks.length; ++i ) {
                failurecallbacks[ i ]( 0 );
            }
			return;
		}

		try {
			if ( typeof xh.status !== 'undefined' && xh.status !== 0 ) {
				httpStatus = xh.status;
			}
			else {
				httpStatus = 13030;
			}
		}
		catch ( e ) {
            httpStatus = 13030;
		}
        
        if ( httpStatus < 200 || httpStatus > 300 && httpStatus !== 1223 ) {
            for ( i = 0; i < failurecallbacks.length; ++i ) {
                failurecallbacks[ i ]( httpStatus );
            }
            return;
        }
        if ( typeof water_debug_data !== 'undefined' ) {
            old_water_debug_data = water_debug_data;
        }
        else {
            old_water_debug_data = {};
        }
        
		// execute unit
        resp = xh.responseText;
        
        if ( resp.substr( 0, 'while(1);'.length ) != 'while(1);' ) {
            alert( 'Invalid Coala initization string: \n' + resp );
            return;
        }
        
        resp = resp.substr( 'while(1);'.length ); // JS hijacking prevention
		eval( resp );
		if ( typeof water_debug_data !== 'undefined' ) {
			coala_water_debug_data = water_debug_data; // could be used later, if water improves
			water_debug_data = old_water_debug_data;
		}
	},
	_AJAXSocket: function () {
		// internal class variables
		var xh; // contains a reference to our xmlhttp object
		var bComplete = false;
		
		// public class functions; callable from outside
		
		// main connect function, used to perform an XMLHTTP request
		this.connect = function( sURL, sMethod, sVars, fnDone ) {
			// if we don't have an xmlhttp object there's no point in requesting anything
			if ( !xh ) {
				// just return false
				return false;
            }
			// okay, let's get started; operation hasn't been completed yet
			bComplete = false;
			// make sure the method is uppercase ("GET" or "POST")
			sMethod = sMethod.toUpperCase();
			
			// just to make sure no errors occur
			try {
				// if it's a GET method
				if ( sMethod == "GET" ) {
					// do a simple request
					xh.open( sMethod, sURL + "?" + sVars, true );
					sVars = "";
				}
				else {
					// do a request in the same manner
					xh.open( sMethod, sURL, true );
					// and add the variables to the HTTP header
					xh.setRequestHeader( "Method", "POST " + sURL + " HTTP/1.1" );
					xh.setRequestHeader( "Content-Type",
						"application/x-www-form-urlencoded" );
				}
				// use the onreadystatechange callback method of the xmlhttp object
				xh.onreadystatechange = function() {
					// if the xmlhttp request was successful and we think that the operation hasn't been completed...
					if ( !bComplete && xh.readyState == 4 ) {
						// mark the operation as completed
						bComplete = true;
						// and call the callback function passing the requests identifiers and the xmlhttp object required to get the downloaded results
						fnDone( xh );
					}
				};
				// okay, after we've set up everything, we can safely send the request
				xh.send( sVars );
			}
			catch ( z ) { 
				// woops, something went wrong
                alert( 'Something went wrong during a Coala request: ' + z );
				return false; 
			}
			// everything okay
			return true;
		};
		
		// constructor function begins here
		// try to create an XMLHTTP object instance
		// try/catch pairs to avoid errors
		try {
			// ActiveX, Msxml2.XMLHTTP
			xh = new ActiveXObject( "Msxml2.XMLHTTP" ); 
		}
		catch ( e1 ) {
			try { 
				// ActiveX, Microsoft.XMLHTTP
				xh = new ActiveXObject( "Microsoft.XMLHTTP" ); 
			}
			catch ( e2 ) { 
				try { 
					// non-ActiveX, normal class (used by everyone apart from microsoft ._.)
					xh = new XMLHttpRequest(); 
				}
				catch ( e3 ) { 
					// last catch, exceptions everywhere, can't create XMLHTTP
					xh = false; 
				}
			}
		}
		
		// no xmlhttp object was created, the constructor should return null
		if ( !xh ) {
            alert( 'Failed to create XMLHTTP object; check your browser?' );
			return null;
        }
		
		// return the newly created class
		return this;
	}
};
var Places = {
	onedit : false,
	showLinks : function( id ) {
		if ( !Places.onedit ) {
			g( 'peditlink_' + id ).style.display = 'inline';
			g( 'pdeletelink_' + id ).style.display = 'inline';
		}
	}
	,hideLinks : function( id ) {
		g( 'peditlink_' + id ).style.display = 'none';
		g( 'pdeletelink_' + id ).style.display = 'none';
	}
	,deletep : function( id ) {
		if ( confirm( 'Θέλεις σίγουρα να διαγράψεις τη συγκεκριμένη τοποθεσία;' ) ) { 
			var element = g( 'place_' + id );
			while( element.firstChild ) {
				element.removeChild( element.firstChild );
			}
			element.appendChild( d.createElement( 'Διαγραφή...' ) );
			Coala.Warm( 'place/delete' , {'placeid':id} ); 
		}
	}
	,edit : function( id ) {
		if( Places.onedit ) {
			return;
		}
		
		var place = g( 'praw_' + id ).innerHTML;
		
		var pform = d.createElement( 'form' );
		pform.id = 'editpform';
		pform.name = 'editp';
		pform.action = 'do/place/new';
		pform.method = 'post';
						
		var pid = d.createElement( 'input' );
		pid.type = 'hidden';
		pid.name = 'eid';
		pid.value = id;
		
		var pinput = d.createElement( 'input' );
		pinput.size = '100';
		pinput.type = 'text';
		pinput.name = 'name';
		pinput.value = place;
		pinput.className = 'bigtext';
		
		var psubmit = d.createElement( 'input' );
		psubmit.type = 'submit';
		psubmit.value = 'Επεξεργασία';
		
		var pcancel = d.createElement( 'input' );
		pcancel.type = 'button';
		pcancel.value = 'Ακύρωση';
		pcancel.onclick = ( function( id ) { 
            return function() { 
                Places.cancelEdit( id );
            };
        } )( id );
		
		pform.appendChild( pid );
		pform.appendChild( pinput );
		pform.appendChild( d.createTextNode( ' ' ) );
		pform.appendChild( psubmit );
		pform.appendChild( d.createTextNode( ' ' ) );
		pform.appendChild( pcancel );
		pform.appendChild( d.createElement( 'br' ) );
		
		g( 'place_' + id ).style.display = 'none';
		g( 'place_' + id ).parentNode.insertBefore( pform, g( 'place_' + id ).nextSibling );
		
		Places.onedit = true;
	}
	,cancelEdit : function( id ) {
		g( 'place_' + id ).parentNode.removeChild( g( 'editpform' ) );
		g( 'place_' + id ).style.display = '';
		
		Places.onedit = false;
	}
	,create : function() {
		g( 'newp' ).style.display = 'none';
		g( 'newpform' ).style.display = 'block';
	}
	,cancelCreate : function() {
		g( 'newp' ).style.display = '';
		g( 'newpform' ).style.display = 'none';
	}
};
var AdManager = {
    Create: {
        OnLoad: function() {
            $( "#adtitle" ).keyup( function () {
                var a = $( "div.adspreview div.ad h4 a" )[ 0 ];
				while ( a.firstChild ) {
					a.removeChild( a.firstChild );
				}
				var text = document.createTextNode( $( "#adtitle" )[ 0 ].value );
				a.appendChild( text );
            } );
			$( "#adbody" ).keyup( function () {
                var a = $( "div.adspreview div.ad p a" )[ 0 ];
				while ( a.firstChild ) {
					a.removeChild( a.firstChild );
				}
				var text = document.createTextNode( $( "#adbody" )[ 0 ].value );
				a.appendChild( text );		
            } );
        }
    },
	Demographics: {
		OnLoad: function() {
			AdManager.Demographics.MakeTarget();
		
			var sex = document.getElementById( 'sex' );
			sex.onchange = function() {
				AdManager.Demographics.MakeTarget();
			};
			var minage = document.getElementById( 'minage' );
			minage.onchange = function() {
				AdManager.Demographics.MakeTarget();
			};
			var maxage = document.getElementById( 'maxage' );
			maxage.onchange = function() {
				AdManager.Demographics.MakeTarget();
			};
			var place = document.getElementById( 'place' );
			place.onchange = function() {
				AdManager.Demographics.MakeTarget();
			};
		},
		MakeTarget: function() {
			var sex = document.getElementById( 'sex' ).value;
			var place = document.getElementById( 'place' );
			var minage = document.getElementById( 'minage' ).value;
			var maxage = document.getElementById( 'maxage' ).value;
			if ( place.selectedIndex == 0 ) {
				place = [];
			}
			else {
				var place = place.getElementsByTagName( 'option' )[ place.selectedIndex ].text;
				place = [ place ];
			}
			sex = Number( sex );
			var target = document.getElementById( 'target' ); 
			target.textContent = AdManager.Demographics.TargetGroup( minage, maxage, sex, place );
		},
		TargetGroup: function( minage, maxage, sex, places ) {
			var age = '';
			if ( minage > 0 && maxage == 0 ) {
				age = ' τουλάχιστον ' + minage + ' ετών';
			}
			else if ( minage == 0 && maxage > 0 ) {
				age = ' το πολύ ' + maxage + ' ετών ';
			}
			else if ( minage > 0 && maxage > 0 ) {
				age = ' ' + minage + ' - ' + maxage + ' ετών';
			}
			
			switch ( sex ) {
				case 1:
					sex = ' άντρες';
					if ( maxage != 0 && maxage < 18 ) {
						sex = ' αγόρια ';
					}
					break;
				case 2:
					sex = ' γυναίκες';
					if ( maxage != 0 && maxage < 18 ) {
						sex = ' κορίτσια';
					}
					break;
				default:
					sex = ' άτομα';
					break;
			}
		
			var location = ' από οπουδήποτε';
			if ( places.length > 1 ) {
				location = '';
				for ( var i = 1; i < places.length - 1; ++i ) {
					location += ', ' + places[ i ];
				}
				location = ' από ' + places[ 0 ] + location + ' και ' + places[ places.length - 1 ];
			}
			else if ( places.length == 1 ) {
				location = ' από ' + places[ 0 ];
			}
			
			return 'Στοχεύετε σε' + sex + age + location;
		}
	}
};
var AlbumList = {
	Create : function() {
		var newalbum = document.createElement( 'li' );
		$( newalbum ).append( $( 'div.createalbum' ).clone() ).css( "display" , "none" );
		$( 'ul.albums' )[ 0 ].insertBefore( newalbum , $( 'li.create' )[ 0 ] );
		$( 'span.desc input' ).keydown( function( event ) {
			if ( event.keyCode == 13 ) {
				AlbumList.renameFunc( this, newalbum );
			}
		} ).blur( function() { AlbumList.renameFunc( this, newalbum ); } );
		setTimeout( function() {
			$( newalbum ).show( 400 , function() {
				$( 'span.desc input' )[ 0 ].focus();
			} );
		} , 50 );
		var link = document.createElement( "a" );
		$( link ).attr( { href: "" } ).addClass( "new" ).append( document.createTextNode( "«Ακύρωση" ) ).click( function() {
			$( newalbum ).hide( 400 , function() {
				$( newalbum ).remove();
			} );
			AlbumList.Cancel( newalbum );
			return false;
		} );
		$( 'li.create' ).empty().append( link );
	},
	Cancel : function( albumnode ) {
		var link = document.createElement( "a" );
		var createimg = document.createElement( "img" );
		$( createimg ).attr( {
			src: ExcaliburSettings.imagesurl + "add3.png",
			alt: "Δημιουργία album",
			title: "Δημιουργία album"
		} );
		$( link ).attr( { href: "" } ).addClass( "new" ).append( createimg ).append( document.createTextNode( "Δημιουργία album" ) ).click( function() {
			AlbumList.Create();
			return false;
		} );
		$( 'li.create' ).empty().append( link );
	},
	renameFunc : function( elem, newalbum ) {
		var albumname = $( 'span.desc input' )[ 0 ].value;
		if ( albumname !== '' ) {
			var spandesc = document.createElement( 'span' );
			$( spandesc ).append( document.createTextNode( albumname ) ).addClass( "desc" );
			$( elem ).parent().parent().find( "a" ).append( spandesc );
			$( elem ).parent().remove();
			//$( 'li.create' ).html( $( 'div.creating' ).html() );
			document.body.style.cursor = 'wait';
			Coala.Warm( 'album/create' , { albumname : albumname , albumnode : newalbum } );
		}
	},
    OnLoad : function() {
        $( 'ul.albums li.create a.new' ).click( function() {
            AlbumList.Create();
            return false;
        } );
    }
};
var PhotoList = {
	renaming : false,
	Delete : function( albumid ) {
		$( 'div#photolist div.owner div.delete a' ).css( 'background-image' , 'url( "' + ExcaliburSettings.imagesurl + 'delete.gif" )' );
		if ( confirm( 'Θέλεις σίγουρα να διαγράψεις το album;' ) ) {
			document.body.style.cursor = 'wait';
			Coala.Warm( 'album/delete' , { albumid : albumid } );
		}
		$( 'div#photolist div.owner div.delete a' ).css( 'background-image' , 'url( "' + ExcaliburSettings.imagesurl + 'delete2.gif" )' );		
		return false;
	},
	Rename : function( albumid ) {
		if ( !PhotoList.renaming ) {
			PhotoList.renaming = true;
			var inputbox = document.createElement( 'input' );
			var albumname = $( 'div#photolist h2' ).html();
			$( inputbox ).attr( { 'type' : 'text' } ).css( 'width' , '200px' ).keydown( function( event ) {
				if ( event.keyCode == 13 ) {
					PhotoList.renameFunc( this, albumid, albumname );
				}
			} ).blur( function() { PhotoList.renameFunc( this, albumid, albumname ); } );
			$( inputbox )[ 0 ].value = albumname;
			$( 'div#photolist h2' ).empty().append( inputbox );
		}
		$( 'div#photolist h2 input' )[ 0 ].select();
		return false;
	},
	UploadPhoto : function() {
		$( 'form#uploadform' )[ 0 ].submit();
		$( 'form#uploadform' ).hide();
		$( 'div#uploadingwait' ).show();
	},
	AddPhoto : function( imageinfo , x100 ) {
		imageid = imageinfo.id;
		var li = document.createElement( 'li' );
		$( li ).css( 'display' , 'none' );
		$( 'div#photolist ul' ).prepend( li );
		Coala.Warm( 'album/photo/upload' , { imageid : imageid , node : li , x100 : x100 } );
		if ( imageinfo.imagesnum == 1 ) {
			var dt = document.createElement( 'dt' );
			$( dt ).addClass( 'photonum' );
			$( 'div#photolist dl' ).prepend( dt );
		}
        if ( x100 ) { // if on schools page...
            Modals.Destroy();
        }
		PhotoList.UpdatePhotoNum( imageinfo.imagesnum );
	},
	UpdatePhotoNum : function( photonum ) {
		if ( photonum === 0 ) {
			$( 'div#photolist dl dt.photonum' ).remove();
		}
		else {
			var text = document.createTextNode( photonum );
			$( 'div#photolist dl dt.photonum' ).empty().append( text );
		}
	},
	renameFunc : function( elem, albumid, albumname ) {
		var name = elem.value;
		if ( albumname != name && name !== '' ) {
			window.document.title = name + ' | ' + ExcaliburSettings.applicationname;
			Coala.Warm( 'album/rename' , { albumid : albumid , albumname : name } );
		}
		if ( name!== '' ) {
			$( 'div#photolist h2' ).empty().append( document.createTextNode( name ) );
		}
		PhotoList.renaming = false;
	}
};
var PhotoView = {
	renaming : false,
	Rename : function( photoid , albumname ) {
		if ( !PhotoView.renaming ) {
			PhotoView.renaming = true;
			var inputbox = document.createElement( 'input' );
			var photoname = $( 'div#pview h2' ).text();
			$( inputbox ).attr( { 'type' : 'text' } ).css( 'width' , '200px' ).keydown( function( event ) {
				if ( event.keyCode == 13 ) {
					PhotoView.renameFunc( this, photoid, photoname, albumname );
				}
			} ).blur( function() { PhotoView.renameFunc( this, photoid, photoname, albumname ); } );
			$( inputbox )[ 0 ].value = photoname;
			$( 'div#pview h2' ).empty().append( inputbox );
		}
		$( 'div#pview h2 input' )[ 0 ].select();
		return false;
	},
	Delete : function( photoid ) {
		if ( confirm( "Θέλεις σίγουρα να διαγράψεις την φωτογραφία;" ) ) {
			Coala.Warm( 'album/photo/delete' , { photoid : photoid } );
		}
		return false;
	},
	MainImage : function( photoid , node ) {
		Coala.Warm( 'album/photo/mainimage' , { photoid : photoid } );
		$( node.parentNode ).fadeOut( 200 , function() {
			$( this ).empty()
			.append( document.createTextNode( 'Ορίστηκε ως προεπιλεγμένη' ) )
			.fadeIn( 400 );
		} );
		return false;
	},
	AddFav : function( photoid , linknode ) {
		if ( $( linknode ).find( 'span' ).hasClass( 's_addfav' ) ) {
			$( linknode ).fadeOut( 800 , function() {
				$( linknode ).attr( {
					href : '',
					title : 'Αγαπημένο'
				} )
				.removeClass( 's_addfav' )
				.addClass( 's_isaddedfav' )
				.empty()
				.fadeIn( 800 );
			} );
			Coala.Warm( 'favourites/add' , { itemid : photoid , typeid : Types.Image } );
		}
		return false;
	},
    completeFav : function( photoid ) {
        Coala.Cold( 'album/photo/getfavs', { 'id' : photoid } );
        return false;
    },
	renameFunc : function( elem, photoid, photoname, albumname ) {
		var name = elem.value;
		if ( photoname != name ) {
			Coala.Warm( 'album/photo/rename' , { photoid : photoid , photoname : name } );
			var span = document.createElement( 'span' );
			$( span ).addClass( 's_edit' ).css( 'paddingLeft' , '19px' );
			if ( name === '' ) {
				window.document.title = albumname + ' | ' + ExcaliburSettings.applicationname;
				$( 'div.owner div.edit a' ).empty()
				.append( span )
				.append( document.createTextNode( 'Όρισε όνομα' ) );
			}
			else {
				window.document.title = name + ' | ' + ExcaliburSettings.applicationname;
				$( 'div.owner div.edit a' ).empty()
				.append( span )
				.append( document.createTextNode( 'Μετονομασία' ) );
			}
		}
		$( 'div#pview h2' ).empty().append( document.createTextNode( name ) );
		PhotoView.renaming = false;
	},
    scroll : function( direction ){
        if ( direction == "left" ){
            var target = $( "div.plist > ul > li.selected" ).prev().find( "a" ).attr( "href" );
        }
        else if ( direction == "right" ){
            var target = $( "div.plist > ul > li.selected" ).next().find( "a" ).attr( "href" );
        }
        if ( target != undefined ){
            window.location = target;
        }
    },
    scrollInit : function(){
        $( ".comments textarea" ).keydown( function( e ){
            if (e.which == 37 || e.which == 39 ){
                e.stopImmediatePropagation();
            }
        });
        $( "input" ).live( "keydown", function( e ){
            if (e.which == 37 || e.which == 39 ){
                e.stopImmediatePropagation();
            }
        });
        $( document ).keydown( function( e ) {
            if ( e.which == 37 ){
                PhotoView.scroll( "left" );
                return;
            }
            if ( e.which == 39 ){
                PhotoView.scroll( "right" );
                return;
            }
        });
    }
};
/*
TODO:
    --Resolve Overlapping Tags
    --Meta to Tag creation na gini fadeIn to tag gia 2-3s
    --Otan iparxi ena Tag stn gonia ( i teleuteo tag? ) k to mouse plisiazi to onoma, emfanizi border
    --Ta onomata dn exoun style.cursor="pointer"
    --An kanis drag to box os tn akri, k drop ektos ikonas, dn emfanizete sosta
    --To box dn ginete panta hide me onmouseout
	
    From IE7 with love:
        --To onoma dn emfanizete terma aristera, alla sto kentro i sta deksia
        --Otan ginete onmouseover pano sto onoma sto tag, anabosbini
        --Otan ginonte onmouseover ta tags kato apo tn ikona, dn emfanizonte ta onomata
        --Otan kano click sto box dn me kani redirect sto profil tou xristi
*/
var Tag = {
	virgin : true, // controls whether friends,genders has been set
    photoid : false, // set by view.php, contains the id of the current photo
    friends : [], // an array of all your mutual friends
	genders : [], // an array of all your mutual friends' genders.  friends[ i ]  gender
    already_tagged : [], // an array of all the people tagged in this photo
    clicked : false, // true when the mouse is pressed on the image, false otherwiser
	resized : false, // true when the tag is resized
    run : false, // when tagging action is enabled
	prestart : function( kollitaria, keyword, aux ) {
		if ( Tag.virgin ) {
			Coala.Cold( 'album/photo/tag/getstuff', { 'callmeback' : Tag.start } );
		}
		else {
			Tag.start( kollitaria, keyword, aux );
		}
	},
    // updates the friendlist and enables tagging
    start : function( kollitaria, keyword, aux ) {
		if ( Tag.virgin ) { // after Coala still Virgin
			return;
		}
		var ul = $( 'div.thephoto div.frienders ul' ).find( 'li' ).remove().end()
		.get( 0 );
        if ( kollitaria === false ) {
            kollitaria = Tag.friends;
        }
        for( var i=0; i < kollitaria.length; ++i ) {
            if ( kollitaria[i] === '' ) { // flagged username. Do not display
                continue;
            }
            
            var li = document.createElement( 'li' );
            li.style.cursor = "pointer";
            if ( $.inArray( kollitaria[ i ], Tag.already_tagged ) != -1 ) { // the person is already recognised on this pic. Do not display
                li.style.display = "none";
            }
            
            var div = document.createElement( 'div' );
            div.onmousedown = ( function( username ) {
                            return function( event ) {
                                Tag.submitTag( event, username, this );
                                return false;
                            };
                } )( kollitaria[ i ] );
                
            var span = document.createElement( 'span' );
            
            span.appendChild( document.createTextNode( kollitaria[ i ].substr( 0, keyword.length ) ) );
            div.appendChild( span );
            div.appendChild( document.createTextNode( kollitaria[ i ].substr( keyword.length ) ) );
            li.appendChild( div );
            ul.appendChild( li );
        }
        $( 'dd.addtag' ).hide(); // Hide tagging button
        $( 'div.thephoto > div:not(.tanga)' ).show(); // Show tagging windows, but not image tags
        if ( aux === true ) { // Smooth Scrolling
            $( 'html, body' ).animate( { scrollTop: ( $( 'div.thephoto' ).offset().top - 20 ) }, 700 );
        }
        $( 'div.thephoto div.frienders form input' ).focus();
        Tag.run = true; // Tagging is now fully enabled
    },
    submitTag : function( event, username, node ) {
        // Get the current position of the tagging window
        var left = parseInt( $( 'div.tagme' ).css( 'left' ), 10 );
        var top = parseInt( $( 'div.tagme' ).css( 'top' ), 10 );
		var width = parseInt( $( 'div.tagme' ).css( 'width' ), 10 );
		var height = parseInt( $( 'div.tagme' ).css( 'height'), 10 );
        var ind = $.inArray( username, Tag.friends );
		if ( ind === -1 ) {
			alert( "Δεν μπορείται να σημάνεται τον συγκεκριμένο χρήστη" );
			return;
		}
		var gender = ( Tag.genders[ ind ] == 'f' )?"η ":"ο ";
		
        $( node ).parent().hide(); // hide username from friends TODO: why not just remove?
        $( 'div.thephoto div.frienders form input' ).val( '' ); // clear the input field
        Tag.already_tagged.push( username ); // add username to the array of the people tagged
        
        // Add username to tagged people below the photo
        var div = document.createElement( 'div' );
		div.appendChild( document.createTextNode( gender ) );
		
        var a = document.createElement( 'a' );
        a.title = username;
        a.style.cursor = "pointer";
        a.onmouseover = ( function( username ) { 
                   return function( event ) {
                        var nod = $( "div.thephoto div.tanga div:contains('" + username + "')" );
                        Tag.showhideTag( nod, true, event );
                        if ( !Tag.run ) {
                            nod.find( 'div' ).css( 'borderWidth', '0px' ).show().end();
                        }
                    };
                } )( username );
        a.onmouseout = ( function( username ) { 
                return function () {
                    var nod = $( "div.thephoto div.tanga div:contains('" + username + "')" );
                    Tag.showhideTag( nod, false );
                    if ( !Tag.run ) {
                        nod.find( 'div' ).hide().end();
                    }
                };
            } )( username );
        a.appendChild( document.createTextNode( username ) );
        
        div.appendChild( a );
        
		if ( $( 'div.image_tags:first' ).children().length !== 0 ) {
			var las = $( 'div.image_tags:first div:last' ).get( 0 );
			las.appendChild( document.createTextNode( ' και ' ) );
			if ( $( las ).prevAll().length !== 0 ) {
				las = $( las ).prev().get( 0 );
				las.removeChild( las.lastChild );
				las.appendChild( document.createTextNode( ', ' ) );
			}
		}
		
        $( 'div.image_tags:first' ).get( 0 ).appendChild( div );
        
        // Add a place on the image where the user appears
        var divani = document.createElement( 'div' );
        divani.className = "tag";
        divani.style.left = left + 'px';
        divani.style.top = top + 'px';
		divani.style.width = width + 'px';
		divani.style.height = height + 'px';
        var divani2 = document.createElement( 'div' );
        // updates the friendlist and enables tagging
        divani2.appendChild( document.createTextNode( username ) );
        divani.appendChild( divani2 );
        $( 'div.tanga' ).get( 0 ).appendChild( divani );
        
        // Show all the actual image tags    
        $( 'div.image_tags:first' ).show();
        
        Coala.Warm( 'album/photo/tag/new', { 'photoid' : Tag.photoid,
                                             'username' : username,
                                             'left' : left,
                                             'top' : top,
											 'width' : width,
											 'height' : height,
                                             'callback' : Tag.newCallback
                                            } );
        
        // Disable tagging
        Tag.close();
        return false;
    },
    // Moves the tagging windows to a new position
    focus : function( event ) {
        if ( !Tag.run ) {
            return;
        }
        // Click position, relative to the image
        var x = event.offsetX?(event.offsetX):event.pageX-$( "div.thephoto" ).get( 0 ).offsetLeft;
        var y = event.offsetY?(event.offsetY):event.pageY-$( "div.thephoto" ).get( 0 ).offsetTop;
        // Size of the tagging frame. At the moment it is fixed to 170x170px
        var tag_width = parseInt( $( 'div.tagme' ).css( 'width' ), 10 );
        var tag_height = parseInt( $( 'div.tagme' ).css( 'height' ), 10 );
        // Change border_width accordingly
        // 2 borders of 3 pixels each. Should be used in the calculations as well
        var border_width = 3*2;
        // Size of the image
        var image_width = parseInt( $( 'div.thephoto' ).css( 'width' ), 10 );
        var image_height = parseInt( $( 'div.thephoto' ).css( 'height' ), 10 );
        // We want to place the center of the tagging frame to the position it was clicked, not the top left corner. Change click position accordingly
        x -= tag_width / 2;
        y -= tag_height / 2;
        // Do not allow tagging frame to "escape" from the image
        if ( x < 0 ) { // The new position was really close to the left border of the image.
            x = 0;
        }
        if ( x + tag_width + border_width > image_width ) { // The new position was really close to the right border of the image
            x = image_width - tag_width - border_width;
        }
        if ( y < 0 ) { // The new position was really close to the top border of the image
            y = 0;
        }
        if ( y + tag_height + border_width > image_height ) { // The new position was really close to the bottom border of the image
            y = image_height - tag_height - border_width;
        }
        $( 'div.tagme' ).css( { left : x + 'px', top : y + 'px' } ); // Move to new position
        $( 'div.thephoto div.frienders' ).css( { left: ( x + tag_width ) + 'px', top : y + 'px' } ); // Move TagFriend window accordingly
    },
    // Drags the tagging window while tagging, or shows tags otherwise
    drag : function( event ) {
        if ( !Tag.run ) { // not tagging
            var x = event.offsetX?(event.offsetX):event.pageX-$( "div.thephoto" ).get( 0 ).offsetLeft;
            var y = event.offsetY?(event.offsetY):event.pageY-$( "div.thephoto" ).get( 0 ).offsetTop;
            $( 'div.tanga div' ).each( function( i ) { // Move through all the tags and display appropriate ones. Hide the rest
                var left = parseInt( $( this ).css( 'left' ), 10 );
                var top = parseInt( $( this ).css( 'top' ), 10 );
				var width = parseInt( $( this ).css( 'width' ), 10 );
				var height = parseInt( $( this ).css( 'height' ), 10 );
                if ( x>left && x < left + width && y > top && y < top + height ) { // mouse is over the current tag area
                    $( this ).css( { "borderWidth" : "2px", "cursor" : "pointer" } ).find( 'div' ).show();
                }
                else {
                    $( this ).css( {"borderWidth" : "0px", "cursor" : "default" } ).find( 'div' ).hide();
                }
            } );
            return;
        }
		if ( Tag.resized ) {
			$( 'div.thephoto div.frienders' ).hide();
			Tag.resize_do( event );
		}
        else if ( Tag.clicked ) { // Click is pressed and tagging mode enabled. Drag
            $( 'div.thephoto div.frienders' ).hide();
            Tag.focus( event );
        }
    },
    // Works as event bubble canceling function, so that the rest of the events won't be triggered
    ekso : function( event, stop ) { 
        if ( $.browser.msie ) {
            event.cancelBubble = true;
        }
        else {
            event.stopPropagation();
        }
		if ( stop !== true ) {
			//$( 'div.messageboxer' ).get( 0 ).innerHTML += "<br />Tag.clicked=false apo to ekso";
			Tag.clicked=false; // Drop
		}
    },
    // Runs only when the input is focused
    focusInput : function( event ) {
        $( 'div.thephoto div.frienders form input' ).focus();
        Tag.ekso( event ); // Do not move tagging window
    },
    // Shows friend list
    showSug : function( event ) {
        if ( !Tag.run ) {
            return;
        }
		//$( 'div.messageboxer' ).get( 0 ).innerHTML += "<br /> Tag.clicked=false apo toshowSug";
        Tag.clicked = false;
		Tag.resized = false;
        $( 'div.thephoto div.frienders' ).show();
        $( 'div.thephoto div.frienders form input' ).focus();
    },
    // onmousedown the image
    katoPontike : function( event ) {
        if ( !Tag.run ) {
            return;
        }
		//$( 'div.messageboxer' ).get( 0 ).innerHTML += "<br />Tag.clicked=true apo tn katoPontike";
        Tag.clicked=true;
        Tag.focus( event );
    },
    // Displays friends based on what the user typed in the input box
    filterSug : function( event ) {
        var text = $( 'div.thephoto div.frienders form input' ).val();
        if ( event.keyCode === 27 ) {
            Tag.close();
            return;
        }
        if ( event.keyCode === 13 ) {
            var index, found;
            found = false;
            for( index=0; index < Tag.friends.length; ++index ) {
                if ( text.toUpperCase() == Tag.friends[ index ].toUpperCase() ) {
                    found = true;
                    break;
                }
            }
            if ( !found ) {
                return;
            }
            Tag.submitTag( event, Tag.friends[ index ], $( "div.thephoto div.frienders ul li:contains('" + Tag.friends[ index ] + "') a" ).get( 0 ) );
            return;
        }
        var friends = $.grep( Tag.friends, function( item, index ) { // select friends
                        return ( item.toUpperCase().substr( 0, text.length ) == text.toUpperCase() );
		               } );
        Tag.start( friends, text ); // update friend list
    },
    // Disable tagging
    close : function() {
        $( 'div.thephoto div.frienders form input' ).val( '' );
        $( 'dd.addtag' ).show();
        $( 'div.thephoto div.frienders, div.thephoto div.tagme' ).hide();
        Tag.run = false;
        return false;
    },
    // Delete an image tag
    del : function( id, username ) {
        var index = $.inArray( username, Tag.already_tagged );
        if ( index === -1 ) { // This will never run under normal cirmustances
            return;
        }
        
        $( "div.thephoto div.tanga div:contains('" + username + "')" ).remove();
        
        // Determine the number of the actual tagged people. TODO: Why not use DOM?
        var count = Tag.already_tagged.length;
        for( var i=0; i<Tag.already_tagged.length; ++i ) {
            if ( Tag.already_tagged[ i ] === '' ) {
                --count;
            }
        }
        Tag.already_tagged[ index ] = '';
        --count;
        if ( count === 0 ) {
            $( 'div.image_tags:first' ).hide();
        }
        Coala.Warm( 'album/photo/tag/delete', { 'id' : id } );
    },
    // Appends Tag Id to tags below the image and links on the actual tags
    newCallback : function( id, username, subdomain ) {
        var a = document.createElement( 'a' );
        a.title = "Διαγραφή";
        a.onclick = function() { 
            Tag.del( id, username );
            return false;
          };
        a.style.cursor = "pointer";
        a.className = "tag_del";
        $( a ).click( function() {
				Tag.parseDel( $( this ).parent() );
            } );
        
        a.appendChild( document.createTextNode( " " ) ); // Space needed for CSS Spriting
        $( 'div.image_tags:first div:last' ).get( 0 ).appendChild( a );
        $( 'div.thephoto div.tanga div.tag:last' ).click( function() { document.location.href = "http://" + subdomain + ".zino.gr"; } );
        return false;
    },
    showhideTag : function( node, show, event ) {
        if ( show ) {
            if ( !Tag.run ) {
                //$( node ).css( { "borderWidth" : "2px", "cursor" : "pointer" } );
                $( node ).css( 'borderWidth', '2px' );
                Tag.ekso( event );
            }
            return;
        }
        //$( node ).css( { "borderWidth" : "0px", "cursor" : "default" } ); 
        $( node ).css( 'borderWidth', '0px' );
    },
	// displays conjucates and punctuation correctly
	parseDel : function( par ) {
        var neighbor;
		var deksia = par.nextAll().length;
		if ( deksia === 0 ) { // deleting last tag
			if ( par.prevAll().length !== 0 ) { // there is some tag left to it
				neighbor = $( par ).prev().get( 0 );
				neighbor.removeChild( neighbor.lastChild ); // remove "and" text
				if ( $( neighbor ).prevAll().length !== 0 ) { // if there is something even lefter, append the text there
					neighbor = neighbor.previousSibling;
					neighbor.removeChild( neighbor.lastChild );
					neighbor.appendChild( document.createTextNode( " και " ) );
				}
			}
		}
		else if ( deksia === 1 && par.prevAll().length !== 0 ) {
			neighbor = par.prev().get( 0 );
			neighbor.removeChild( neighbor.lastChild );
			neighbor.appendChild( document.createTextNode( " και " ) );
		}
		$( par ).hide( 400, function() {
			$( this ).remove(); 
		} );
	},
	resize_down : function( event ) {
		if ( !Tag.run ) {
			return;
		}
		Tag.ekso( event );
		Tag.resized = true;
	},
	resize_do : function( event ) {
		if ( !Tag.run ) {
			return;
		}
		// Click position, relative to the image
        var x = event.offsetX?(event.offsetX):event.pageX-$( "div.thephoto" ).get( 0 ).offsetLeft;
        var y = event.offsetY?(event.offsetY):event.pageY-$( "div.thephoto" ).get( 0 ).offsetTop;
		var pos_x = parseInt( $( 'div.tagme' ).css( 'left' ), 10 );
		var pos_y = parseInt( $( 'div.tagme').css( 'top' ), 10 );
		var width = x - pos_x;
		var height = y - pos_y;
		
		if ( width >= 45 ) {
			$( 'div.tagme' ).css( { "width" : width + 'px' } );
		}
		if ( height >= 45 ) {
			$( 'div.tagme' ).css( { "height" : height + 'px' } );
		}
		
		var left = parseInt( $( 'div.tagme' ).css( 'left' ), 10 );
		$( 'div.thephoto div.frienders' ).css( 'left', ( left + width ) + 'px' );
	},
	autocomplete : function( event ) {
		if ( event.keyCode == 9 ) {
			var node = $( "div.thephoto div.frienders ul li div" );
			var text = node.text();
            if ( $.inArray( text, Tag.friends ) !== -1 ) {
				Tag.submitTag( event, text, $( "div.thephoto div.frienders ul li:contains('" + text + "') a" ).get( 0 ) );
				window.setTimeout( "$( 'div.thephoto' ).get( 0 ).scrollIntoView( true )", 5 );
			}
			Tag.ekso( event );
		}
	},
    OnLoad : function() {
        // Already Tagged people
        $( 'div.image_tags:first div' ).each( function( i ) {
                var username = $( this ).find( 'a:first' ).text();
                Tag.already_tagged.push( username );
                var a = $( this ).find( 'a:first' ).get( 0 );
                a.onmouseover = ( function( username ) { 
                           return function( event ) {
                                var nod = $( "div.thephoto div.tanga div:contains('" + username + "')" );
                                Tag.showhideTag( nod, true, event );
                                if ( !Tag.run ) {
                                    nod.find( 'div' ).css( 'borderWidth', '0px' ).show().end();
                                }
                            };
                        } )( username );
                a.onmouseout = ( function( username ) { 
                        return function () {
                            var nod = $( "div.thephoto div.tanga div:contains('" + username + "')" );
                            Tag.showhideTag( nod, false );
                            if ( !Tag.run ) {
                                nod.find( 'div' ).hide().end();
                            }
                        };
                    } )( username );
            } );
        $( 'div.image_tags:first div a.tag_del' ).click( function() {
				Tag.parseDel( $( this ).parent() );
            } );
        // Show/Hide tags when not tagging
        $( 'div.thephoto div.tanga div' ).mouseover( function(event) { Tag.showhideTag( this, true, event ); } );
        $( 'div.thephoto div.tanga div' ).mouseout( function() { Tag.showhideTag( this, false ); } );
        
        // Dump Face Detection Heuristic. Most faces are located on the first quarter of the image vertically, and in the middle horizontally. Place tag frame there
        // Change border_width accordingly
        var border_width = 3*2;
        var tag_width = parseInt( $( 'div.tagme' ).css( 'width' ), 10 );
        var tag_height = parseInt( $( 'div.tagme' ).css( 'height' ), 10 );
        var image_width = parseInt( $( 'div.thephoto' ).css( 'width' ), 10 );
        var image_height = parseInt( $( 'div.thephoto' ).css( 'height' ), 10 );
        var x = ( image_width - tag_width - border_width )/2;
        var y = ( image_height - tag_height - border_width )*0.25; // 1/4
        $( 'div.tagme' ).css( { left : x + 'px', top : y + 'px' } );
        $( 'div.thephoto div.frienders' ).css( { left: ( x + tag_width ) + 'px', top : y + 'px' } );
    }
};
var Notification = {
    Expanded : true,
    TraversedAll : false,
	Visit : function( url , typeid , eventid , commentid ) {
        //Notification.DecrementCount();
		if ( typeid == 3 ) {
			document.location.href = url;
		} 
		else {
			Coala.Warm( 'notification/delete' , { notificationid: eventid , relationnotif : false } );
			document.location.href = url;
		}
		return false;
	},
	Delete : function( eventid ) {
        if ( Notification.INotifs === 0 && Notification.VNotifs <= 5 ) {
            --Notification.VNotifs;
        }
		$( '#event_' + eventid ).animate( { opacity : "0" , height : "0" } , 400 , "linear" , function() {
			$( this ).remove();
            if ( Notification.VNotifs === 0 ) {
                $( "div.notifications" ).remove();
            }
		} );
		Coala.Warm( 'notification/delete' , { notificationid: eventid , relationnotif : false } );
        
        if ( Notification.INotifs > 0 ) {
            var newnotif = $( '#inotifs div.event:first-child' );
            var clonenew = $( newnotif ).clone( true );
            $( "div.notifications div.list" ).append( clonenew );
            clonenew = $( "div.notifications div.list div.event:last-child" )[ 0 ];
            var targetheight = clonenew.offsetHeight;
            $( clonenew ).css( {
                "height" : "0",
                "opacity" : "0"
            } )
            .animate( {
                "height" : targetheight,
                "opacity" : "1"
            } , 400 , "linear" );
            $( newnotif ).remove();
            --Notification.INotifs;
        }
        if ( Notification.INotifs > 0 && Notification.INotifs < 3 && !Notification.TraversedAll ) {
            var lastnodeid = $( '#inotifs div.event:last-child' ).attr( "id" );
            var id = lastnodeid.substr( 6 );
            Coala.Warm( "notification/find" , {
                notifid : id,
                limit : "3"
            } );

        }
        //Notification.DecrementCount(); 

		return false;
	},
    DecrementCount: function () {
        var count = document.title.split( '(' )[ 1 ].split( ')' )[ 0 ];
        
        if ( count == '10+' ) {
            return;
        }
        --count;
        if ( count === 0 ) {
            document.title = 'Zino';
        }
        else {
            document.title = 'Zino (' + count + ')';
        }
    },
	AddFriend : function( eventid , theuserid ) {
		$( '#addfriend_' + theuserid  + ' a' )
		.fadeOut( 400 , function() {
			$( this )
			.parent()
			.empty()
			.append( document.createTextNode( 'Έγινε προσθήκη' ) );
		} );
		Coala.Warm( 'notification/addfriend' , { userid : theuserid } );
		Coala.Warm( 'notification/delete' , { notificationid: eventid , relationnotif : false } );
        //Notification.DecrementCount();
		return false;
	},
	AddNotif : function( node ) {
		if ( Notification.VNotifs === 0 ) {
			Notification.VNotifs++;
			var notifscontainer = document.createElement( 'div' );
			var list = document.createElement( 'div' );
			var h3 = document.createElement( 'h3' );
			var expand = document.createElement( 'div' );
			var link = document.createElement( 'a' );
            var inotifsdiv = document.createElement( 'div' );

            $( inotifsdiv ).attr( "id" , "inotifs" ).addClass( "invisible" );
			$( expand ).addClass( "expand" ).append( link );
			$( h3 ).append( document.createTextNode( "Ενημερώσεις" ) );
			$( list ).addClass( "list" );
			$( notifscontainer ).addClass( "notifications" )
			.append( h3 ).append( list ).append( inotifsdiv ).append( expand );
			$( 'div.content div.frontpage' ).prepend( notifscontainer );
			//var notiflistheight = $( notiflist )[ 0 ].offsetHeight;
			$( link ).css( {
                "background-position" : "4px -1440px",
                "cursor" : "pointer"
            } ).attr( {
				title : "Απόκρυψη",
				href : ""
			} ).append( document.createTextNode( ' ' ) )
			.click( function() {
                if ( !Notification.Expanded ) {
                    $( this ).css( "background-position" , "4px -1440px" )
                    .attr( {
                        title : 'Απόκρυψη'
                    } );
                    Notification.Expanded = true;
                }
                else {  
                    $( this ).css( "background-position" , "4px -1252px" )
                    .attr( {
                        title : 'Εμφάνιση:'
                    } );
                    Notification.Expanded = false;
                }
                $( 'div.notifications div.list' ).slideToggle( "slow" );
			
                return false;
			} );
		}
		else if ( Notification.VNotifs < 5 ) {
			Notification.VNotifs++;
		}
		else {
			$( 'div.frontpage div.notifications div.list>div:last-child' ).animate( {
				opacity : "0",
				height: "0"
			} , 400 , "linear" , function() {
				var cloneit = $( this ).clone( true );
                $( "#inotifs" ).prepend( cloneit );
                $( this ).remove();
			} );
            Notification.INotifs++;
		}
		Notification.Show( node );
	},
	Show : function( node ) {
		$( 'div.notifications div.list' ).prepend( node );
		var targetheight = $( 'div.notifications div.list div.event' )[ 0 ].offsetHeight;
		$( node ).css( {
            'opacity' : '0',
            'height' : '0'
        } ).animate( {
			height: targetheight,
			opacity: "1"
		} , 400 , 'linear' )
		.mouseover( function() {
			$( this ).css( "border" , "1px dotted #666" ).css( "padding" , "4px" );
		} )
		.mouseout( function() {
			$( this ).css( "border" , "0" ).css( "padding" , "5px" );
		} );
	}
};
var PollList = {
	numoptions : 0,
	QuestionText: '',
	OptionsText: '',
	CreateQuestion : function() {
		if ( $( 'div#polist ul div.creationmockup input' )[ 0 ].value !== '' ) {
			var heading = document.createElement( 'h4' );
			$( heading ).append( document.createTextNode( $( 'div#polist ul div.creationmockup input' )[ 0 ].value ) );
			$( heading ).css( 'margin-top', '0' );
			PollList.QuestionText = $( 'div#polist ul div.creationmockup input' )[ 0 ].value;
			$( 'div#polist ul div.creationmockup' ).empty().append( heading );
			$( 'div#polist ul div.creationmockup' ).append( $( 'div#polist div.tip2' ).clone().css( 'display', 'block' ) );
			PollList.NewOption();
		}
	},
	CreateOption : function( newoption ) {
		if ( newoption.value !== '' ) {
			var option = document.createElement( 'div' );
			$( option ).append( document.createTextNode( $( newoption )[ 0 ].value ) ).addClass( 'newoption' );
			$( $( newoption )[ 0 ].parentNode ).remove();
			$( 'div#polist ul li div.creationmockup')[ 0 ].insertBefore( option, $( 'div#polist ul li div.creationmockup div.tip2' )[ 0 ] );
			if ( PollList.numoptions === 0 ) {
				var donelink = document.createElement( 'a' );
				$( donelink ).attr( { 'href' : '' } ).addClass( 'button' ).css( 'font-weight', 'bold' ).append( document.createTextNode( 'Δημιουργία' ) ).click( function() {
					PollList.OptionsText = PollList.OptionsText.substr( 0, PollList.OptionsText.length - 1 );
					var newpoll = document.createElement( 'li' );
					$( newpoll ).html( $( 'div#polist div.creatingpoll' ).html() );
					$( 'div#polist ul' )[ 0 ].insertBefore( newpoll, $( 'div#polist ul li div.creationmockup' )[ 0 ].parentNode.nextSibling.nextSibling );
					$( $( 'div#polist ul li div.creationmockup' )[ 0 ].parentNode ).remove();
					Coala.Warm( 'poll/new', { question : PollList.QuestionText, options : PollList.OptionsText, node : newpoll } );
					PollList.numoptions = 0;
					PollList.OptionsText = '';
					return false;
				} );
				$( 'div#polist ul li div.creationmockup' ).append( donelink );
			}
			PollList.OptionsText += newoption.value + '|';
			++PollList.numoptions;
			PollList.NewOption();
		}
	},
	Create : function() {
		var newpoll = document.createElement( 'li' );
		$( newpoll ).append( $( 'div.creationmockup' ).clone() );
		$( 'div#polist ul' )[ 0 ].insertBefore( newpoll, $( 'ul li.create' )[ 0 ] );
		$( 'div#polist ul div.creationmockup' ).css( 'height', '0' ).animate( { height: '40px' }, 400, function() {
			$( this ).css( 'height', '' );
		} );
		$( 'div#polist ul div.creationmockup input' )[ 0 ].focus();
		$( 'div#polist ul div.creationmockup input' ).keydown( function( event ) {
            $( function() {
                if ( $( 'div#pollview' )[ 0 ] ){
                    var delete1 = new Image();
                    delete1.src = ExcaliburSettings.imagesurl + 'delete.gif';
                    var delete2 = new Image();
                    delete2.src = ExcaliburSettings.imagesurl + 'delete2.gif';
                }
                
            } );
			if ( event.keyCode == 13 ) {
				PollList.CreateQuestion();
			}		
		} );
		$( 'div#polist ul div.creationmockup div a' ).click( function() {
			PollList.CreateQuestion();
			return false;
		} );
		var link = document.createElement( "a" );
		$( link ).attr( { href: "" } ).addClass( "new" ).append( document.createTextNode( "«Ακύρωση" ) ).click( function() {
			$( 'div#polist ul div.creationmockup' ).animate( { height: "0" }, 400, function() {
				$( newpoll ).remove();
				$( this ).css( 'display', 'none');
			} );
			PollList.Cancel();
			return false;
		} );
		$( 'div#polist ul li.create' ).empty().append( link );
		return false;
	},
	Cancel : function() {
		PollList.numoptions = 0;
		PollList.OptionsText = '';
		var link = document.createElement( "a" );
		var createimg = document.createElement( "img" );
		$( createimg ).attr( {
			src: ExcaliburSettings.imagesurl + "add3.png",
			alt: "Δημιουργία δημοσκόπησης",
			title: "Δημιουργία δημοσκόπησης"
		} );
		$( link ).attr( { href: "" } ).append( createimg ).append( document.createTextNode( "Δημιουργία δημοσκόπησης" ) ).click( function() {
			PollList.Create();
			return false;
		} );
		$( 'div#polist ul li.create' ).empty().append( link );
	},
	NewOption : function() {
		var container = document.createElement( 'div' );
		var newoption = document.createElement( 'input' );
		var acceptlink = document.createElement( 'a' );
		var acceptimage = document.createElement( 'img' );
		$( acceptimage ). attr( { 
			'src' : ExcaliburSettings.imagesurl + "accept.png",
			'alt' : "Δημιουργία",
			'title' : "Δημιουργία"
		} );
		$( newoption ).attr( { 'type' : 'text' } ).css( 'width', '300px' ).keydown( function( event ) {
			if ( event.keyCode == 13 ) {
				PollList.CreateOption( newoption );
				return false;
			}
		} );

		$( container ).append( newoption ).append( acceptlink );
		$( 'div#polist ul li div.creationmockup')[ 0 ].insertBefore( container, $( 'div#polist ul li div.creationmockup div.tip2' )[ 0 ] );
		$( acceptlink ).attr( { 'href' : '' } ).append( acceptimage ).click( function( node ) {
			PollList.CreateOption( newoption );
			return false;
		} );
		$( 'div#polist ul li div.creationmockup div input' )[ 0 ].focus();
	},
    OnLoad : function() {
        $( 'div#polist li.create a' ). click( function() {
            PollList.Create();
            return false;
        } );
    }
};
var PollView = {
	Delete : function( pollid ) {
		if ( confirm( "Θέλεις σίγουρα να διαγράψεις τη δημοσκόπηση;" ) ) {
			document.body.style.cursor = 'wait';
			Coala.Warm( 'poll/delete' , { pollid : pollid } );
		}
		return false;
	},
	Vote : function( optionid , pollid , node ) {
		var parent = node.parentNode.parentNode.parentNode.parentNode.parentNode;
		$( parent ).html( $( 'div.posmall div.voting' ).html() );
		Coala.Warm( 'poll/vote' , { optionid : optionid , pollid : pollid , node : parent } );
	}
};
var JournalView = {
	Delete : function( journalid ) {
		if ( confirm( "Θέλεις σίγουρα να διαγράψεις την καταχώρηση;" ) ){
			document.body.style.cursor = 'wait';
			Coala.Warm( 'journal/delete' , { journalid : journalid } );
		}
		return false;
	},
	AddFav : function( journalid , linknode ) {
		if ( $( linknode ).find( 'span' ).hasClass( 's_addfav' ) ) {
			$( linknode ).fadeOut( 800 , function() {
				$( linknode )
				.attr( {
					href : '',
					title : 'Αγαπημένο'
				} )
				.removeClass( 's_addfav' )
				.addClass( 's_isaddedfav' )
				.empty()
				.fadeIn( 800 );
			} );
			Coala.Warm( 'favourites/add' , { itemid : journalid , typeid : Types.Journal } );
		}
		return false;
	}
};var JournalNew = {
	Create : function( journalid ) {
		var title = $( 'div#journalnew form div.title input' )[ 0 ].value;
		var text = WYSIWYG.ByName.text.getContents();
		if ( title === '' ) {
			alert( "Πρέπει να ορίσεις τίτλο" );
			$( 'div#journalnew form div.title input' )[ 0 ].focus();
			return false;
		}
		if ( text.length < 5 ) {
			alert( "Δε μπορείς να δημοσιεύσεις κενή καταχώρηση" );
			return false;
		}
        $( '#publish' )[ 0 ].disabled = true;
		return true;
	},
    OnLoad : function() {
        window.title = 'Firing "Create"';
        WYSIWYG.Create( document.getElementById( 'wysiwyg' ), 'text', [
            {
                'tooltip': 'Έντονη Γραφή',
                'image': ExcaliburSettings.imagesurl + 'text_bold.png',
                'command': 'bold'
            },
            {
                'tooltip': 'Πλάγια Γραφή',
                'image': ExcaliburSettings.imagesurl + 'text_italic.png',
                'command': 'italic'
            },
            {
                'tooltip': 'Εισαγωγή Link',
                'image': ExcaliburSettings.imagesurl + 'world.png',
                'command': WYSIWYG.CommandLink( 'text' )
            },
            {
                'tooltip': 'Εισαγωγή Εικόνας',
                'image': ExcaliburSettings.imagesurl + 'picture.png',
                'command': WYSIWYG.CommandImage( 'text' )
            },
            {
                'tooltip': 'Εισαγωγή Video',
                'image': ExcaliburSettings.imagesurl + 'television.png',
                'command': WYSIWYG.CommandVideo( 'text' )
            }
        ], 2 );
    }
};
var Join = {
	ShowTos : function () {
		var area = $( 'div#join_tos' )[ 0 ].cloneNode( true );
		$( area ).css( "display" , "block" );
		Modals.Create( area, 620, 520 );
	},
    UserExists : function() {
        if ( !Join.usernameexists ) {
            Join.usernameexists = true;
            $( $( 'form.joinform div > span' )[ 1 ] ).css( "opacity" , "0" ).css( "display" , "inline" ).animate( { opacity : "1" } , 700 );
            Join.username.focus();
            Join.username.select();
            document.body.style.cursor = 'default';
        }
    },
    JoinOnLoad : function() {
        Join.timervar = 0;
        Join.hadcorrect = false;
        Join.usernameerror = false; //used to check if a username has been given
        Join.invalidusername = false;
        Join.pwderror = false; //used to check if a password has been given
        Join.repwderror = false; //used to check if password is equal with the retyped password
        Join.usernameexists = false;
        Join.emailerror = false;
        Join.username = $( 'form.joinform div input' )[ 0 ];
        Join.password = $( 'form.joinform div input' )[ 1 ];
        Join.repassword = $( 'form.joinform div input' )[ 2 ];
        Join.enabled = true;
        Join.email = $( 'form.joinform div input' )[ 3 ];
        $( 'form.joinform' ).submit( function() {
            return false;
        } );
        $( 'form.joinform div input' ).focus( function() {
            $( this ).css( "border" , "1px solid #bdbdff" );
        }).blur( function() {
            $( this ).css( "border" , "1px solid #999" );
        });
        $( Join.username ).keyup( function( event ) {
            if ( event.keyCode == 13 && !Join.usernameerror && !Join.usernameexists && !Join.invalidusername ) {
                Join.password.focus();
            }
        } );
        $( Join.password ).keyup( function( event ) {
            if ( event.keyCode == 13 && !Join.pwderror ) {
                Join.repassword.focus();
            }
        } );
        $( Join.repassword ).keyup( function( event ) {
            if ( event.keyCode == 13 && !Join.repwderror ) {
                Join.email.focus();
            }
        } );
        $( Join.email ).keyup( function( event ) {
            if ( event.keyCode == 13 && !Join.emailerror ) {
                $( 'div a.button' )[ 0 ].focus();
            }
        } );
        $( Join.username ).keydown( function( event ) {
            if ( Join.usernameerror ) {
                if ( Join.username.value.length >= 4 && Join.username.value.length <= 20 ) {
                    Join.usernameerror = false;
                    $( $( 'form.joinform div > span' )[ 0 ] ).animate( { opacity: "0" } , 700 , function() {
                        $( this ).css ( "display" , "none");
                    });
                }
            }
            if ( Join.usernameexists ) {
                if ( event.keyCode != 13 ) {
                    Join.usernameexists = false;
                    $( $( 'form.joinform div > span' )[ 1 ] ).animate( { opacity: "0" } , 700 , function() {
                        $( this ).css( "display" , "none" );
                    });
                    $( 'div a.button' ).removeClass( 'button_disabled' );
                    Join.enabled = true;
                }
            }
            if ( Join.invalidusername ) {
                Join.invalidusername = false;
                $( $( 'form.joinform div > span' )[ 2 ] ).animate( { opacity: "0" } , 700 , function() {
                    $( this ).css( "display" , "none" );
                });
            }
        });	
        
        $( Join.password ).keyup( function() {
            if ( Join.pwderror ) {
                if ( Join.password.value.length >= 4 ) {
                    Join.pwderror = false;
                    $( $( 'form.joinform div > span' )[ 3 ] ).animate( { opacity: "0" } , 700 , function() {
                        $( this ).css( "display" , "none" );
                    });
                }
            }
        });
        
        $( Join.repassword ).keyup( function() {
            if ( Join.repwderror ) {
                if ( Join.repassword.value == Join.password.value ) {
                    Join.repwderror = false;
                    $( $( 'form.joinform div > span' )[ 4 ] ).animate( { opacity: "0" } , 700 , function() {
                        $( this ).css( "display" , "none" );
                    });
                }
            }
        });
        
        $( Join.email ).keyup( function() {
            if ( Join.emailerror ) {
                if ( Join.email.value === '' || /^[a-zA-Z0-9.\-_]+@[a-zA-Z0-9.\-_]+$/.test( Join.email.value ) ) {
                    Join.emailerror = false;
                    $( $( 'form.joinform div > span' )[ 5 ] ).animate( { opacity: "0" } , 700 , function() {
                        $( this ).css( "display" , "none" );
                    });
                }
            }
        });
        
        if ( Join.username ) {
            Join.username.focus();
        }
        
        $( 'form.joinform p a' ).click( function () {
            Join.ShowTos();
            return false;
        });
        
        $( 'div a.button' ).click( function() {
            var create = true;
            if ( Join.username.value.length < 4 || Join.username.value.length > 20 ) {
                if ( !Join.usernameerror ) {
                    Join.usernameerror = true;
                    $( $( 'form.joinform div > span' )[ 0 ] ).css( "opacity" , "0" ).css( "display" , "inline" ).animate( { opacity : "1" } , 400 );
                }
                Join.username.focus();
                create = false;
            }
            if ( Join.username.value.length >= 4 && !/^[a-zA-Z][a-zA-Z\-_0-9]{3,49}$/.test( Join.username.value ) ) {
                if ( !Join.invalidusername ) {
                    Join.invalidusername = true;
                    $( $( 'form.joinform div > span' )[ 2 ] ).css( "opacity" , "0" ).css( "display" , "inline" ).animate( { opacity : "1" } , 400 );
                }
                Join.username.focus();
                create = false;
            }
            if ( Join.password.value.length < 4 ) {
                if ( !Join.pwderror ) {
                    Join.pwderror = true;
                    $( $( 'form.joinform div > span' )[ 3 ] ).css( "opacity" , "0" ).css( "display" , "inline" ).animate( { opacity: "1" } , 400 );
                }
                if ( !Join.usernamerror && !Join.invalidusername && !Join.usernameexists ) {
                    //if the username and password are empty then focus the username inputbox
                    Join.password.focus();
                }
                create = false;
            }
            if ( Join.password.value != Join.repassword.value && !Join.pwderror ) {
                if ( !Join.repwderror ) {
                    Join.repwderror = true;
                    $( $( 'form.joinform div div > span' )[ 0 ] ).css( "opacity" , "0" ).css( "display" , "inline" ).animate( { opacity: "1" } , 400 );
                }
                if ( !Join.usernameerror && !Join.invalidusername && !Join.usernameexists ) {
                    Join.repassword.focus();
                }
                create = false;
            }
            if ( !/^[a-zA-Z0-9.\-_]+@[a-zA-Z0-9.\-_]+$/.test( Join.email.value ) ) {
                if ( !Join.emailerror ) {
                    Join.emailerror = true;
                    $( $( 'form.joinform div > span' )[ 5 ] ).css( "opacity" , "0" ).css( "display" , "inline" ).animate( { opacity: "1" } , 400 );
                }
                if ( !Join.usernameerror && !Join.invalidusername && !Join.usernameexists && !Join.pwderror && !Join.repwderror ) {
                    Join.email.focus();
                }
                create = false;
            }
            if ( create ) {
                if ( Join.enabled ) {
                    document.body.style.cursor = 'wait';
                    $( this ).addClass( 'button_disabled' );
                    Coala.Warm( 'user/join' , { username : Join.username.value , password : Join.password.value , email : Join.email.value } );
                }
            }
            return false;
        } );
    }
};
var Joined = {
    JoinedOnLoad : function() {
        Joined.doby = $( 'div.profinfo form div select' )[ 2 ];
        Joined.dobm = $( 'div.profinfo form div select' )[ 1 ];
        Joined.dobd = $( 'div.profinfo form div select' )[ 0 ];
        Joined.gender = $( 'div.profinfo form div select' )[ 3 ];
        Joined.location = $( 'div.profinfo form div select' )[ 4 ];
        Joined.enabled = true;
        Joined.invaliddob = false;
        $( 'div.profinfo form div select' ).change( function() {
            if ( Joined.invaliddob ) {
                $( 'div.profinfo form span.invaliddob' ).animate( { opacity : "0" } , 200 , function() {
                    $( this ).hide();
                } );
                Joined.invaliddob = false;
            }
            else {
                if ( Joined.doby.options[ Joined.doby.selectedIndex ].value != -1 && Joined.dobm.options[ Joined.dobm.selectedIndex ].value != -1 && Joined.dobd.options[ Joined.dobd.selectedIndex ].value != -1 ) {
                    if ( !Dates.ValidDate( Joined.dobd.options[ Joined.dobd.selectedIndex ].value , Joined.dobm.options[ Joined.dobm.selectedIndex ].value , Joined.doby.options[ Joined.doby.selectedIndex ].value ) ) {
                        $( 'div.profinfo form span.invaliddob' ).css( 'opacity' , '0' ).show().animate( { opacity : "1" } , 200 ) ;
                        Joined.invaliddob = true;
                    }
                }
            }
            
        } );
		$( 'div a.button' ).click( function() {
			if ( Joined.enabled ) {
				$( this ).addClass( 'button_disabled' );
				Coala.Warm( 'user/joined' , { 
					doby : Joined.doby.options[ Joined.doby.selectedIndex ].value,
					dobm : Joined.dobm.options[ Joined.dobm.selectedIndex ].value,
					dobd : Joined.dobd.options[ Joined.dobd.selectedIndex ].value,
					gender : Joined.gender.options[ Joined.gender.selectedIndex ].value,
					location : Joined.location.options[ Joined.location.selectedIndex ].value 
				});
				Joined.enabled = false;
			}
			return false;
		});
    }
};
var Settings = {
	SwitchSettings : function( divtoshow ) {
		//hack so that it is executed only when it is loaded
		var validtabs = [ 'personal', 'characteristics', 'interests', 'contact', 'settings' ];
		var found = false;
		var settingslis = $( 'div.settings div.sidebar ol li' );
		for ( i = 0; i < validtabs.length; ++i ) {
			if ( divtoshow == validtabs[ i ] ) {
				$( '#' + divtoshow + 'info' ).show();
				Settings.FocusSettingLink( settingslis[ i ], true , validtabs[ i ] );
				window.location.hash = window.location.hash.substr( 0, 1 ) + validtabs[ i ];
				found = true;
			}
			else {
				$( '#' + validtabs[ i ] + 'info' ).hide();
				Settings.FocusSettingLink( settingslis[ i ], false , validtabs[ i ] );
				
			}
		}
		if ( !found ) {
			$( '#' + validtabs[ 0 ] + 'info' ).show();
			window.location.hash = window.location.hash.substr( 0, 1 ) + 'personal';
			Settings.FocusSettingLink( settingslis[ 0 ] , true , validtabs[ 0 ] );
		}
	},
	FocusSettingLink : function( li, focus , tabname ) {
		if ( li ) {
			if ( focus ) {
				$( li ).addClass( 'selected' );
				li.getElementsByTagName( 'a' )[ 0 ].style.color = 'white';
			}
			else {
				$( li ).removeClass( 'selected' );
				li.getElementsByTagName( 'a' )[ 0 ].style.color = '#105cb6';
			}
		}
	},
	DoSwitchSettings : function() {
		setTimeout( Settings.SwitchSettings, 20 );
	},
	Enqueue : function( key , value ) {
		Settings.queue[ key ] = value;
        $( 'div.savebutton a' ).removeClass( 'disabled' );
	},
	Dequeue : function() {
		Settings.queue = {};
	},
	Save : function( visual ) {
        if ( visual ) {
			$( 'div.savebutton a' ).html( $( Settings.showsaving ).html() );
		}
		Coala.Warm( 'user/settings/save' , Settings.queue );
		Settings.Dequeue();
	},
	AddInterest : function( type , typeid ) {
		//type can be either: hobbies, movies, books, songs, artists, games, quotes, shows
		var intervalue = $( 'div.settings div.tabs form#interestsinfo div.option div.setting div.' + type + ' input' )[ 0 ].value;
		if ( $.trim( intervalue ) !== '' ) {
			if ( intervalue.length <= 32 ) {
				var newli = document.createElement( 'li' );
				var newspan = $( 'div.settings div.tabs form#interestsinfo div.creation' )[ 0 ].cloneNode( true );
				$( newspan ).removeClass( 'creation' ).find( 'span.bbblmiddle' ).append( document.createTextNode( intervalue ) );
				var link = newspan.getElementsByTagName( 'a' )[ 0 ];
				$( newli ).append( newspan );
				$( 'div.settings div.tabs form#interestsinfo div.option div.setting ul.' + type ).prepend( newli );
				Suggest.added[ type ].push( intervalue );
				Coala.Warm( 'user/settings/tags/new' , { text : intervalue , typeid : typeid , node : link } );
			}
			else {
				alert( 'Το κείμενό σου μπορεί να έχει 32 χαρακτήρες το πολύ' );
			}
			$( 'div.settings div.tabs form#interestsinfo div.option div.setting div.' + type + ' input' )[ 0 ].value = '';
			$( 'div.settings div.tabs form#interestsinfo div.option div.setting div.' + type + ' input' )[ 0 ].focus();
		}
		else {
			alert( 'Δε μπορείς να προσθέσεις κενό ενδιαφέρον' );
			$( 'div.settings div.tabs form#interestsinfo div.option div.setting div.' + type + ' input' )[ 0 ].value = '';
			$( 'div.settings div.tabs form#interestsinfo div.option div.setting div.' + type + ' input' )[ 0 ].focus();
		}
	},
	RemoveInterest : function( tagid , node ) {
		var parent = node.parentNode.parentNode;
		$( node ).remove();
		$( parent ).hide( 'slow' );
		Coala.Warm( 'user/settings/tags/delete' , { tagid : tagid } );
		return false;
	},
	SelectAvatar : function( imageid ) {
        $( '#avatarlist' ).jqmHide();
		Coala.Warm( 'user/settings/avatar' , { imageid : imageid } );
	},
	AddAvatar : function( imageid ) {
        var li = document.createElement( 'li' );
		$( li ).hide();
		$( 'div#avatarlist ul' ).prepend( li );
		Coala.Warm( 'user/settings/upload' , { imageid : imageid } );
		var li2 = document.createElement( 'li' );
		$( 'div#avatarlist ul' ).prepend( li2 );
	},
	ChangePassword : function( oldpassword , newpassword , renewpassword ) {
		if ( oldpassword.length < 4 ) {
			Settings.oldpassworderror = true;
			$( Settings.oldpassworddiv ).find( 'div div span' ).fadeIn( 300 );
			Settings.oldpassword.focus();
		}
		if ( newpassword.length < 4 && !Settings.oldpassworderror ) {
			Settings.newpassworderror = true;
			$( Settings.newpassworddiv ).find( 'div div span' ).fadeIn( 300 );
			Settings.newpassword.focus();
		}
		if ( newpassword != renewpassword && !Settings.oldpassworderror && !Settings.newpassworderror ) {
			Settings.renewpassworderror = true;
			$( Settings.renewpassworddiv ).find( 'div div span' ).fadeIn( 300 );
			Settings.renewpassword.focus();
		}
		if ( !Settings.oldpassworderror && !Settings.newpassworderror && !Settings.renewpassworderror ) {
			Settings.Enqueue( 'oldpassword' , oldpassword );
			Settings.Enqueue( 'newpassword' , newpassword );
            Settings.Save( false );
		}
	},
    SettingsOnLoad : function() {
        Settings.saver = 0;
        Settings.queue = {};
        Settings.showsaving = $( 'div.settings div.sidebar div.saving' );
        Settings.invaliddob = false;
        Settings.slogan =  $( '#slogan input' )[ 0 ].value;
        Settings.favquote = $( '#favquote input' )[ 0 ].value;
        Settings.aboutmetext = $( '#aboutme textarea' )[ 0 ].value;
        Settings.email = $( '#email input' )[ 0 ].value;
        Settings.msn = $( '#msn input' )[ 0 ].value;
        Settings.gtalk = $( '#gtalk input' )[ 0 ].value;
        Settings.skype = $( '#skype input' )[ 0 ].value;
        Settings.yahoo = $( '#yahoo input' )[ 0 ].value;
        Settings.web = $( '#web input' )[ 0 ].value;
        Settings.invalidemail = false;
        Settings.invalidmsn = false;
        Settings.oldpassworderror = false;
        Settings.newpassworderror = false;
        Settings.renewpassworderror = false;
        Settings.oldpassworddiv = $( 'div#pwdmodal div.oldpassword' );
        Settings.newpassworddiv = $( 'div#pwdmodal div.newpassword' );
        Settings.renewpassworddiv = $( 'div#pwdmodal div.renewpassword' );
        Settings.oldpassword = $( 'div#pwdmodal div.oldpassword div input' )[ 0 ];
        Settings.newpassword = $( 'div#pwdmodal div.newpassword div input' )[ 0 ];
        Settings.renewpassword = $( 'div#pwdmodal div.renewpassword div input' )[ 0 ];
        Settings.SwitchSettings( window.location.hash.substr( 1 ) );
        $( '#gender select' ).change( function() {
            var sexselected = $( '#sex select' )[ 0 ].value;
            var relselected = $( '#religion select' )[ 0 ].value;
            var polselected = $( '#politics select' )[ 0 ].value;
            var relationshipselected = $( '#relationship select' )[ 0 ].value;
            Coala.Cold( 'user/settings/genderupdate' , { 
                gender : this.value,
                sex : sexselected,
                relationship: relationshipselected,
                religion : relselected,
                politics : polselected
            } );
            Settings.Enqueue( 'gender' , this.value );
        });
        $( '#dateofbirth select' ).change( function() {
            var day = $( '#dateofbirth select' )[ 0 ].value;
            var month = $( '#dateofbirth select' )[ 1 ].value;
            var year = $( '#dateofbirth select' )[ 2 ].value;
            //check for validdate
            if ( day != -1 && month != -1 && year != -1 ) {
                if ( Dates.ValidDate( day , month , year ) ) {
                    if ( Settings.invaliddob ) {
                        $( 'div.settings div.tabs form#personalinfo div span.invaliddob' )
                            .animate( { opacity: "0" } , 1000 , function() {
                                $( this ).css( "display" , "none" );
                            });
                        Settings.invaliddob = false;
                    }
                    Settings.Enqueue( 'dobd' , day );
                    Settings.Enqueue( 'dobm' , month );
                    Settings.Enqueue( 'doby' , year );
                }
                else {
                    if ( !Settings.invaliddob ) {
                        $( 'div.settings div.tabs form#personalinfo div span.invaliddob' )
                            .css( "display" , "inline" )
                            .animate( { opacity: "1" } , 200 );	
                        Settings.invaliddob = true;
                    }
                }
            }
        });
        $( '#place select' ).change( function() {
            Settings.Enqueue( 'place' , this.value );
            Settings.Save( false );
        });
        $( '#education select' ).change( function() {
            Settings.Enqueue( 'education' , this.value );
            Settings.Save( false );
        });
        $( '#school select' ).change( function() {
            Settings.Enqueue( 'school' , this.value );
        });
        $( '#sex select' ).change( function() {
            Settings.Enqueue( 'sex' , this.value );
        });
        $( '#relationship select' ).change( function() {
            Settings.Enqueue( 'relationship' , this.value );
        });
        $( '#religion select' ).change( function() {
            Settings.Enqueue( 'religion' , this.value );
        });
        $( '#politics select' ).change( function() {
            Settings.Enqueue( 'politics' , this.value );
        });
        $( '#haircolor select' ).change( function() {
            Settings.Enqueue( 'haircolor' , this.value );
        });
        $( '#eyecolor select' ).change( function() {
            Settings.Enqueue( 'eyecolor' , this.value );
        });
        $( '#height select' ).change( function() {
            Settings.Enqueue( 'height' , this.value );
        });
        $( '#weight select' ).change( function() {
            Settings.Enqueue( 'weight' , this.value );
        });
        $( '#smoker select' ).change( function() {
            Settings.Enqueue( 'smoker' , this.value );
        });
        $( '#drinker select' ).change( function() {
            Settings.Enqueue( 'drinker' , this.value );
        });
        
        $( '#slogan input' ).change( function() {
            var text = this.value;
            if ( this.value === '' ) {
                text = '-1';
            }
            Settings.Enqueue( 'slogan' , text );
        }).keyup( function() {
            var text = this.value;
            if ( this.value === '' ) {
                text = '-1';
            }
            Settings.Enqueue( 'slogan' , text );
            if ( Settings.slogan ) {
                Settings.slogan = this.value;
            }
        });
        
        $( '#aboutme textarea' ).change( function() {
            var text = this.value;
            if ( this.value === '' ) {
                text = '-1';
            }
            Settings.Enqueue( 'aboutme' , text );
        }).keyup( function() {
            if ( Settings.aboutmetext != this.value ) {
                var text = this.value;
                if ( this.value === '' ) {
                    text = '-1';
                }
                Settings.Enqueue( 'aboutme' , text );
                if ( Settings.aboutmetext ) {
                    Settings.aboutmetext = this.value;
                }
            }
        } );
        
        $( '#favquote input' ).change( function() {
            var text = this.value;
            if ( this.value === '' ) {
                text = '-1';
            }
            Settings.Enqueue( 'favquote' , text );
        }).keyup( function() {
            var text = this.value;
            if ( this.value === '' ) {
                text = '-1';
            }
            Settings.Enqueue( 'favquote' , text );
            if ( Settings.favquote ) {
                Settings.favquote = this.value;
            }
        });
        
        $( '#email input' ).change( function() {
            var text = this.value;
            if ( this.value === '' ) {
                text = '-1';
            }
            Settings.Enqueue( 'email' , text );
        }).keyup( function() {
            var text = this.value;
            if ( Settings.invalidemail ) {
                if ( /^[a-zA-Z0-9.\-_]+@[a-zA-Z0-9.\-_]+$/.test( text ) ) {
                    $( 'div#email span' ).animate( { opacity: "0" } , 1000 , function() {
                        $( 'div#email span' ).css( "display" , "none" );
                    });
                    Settings.invalidemail = false;
                    Settings.Enqueue( 'email' , text );
                }
            }
            else {
                if ( this.value === '' ) {
                    text = '-1';
                }
                Settings.Enqueue( 'email' , text );
            }
            if ( Settings.email ) {
                Settings.email = this.value;
            }
        });
        
        $( '#msn input' ).change( function() {
            var text = this.value;
            if ( this.value === '' ) {
                text = '-1';
            }
            Settings.Enqueue( 'msn' , text , 500 );
        }).keyup( function() {
            var text = this.value;
            if ( Settings.invalidmsn ) {
                if ( /^[a-zA-Z0-9.\-_]+@[a-zA-Z0-9.\-_]+$/.test( text ) ) {
                    $( 'div#msn span' ).animate( { opacity: "0" } , 1000 , function() {
                        $( 'div#msn span' ).css( "display" , "none" );
                    });
                    Settings.invalidmsn = false;
                    Settings.Enqueue( 'msn' , text );
                }
            }
            else {
                if ( this.value === '' ) {
                    text = '-1';
                }
                Settings.Enqueue( 'msn' , text );
            }
            if ( Settings.msn ) {
                Settings.msn = this.value;
            }
        });
        
        $( '#gtalk input' ).change( function() {
            var text = this.value;
            if ( this.value === '' ) {
                text = '-1';
            }
            Settings.Enqueue( 'gtalk' , text );
        }).keyup( function() {
            var text = this.value;
            if ( this.value === '' ) {
                text = '-1';
            }
            Settings.Enqueue( 'gtalk' , text );
            if ( Settings.gtalk ) {
                Settings.gtalk = this.value;
            }
        });
        
        $( '#skype input' ).change( function() {
            var text = this.value;
            if ( this.value === '' ) {
                text = '-1';
            }
            Settings.Enqueue( 'skype' , text );
        }).keyup( function() {
            var text = this.value;
            if ( this.value === '' ) {
                text = '-1';
            }
            Settings.Enqueue( 'skype' , text );
            if ( Settings.skype ) {
                Settings.skype = this.value;
            }
        });
        
        $( '#yahoo input' ).change( function() {
            var text = this.value;
            if ( this.value === '' ) {
                text = '-1';
            }
            Settings.Enqueue( 'yahoo' , text );
        }).keyup( function() {
            var text = this.value;
            if ( this.value === '' ) {
                text = '-1';
            }
            Settings.Enqueue( 'yahoo' , text );
            if ( Settings.yahoo ) {
                Settings.yahoo = this.value;
            }
        });
        
        $( '#web input' ).change( function() {
            var text = this.value;
            if ( this.value === '' ) {
                text = '-1';
            }
            Settings.Enqueue( 'web' , text0 );
        }).keyup( function() {
            var text = this.value;
            if ( this.value === '' ) {
                text = '-1';
            }
            Settings.Enqueue( 'web' , text );
            if ( Settings.skype ) {
                Settings.skype = this.value;
            }
        });
        
        //interesttags
        // INTEREST_TAG_TYPE   Please Update everytime you define a new interesttag_type constant
        var interesttagtypes = [ 'hobbies', 'movies', 'books', 'songs', 'artists', 'games', 'shows' ];
		for( var i in interesttagtypes ) {
			$( 'form#interestsinfo div.option div.setting div.' + interesttagtypes[ i ] + ' a' ).click( function( i ) {
				return function() {
					Settings.AddInterest( interesttagtypes[ i ] , Suggest.type2int( interesttagtypes[ i ] ) );
					$( 'div.' + interesttagtypes[ i ] + ' ul' ).hide();
					return false;
				};
			}( i ) );
		}
		
        //settingsinfo
        $( 'form#settingsinfo div.setting table tbody tr td input' ).click( function() {
            var value = $( this )[ 0 ].checked;
            if ( value ) {
                value = 'yes';
            }
            else {
                value = 'no';
            }
            Settings.Enqueue( $( this )[ 0 ].id , value );
        } );
        $( 'div.savebutton a' ).click( function() {
            if ( !$( this ).hasClass( 'disabled' ) ) {
                Settings.Save( true );
            }
            return false;
        } );
        $( '#avatarlist' ).jqm( {
            trigger : 'div.changeavatar a',
            overlayClass : 'mdloverlay1'
        } );
        $( '#pwdmodal' ).jqm( {
            trigger : 'div.changepwdl a.changepwdlink',
            overlayClass : 'mdloverlay1'
        } );
        $( Settings.oldpassword ).keyup( function( event ) {
            if ( event.keyCode == 13 && !Settings.oldpassworderror ) {
                Settings.newpassword.focus();
            }
            if ( event.keyCode != 13 && Settings.oldpassworderror && Settings.oldpassword.value.length >= 4 ) {
                Settings.oldpassworderror = false;
                $( Settings.oldpassworddiv ).find( 'div div span' ).fadeOut( 300 );
            }

        } );
        
        $( Settings.newpassword ).keyup( function( event ) {
            if ( event.keyCode == 13 && !Settings.newpassworderror ) {
                Settings.renewpassword.focus();
            }
            if ( Settings.newpassworderror && Settings.newpassword.value.length >= 4 ) {
                Settings.newpassworderror = false;
                $( Settings.newpassworddiv ).find( 'div div span' ).fadeOut( 300 );
            }
        } );

        $( Settings.renewpassword ).keyup( function( event ) {
            if ( event.keyCode == 13 && !Settings.renewpassworderror ) {
                $( 'div#pwdmodal div.save a.save' )[ 0 ].focus();
            }
            if ( Settings.renewpassworderror && Settings.renewpassword.value == Settings.newpassword.value ) {
                Settings.renewpassworderror = false;
                $( Settings.renewpassworddiv ).find( 'div div span' ).fadeOut( 300 );
            }
        } );
        $( 'div#pwdmodal div.save a' ).click( function() {
            Settings.ChangePassword( Settings.oldpassword.value , Settings.newpassword.value , Settings.renewpassword.value );
            return false;
        } );
        Settings.oldpassword.focus();
    }
};
var Profile = {
    AntisocialCalled : false,
	AddAvatar : function( imageid ) {
		var li = document.createElement( 'li' );
		var link = document.createElement( 'a' );
		$( li ).append( link );
		$( 'div.main div.photos ul' ).prepend( li );
		Coala.Warm( 'user/avatar' , { imageid : imageid } );
		$( 'div.main div.ybubble' ).animate( { height: "0" } , 400 , function() {
			$( this ).remove();
		} );
	},
	AddFriend : function( userid ) {
        if ( !this.AntisocialCalled ) {
            return this.AntisocialAddFriend( userid );
        }
		$( 'div.sidebar div.basicinfo div a span.s_addfriend' ).parent().fadeOut( 400 , function() {
			$( this )
			.parent()
			.css( 'display' , 'hidden' )
			.append( document.createTextNode( 'Έγινε προσθήκη' ) )
			.fadeIn( 400 );
		} );
		Coala.Warm( 'user/relations/new' , { userid : userid } );
		return false;
	},
	DeleteFriend : function( relationid ) {
		$( 'div.sidebar div.basicinfo div a span.s_deletefriend' ).parent().fadeOut( 400 , function() {
			$( this )
			.parent()
			.css( 'display' , 'hidden' )
			.append( document.createTextNode( 'Έγινε διαγραφή' ) )
			.fadeIn( 400 );
		} );
		Coala.Warm( 'user/relations/delete' , { relationid : relationid } );		
		return false;
	},
    ShowFriendLinks : function( relationstatus , id ) {
    	var text;
        if ( relationstatus ) {
            text = document.createTextNode( 'Προσθήκη στους φίλους' );
            $( 'div.sidebar div.basicinfo div.friendedit' )
            .addClass( 'common' )
            .removeClass( 'friendedit' )
            .find( 'a' ).click( function() {
                Profile.AddFriend( id );
                return false;
            } )
            .append( text )
			.find( 'span' ).addClass( 's_addfriend' );
        }
        else {
            text = document.createTextNode( 'Διαγραφή από τους φίλους' );
            $( 'div.sidebar div.basicinfo div.friendedit' )
            .addClass( 'common' )
            .removeClass( 'friendedit' )
            .find( 'a' ).click( function() {
                Profile.DeleteFriend( id );
                return false;
            } )
            .append( text )
			.find( 'span' ).addClass( 's_deletefriend' );
        }
        //if relationstatus is anything else don't do something, user views his own profile
    },
    ShowOnlineSince : function( lastonline ) {
        if ( lastonline ) {
            text = document.createTextNode( lastonline );
            $( 'div.sidebar > div.basicinfo > dl.online > dd' ).append( text ); 
        }
        else {
            $( 'div.sidebar > div.basicinfo > dl.online' ).hide();
        }
    },
    AntisocialAddFriend : function ( userid ) {
        this.AntisocialCalled = true;
        if ( !$( '#antisocial' )[ 0 ] ) {
            this.AddFriend( userid );
            return;
        }
        setTimeout( function() {
            $( '#antisocial' ).slideUp( 'slow' );
        }, 1201 );
        $( '#antisocial div' ).animate( {
            opacity: 0
        }, 200, 'swing', function() {
            $( '#antisocial div' ).html( '<strong>Έγινε προσθήκη</strong>' ).animate( {
                opacity: 1
            }, 200 );
        } );
        this.AddFriend( userid );
        return false;
    },
    CheckBirthday : function ( year, month, day ) {
        var Now = new Date();
        var age = Now.getFullYear() - year;
        if ( Now.getMonth() < month - 1
             || (  Now.getMonth() == month - 1
                && Now.getDate() < day ) ) {
            --age;
        }
        $( '#birthday + dd' ).html( age ); // real age, based on user date settings, not on server date (to avoid server date differences and server-side HTML chunk caching)
        if ( Now.getDate() == day && Now.getMonth() == month - 1 ) {
            $( '#birthday' ).html( '<img src="' + ExcaliburSettings.imagesurl + 'cake.png" alt="Χρόνια πολλά!" title="Χρόνια πολλά!" /> <strong>Μόλις έγινε</strong>' );
        }
    },
    Tweet: {
        Delete : function () {
            Coala.Warm( 'status/new', { message: '' } );
            $( 'div.tweetactive' ).remove();
            $( '#tweetedit' ).jqmHide();
        },
        Renew: function ( message ) {
            if ( message === '' ) {
                return Profile.Tweet.Delete();
            }
            $( 'div.tweetactive div.tweet a span' ).empty()[ 0 ].appendChild( document.createTextNode( message ) );
            $( '#tweetedit form input' )[ 0 ].value = message;
            Coala.Warm( 'status/new', { message: message } );
            $( '#tweetedit' ).jqmHide();
        }
    },
    Easyuploadadd : function ( imageid ) {
        var uplalbid = $( '#easyphotoupload div.modalcontent div ul li.selected' ).attr( 'id' ).substr( 6 );
        Coala.Warm( 'user/profile/easyuploadadd' , { imageid : imageid , albumid : uplalbid } );
    },
    OnLoad: function () {
        Coala.Cold( 'admanager/showad', { f: function ( html ) {
            $( 'div.ads' )[ 0 ].innerHTML = html;
        } } );
    },
    MyProfileOnLoad: function () {
        $( '#reportabusemodal' ).jqm( {
            trigger : '#reportabuse a.report',
            overlayClass : 'mdloverlay1'
        } );
        $( '#tweetedit' ).jqm( {
            trigger : 'div.tweetbox div.tweet div a',
            overlayClass : 'mdloverlay1'
        } );
        $( '#easyphotoupload' ).jqm( {
            trigger : 'div#profile div.main div.photos ul li.addphoto a',
            overlayClass : 'mdloverlay1'
        } );
        $( 'div#profile div.main div.photos ul li.addphoto a' ).click( function() {
            if ( !$( '#easyphotoupload div.modalcontent div.uploaddiv' )[ 0 ] ) {
                Coala.Cold( 'user/profile/easyupload' , {} );
            }
            return false;
        } );
        $( 'div.tweetactive div.tweet a' ).click( function () {
            var win = $( '#tweetedit' )[ 0 ];
            var links = $( win ).find( 'a' );
            $( links[ 0 ] ).click( function () { // save
                Profile.Tweet.Renew( $( win ).find( 'input' )[ 0 ].value );
                return false;
            } );
            $( links[ 1 ] ).click( function () { // delete
                Profile.Tweet.Delete();
                return false;
            } );
            $( win ).find( 'form' ).submit( function () {
                Profile.Tweet.Renew( $( win ).find( 'input' )[ 0 ].value );
                return false;
            } );
            var inp = $( win ).find( 'input' )[ 0 ];
            inp.select();
            inp.focus();
            return false;
        } );
    }
};
var Suggest = {
	allowHover : true, // Allow onmouseover to select a li
    // INTEREST_TAG_TYPE   Please Update everytime you define a new interesttag_type constant
    // Holds the suggestions that we have already received from the server
    list : {
        'hobbies' : [],
        'movies' : [],
        'books' : [],
        'songs' : [],
        'artists' : [],
        'games' : [],
        'shows' : []
    },
    // INTEREST_TAG_TYPE   Please Update everytime you define a new interesttag_type constant
    // Holds all the requests we have done to the server
    requested : { 
        'hobbies' : [],
        'movies' : [],
        'books' : [],
        'songs' : [],
        'artists' : [],
        'games' : [],
        'shows' : []
    },
	// INTEREST_TAG_TYPE   Please Update everytime you define a new interesttag_type constant
	// Holds whether the mouse is over a suggestion list. Necessary for the scrolling bar to work
	over : {
		'hobbies' : false,
		'movies' : false,
		'books' : false,
		'songs' : false,
		'artists' : false,
		'games' : false,
		'shows' : false
	},
	// INTEREST_TAG_TYPE   Please Update everytime you define a new interesttag_type constant
	// Contains all the already added interests
	added : {
		'hobbies' : [],
		'movies' : [],
		'books' : [],
		'songs' : [],
		'artists' : [],
		'games' : [],
		'shows' : []
	},
    type2int : function( type ) {
		switch( type ) {
			// INTEREST_TAG_TYPE   Please Update everytime you define a new interesttag_type constant
			case 'hobbies':
				return 1;
			case 'movies':
				return 2;
			case 'books':
				return 3;
			case 'songs':
				return 4;
			case 'artists':
				return 5;
			case 'games':
				return 6;
			case 'shows':
				return 7;
			default:
				return -1;
		}
	},
    inputMove : function( event, type ) {
        var ul = $( 'div.' + type + ' ul' );
        if ( ul.css( "display" ) == "none" && event.keyCode != 13 ) {
			return;
		}
        var lis = ul.find( 'li.selected' );
		var text = $( 'div.' + type + ' input' ).val();
        if ( event.keyCode == 40 ) { // down
            if ( lis.length === 0 ) {
                ul.find( 'li:first' ).addClass( 'selected' ).get( 0 ).scrollIntoView( false );
                return;
            }
            lis.removeClass( 'selected' ).next().addClass( 'selected' ).get( 0 ).scrollIntoView( false );
			Suggest.allowHover = false;
			setTimeout( function() { Suggest.allowHover = true; }, 15 );
        }
        else if ( event.keyCode == 38 ) { // up
            if ( lis.length === 0 ) {
                ul.find( 'li:last' ).addClass( 'selected' ).get( 0 ).scrollIntoView( false );
                return;
            }
            ul.find( 'li.selected' ).removeClass( 'selected' ).prev().addClass( 'selected' ).get( 0 ).scrollIntoView( false );
			Suggest.allowHover = false;
			setTimeout( function() { Suggest.allowHover = true; }, 15 );
        }
		else if ( event.keyCode == 33 ) { // PageUp
			var piso = ul.find( 'li.selected' ).removeClass( 'selected' ).prevAll();
			Suggest.allowHover = false;
			if ( piso.length < 5 ) {
				ul.find( 'li:first' ).addClass( 'selected' ).get( 0 ).scrollIntoView( false );
			}
			else {
				$( piso[ 4 ] ).addClass( 'selected' ).get( 0 ).scrollIntoView( false );
			}
			setTimeout( function() { Suggest.allowHover = true; }, 15 );
		}
		else if ( event.keyCode == 34 ) { // PageDown
			var mprosta = ul.find( 'li.selected' ).removeClass( 'selected' ).nextAll();
			Suggest.allowHover = false;
			if ( mprosta.length < 5 ) {
				ul.find( 'li:last' ).addClass( 'selected' ).get( 0 ).scrollIntoView( false );
			}
			else {
				$( mprosta[ 4 ] ).addClass( 'selected' ).get( 0 ).scrollIntoView( false );
			}
			setTimeout( function() { Suggest.allowHover = true; }, 15 );
		}
        else if ( event.keyCode == 27 ) { // escape
            ul.find( 'li' ).remove();
        }
        else if ( event.keyCode == 13 ) { // enter
			Suggest.over[ type ] = false;
			if ( lis.length !== 0 ) {
				$( 'div.' + type + ' input' ).attr( 'value', lis.text() );
			}
			Settings.AddInterest( type, Suggest.type2int( type ) );
			ul.find( 'li' ).remove();
        }
		else if ( $.trim( text ) !== '' ) {
			var suggestions = $.grep( Suggest.list[ type ], function( item, index ) {
		                return( item.toUpperCase().substr( 0, text.length ) == text.toUpperCase() );
		               } );
			Suggest.suggestCallback( type, suggestions, false );
			
			if ( suggestions.length > 40 || $.inArray( text, Suggest.requested[ type ] ) !== -1 ) {
				return;
			}

			Coala.Cold( 'user/settings/tags/suggest', { 'text' : text,
														'type' : type,
														'callback' : Suggest.suggestCallback
		                                           } );
			Suggest.requested[ type ].push( text );
		}
		else {
			$( 'div.' + type + ' ul li' ).remove();
		}
    },
	suggestCallback : function( type, suggestions, callbacked ) {
		/*if ( suggestions.length === undefined || suggestions.length == 0 ) {
			return;
		}*/
		
		if ( !callbacked ) {
			$( 'div.' + type + ' ul li' ).remove();
		}

		// Marks duplicate entries
		var sugLength = suggestions.length;
		for( var j=0;j<suggestions.length;++j ) {
		    if ( $.inArray( suggestions[ j ], Suggest.list[ type ] ) === -1 && $.inArray( suggestions[ j ], Suggest.added[ type ] ) === -1 ) {
		        Suggest.list[ type ].push( suggestions[ j ] );
		    }
		    else if ( callbacked ) { // If callbacked is set to true, then the current suggestion always exists in the options. It was added the first time when callbacked was false
		        suggestions[ j ] = '';
		        --sugLength;
		    }
		}
		
		$( 'div.' + type + ' ul' ).show();
		
		var text = $( 'div.' + type + ' input' ).val();
		for( var i in suggestions ) {
		    if ( suggestions[i] !== '' ) {
				var li = document.createElement( 'li' );
				li.onmousedown = function( i ) {
					return function() {
						Suggest.over[ type ] = false;
						$( 'div.' + type + ' input' ).attr( 'value', suggestions[ i ] );
						Settings.AddInterest( type, Suggest.type2int( type ) );
						$( 'div.' + type + ' ul li' ).remove();
					};
				}( i );
				li.onmousemove = function() {
					if ( !Suggest.allowHover ) {
						return;
					}
					$( 'div.' + type + ' ul li.selected' ).removeClass( 'selected' );
					this.className = "selected";
				};

				var b = document.createElement( 'b' );
				b.appendChild( document.createTextNode( suggestions[ i ].substr( 0, text.length ) ) );
				li.appendChild( b );
				li.appendChild( document.createTextNode( suggestions[ i ].substr( text.length ) ) );
			
				$( 'div.' + type + ' ul' ).append( li );
			}
		}
		
	},
	hideBlur : function( type ) {
		if ( Suggest.over[ type ] ) {
			setTimeout( "$( 'div." + type + " input' ).focus()", 5 );
			return false;
		}
		$( 'div.' + type + ' ul' ).hide();
	},
    OnLoad : function() {
        $( 'form#interestsinfo div.option div.setting div.add ul li' ).remove();
        var inttagtypes = [ 'hobbies', 'movies', 'books', 'songs', 'artists', 'games', 'shows' ];
        for( var i=0;i<inttagtypes.length;++i ) {
            var spans = $( 'form#interestsinfo div.option div.setting ul.' + inttagtypes[ i ] + ' li div.aplbubble span.aplbubblemiddle' );
            if ( spans.length === 0 ) {
                continue;
            }
            $.each( spans, function() {
                Suggest.added[ inttagtypes[ i ] ].push( $( this ).text() );
            } );
        }
    }
};
var Banner = {
	isanimating : false,
	Login : function () {
		/*
		var banner = document.getElementById( 'banner' );
		var menu = banner.getElementsByTagName( 'ul' )[ 0 ];
		var options = menu.getElementsByTagName( 'li' );
		*/
		var menu = $( 'div#banner ul' )[ 0 ];
		var options = $( 'div#banner ul li' );
		if ( Banner.isanimating ) {
			return;
		}

        $( '#bannerPasswd' ).keydown( function( event ) {
            switch( event.keyCode ) {
				case 13:
					$('#loginForm')[ 0 ].submit();
					break;
			}
         });
            
		Banner.isanimating = true;
		if ( options[ 0 ].style.display === '' ) {
			//Animations.Create( menu, 'opacity', 500, 1, 0, function () {
			$( menu ).animate( { opacity: "0" } , function() {
				options[ 0 ].style.display = 'none';
				options[ 1 ].style.display = 'none';
				$( options[ 3 ] ).show();
				$( options[ 4 ] ).show();
				$( options[ 5 ] ).show();
				$( menu ).animate( { opacity: "1" } , 500 , function() {
					Banner.isanimating = false;
				} );
				$( 'div#banner ul input' )[ 0 ].value = '';
				$( 'div#banner ul input' )[ 1 ].value = '';
				$( 'div#banner ul input' )[ 0 ].focus();
			} );
		}
		else {
			$( menu ).animate( { opacity: "1" } , 500 , function() {	
				$( options[ 0 ] ).show();
				$( options[ 1 ] ).show();
				$( options[ 3 ] ).hide();
				$( options[ 4 ] ).hide();
				$( options[ 5 ] ).hide();
				$( menu ).animate( { opacity: "1" } , 500 , function() {
					Banner.isanimating = false;
				} );
			} );
		}
	},
    OnLoad : function() {
        $( 'div.search form input.text' ).focus( function() {
            this.value = '';
        });
        $( 'div.search form input.text' ).blur( function() {
            this.value = 'αναζήτησε φίλους';
        });
    }
};
function dateDiff( dateTimeBegin, dateTimeEnd ) {
    var endval = new Date();
    var beginval = new Date();

    if ( typeof dateTimeEnd != 'undefined' ) {
        var dateAndTime = dateTimeEnd.split( ' ' );
        var dateEnd = dateAndTime[ 0 ];
        var timeEnd = dateAndTime[ 1 ];
        var dateParts = dateEnd.split( '-' );
        var timeParts = timeEnd.split( ':' );

        endval.setFullYear( dateParts[ 0 ] );
        endval.setMonth( dateParts[ 1 ] );
        endval.setDate( dateParts[ 2 ] );
        endval.setHours( timeParts[ 0 ] );
        endval.setMinutes( timeParts[ 1 ] );
        endval.setSeconds( timeParts[ 2 ] );
    }
    var dateAndTime = dateTimeBegin.split( ' ' );
    var dateBegin = dateAndTime[ 0 ];
    var timeBegin = dateAndTime[ 1 ];
    var dateParts = dateBegin.split( '-' );
    var timeParts = timeBegin.split( ':' );

    beginval.setFullYear( dateParts[ 0 ] );
    beginval.setMonth( dateParts[ 1 ] );
    beginval.setDate( dateParts[ 2 ] );
    beginval.setHours( timeParts[ 0 ] );
    beginval.setMinutes( timeParts[ 1 ] );
    beginval.setSeconds( timeParts[ 2 ] );

    var diff = Date.parse( endval.toString() ) - Date.parse( beginval.toString() );
    diff /= 1000;

    if ( diff < 0 ) {
        // error condition
        return false;
    }

    var years = 0;
    var months = 0;
    var weeks = 0;
    var days = 0;
    var hours = 0;
    var minutes = 0;
    var seconds = 0; // initialize vars

    if ( diff % 604800 > 0 ) {
        var rest1 = diff % 604800;
        weeks = ( diff - rest1 ) / 604800; // seconds a week
        if ( rest1 % 86400 > 0 ) {
            var rest2 = ( rest1 % 86400 );
            days = ( rest1 - rest2 ) / 86400; // seconds a day
            if ( rest2 % 3600 > 0 ) {
                var rest3 = ( rest2 % 3600 );
                hours = ( rest2 - rest3 ) / 3600; // seconds an hour
                if ( rest3 % 60 > 0 ) {
                    seconds = ( rest3 % 60 );
                    minutes = ( rest3 - seconds ) / 60; // seconds a minute
                } 
                else {
                    minutes = rest3 / 60;
                }
            }
            else {
                hours = rest2 / 3600;
            }
        }
        else {
            days = rest1 / 86400;
        }
    }
    else {
        weeks = diff / 604800;
    }
    if ( weeks ) {
        hours = 0;
    }
    if ( days || weeks ) {
        minutes = 0;
    }
    months = Math.floor( weeks / 4 );
    if ( months ) {
        weeks -= months * 4;
    }
    years = Math.floor( months / 12 );
    if ( years ) {
        months -= years * 12;
    }

    return {
        'years': years,
        'months': months,
        'weeks': weeks,
        'days': days,
        'hours': hours,
        'minutes': minutes
    };
}

function greekDateDiff( diff ) {
    years = diff.years;
    months = diff.months;
    weeks = diff.weeks;
    days = diff.days;
    hours = diff.hours;
    minutes = diff.minutes;

    if ( years ) {
        if ( years == 1 ) {
            return 'πέρσι';
        }
        if ( years == 2 ) {
            return 'πρόπερσι';
        }
        return 'πριν ' + years + ' χρόνια';
    }
    if ( months ) {
        if ( months == 1 ) {
            return 'τον προηγούμενο μήνα';
        }
        return 'πριν ' + months + ' μήνες';
    }
    if ( weeks ) {
        if ( weeks == 1 ) {
            return 'την προηγούμενη εβδομάδα';
        }
        return 'πριν ' + weeks + ' εβδομάδες';
    }
    if ( days ) {
        if ( days == 1 ) {
            return 'χθες';
        }
        if ( days == 2 ) {
            return 'προχθές';
        }
        return 'πριν ' + days + ' μέρες';
    }
    if ( hours ) {
        if ( hours == 1 ) {
            return 'πριν 1 ώρα';
        }
        return 'πριν ' + hours + ' ώρες';
    }
    if ( minutes ) {
        if ( minutes == 1 ) {
            return 'πριν 1 λεπτό';
        }
        if ( minutes == 15 ) {
            return 'πριν ένα τέταρτο';
        }
        if ( minutes == 30 ) {
            return 'πριν μισή ώρα';
        }
        if ( minutes == 45 ) {
            return 'πριν τρία τέταρτα';
        }
        return 'πριν ' + minutes + ' λεπτά';
    }
    return 'πριν λίγο';
}

var pms = {
	unreadpms : 0,
	activefolder : 0,
	node : 0,
	activepm : 0,
	pmsinfolder : 0,
	messagescontainer : $( '#messages' )[ 0 ],
	writingnewpm : false,
	ShowFolder : function( folder , folderid ) {
		if ( pms.activefolder === 0 ) {
			pms.node = $( '#folders div' )[ 0 ];
			pms.activefolder = pms.node;
		}
		pms.activefolder.className = '';
		$( pms.activefolder ).addClass( 'folder' );
		if ( pms.activefolder != pms.node ) {
			//pms.activefolder.className = 'folder top';
			$( pms.activefolder ).addClass( 'top' );
		}
		folder.className = '';
		$( folder ).addClass( 'activefolder' )
		.addClass( 'folder' );
		if ( folder != pms.node ) {
			//folder.className = 'activefolder top';
			$( folder ).addClass( 'top' );
		}
		pms.activefolder = folder;
		Coala.Cold( 'pm/folder/show' , { folderid : folderid } , function( errcode ) {
			alert( 'Coala error: ' + errcode );
		} );	
	}
	,
	ShowFolderPm : function( folder , folderid ) {
		//this function uses the ShowFolder function to show the contents of a folder using a little animation
		pms.activepm = 0;
		pms.writingnewpm = false;
		pms.ShowAnimation( 'Παρακαλώ περιμένετε...' );
		pms.ShowFolder( folder , folderid );
		return false;
	}
	,
	ExpandPm : function( pmdiv , notread , pmid, folderid ) {
		//the function is responsible for expanding and minimizing pms, allowing only one expanded pm
		//notread is true when the pm hasn't been read else it is true
		var messagesdivdivs = $( '#pm_' + pmid + ' div')[ 0 ];
		var textpm = $( '#pm_' + pmid + ' div.text' )[ 0 ];
		var lowerlinepm = $( '#pm_' + pmid + ' div.lowerline' )[ 0 ];
		$( textpm ).toggle();
		$( lowerlinepm ).toggle();
		
		pms.activepm = pmdiv;
		if ( notread ) {
			//remove the unread icon
			$( '#pm_' + pmid + ' div.infobar span.unreadpm' ).hide();
			Coala.Warm( 'pm/expand' , { pmid : pmid, folderid: folderid } );
			pms.UpdateUnreadPms( -1 );
		}
		return false;
	}
	,
	NewFolder : function() {
		//showing modal dialog for new folder name
		var newfolderdiv = $( '#newfolderlink' )[ 0 ];
		var newfoldermodal = document.getElementById( 'newfoldermodal' ).cloneNode( true );
		$( newfoldermodal ).show();
		newfoldermodalinput = newfoldermodal.getElementsByTagName( 'input' );
		textbox = newfoldermodalinput[ 0 ];
		Modals.Create( newfoldermodal , 250 , 80 );
		textbox.focus();
		textbox.select();
		newfolderdiv.className = '';
		$( newfolderdiv ).addClass( 'folder' )
		.addClass( 'newfolderactive' )
		.addClass( 'top' );
		if ( pms.activefolder === 0 ) {
			pms.node = $( '#folders div' )[ 0 ];
			pms.activefolder = pms.node;
		}
		pms.activefolder.className = '';
		$( pms.activefolder ).addClass( 'folder' );
		if ( pms.activefolder != pms.node ) {
			$( pms.activefolder ).addClass( 'top' );
		}
	}
	,
	CancelNewFolder : function () {
		if ( pms.activefolder === 0 ) {
			pms.node = $( '#folders div' )[ 0 ];
			pms.activefolder = pms.node;
		}
		pms.activefolder.className = '';
		$( pms.activefolder ).addClass( 'folder' )
		.addClass( 'activefolder' );
		if ( pms.activefolder != pms.node ) {
			$( pms.activefolder ).addClass( 'top' );
		}
		$( '#newfolderlink' )[ 0 ].className = '';
		$( '#newfolderlink' ).addClass( 'folder' )
		.addClass( 'top' )
		.addClass( 'newfolder' );
		Modals.Destroy();
	}
	,
    ValidFolderName : function ( text ) {
		var name = text.replace(/(\s+$)|(^\s+)/g , '');
		if ( name == 'Εισερχόμενα' || name == 'Απεσταλμένα' ) {
            return false;
		}
		else if ( name.length <= 2 ) {
            return false;
		}
		else if ( name === '' ) {
            return false;
		}
        return true;
    }
    ,
	CreateNewFolder : function ( formnode ) {
		//creating a new folder and showing it (using a coala call)
		var formnodeinput = formnode.getElementsByTagName( 'input' );
		inputbox = formnodeinput[ 0 ];
		var foldername = inputbox.value;
		if ( !pms.ValidFolderName( foldername ) ) {
			alert( 'Δεν μπορείς να ονομάσεις έτσι τον φάκελό σου' );
			inputbox.select();
            return;
		}
        pms.ShowAnimation( 'Δημιουργία φακέλου...' );
        Coala.Warm( 'pm/folder/new' , { foldername : foldername } );
	}
	,
	DeleteFolder : function( folderid ) {
		//the function for deleting a pm folder
		Modals.Confirm( 'Θέλεις σίγουρα να σβήσεις τον φάκελο;' , function () {
			$( '#folder_' + folderid ).animate( { opacity : '0' , height : '0' } , function() {
				$( this ).remove();
				if ( !pms.writingnewpm ) {
				    // Show inbox
				    var inboxstring = $( $( '#folders div' )[ 0 ] ).attr("id");
				    var inboxarr = inboxstring.split('_');
				    var inboxid = inboxarr[1];
					pms.ShowFolderPm( $( '#folders div' )[ 0 ],  inboxid );
				}
			} );
			Coala.Warm( 'pm/folder/delete' , { folderid : folderid } );
		} );
	}
	,
    RenameFolder : function ( folderid ) {
        var name = prompt( 'Πληκτρολόγησε ένα νέο όνομα για τον φάκελό σου' );
        if ( name === null ) {
            return;
        }
        if ( !pms.ValidFolderName( name ) ) {
            alert( 'Δεν μπορείς να ονομάσεις έτσι τον φάκελό σου' );
            return;
        }
        Coala.Warm( 'pm/folder/rename', {
            'folderid': folderid,
            'newname': name
        } );
		var span = document.createElement( 'span' );
		$( span ).append( document.createTextNode( ' ' ) ).css( 'padding-left' , '16px' );
        $( '#folder_' + folderid + ' a.folderlinks' ).empty().append( span ).append( document.createTextNode( name ) );
		return false;
    }
    ,
	NewMessage : function( touser , answertext ) {
		pms.ClearMessages();
		var receiversdiv = document.createElement( 'div' );
		var receiversinput = document.createElement( 'input' );
		receiversinput.type = 'text';
		$( receiversinput ).css( "width" , "250px" ).css( "color" , "#9d9d9d" );
		if ( touser !== '' ) {
			receiversinput.value = touser;
		}
		pms.messagescontainer.appendChild( receiversdiv );
		if ( answertext !== '' ) {
			var textmargin = document.createElement( 'div' );
			$( textmargin ).css( "border" , "1px dotted #b9b8b8" ).css( "padding" , "4px" ).css( "color" , "#767676" ).css( "width" , "550px" );
			$( textmargin ).html( answertext );
			$( pms.messagescontainer ).append( textmargin ).append( document.createElement( 'br' ) ).append( document.createElement( 'br' ) );
		}
		var receiverstext = document.createElement( 'span' );
		$( receiverstext ).css( "padding-right" , "30px" );
		receiverstext.appendChild( document.createTextNode( 'Παραλήπτες' ) );
		$( receiverstext ).css( "font-weight" , "bold" );
		$( receiversdiv ).append( receiverstext ).append( receiversinput ).append( document.createElement( 'br' ) ).append( document.createElement( 'br' ) ); 
		var pmtext = document.createElement( 'textarea' );
		$( pmtext ).css( "width" , "550px" ).css( "height" , "330px" );
		var sendbutton = document.createElement( 'input' );
		$( sendbutton ).attr( { type : 'button' , value : 'Αποστολή' } );
		$( sendbutton ).click( function() {	
			pms.SendPm();
		});
		var cancelbutton = document.createElement( 'input' );
		$( cancelbutton ).attr( { type : 'button' , value : 'Επαναφορά' } );
		$( cancelbutton ).click( function() {
			receiversinput.value = '';
			pmtext.value = '';
		});
		var actions = document.createElement( 'div' );
		$( actions ).append( sendbutton ).append( cancelbutton );
		$( pms.messagescontainer ).append ( pmtext ).append( document.createElement( 'br' ) ).append( document.createElement( 'br' ) ).append( actions );
		pms.ShowFolderNameTop( 'Νέο μήνυμα' );
	    if ( answertext !== '' ) {
            pmtext.focus();
            pmtext.select();
        }
        else {
            receiversinput.focus();
            receiversinput.select();
        }
		pms.writingnewpm = true;
		return false;
	}
	,
	SendPm : function() {
		//responsible for sending the pm to the specified user or users
		var messagesdivinputlist = pms.messagescontainer.getElementsByTagName( 'input' );
		var receiverslist = messagesdivinputlist[ 0 ];
		var messagesdivtextarealist = pms.messagescontainer.getElementsByTagName( 'textarea' );
		var pmtext = messagesdivtextarealist[ 0 ];
		pms.ShowAnimation( 'Αποστολή μηνύματος...' );
		Coala.Warm( 'pm/send' , { usernames : receiverslist.value , pmtext : pmtext.value } );
	}
	,
	DeletePm : function( msgnode, pmid, folderid, read ) {
		Modals.Confirm( 'Θέλεις σίγουρα να διαγράψεις το μήνυμα;' , function() {
			pms.activepms = 0;
			var delimg2 = $( '#pm_' + pmid + ' div.infobar span.unreadpm' )[ 1 ];
			$( '#pm_' + pmid + ' div.lowerline' ).hide();
			//$( $( '#pm_' + pmid + ' img' )[ 0 ] ).hide();
			if ( delimg2 ) {
				//if the message is already read there is no such image
				$( delimg2 ).hide();
			}
			$( msgnode ).hide( 700 , function() {
				$( this ).remove();
			} );
			//check whether the msg is read or not, if it in unread only then execute the next function : TODO
			if ( !read ) {
				pms.UpdateUnreadPms( -1 );
			}
			pms.pmsinfolder--;
			pms.WriteNoPms();
			Coala.Warm( 'pm/delete' , { pmid : pmid, folderid : folderid } );
		} );
		return false;
	},
	UpdateUnreadPms : function( specnumber ) {
		//reduces the number of unread messages by one
		//if specnumber is - 1 the unread pms number is reduced by one, else the specnumber is used as the number for the unread msgs
		var unreadmsgbanner = $( "#unreadmessages" )[0];
		var incomingdiv = $( '#folders div' )[ 0 ];
		var incominglink = incomingdiv.firstChild;
		var newtext;
		var newtext2;		
		while( incominglink.firstChild ) {
			incominglink.removeChild( incominglink.firstChild );
		}
		while( unreadmsgbanner.firstChild ) {
			unreadmsgbanner.removeChild( unreadmsgbanner.firstChild );
		}
		if ( unreadpms > 1 ) {
			if ( specnumber == -1 ) {
				--unreadpms;
				newtext = document.createTextNode( 'Εισερχόμενα (' + unreadpms + ')' );
				if ( unreadpms == 1 ) {
					newtext2 = document.createTextNode( '1 Νέο Μήνυμα' );
				}
				else {
					newtext2 = document.createTextNode( unreadpms + ' Νέα Μηνύματα' );
				}
			}
			else {
				newtext = document.createTextNode( 'Εισερχόμενα (' + specnumber + ')' );
				newtext2 = document.createTextNode( specnumber + ' Νέα Μηνύματα' );
			}
		}
		else {
		    if ( specnumber == -1 && unreadpms == 1 ) {
		        unreadpms = 0;
		    }
			newtext = document.createTextNode( 'Εισερχόμενα' );
			newtext2 = document.createTextNode( 'Μηνύματα' );
		}
		var folderspan = document.createElement( 'span' );
		var bannerspan = document.createElement( 'span' );
		$( incominglink ).append( folderspan );
		$( incominglink ).append( newtext );
		$( unreadmsgbanner ).append( bannerspan );
		$( unreadmsgbanner ).append( newtext2 );
		if ( unreadpms === 0 ) {
		    $( unreadmsgbanner ).removeClass( "unread" );
		}
	}
	,
	ShowFolderNameTop : function( texttoshow ) {
		//showing the name of the folder in the right upper corner
		var messagesdivparent = pms.messagescontainer.parentNode.parentNode;
		var messagesdivdiv = messagesdivparent.getElementsByTagName( 'div' );
		var foldertext = messagesdivdiv[ 1 ];
		$( foldertext.firstChild ).remove();
		$( foldertext ).append( document.createTextNode( texttoshow ) );
	}
	,
	ShowAnimation : function( texttoshow ) {
		pms.ClearMessages();
		var loadinggif = document.createElement( 'img' );
		$( loadinggif ).attr( { src : ExcaliburSettings.imagesurl + 'ajax-loader.gif' , alt : texttoshow , title : texttoshow } );
		var loadingtext = document.createTextNode( ' ' + texttoshow );
		$( pms.messagescontainer ).append( loadinggif ).append( loadingtext );
	}
	,
	ClearMessages : function() {
		//clears the area where pms appear
		$( pms.messagescontainer ).empty();
	},
	WriteNoPms : function() {
		var messagescontainerdivlist = pms.messagescontainer.getElementsByTagName( 'div' );
		if ( messagescontainerdivlist.length / 12 == 1 ) {
			nopmsspan = document.createElement( 'span' );
			$( nopmsspan ).html( 'Δεν έχεις μηνύματα σε αυτό το φάκελο.<br />Μετακίνησε κάποια μηνύματα με το ποντίκι σε αυτό το φάκελο για να τα μεταφέρεις εδώ.' );
			$( pms.messagescontainer ).append( nopmsspan );
			$( nopmsspan ).animate( { opacity : "1" } , 2000 );
		}
	},
    OnLoad : function() {
    	$( 'div.message' ).draggable( { 
    		helper : 'original',
    		revert : 'true',
    		cursor : 'move'
    	} );
    	$( 'div.createdfolder' ).droppable( {
    		accept: "div.message",
    		hoverClass: "hoverfolder",
    		tolerance: "pointer",
    		drop : function(ev, ui) {
    			Coala.Warm( 'pm/transfer' , { 'pmid' : ui.draggable.attr( "id" ).substring( 3 ) , 'folderid': $( 'div.activefolder' ).attr( "id" ).substring( 7 ), 'targetfolderid': $( this ).attr( "id" ).substring( 7 ) } );
    			ui.draggable.animate( { 
    				opacity: "0",
    				height: "0"
    				} , 700 , function() {
    					ui.draggable.remove();
    			} );
    		}
    	} );
    }
};
var Frontpage = {
	Closenewuser : function ( node ) {
		$( 'div.frontpage div.ybubble' ).animate( { height : '0'} , 800 , function() {
			$( this ).remove();
		} );
	},
	DeleteShout : function( shoutid ) {
		if ( confirm( 'Θέλεις σίγουρα να διαγράψεις το μήνυμα;' ) ) {
			$( 'div#s_' + shoutid ).animate( { height : "0" , opacity : "0" } , 300 , function() {
				$( this ).remove();
			} );
			Coala.Warm( 'shoutbox/delete' , { shoutid : shoutid } );
		    return false;
		}
	},
    FrontpageOnLoad : function() {
        if ( $( 'div.members div.join' )[ 0 ] ) {
            $( 'div.members div.join input' )[ 1 ].focus();
        }
        if ( $( 'div.frontpage div.ybubble' )[ 0 ] ) {
            $( '#selectplace select' ).change( function() {
                var place = $( '#selectplace select' )[ 0 ].value;
                $( 'div.ybubble div.body div.saving' ).removeClass( 'invisible' );
                Coala.Warm( 'frontpage/welcomeoptions' , { place : place } );
            } );
            $( '#selecteducation select' ).change( function() {
                var edu = $( '#selecteducation select' )[ 0 ].value;
                $( 'div.ybubble div.body div.saving' ).removeClass( 'invisible' );
                Coala.Warm( 'frontpage/welcomeoptions' , { education : edu } );
            } );
            $( '#selectuni select' ).change( function() {
                var uni = $( '#selectuni select' )[ 0 ].value;
            $( 'div.ybubble div.body div.saving' ).removeClass( 'invisible' );
                Coala.Warm( 'frontpage/welcomeoptions' , { university : uni } );
            } );
        }
        if ( $( 'div.notifications div.list' )[ 0 ] ) {
            /*var notiflist = $( 'div.notifications div.list' )[ 0 ];
            var notiflistheight = $( notiflist )[ 0 ].offsetHeight;
            */
            $( 'div.notifications div.event' ).mouseover( function() {
                $( this ).css( "border" , "1px dotted #666" ).css( "padding" , "4px" );
            } )
            .mouseout( function() {
                $( this ).css( "border" , "0" ).css( "padding" , "5px" );
            } );
        
            $( 'div.notifications div.expand a' ).click( function() {
                if ( !Notification.Expanded ) {
                    $( this ).css( "background-position" , "4px -1440px" )
                    .attr( {
                        title : 'Απόκρυψη'
                    } );
                    Notification.Expanded = true;
                }
                else {  
                    $( this ).css( "background-position" , "4px -1252px" )
                    .attr( {
                        title : 'Εμφάνιση:'
                    } );
                    Notification.Expanded = false;
                }
                $( 'div.notifications div.list' ).slideToggle( "slow" );

                return false;
            } );  
         }
        $( 'div.right div.latest' ).mousemove( function() {
            if ( typeof( timer ) != 'undefined'  && timer ) {
                clearTimeout( timer );
            }
            timer = setTimeout( "Frontpage.Comment.MouseOver=false;Frontpage.Comment.NextComment();" , 1000 );
        
        } );
        $( 'div.right div.latest' ).mouseenter( function() {
            Frontpage.Comment.MouseOver = true;
        } ).mouseleave( function() {
            Frontpage.Comment.MouseOver = false;
            Frontpage.Comment.NextComment();
        } );
        Frontpage.Shoutbox.OnLoad();
	},
    Shoutbox: {
        Animating: 0,
        Changed: false,
        Typing: [], // people who are currently typing (not including yourself)
        TypingUpdated: false, // whether "I am typing" has been sent recently (we don't want to send it for every keystroke!)
        TypingCancelTimeout: 0, // this timeout is used to send a "I have stopped typing" request
        OnLoad: function () {
            var textarea = $( '#shoutbox_text' );
            
            $( '#shoutbox div.newcomment div.bottom input' ).click( function() {
                var list = $( '#shoutbox div.comments' );
                var text = $( list ).find( 'div.newcomment div.text input' )[ 0 ].value;
                if ( $.trim( text ) === '' || !Frontpage.Shoutbox.Changed ) {
                    alert( 'Δε μπορείς να δημοσιεύσεις κενό μήνυμα' );
                    textarea[ 0 ].value = '';
                    textarea[ 0 ].focus();
                }
                else {
                    var newshout = $( list ).find( 'div.empty' )[ 0 ].cloneNode( true );
                    $( newshout ).removeClass( 'empty' ).insertAfter( $( list ).find( 'div.newcomment' )[ 0 ] ).show().css( "opacity" , "0" ).find( 'div.text' );
                    var copytext = text;
                    $( newshout ).find( 'div.text' ).append( document.createTextNode( copytext ) ); 
                    Coala.Warm( 'shoutbox/new' , { text : text , node : newshout } );
                    Frontpage.Shoutbox.ShowShout( newshout );
                    Frontpage.Shoutbox.Changed = false;
                    textarea[ 0 ].value = '';
                    q();
                    setTimeout( function () {
                        textarea[ 0 ].focus();
                    }, 100 );
                }
            } );

            // insert deletion in shoutbox 
            // check if user is logged in
            var username = GetUsername();
            
            if ( username ) {
                $( "#shoutbox div.comment[id^='s_']" ).each( function() { // match shouts that have an id (exclude the reply)
                    if ( username == $( this ).find( 'div.who a img.avatar' ).attr( 'alt' ) ) {
                        var shoutid = this.id.substr( 2 , this.id.length - 2 );
                        var toolbox = document.createElement( 'div' ); 
                        var deletelink = document.createElement( 'a' );
                        $( deletelink ).attr( 'href' , '' )
                        .css( 'padding-left' , '16px' )
                        .click( function() {
                            return Frontpage.DeleteShout( shoutid );
                        } );
                        $( toolbox ).addClass( 'toolbox' ).append( deletelink );
                        $( this ).prepend( toolbox );
                    }
                } );
            }       
            var q = function () {
                var submit = $( '#shoutbox_submit' )[ 0 ];
                if ( $.trim( textarea[ 0 ].value ).length === 0 ) {
                    if ( !submit.disabled ) {
                        submit.disabled = true;
                    }
                }
                else {
                    if ( submit.disabled ) {
                        submit.disabled = false;
                    }
                }
            };
            
            textarea.keyup( function ( e ) {
                if ( e.keyCode == 13 ) { // enter
                    textarea.blur();
                    $( '#shoutbox div.newcomment div.bottom input' ).click();
                }
                else {
                    q();
                }
            } ).keydown( function ( e ) { // send an "I'm typing" request
                if ( Frontpage.Shoutbox.TypingCancelTimeout !== 0 ) { // if we were about to send a "I've stopped typing" request...
                    clearTimeout( Frontpage.Shoutbox.TypingCancelTimeout ); // delay it for a while
                }
                Frontpage.Shoutbox.TypingCancelTimeout = setTimeout( function () {
                    Coala.Warm( 'shoutbox/typing', { 'typing': false } ); // OK send the actual "I've stopped typing" request
                }, 10000 ); // send an "I've stopped typing" request if I haven't touched the keyboard for 10 seconds
                if ( Frontpage.Shoutbox.TypingUpdated ) { // We've already sent an "I'm typing" request recently; don't do it again for every keystroke!
                    return;
                }
                Frontpage.Shoutbox.TypingUpdated = true; // OK we're about to send an "I'm typing" request now; make sure we don't send one again very soon
                setTimeout( function () { // After we've sent an "I'm typing" request, we don't want to send more. But only for 10 seconds; we'll send another "I'm typing" request if I'm still typing by then.
                    Frontpage.Shoutbox.TypingUpdated = false;
                }, 10000 );
                Coala.Warm( 'shoutbox/typing', { 'typing': true } ); // OK send the actual request
            } ).change( q ).focus( function() {
                if ( !Frontpage.Shoutbox.Changed ) {
                    textarea[ 0 ].value = '';
                    textarea[ 0 ].style.color = '#000';
                }
            } ).blur( function () {
                q();
                if ( textarea[ 0 ].value === '' ) {
                    textarea[ 0 ].value = 'Πρόσθεσε ένα σχόλιο στη συζήτηση...';
                    textarea[ 0 ].style.color = '#666';
                    Frontpage.Shoutbox.Changed = false;
                }
                else {
                    Frontpage.Shoutbox.Changed = true;
                }
            } ).blur();
            
            textarea[ 0 ].disabled = false;
        },
        OnStartTyping: function ( who ) { // received when someone starts typing
            if ( who.name == GetUsername() ) { // don't show it when you're typing
                return;
            }
            for ( var i = 0; i < Frontpage.Shoutbox.Typing.length; ++i ) {
                var typist = Frontpage.Shoutbox.Typing[ i ];
                if ( typist.name == who.name ) {
                    clearTimeout( typist.timeout );
                    // in case the typing user gets disconnected and is unable to send us a 
                    // "stopped typing" comet request, time it out after 20,000 milliseconds
                    // of no "started typing" comet requests
                    // (also in case we receive the asynchronous "I'm typing" and "I've stopped typing"
                    // requests in the wrong order -- very improbable but possible)
                    Frontpage.Shoutbox.Typing[ i ].timeout = setTimeout( function () {
                        Frontpage.Shoutbox.OnStopTyping( who );
                    }, 20000 );
                    return;
                }
            }
            who.timeout = setTimeout( function () {
                Frontpage.Shoutbox.OnStopTyping( who );
            }, 20000 ); // in case the remote party gets disconnected
            Frontpage.Shoutbox.Typing.push( who );
            Frontpage.Shoutbox.UpdateTyping();
        },
        OnStopTyping: function ( who ) { // received when someone stops typing
            var found = false;
            
            for ( var i = 0; i < Frontpage.Shoutbox.Typing.length; ++i ) {
                var typist = Frontpage.Shoutbox.Typing[ i ];
                if ( typist.name == who.name ) {
                    Frontpage.Shoutbox.Typing.splice( i, 1 );
                    found = true;
                    break;
                }
            }
            if ( !found ) {
                return;
            }
            Frontpage.Shoutbox.UpdateTyping();
        },
        UpdateTyping: function () { // show who's typing
            var typetext = '';
            
            function ucfirst( str ) {
                str += '';
                var f = str.charAt( 0 ).toUpperCase();
                return f + str.substr( 1 );
            }
            
            if ( Frontpage.Shoutbox.Typing.length ) {
                var typists = [];
                for ( var i = 0; i < Frontpage.Shoutbox.Typing.length; ++i ) {
                    var typist = Frontpage.Shoutbox.Typing[ i ];
                    var text;
                    
                    if ( typist.gender == 'f' ) {
                        text = 'η ';
                    }
                    else {
                        text = 'ο ';
                    }
                    text += typist.name;
                    typists.push( text );
                }
                
                if ( typists.length == 1 ) {
                    typetext = ucfirst( typists.pop() ) + ' πληκτρολογεί...';
                }
                else {
                    typists.push( 'και ' + typists.pop() );
                    typetext = ucfirst( typists.join( ', ' ) ) + ' πληκτρολογούν...';
                }
            }
            
            var typingdiv = $( 'div#shoutbox div.newcomment div.typing' );
            
            if ( typetext === '' ) {
                typingdiv.css( 'opacity', 1 ).animate( { 'opacity': 0 } );
            }
            else {
                typingdiv.css( 'opacity', 0 ).animate( { 'opacity': 1 } );
                typingdiv[ 0 ].innerHTML = typetext;
            }
        },
        OnMessageArrival: function ( shoutid, shouttext, who ) {
            if ( who.name == GetUsername() ) {
                return;
            }
            
            Frontpage.Shoutbox.OnStopTyping( { 'name': who.name } );
            
            var avatar;
            var whodiv = document.createElement( 'div' );
            var text = document.createElement( 'div' );
            
            if ( who.avatar !== 0 ) {
                avatar = 'http://images2.zino.gr/media/'
                                + who.id + '/' + who.avatar + '/' + who.avatar 
                                + '_100.jpg';
            }
            else {
                avatar = 'http://static.zino.gr/phoenix/anonymous100.jpg';
            }
            
            whodiv.className = 'who';
            whodiv.innerHTML = '<a href="http://' + who.subdomain + '.zino.gr/">'
                            + '<img src="' + avatar + '" width="50" height="50" alt="' 
                            + who.name + '" class="avatar" />'
                            + who.name + '</a>' + ' είπε:';
            text.className = 'text';
            text.innerHTML = shouttext;
            
            var div = document.createElement( 'div' );
            div.id = 's_' + shoutid;
            div.className = 'comment';
            div.appendChild( whodiv );
            div.appendChild( text );
            
            var comments = $( 'div#shoutbox div.comments' );
            comments[ 0 ].insertBefore( div, comments.find( 'div.comment' )[ 1 ] );
            
            Frontpage.Shoutbox.ShowShout( div );
        },
        ShowShout: function( node ) {
            var targetHeight = node.offsetHeight;
            var comments = $( '#shoutbox div.comments div.comment' );
            var i = 0;
            
            for ( i = comments.length - 2; i >= 1; --i ) { // messages can be posted fast; multiple ones within 500ms :)
                if ( typeof comments[ i ].beingRemoved == 'undefined' ) {
                    comments[ i ].style.marginTop = 0;
                    comments[ i ].style.marginBottom = 0;
                    comments[ i ].beingRemoved = true;
                    break;
                }
            }
            
            $( comments[ i ] ).animate( {
                height: 0,
                opacity: 0
            }, 500, 'linear' );
            node.style.height = '0';
            $( node ).css( 'opacity', 0 );
            $( node ).animate( {
                height: targetHeight,
                opacity: 1
            }, 500, 'linear', function () {
                $( comments[ i ] ).remove();
                --Frontpage.Shoutbox.Animating;
            } );
            
            ++Frontpage.Shoutbox.Animating;
        }        
    },
    Comment : {
        Animating : false,
        Queue : [],
        MouseOver : false,
        ShowComment : function( node , timerint ) {
            Frontpage.Comment.Animating = true;
            setTimeout( "Frontpage.Comment.Animating = false;Frontpage.Comment.NextComment()" , timerint );
            $( 'div.latest div.comments div.list' ).prepend( node );
            var targetheight = $( 'div.latest div.comments div.list div.event' )[ 0 ].offsetHeight;
            $( node ).css( "height" , "0" )
            .animate( {
                height: targetheight,
                opacity: "1"
            } , 500 , 'linear' );
            $( 'div.latest div.comments div.list>div:last-child' ).animate( {
                height: "0",
                opacity: "0"
            } , 350 , 'linear' , function() {
                $( this ).remove();
            } );
        },
        NextComment : function() {
            if ( Frontpage.Comment.Queue.length == 0 ) {
                return;
            }
            /*
            if ( Frontpage.Comment.Queue.length > 20 ) {
                Frontpage.Comment.Queue.slice( 10 , Frontpage.Comment.Queue.length );
            }
            */
            if ( Frontpage.Comment.MouseOver ) {
	    	timerval = 7000;	
            }
            else {
                if ( Frontpage.Comment.Queue.length <= 7 ) {
                    timerval = 5000;
                }
                else if ( Frontpage.Comment.Queue.length <= 15 ) {
                    timerval = 3000;
                }
                else {
                    timerval = 2000;
                }
            }
            Frontpage.Comment.ShowComment( Frontpage.Comment.Queue.pop() , timerval );
        }
    }
};
// if you modify these signatures, also modify the function modifiers in js/user/profile.js
var MoodDropdown = {
    CurrentOpen: 0,
    Unpush: function () {
        if ( MoodDropdown.CurrentOpen === 0 ) {
            return;
        }

        $( MoodDropdown.CurrentOpen ).css( 'overflow', 'hidden' ).find( 'a' )[ 0 ].style.backgroundImage = 'url(\'' + ExcaliburSettings.imagesurl + 'dropbutton.png\')';
        $( MoodDropdown.CurrentOpen ).find( 'div.pick' ).fadeOut( 400 );
        $( MoodDropdown.CurrentOpen ).find( 'div.view' ).css( 'opacity', 1 );
        MoodDropdown.CurrentOpen = 0;
    },
    Select: function ( id, moodid, who ) {
        Settings.Enqueue( 'mood', moodid, 3000 );
        var imgnode = $( who.parentNode.parentNode.parentNode.parentNode ).find( 'div.view img.selected' )[ 0 ];
        imgnode.src = $( who ).find( 'img' )[ 0 ].src;
        imgnode.alt = $( who ).find( 'img' )[ 0 ].alt;
        imgnode.title = $( who ).find( 'img' )[ 0 ].title;
        MoodDropdown.Unpush();
    },
    Push: function ( who ) {
        if ( MoodDropdown.CurrentOpen !== 0 ) {
            if ( MoodDropdown.CurrentOpen == who ) {
                MoodDropdown.Unpush();
                return;
            }
        }
        MoodDropdown.CurrentOpen = who;
        $( who ).css( 'overflow', '' ).find( 'a' )[ 0 ].style.backgroundImage = 'url(\'' + ExcaliburSettings.imagesurl + 'dropbuttonpushed.png\')';
        $( who ).find( 'div.view' ).css( 'opacity', 0.5 );
        $( who ).find( 'div.pick' ).hide();
        $( who ).find( 'div.pick' ).fadeIn( 400 );
        
        $( who ).find( 'ul li a' ).click( function ( event, a ) {
            MoodDropdown.Unpush( who );
            return false;
        } );
    }
};
var ExcaliburSettings = {
    //Production for sandbox/live
	applicationname : 'Zino',
	imagesurl : 'http://static.zino.gr/phoenix/',
	webaddress : 'https://beta.zino.gr/phoenix',
	photosurl : 'http://images.zino.gr/media/',
	image_proportional_210x210 : '210',
	image_cropped_100x100 : '100',
	image_cropped_150x150 : '150',
	image_fullview : 'full'
};
function GetUsername() {
    var username = false;
	if ( $( '#banner a.profile' )[ 0 ] ) {
        username = $( 'a.profile' ).text();
	}
	else {
		username = false;
	}
    var newtime = new Date().getTime();

    return username;
}
$( function() {
    /*if ( $.browser.mozilla ) {
	    $( "img" ).not( ".nolazy" ).lazyload( { 
			threshold : 200
		} );
	}
    */
    if ( ExcaliburSettings.AllowIE6 ) {
        return;
    }
	if ( $.browser.msie && $.browser.version < 7 ) {
		window.location.href = "ie.html";
	}
} );
var Types = {
	Poll : 1,
	Image : 2,
	Userprofile : 3,
	Journal : 4
};	var Questions = {
	busy : false, // do not allow two answers to be edited simultaneously
    Renew: function ( questionid, questiontext ) {
        $( 'div.newquestion p.question' ).empty().text( questiontext );
        $( 'div.newquestion form#newanswer input' )[ 0 ].value = questionid;
        $( 'div.newquestion form#newanswer input' )[ 1 ].value = '';
        $( 'div.newquestion form#newanswer a:last' ).get( 0 ).onclick = function() {
        	Coala.Cold( 'question/get', { 
            	'callback': Questions.Renew,
            	'excludeid' : questionid
            } );
            return false;
        };
        $( 'div.newquestion' ).fadeIn( 'fast' );
        $( 'div.newquestion form#newanswer input' )[ 1 ].focus();
    },
    Answer: function() {
        var answerText = $( 'form#newanswer input' )[ 1 ].value;
        var questionText = $(  'div.newquestion p.question' )[ 0 ].childNodes[ 0 ].nodeValue; 
        
        if ( $.trim( answerText ) === '' ) {
            alert( 'Δεν μπορείς να δημοσιεύσεις μία κενή απάντηση!' );
            return;
        }
        
        Coala.Warm( 'question/answer/new', {
            'questionid': $( 'form#newanswer input' )[ 0 ].value,
            'answertext': answerText,
            'callback': Questions.AnswerCallback
        } );
        Coala.Cold( 'question/get', {
            'callback': Questions.Renew
        } );

        var li = document.createElement( 'li' );
        $( li ).mouseover( function() {
				$( this ).find( 'a' ).show();
			} ).mouseout( function() {
				$( this ).find( 'a' ).hide();
			} );
        
        var question = document.createElement( 'p' );
        question.className = 'question';
        
        var answer = document.createElement( 'p' );
        answer.className = 'answer';
        
        var edit = document.createElement( 'a' );
        edit.style.display = "none";
        var editimg = document.createElement( 'img' );
        editimg.src = ExcaliburSettings.imagesurl + 'edit.png';
        
        var del = document.createElement( 'a' );
        del.style.display = "none";
        var delimg = document.createElement( 'img' );
        delimg.src = ExcaliburSettings.imagesurl + 'delete.png';
        
        question.appendChild( document.createTextNode( questionText ) );
        answer.appendChild( document.createTextNode( answerText ) );
        edit.appendChild( editimg );
        del.appendChild( delimg );
        li.appendChild( question );
        li.appendChild( answer );
        li.appendChild( edit );
        li.appendChild( del );

        $( 'div#answers ul.questions' ).prepend( li );
        $( 'div.newquestion' )[ 0 ].style.display = 'none';
    },
    AnswerCallback: function( id ) {
    	$( 'div#answers ul.questions li:first' ).attr( "id", "q_" + id ).find( "a:first" ).click( function() {
    													Questions.Edit( id );
    													return false;
    												} ).end()
    											.find( "a:last" ).click( function() {
    												Questions.Delete( id );
    												return false;
    											} );
   	},
   	Edit : function( id ) {
   		if ( Questions.busy ) {
   			return;
   		}
   		Questions.busy = true;
   		var form = document.createElement( 'form' );
   		form.onsubmit = function() { return false; };
   		
   		var input = document.createElement( 'input' );
   		input.value = $( 'li#q_' + id + ' p.answer' ).get( 0 ).firstChild.nodeValue;
   		$( input ).keydown( function( event ) {
   				if ( event.keyCode == 13 ) {
   					Questions.submitEdit( id );
   				}
   			} ).blur( function() {
   				Questions.submitEdit( id );
   			} );
   		
   		var accept = document.createElement( 'a' );
   		accept.onclick = function() {
   				Questions.submitEdit( id );	
   			};
   		
   		var acceptimg = document.createElement( 'img' );
   		acceptimg.alt = "Επεξεργασία";
   		acceptimg.title = "Επεξεργασία";
   		acceptimg.src = ExcaliburSettings.imagesurl + 'accept.png';
   		
   		var cancel = document.createElement( 'a' );
   		cancel.onclick = function() { 
   				Questions.finishEdit( id, false );
   				return false;
   			};
   		
   		var cancelimg = document.createElement( 'img' );
   		cancelimg.alt = "Ακύρωση";
   		cancelimg.title = "Ακύρωση";
   		cancelimg.src = ExcaliburSettings.imagesurl + 'cancel.png';
   		
   		accept.appendChild( acceptimg );
   		cancel.appendChild( cancelimg );
   		form.appendChild( input );
   		form.appendChild( document.createTextNode( " " ) );
   		form.appendChild( accept );
   		form.appendChild( cancel );
   		
   		$( 'li#q_' + id + ' p.answer, li#q_' + id + ' a' ).hide();
   		Questions.hide();
   		$( 'li#q_' + id ).get( 0 ).appendChild( form );
   		$( accept ).show();
   		$( cancel ).show();
   	},
   	submitEdit : function( id ) {
   		var texter = $( 'li#q_' + id + ' form input' ).val();
		if ( $.trim( texter ) === '' ) {
			alert( "Δεν μπορείς να δημοσιεύσεις μία κενή απάντηση" );
			return false;
		}
		Coala.Warm( 'question/answer/edit', {
			'id' : id,
			'answertext' : texter
		} );
		Questions.finishEdit( id, texter );
		return false;
	},
   	finishEdit : function( id, texter ) {
   		$( 'li#q_' + id + ' form' ).remove();
   		if ( texter !== false ) {
   			$( 'li#q_' + id + ' p.answer' ).text( texter );
   		}
   		$( 'li#q_' + id + ' p.answer, li#q_' + id + ' a' ).show();
   		Questions.show();
		Questions.busy = false;
	},
   	Delete : function( id ) {
   		Coala.Warm( 'question/answer/delete', {
   			'id' : id
   		} );
   		$( 'li#q_' + id ).hide( 400, function() { 
   				$( this ).remove();
   			} );
   		if ( $( 'div.newquestion:first' ).css( 'display' ) === "none" ) {
   			Coala.Cold( 'question/get', {
		        'callback': Questions.Renew
		    } );
		}
		return false;
   	},
   	show : function() {
   		$( "div#answers ul.questions li" ).each( function( i ) {
			$( this ).mouseover( function() {
				$( this ).find( 'a' ).show();
			} ).mouseout( function() {
				$( this ).find( 'a' ).hide();
			} );
		} );
	},
	hide : function() {
		$( "div#answers ul.questions li" ).each( function( i ) {
			$( this ).unbind( "mouseover" ).unbind( "mouseout" );
		} );
	},
    OnLoad : function() {
        if ( $( 'div#answers div.questions div.newquestion p.answer form input' )[ 1 ] ) {
            $( 'div#answers div.questions div.newquestion p.answer form input' )[ 1 ].focus();
        }
        Questions.show();
    }
};
_uacct = "UA-1065489-1";
urchinTracker();
var Comments = {
    typing : false,
	numchildren : {},
	Create : function( parentid ) {
		var texter;
		if ( parentid === 0 ) { // Clear new comment message
			texter = $( "div.newcomment > div.text > textarea" ).get( 0 ).value;
			$( "div.newcomment > div.text > textarea" ).get( 0 ).value = '';
		}
		else {
			texter = $( "#comment_reply_" + parentid + " > div.text > textarea" ).get( 0 ).value;
		}
		texter = $.trim( texter );
		if ( texter === "" ) {
			alert( "Δε μπορείς να δημοσιεύσεις κενό μήνυμα" );
			return;
		}
		var a = document.createElement( 'a' );
        $( a ).append( document.createTextNode( "Απάντησε" ) )
        .attr( 'href' , '' )
        .click( function() {
            return false;
        } );
		var indent = ( parentid === 0 )? -1: parseInt( $( "#comment_" + parentid ).css( "marginLeft" ), 10 ) / 20;
        var marginright = ( parentid === 0 ) ? 0 : ( indent + 1 ) * 20 + 'px';
		// Dimiourgisa ena teras :-S
		var daddy = ( parentid === 0 )? $( "div.newcomment:first" ).clone( true ):$( "#comment_reply_" + parentid );
        var temp = daddy.css( "opacity", 0 ).removeClass( "newcomment" ).find( "span.time" ).text( "πριν λίγο" ).end()
        .find( "div.toolbox" ).show().end()
        .css( "border-top" , "3px solid #b3d589" )
		.find( "div.text" ).empty()./*html( texter.replace( /\n/gi, "<br />" ) )*/text( texter ).end()
		.find( "div.bottom" ).css( "visibility" , "hidden" ).empty().append( a ).append( document.createTextNode( " σε αυτό το σχόλιο" ) ).end();
		
		var valu = temp.find( "div.text" ).html();
		temp.find( "div.text" ).html( valu.replace( /\n/gi, "<br />" ) );
		
        var link = document.createElement( 'a' );
        var username = GetUsername();
        if ( ExcaliburSettings.Production ) {
            var hrefs = "http://" + username + ".zino.gr/";
        }
        else {
            var hrefs = "http://" + username + ".beta.zino.gr/phoenix/";
        }
        var avatarview = $( daddy ).find( "div.who span.imageview" );
        var avatar = $( avatarview ).clone( true );
        $( link ).attr( "href" , hrefs )
        .append( avatar ).append( document.createTextNode( username ) );
	    $( daddy ).find( "div.who" ).empty().append( link );	
		if ( parentid === 0 ) {
			temp.insertAfter( "div.newcomment:first" ).fadeTo( 400, 1 );
		}
		else {
			temp.insertAfter( "#comment_" + parentid ).fadeTo( 400, 1 );
		}
		var type = temp.find( "#type:first" ).text();
		Comments.FixCommentsNumber( type, true );
		Coala.Warm( 'comments/new', { 	text : texter, 
            parent : parentid,
            compage : temp.find( "#item:first" ).text(),
            type : type,
            node : temp, 
            callback : Comments.NewCommentCallback
        } );
        Comments.ToggledReplies[ parentid ] = 0;
	},
    NewCommentCallback : function( node , id , parentid , newtext ) {
		if ( parentid !== 0 ) {
			++Comments.numchildren[ parentid ];
		}
		Comments.numchildren[ id ] = 0;	
		var indent = ( parentid===0 )? -1 : parseInt( $( "#comment_" + parentid ).css( "marginLeft" ), 10 )/20;
        node.attr( 'id', 'comment_' + id )
		.find( "div.text" ).html( newtext ).end()
        .find( 'div.bottom' ).css( "visibility" , "visible" ).find( 'a' ).click( function() {
                Comments.ToggleReply( id , indent + 1 );
                return false;
            }
        );
	},
	Reply : function( nodeid, indent ) {
		// Atm prefer marginLeft. When the comment is created it will be converted to paddingLeft. Looks better
		var temp = $( "div.newcomment:first" ).clone( true ).css( { marginLeft : (indent+1)*20 + 'px', opacity : 0 } ).attr( 'id', 'comment_reply_' + nodeid );
		$( temp ).find( "div.toolbox" ).show().end()
        .css( "border-top" , "3px solid #b3d589" )
        .find( "div.bottom form input:first" ).get( 0 ).onclick = function() { // Only with DOM JS the onclick event is overwritten
					$( "#comment_reply_" + nodeid ).css( {marginLeft : (indent+1)*20 + 'px' } );
					Comments.Create( nodeid );
					return false;
				} ;

		temp.insertAfter( '#comment_' + nodeid ).fadeTo( 300, 1 );
        Comments[ "Changed" + nodeid ] = false;
        $( temp ).find( "div.text textarea" ).focus( function() {
            if ( !Comments[ "Changed" + nodeid ] ) {
                this.value = "";
                $( this ).css( "color" , "#000" );
                Comments.typing = true;
            }
        
        } ) 
        .blur( function() {
            $( "#comment_" + nodeid + " div.text" ).css( "font-weight" , "400" );
            if ( this.value  === '' ) {
                this.value = "Πρόσθεσε ένα σχόλιο..."; 
                $( this ).css( "color" , "#666" );
                Comments[ "Changed" + nodeid ] = false;
            }
            else {
                Comments[ "Changed" + nodeid ] = true;
            }
            setTimeout( function() {
                Comments.typing = false;
                Comments.Page.NextComment();
            } , 2000 );
        } ).get( 0 ).focus();
	},
	FixCommentsNumber : function( type, inc ) {
		if ( type != 2 && type != 4 ) { // If !Image or Journal
			return;
		}
		var node = $( "dl dd.commentsnum" );
        var icon = document.createElement( "span" );
        $( icon ).addClass( 's_commnum' ).css( 'padding-left' , '19px' ).append( document.createTextNode( ' ' ) );
		if ( node.length !== 0 ) {
			var commentsnum = parseInt( node.text(), 10 );
			commentsnum = (inc)?commentsnum+1:commentsnum-1;
			$( node ).empty().append( icon ).append( document.createTextNode( commentsnum + " σχόλια" ) );
		}
		else {
			var dd = document.createElement( 'dd' );
			$( dd ).addClass( "commentsnum" )
            .append( icon )
			.append( document.createTextNode( "1 σχόλιο" ) );
			$( "div dl" ).prepend( dd );
		}
	},
    FindLeftPadding : function( node ) {
        var leftpadd = $( node ).css( 'margin-left' );
        if ( leftpadd ) {
            return leftpadd.substr( 0 , leftpadd.length - 2 ) - 0;
        }
        else {
            return 0;
        }
    },
    ToggledReplies: {},
    ToggleReply: function ( id, indent ) {
        if ( typeof Comments.ToggledReplies[ id ] != 'undefined' && Comments.ToggledReplies[ id ] === 1 ) {
            $( '#comment_reply_' + id ).remove(); 
            Comments.ToggledReplies[ id ] = 0;

            return;
        }
        Comments.ToggledReplies[ id ] = 1;
        Comments.Reply( id, indent );
    },
    Focus: function ( id, indent, loggedin ) {
        var cmd = $( '#comment_' + id )[ 0 ];
        $( cmd ).find( "div.text" ).css( "font-weight" , "700" );
        cmd.scrollIntoView( false );
        window.scrollBy( 0 , 200 );
        if ( loggedin ) {
            Comments.ToggleReply( id, indent - 1 );
        }
    },
    parents : [],
    indents : [],
    ids     : [],
    lpadd   : [],
    OnLoad : function() {
        if ( $.browser.msie ) {
            $( "[id^='comment_']" ).each( function( i ) {
                var parent =  this;
                Comments.parents[ i ] = parent;
                
                var id = $( parent ).attr( "id" ).substr( 8 );
                Comments.ids[ i ] = id;
                
                Comments.lpadd[ i ] = Comments.FindLeftPadding( parent );

                var indent = parseInt( Comments.lpadd[ i ], 10 )/20;
                Comments.indents[ i ] = indent;
            } );
        }
        else {
            $( "[id^='comment_']" ).each( function( i ) {
                var parent = this;
                Comments.parents[ i ] = parent;
                
                var id = $( parent ).attr( "id" ).substr( 8 );
                Comments.ids[ i ] = id;
                
                Comments.lpadd[ i ] = Comments.FindLeftPadding( parent );

                var indent = parseInt( Comments.lpadd[ i ], 10 )/20;
                Comments.indents[ i ] = indent;
            } );
        }

        $( "[id^='comment_'] > div.bottom > a" ).each( function( i ) {
            $( this ).click( function() {
                Comments.ToggleReply( Comments.ids[ i ] , Comments.indents[ i ] );
                
                return false;
            } );
        } );
        
        if ( $( "div.comments div[id^='comment_']" )[ 0 ] ) {
            var username = GetUsername();
            $( "[id^='comment_'] > div.toolbox > span.time" ).each( function( i ) {
                var commdate = $( this ).text();
                $( this ).empty()
                .text( greekDateDiff( dateDiff( commdate , Comments.nowdate ) ) )
                .show();
            } );

            if ( !username || ( typeof ExcaliburSettings.CommentsDisabled != 'undefined' && ExcaliburSettings.CommentsDisabled ) ) {
                $( "[id^='comment_'] > div.bottom" ).empty();
            }
            else {
                $( "[id^='comment_'] > div.bottom" ).each( function( i ) {
                    var leftpadd = Comments.lpadd[ i ];
                    if ( leftpadd > 500 ) {
                        $( this ).empty();
                    }
                } );
                $( "[id^='comment_'] > div.who > a > span.imageview > img.avatar[alt='" + username + "']" ).each( function( i ) {
                    $( this ).parent().parent().parent().parent().css( "border-top" , "3px solid #b3d589" );
                } );
            }
        }
    },
    NewCommentOnLoad : function() {
        Comments[ "Changed0" ] = false;
        $( "#newcom div.toolbox" ).hide();
        $( "#newcom div.text textarea" ).css( "color" , "#666" ).focus( function() {
            if ( !Comments[ "Changed0" ] ) {
                this.value = "";
                $( this ).css( "color" , "#000" );
            }
            Comments.typing = true; 
        } )
        .blur( function() {
            if ( this.value  === '' ) {
                this.value = "Πρόσθεσε ένα σχόλιο..."; 
                $( this ).css( "color" , "#666" );
                Comments[ "Changed0"] = false;
            }
            else {
                Comments[ "Changed0"] = true;
            }
            setTimeout( function() {
                Comments.typing = false;
                Comments.Page.NextComment();
            } , 2000 );
        } );
    },
    Page : {
        Queue : [],
        NextComment : function() {
            if ( Comments.Page.Queue.length == 0 ) {
                return;
            }
            if ( !Comments.typing ) {
                Comments.Page.ShowComment( Comments.Page.Queue.pop() , 2000 );
            }
        },
        ShowComment : function( qnode , timervalue ) {
            if ( qnode.name == GetUsername() ) {
                return;
            }
            setTimeout( "Comments.Page.NextComment();" , timervalue );
            $( qnode.node ).css( "opacity" , "0" ).find( "div.toolbox span.time" ).empty().text( "πριν λίγο" ).show();
            if ( qnode.parentid == 0 ) {
                $( qnode.node ).insertBefore( "[id^='comment_']:first" ); 
                $( qnode.node ).find( "div.bottom > a" ).click( function() {
                    var id = $( this ).parent().parent().attr( "id" ).substr( 8 );
                    Comments.ToggleReply( id , 0 );
                    return false;
                } );
            }
            else {
                var parent = $( "#comment_" + qnode.parentid );
                var parentleftmargin = Comments.FindLeftPadding( parent );
                var parentident = Math.floor( parseInt( parentleftmargin , 10 ) / 20 );
                var ident = parentident + 1;
                var leftmargin = ident * 20;
                
                $( qnode.node ).insertAfter( parent )
                .css( 'margin-left' , leftmargin + "px" );
                if ( leftmargin > 500 ) {
                    $( qnode.node ).find( "div.bottom" ).empty();
                }
                else {
                    $( qnode.node ).find( "div.bottom > a" ).click( function() {
                        var id = $( this ).parent().parent().attr( "id" ).substr( 8 );
                        Comments.ToggleReply( id , ident );
                        return false;
                    } );
                }
            }
            Comments.FixCommentsNumber( qnode.type , true );
            $( qnode.node ).fadeTo( 400 , 1 );
        }
    }
};
var contacts = {
	provider: "",
	username: "",
	password: "",
    step: 0,
    retrieve: function(){
        contacts.provider = $( "#left_tabs li.selected span" ).attr( 'id' );
        contacts.username = $( "#mail input" ).val().split( '@' )[ 0 ];
        if ( contacts.provider == "hotmail" ){
            contacts.username += "@hotmail.com";
        }
        contacts.password = $( "#password input" ).val();
        Coala.Warm( 'contacts/retrieve', {
            provider: contacts.provider,
            username: contacts.username,
            password: contacts.password
        });
        contacts.loading();
    },
	loading: function(){
        document.title = "Φόρτωση επαφών...";
/*		$( '#foot, #login' ).fadeOut( 2000 ); too heavy...
		$( '#left_tabs li span')
			.fadeTo( 'normal', 0 ).parent()
			.filter( 'li.selected' )
			.css({
				'position': 'absolute',
				'borderTopWidth': 1
				})
			.animate({
				'height': 144,
				'top': '0'
			}, 1000, function(){
				$( '#left_tabs li:not(.selected)').hide();
				$( '#left_tabs li.selected' ).animate({
					'width': 0,
					'paddingLeft': 0,
					'paddingRight': 0,
					'left': 1
					}, 1000, function(){
						$( this ).hide();
				});
				$( '#body' )
					.animate({
						'width': 698,
						'height': 466,
						'marginLeft': 0
					}, 960 );
		});
		setTimeout( function(){
			$( "#loading" ).fadeIn();
		}, 2000 );*/
        contacts.step = 1;
        $( "#foot, #login, #left_tabs li" ).fadeOut( 'normal', function(){
            $( "#body" ).animate({
                'width': 700,
                'height': 466,
                'marginLeft': 0
            }, 'normal', function(){
                $( "#loading" ).fadeIn();
            });
        });
	},
	backToLogin: function(){
        document.title = "Λάθος στοιχεία! | Zino";
        contacts.step = 0;
		$( '#foot, #login, #left_tabs, #left_tabs li, #left_tabs li span, #body, #loading' ).attr( 'style', '' );
		//$( '#password div label' ).css( 'fontWeight', 'bold' );
		$( "#foot input" ).one( 'click', contacts.retrieve );
	},
    addContactInZino: function( display, mail, location, id ){
        div = document.createElement( "div" );
        var text = "<div class='contactName'>";
        text += "<input type='checkbox' checked='checked' />";
        //text += "<input type='hidden' name='mails[]' value='" + mail + "' />";
        text += display;
        text += "<div class='contactMail'>" + mail + "</div>";
        text += "</div>";
        text += "<div class='location'>";
        text += location;
        text += "</div>";
        
        $( div ).addClass( "contact" ).attr( 'id', id ).html( text ).appendTo( '#contactsInZino .contacts' );
    },
    previwContactsInZino: function(){
        document.title = "Προσθήκη φίλων | Zino";
        contacts.step = 2;
		$( "#foot input" ).removeClass().addClass( "add" );
		$( "#loading" ).css( 'position', 'absolute' ).fadeOut();
		$( "#contactsInZino, #foot" ).fadeIn();
		
		$( "#foot input" ).one( 'click', contacts.addFriends );
	},
    addContactNotZino: function( mail, nickname ){
        div = document.createElement( "div" );
        var text = "<input type='checkbox' checked='checked' />";
        if ( mail != nickname ){
            text += "<div class='contactNickname'>" + nickname + "</div>";
            text += "<div class='contactMail'>" + mail + "</div>";
        }
        else{
            text += "<div style='margin-top: 8px' class='contactMail'>" + mail + "</div>";
        }
        $( div ).addClass( "contact" ).html( text ).appendTo( '#contactsNotZino .contacts' );
    },
    previwContactsNotInZino: function(){
        document.title = "Πρόσκληση φίλων | Zino";
        contacts.step = 3;
		$( "#foot input" ).removeClass().addClass( "invite" );
		$( "#contactsInZino, #loading" ).fadeOut();
		$( "#body" ).animate({
			"height": 420,
			"marginLeft": 80,
			"width": 570
			}, 1000, function(){
				$( "#contactsNotZino, #foot" ).fadeIn();
		});
        $( "#foot input" ).one( 'click', contacts.invite );
	},
    addFriends: function(){
    var ids = new Array;
        $( "#contactsInZino .contact input:checked" ).parent().parent().each( function( i ){
            ids.push( $( this ).attr( "id" ) );
        });
        idsString = ids.join( " " );
        /*if ( !confirm( "The following users will be added as friends\n" + idsString ) ){
            return 0;
        }*/
        Coala.Warm( "contacts/addfriends", {
            "ids": idsString
        });
    },
    invite: function(){
    var mails = new Array;
        $( "#contactsNotZino .contact input:checked" ).siblings( ".contactMail" ).each( function( i ){
            mails.push( $( this ).html() );
        });
        mailsString = mails.join( " " );
        /*if ( !confirm( "Invitations will be send to:\n" + mailsString ) ){
            return 0;
        }*/
        Coala.Warm( "contacts/invite", {
            "mails": mailsString
        });
    },
    calcCheckboxes: function( step ){
        if ( step == 2 ){
            if ( $( "#contactsInZino input:checked" ).size() ){
                $( "#foot input" ).removeClass().addClass( "add" );
            }
            else{
                $( "#foot input" ).removeClass();
            }
        }
        else{ //if step == 3
            if ( $( "#contactsNotZino input:checked" ).size() ){
                $( "#foot input" ).removeClass().addClass( "invite" );
            }
            else{
                $( "#foot input" ).removeClass().addClass( "finish" );
            }
        }
    },
	init: function(){
		$( "#foot input" ).one( 'click', contacts.retrieve );
		//left tabs clickable
		$('#left_tabs li').click( function(){
			$('#left_tabs li').removeClass();
			$( this ).addClass( 'selected' );
		});
		//checkboxes
		$( ".step .contact input" ).attr( "checked", "checked" );
		
		$( ".step .selectAll .all" ).click( function(){
			$( this ).parent().siblings( '.contacts' ).find( 'input' ).attr( "checked", "checked" ).each(function(){
                this.checked=true;
            });
            contacts.calcCheckboxes( contacts.step );
		});
		$( ".step .selectAll .none" ).click( function(){
			$( this ).parent().siblings( '.contacts' ).find( 'input' ).attr( "checked", "" ).each(function(){
                this.checked=false;
            });
            contacts.calcCheckboxes( contacts.step );
		});
	}
};
$( function(){
    contacts.init();
});
var Search = {
    check : function() {
        var check = false;
        if ( !$( 'div.ybubble div.body form div.search input' )[2].checked ) {
            return true;
        }
        $( 'div.ybubble div.body form div.search select' ).each( function() {
                if ( this.selectedIndex !== 0 ) {
                    check = true;
                }
            } );
        if ( !check ) {
            alert( "Όρισε κάποιες επιλογές για να πραγματοποιήσεις αναζήτηση ατόμων." );
            return false;
        }
        return true;
    }
};
var Im = {
	ImOnLoad : function () {
		var email = $( 'div#im div.cred div.empwd div.mail input' )[ 0 ];
		var pwd = $( 'div#im div.cred div.empwd div.pwd input' )[ 0 ];
		var mailerror = false;
		var pwderror = false;
		email.focus();
		email.select();
		$( email ).keyup( function( event ) {
			if ( event.keyCode == 13 ) {
				if ( !mailerror ) {
					$( 'div#im div.cred div.empwd div.mail select' )[ 0 ].focus();
				}
			}
			else {
				if ( mailerror ) {
					$( 'div#nullmail' ).fadeOut( 400 );
					mailerror = false;
				}
			}
		} );
		$( pwd ).keyup( function( event ) {
			if ( event.keyCode == 13 ) {
				if ( !pwderror ) {
					$( 'div#im div.cred div.next a' )[ 0 ].focus();
				}
			}
			else {
				if ( pwderror ) {
					$( 'div#nullpwd' ).fadeOut( 400 );
					pwderror = false;
				}
			}
		} );
		$( 'div#im div.cred div.next a' ).click( function() {
			if ( email.value && pwd.value ) {
				$( 'div#im div.cred div.wrong div.w' ).fadeIn( 400 );
				var emailaddr = email.value + '@' + $( 'div#im div.cred div.empwd div.mail select' )[ 0 ].value;
				alert( 'email is: ' + emailaddr );
				alert( 'password is: ' + pwd.value );
			}
			else {
				if ( !email.value ) {
					$( 'div#nullmail' ).fadeIn( 400 );
					email.focus();
					mailerror = true;
				}
				else {
					$( 'div#nullpwd' ).fadeIn( 400 );
					pwd.focus();
					pwderror = true;
				}
			}
			return false;
		} );
	}
};var School = {
    OnLoad : function() {
        $( '#schview div.photos div.plist ul li a.s_bigadd' ).click( function() {
            var modal = $( '#schooluploadmodal' )[ 0 ].cloneNode( true );
            $( modal ).show();
            $( modal ).find( 'a.close' ).click( function() {
                Modals.Destroy();
                return false;
            } );
            Modals.Create( modal , 350 , 250 );
            return false;
        } );
    }
}
/*
stream: xhrinteractive, iframe, serversent
longpoll
smartpoll
simplepoll
*/

var Meteor = {

	callbacks: {
		process: function() {},
		reset: function() {},
		eof: function() {},
		statuschanged: function() {},
		changemode: function() {}
	},
	channelcount: 0,
	channels: {},
	debugmode: false,
	frameref: null,
	host: null,
	hostid: null,
	maxpollfreq: 60000,
	minpollfreq: 2000,
	mode: "stream",
	pingtimeout: 20000,
	pingtimer: null,
	pollfreq: 3000,
	port: 80,
	polltimeout: 30000,
	recvtimes: [],
	status: 0,
	updatepollfreqtimer: null,

	register: function(ifr) {
		ifr.p = Meteor.process;
		ifr.r = Meteor.reset;
		ifr.eof = Meteor.eof;
		ifr.ch = Meteor.channelInfo;
		clearTimeout(Meteor.frameloadtimer);
		Meteor.setstatus(4);
		Meteor.log("Frame registered");
	},

	joinChannel: function(channelname, backtrack) {
		if (typeof(Meteor.channels[channelname]) != "undefined") { throw "Cannot join channel "+channelname+": already subscribed"; }
		Meteor.channels[channelname] = {backtrack:backtrack, lastmsgreceived:0};
		Meteor.log("Joined channel "+channelname);
		Meteor.channelcount++;
		if (Meteor.status !== 0) { Meteor.connect(); }
	},

	leaveChannel: function(channelname) {
		if (typeof(Meteor.channels[channelname]) == "undefined") { throw "Cannot leave channel " + channelname + ": not subscribed"; }
		delete Meteor.channels[channelname];
		Meteor.log("Left channel "+channelname);
		if (Meteor.status !== 0) {Meteor.connect(); }
		Meteor.channelcount--;
	},

	connect: function() {
		Meteor.log("Connecting");
		if (!Meteor.host) { throw "Meteor host not specified"; }
		if (isNaN(Meteor.port)) { throw "Meteor port not specified"; }
		if (!Meteor.channelcount) { throw "No channels specified"; }
		if (Meteor.status) { Meteor.disconnect(); }
		Meteor.setstatus(1);
		var now = new Date();
		var t = now.getTime();
		if (!Meteor.hostid) { Meteor.hostid = t+""+Math.floor(Math.random()*1000000); }
		document.domain = Meteor.extract_xss_domain(document.domain);
		if (Meteor.mode=="stream") { Meteor.mode = Meteor.selectStreamTransport(); }
		Meteor.log("Selected "+Meteor.mode+" transport");
		if (Meteor.mode=="xhrinteractive" || Meteor.mode=="iframe" || Meteor.mode=="serversent") {
			if (Meteor.mode == "iframe") {
				Meteor.loadFrame(Meteor.getSubsUrl());
			} else {
				Meteor.loadFrame("http://"+Meteor.host+((Meteor.port==80)?"":":"+Meteor.port)+"/stream.html");
			}
			clearTimeout(Meteor.pingtimer);
			Meteor.pingtimer = setTimeout(Meteor.pollmode, Meteor.pingtimeout);

		} else {
			Meteor.loadFrame("http://"+Meteor.host+((Meteor.port==80)?"":":"+Meteor.port)+"/poll.html");
			Meteor.recvtimes[0] = t;
			if (Meteor.updatepollfreqtimer) { clearTimeout(Meteor.updatepollfreqtimer); }
			if (Meteor.mode=='smartpoll') { Meteor.updatepollfreqtimer = setInterval(Meteor.updatepollfreq, 2500); }
			if (Meteor.mode=='longpoll') { Meteor.pollfreq = Meteor.minpollfreq; }
		}
		Meteor.lastrequest = t;
	},

	disconnect: function() {
		if (Meteor.status) {
			clearTimeout(Meteor.pingtimer);
			clearTimeout(Meteor.updatepollfreqtimer);
			clearTimeout(Meteor.frameloadtimer);
			if (typeof CollectGarbage == 'function') { CollectGarbage(); }
			if (Meteor.status != 6) { Meteor.setstatus(0); }
			Meteor.log("Disconnected");
		}
	},
	
	selectStreamTransport: function() {
		try {
			var test = ActiveXObject;
			return "iframe";
		} catch (e) {}
		if ((typeof window.addEventStream) == "function") { return "iframe"; }
		return "xhrinteractive";
	},

	getSubsUrl: function() {
		var surl = "http://" + Meteor.host + ((Meteor.port==80)?"":":"+Meteor.port) + "/push/" + Meteor.hostid + "/" + Meteor.mode;
		for (var c in Meteor.channels) {
			surl += "/"+encodeURIComponent(c);
			if (Meteor.channels[c].lastmsgreceived > 0) {
				surl += ".r"+(Meteor.channels[c].lastmsgreceived+1);
			} else if (Meteor.channels[c].backtrack > 0) {
				surl += ".b"+Meteor.channels[c].backtrack;
			} else if (Meteor.channels[c].backtrack < 0 || isNaN(Meteor.channels[c].backtrack)) {
				surl += ".h";
			}
		}
		var now = new Date();
		surl += "?nc="+now.getTime();
		return surl;
	},

	loadFrame: function(url) {
		try {
			if (!Meteor.frameref) {
				var transferDoc = new ActiveXObject("htmlfile");
				Meteor.frameref = transferDoc;
			}
			Meteor.frameref.open();
			Meteor.frameref.write("<html><script>");
			Meteor.frameref.write("document.domain=\""+(document.domain)+"\";");
			Meteor.frameref.write("</"+"script></html>");
			Meteor.frameref.parentWindow.Meteor = Meteor;
			Meteor.frameref.close();
			var ifrDiv = Meteor.frameref.createElement("div");
			Meteor.frameref.appendChild(ifrDiv);
			ifrDiv.innerHTML = "<iframe src=\""+url+"\"></iframe>";
		} catch (e) {
			if (!Meteor.frameref) {
				var ifr = document.createElement("iframe");
				ifr.style.width = "10px";
				ifr.style.height = "10px";
				ifr.style.border = "none";
				ifr.style.position = "absolute";
				ifr.style.top = "-10px";
				ifr.style.marginTop = "-10px";
				ifr.style.zIndex = "-20";
				ifr.Meteor = Meteor;
				document.body.appendChild(ifr);
				Meteor.frameref = ifr;
			}
			Meteor.frameref.setAttribute("src", url);
		}
		Meteor.log("Loading URL '"+url+"' into frame...");
		Meteor.frameloadtimer = setTimeout(Meteor.frameloadtimeout, 5000);
	},

	pollmode: function() {
		Meteor.log("Ping timeout");
        /*
		Meteor.mode="smartpoll";
		clearTimeout(Meteor.pingtimer);
		Meteor.callbacks.changemode("poll");
		Meteor.lastpingtime = false;
		Meteor.connect();
        */
	},

	process: function(id, channel, data) {
		if (id == -1) {
			Meteor.log("Ping");
			Meteor.ping();
		} else if (typeof(Meteor.channels[channel]) != "undefined") {
			Meteor.log("Message "+id+" received on channel "+channel+" (last id on channel: "+Meteor.channels[channel].lastmsgreceived+")\n"+data);
			Meteor.callbacks.process(data);
			Meteor.channels[channel].lastmsgreceived = id;
			if (Meteor.mode=="smartpoll") {
				var now = new Date();
				Meteor.recvtimes[Meteor.recvtimes.length] = now.getTime();
				while (Meteor.recvtimes.length > 5) { Meteor.recvtimes.shift(); }
			}
		}
		Meteor.setstatus(5);
	},

	ping: function() {
		if (Meteor.pingtimer) {
			clearTimeout(Meteor.pingtimer);
			Meteor.pingtimer = setTimeout(Meteor.pollmode, Meteor.pingtimeout);
			var now = new Date();
			Meteor.lastpingtime = now.getTime();
		}
		Meteor.setstatus(5);
	},

	reset: function() {
		if (Meteor.status != 6) {
			Meteor.log("Stream reset");
			Meteor.ping();
			Meteor.callbacks.reset();
			var now = new Date();
			var t = now.getTime();
			var x = Meteor.pollfreq - (t-Meteor.lastrequest);
			if (x < 10) { x = 10; }
			setTimeout(Meteor.connect, x);
		}
	},

	eof: function() {
		Meteor.log("Received end of stream, will not reconnect");
		Meteor.callbacks.eof();
		Meteor.setstatus(6);
		Meteor.disconnect();
	},

	channelInfo: function(channel, id) {
		Meteor.channels[channel].lastmsgreceived = id;
		Meteor.log("Received channel info for channel "+channel+": resume from "+id);
	},

	updatepollfreq: function() {
		var now = new Date();
		var t = now.getTime();
		var avg = 0;
		for (var i=1; i<Meteor.recvtimes.length; i++) {
			avg += (Meteor.recvtimes[i]-Meteor.recvtimes[i-1]);
		}
		avg += (t-Meteor.recvtimes[Meteor.recvtimes.length-1]);
		avg /= Meteor.recvtimes.length;
		var target = avg/2;
		if (target < Meteor.pollfreq && Meteor.pollfreq > Meteor.minpollfreq) { Meteor.pollfreq = Math.ceil(Meteor.pollfreq*0.9); }
		if (target > Meteor.pollfreq && Meteor.pollfreq < Meteor.maxpollfreq) { Meteor.pollfreq = Math.floor(Meteor.pollfreq*1.05); }
	},

	registerEventCallback: function(evt, funcRef) {
		Function.prototype.andThen=function(g) {
			var f=this;
			var a=Meteor.arguments;
			return function(args) {
				f(a);g(args);
			};
		};
		if (typeof Meteor.callbacks[evt] == "function") {
			Meteor.callbacks[evt] = (Meteor.callbacks[evt]).andThen(funcRef);
		} else {
			Meteor.callbacks[evt] = funcRef;
		}
	},

	frameloadtimeout: function() {
		Meteor.log("Frame load timeout");
		if (Meteor.frameloadtimer) { clearTimeout(Meteor.frameloadtimer); }
		Meteor.setstatus(3);
		Meteor.pollmode();
	},

	extract_xss_domain: function(old_domain) {
		if (old_domain.match(/^(\d{1,3}\.){3}\d{1,3}$/)) { return old_domain; }
		domain_pieces = old_domain.split('.');
		return domain_pieces.slice(-2, domain_pieces.length).join(".");
	},

	setstatus: function(newstatus) {
		// Statuses:	0 = Uninitialised,
		//				1 = Loading stream,
		//				2 = Loading controller frame,
		//				3 = Controller frame timeout, retrying.
		//				4 = Controller frame loaded and ready
		//				5 = Receiving data
		//				6 = End of stream, will not reconnect

		if (Meteor.status != newstatus) {
			Meteor.status = newstatus;
			Meteor.callbacks.statuschanged(newstatus);
		}
	},

	log: function(logstr) {
		if (Meteor.debugmode) {
			if (window.console) {
				window.console.log(logstr);
			} else if (document.getElementById("meteorlogoutput")) {
				document.getElementById("meteorlogoutput").innerHTML += logstr+"<br/>";
			}
		}
	}
};

var oldonunload = window.onunload;
if (typeof window.onunload != 'function') {
	window.onunload = Meteor.disconnect;
} else {
	window.onunload = function() {
		if (oldonunload) { oldonunload(); }
		Meteor.disconnect();
	};
}
var Comet = {
    Connected: false,
    ConnectionTimer: 0,
    Connect: function () {
        if ( Comet.Connected ) {
            return;
        }
        if ( Comet.ConnectionTimer !== 0 ) {
            clearTimeout( Comet.ConnectionTimer );
        }
        Comet.ConnectionTimer = setTimeout( function () {
            Comet.Connected = true;
            Meteor.connect();
        }, 100 );
    },
    Subscribe: function ( channel ) {
        Comet.Connect();
        if ( ExcaliburSettings.Production ) {
            channel = 'P' + channel;
        }
        else {
            channel = 'S' + channel;
        }
        Meteor.joinChannel( channel, 0 );
    },
    Process: function ( json ) {
        var obj = eval( json );
        var channel = obj.shift(); // unused
        var code = obj.shift();
        
        eval( code );
    },
    Init: function ( uniq ) {
        Meteor.hostid = uniq;
        Meteor.host = "universe." + location.hostname;
        Meteor.registerEventCallback( "process", Comet.Process );
        Meteor.registerEventCallback( 'pollmode', Comet.ChangeMode );
        Meteor.mode = 'stream';
    },
    ChangeMode: function ( mode ) {
        if ( mode == 'poll' ) { // don't allow polling
            Meteor.disconnect();
        }
    }
};
