// timestamp: Mon, 04 Feb 2008 17:10:24
/*
  IE7/IE8.js - copyright 2004-2008, Dean Edwards
  http://dean.edwards.name/IE7/
  http://www.opensource.org/licenses/mit-license.php
*/

/* W3C compliance for Microsoft Internet Explorer */

/* credits/thanks:
  Shaggy, Martijn Wargers, Jimmy Cerra, Mark D Anderson,
  Lars Dieckow, Erik Arvidsson, Gellért Gyuris, James Denny,
  Unknown W Brackets, Benjamin Westfarer, Rob Eberhardt,
  Bill Edney, Kevin Newman, James Crompton, Matthew Mastracci,
  Doug Wright, Richard York, Kenneth Kolano, MegaZone,
  Thomas Verelst, Mark 'Tarquin' Wilton-Jones, Rainer Åhlfors,
  David Zulaica, Ken Kolano, Kevin Newman
*/

// =======================================================================
// TO DO
// =======================================================================

// PNG - unclickable content

// =======================================================================
// TEST/BUGGY
// =======================================================================

// hr{margin:1em auto} (doesn't look right in IE5)

(function() {


IE7 = {
  toString: function(){return "IE7 version 2.0 (beta4)"}
};
var appVersion = IE7.appVersion = navigator.appVersion.match(/MSIE (\d\.\d)/)[1];

if (/ie7_off/.test(top.location.search) || appVersion < 5) return;

var Undefined = K();
var quirksMode = document.compatMode != "CSS1Compat";
var documentElement = document.documentElement, body, viewport;
var ANON = "!";
var HEADER = ":link{ie7-link:link}:visited{ie7-link:visited}";

// -----------------------------------------------------------------------
// external
// -----------------------------------------------------------------------

var RELATIVE = /^[\w\.]+[^:]*$/;
function makePath(href, path) {
  if (RELATIVE.test(href)) href = (path || "") + href;
  return href;
};

function getPath(href, path) {
  href = makePath(href, path);
  return href.slice(0, href.lastIndexOf("/") + 1);
};

// get the path to this script
var script = document.scripts[document.scripts.length - 1];
var path = getPath(script.src);

// we'll use microsoft's http request object to load external files
try {
  var httpRequest = new ActiveXObject("Microsoft.XMLHTTP");
} catch (e) {
  // ActiveX disabled
}

var fileCache = {};
function loadFile(href, path) {
try {
  href = makePath(href, path);
  if (!fileCache[href]) {
    // easy to load a file huh?
    httpRequest.open("GET", href, false);
    httpRequest.send();
    if (httpRequest.status == 0 || httpRequest.status == 200) {
      fileCache[href] = httpRequest.responseText;
    }
  }
} catch (e) {
  // ignore errors
} finally {
  return fileCache[href] || "";
}};

// -----------------------------------------------------------------------
// IE5.0 compatibility
// -----------------------------------------------------------------------


if (appVersion < 5.5) {
  undefined = Undefined();

  ANON = "HTML:!"; // for anonymous content
  
  // Fix String.replace (Safari1.x/IE5.0).
  var GLOBAL = /(g|gi)$/;
  var _String_replace = String.prototype.replace; 
  String.prototype.replace = function(expression, replacement) {
    if (typeof replacement == "function") { // Safari doesn't like functions
      if (expression && expression.constructor == RegExp) {
        var regexp = expression;
        var global = regexp.global;
        if (global == null) global = GLOBAL.test(regexp);
        // we have to convert global RexpExps for exec() to work consistently
        if (global) regexp = new RegExp(regexp.source); // non-global
      } else {
        regexp = new RegExp(rescape(expression));
      }
      var match, string = this, result = "";
      while (string && (match = regexp.exec(string))) {
        result += string.slice(0, match.index) + replacement.apply(this, match);
        string = string.slice(match.index + match[0].length);
        if (!global) break;
      }
      return result + string;
    }
    return _String_replace.apply(this, arguments);
  };
  
  Array.prototype.pop = function() {
    if (this.length) {
      var i = this[this.length - 1];
      this.length--;
      return i;
    }
    return undefined;
  };
  
  Array.prototype.push = function() {
    for (var i = 0; i < arguments.length; i++) {
      this[this.length] = arguments[i];
    }
    return this.length;
  };
  
  var ns = this;
  Function.prototype.apply = function(o, a) {
    if (o === undefined) o = ns;
    else if (o == null) o = window;
    else if (typeof o == "string") o = new String(o);
    else if (typeof o == "number") o = new Number(o);
    else if (typeof o == "boolean") o = new Boolean(o);
    if (arguments.length == 1) a = [];
    else if (a[0] && a[0].writeln) a[0] = a[0].documentElement.document || a[0];
    var $ = "#ie7_apply", r;
    o[$] = this;
    switch (a.length) { // unroll for speed
      case 0: r = o[$](); break;
      case 1: r = o[$](a[0]); break;
      case 2: r = o[$](a[0],a[1]); break;
      case 3: r = o[$](a[0],a[1],a[2]); break;
      case 4: r = o[$](a[0],a[1],a[2],a[3]); break;
      case 5: r = o[$](a[0],a[1],a[2],a[3],a[4]); break;
      default:
        var b = [], i = a.length - 1;
        do b[i] = "a[" + i + "]"; while (i--);
        eval("r=o[$](" + b + ")");
    }
    if (typeof o.valueOf == "function") { // not a COM object
      delete o[$];
    } else {
      o[$] = undefined;
      if (r && r.writeln) r = r.documentElement.document || r;
    }
    return r;
  };
  
  Function.prototype.call = function(o) {
    return this.apply(o, _slice.apply(arguments, [1]));
  };

  // block elements are "inline" according to IE5.0 so we'll fix it
  HEADER += "address,blockquote,body,dd,div,dt,fieldset,form,"+
    "frame,frameset,h1,h2,h3,h4,h5,h6,iframe,noframes,object,p,"+
    "hr,applet,center,dir,menu,pre,dl,li,ol,ul{display:block}";
}

// -----------------------------------------------------------------------
// OO support
// -----------------------------------------------------------------------


// This is a cut-down version of base2 (http://code.google.com/p/base2/)

var _slice = Array.prototype.slice;

// private
var _FORMAT = /%([1-9])/g;
var _LTRIM = /^\s\s*/;
var _RTRIM = /\s\s*$/;
var _RESCAPE = /([\/()[\]{}|*+-.,^$?\\])/g;           // safe regular expressions
var _BASE = /\bbase\b/;
var _HIDDEN = ["constructor", "toString"];            // only override these when prototyping

var prototyping;

function Base(){};
Base.extend = function(_instance, _static) {
  // Build the prototype.
  prototyping = true;
  var _prototype = new this;
  extend(_prototype, _instance);
  prototyping = false;

  // Create the wrapper for the constructor function.
  var _constructor = _prototype.constructor;
  function klass() {
    // Don't call the constructor function when prototyping.
    if (!prototyping) _constructor.apply(this, arguments);
  };
  _prototype.constructor = klass;

  // Build the static interface.
  klass.extend = arguments.callee;
  extend(klass, _static);
  klass.prototype = _prototype;
  return klass;
};
Base.prototype.extend = function(source) {
  return extend(this, source);
};

// A collection of regular expressions and their associated replacement values.
// A Base class for creating parsers.

var _HASH   = "#";
var _KEYS   = "~";

var _RG_ESCAPE_CHARS    = /\\./g;
var _RG_ESCAPE_BRACKETS = /\(\?[:=!]|\[[^\]]+\]/g;
var _RG_BRACKETS        = /\(/g;

var RegGrp = Base.extend({
  constructor: function(values) {
    this[_KEYS] = [];
    this.merge(values);
  },

  exec: function(string) {
    var items = this, keys = this[_KEYS];    
    return String(string).replace(new RegExp(this, this.ignoreCase ? "gi" : "g"), function() {
      var item, offset = 1, i = 0;
      // Loop through the RegGrp items.
      while ((item = items[_HASH + keys[i++]])) {
        var next = offset + item.length + 1;
        if (arguments[offset]) { // do we have a result?
          var replacement = item.replacement;
          switch (typeof replacement) {
            case "function":
              return replacement.apply(items, _slice.call(arguments, offset, next));
            case "number":
              return arguments[offset + replacement];
            default:
              return replacement;
          }
        }
        offset = next;
      }
    });
  },

  add: function(expression, replacement) {
    if (expression instanceof RegExp) {
      expression = expression.source;
    }
    if (!this[_HASH + expression]) this[_KEYS].push(String(expression));
    this[_HASH + expression] = new RegGrp.Item(expression, replacement);
  },

  merge: function(values) {
    for (var i in values) this.add(i, values[i]);
  },

  toString: function() {
    // back references not supported in simple RegGrp
    return "(" + this[_KEYS].join(")|(") + ")";
  }
}, {
  IGNORE: "$0",

  Item: Base.extend({
    constructor: function(expression, replacement) {
      expression = expression instanceof RegExp ? expression.source : String(expression);

      if (typeof replacement == "number") replacement = String(replacement);
      else if (replacement == null) replacement = "";

      // does the pattern use sub-expressions?
      if (typeof replacement == "string" && /\$(\d+)/.test(replacement)) {
        // a simple lookup? (e.g. "$2")
        if (/^\$\d+$/.test(replacement)) {
          // store the index (used for fast retrieval of matched strings)
          replacement = parseInt(replacement.slice(1));
        } else { // a complicated lookup (e.g. "Hello $2 $1")
          // build a function to do the lookup
          var Q = /'/.test(replacement.replace(/\\./g, "")) ? '"' : "'";
          replacement = replacement.replace(/\n/g, "\\n").replace(/\r/g, "\\r").replace(/\$(\d+)/g, Q +
            "+(arguments[$1]||" + Q+Q + ")+" + Q);
          replacement = new Function("return " + Q + replacement.replace(/(['"])\1\+(.*)\+\1\1$/, "$1") + Q);
        }
      }

      this.length = RegGrp.count(expression);
      this.replacement = replacement;
      this.toString = K(expression);
    }
  }),

  count: function(expression) {
    // Count the number of sub-expressions in a RegExp/RegGrp.Item.
    expression = String(expression).replace(_RG_ESCAPE_CHARS, "").replace(_RG_ESCAPE_BRACKETS, "");
    return match(expression, _RG_BRACKETS).length;
  }
});

// =========================================================================
// lang/extend.js
// =========================================================================

function extend(object, source) { // or extend(object, key, value)
  if (object && source) {
    var proto = (typeof source == "function" ? Function : Object).prototype;
    // Add constructor, toString etc
    var i = _HIDDEN.length, key;
    if (prototyping) while (key = _HIDDEN[--i]) {
      var value = source[key];
      if (value != proto[key]) {
        if (_BASE.test(value)) {
          _override(object, key, value)
        } else {
          object[key] = value;
        }
      }
    }
    // Copy each of the source object's properties to the target object.
    for (key in source) if (proto[key] === undefined) {
      var value = source[key];
      // Check for method overriding.
      if (object[key] && typeof value == "function" && _BASE.test(value)) {
        _override(object, key, value);
      } else {
        object[key] = value;
      }
    }
  }
  return object;
};

function _override(object, name, method) {
  // Override an existing method.
  var ancestor = object[name];
  object[name] = function() {
    var previous = this.base;
    this.base = ancestor;
    var returnValue = method.apply(this, arguments);
    this.base = previous;
    return returnValue;
  };
};

function combine(keys, values) {
  // Combine two arrays to make a hash.
  if (!values) values = keys;
  var hash = {};
  for (var i in keys) hash[i] = values[i];
  return hash;
};

function format(string) {
  // Replace %n with arguments[n].
  // e.g. format("%1 %2%3 %2a %1%3", "she", "se", "lls");
  // ==> "she sells sea shells"
  // Only %1 - %9 supported.
  var args = arguments;
  var _FORMAT = new RegExp("%([1-" + arguments.length + "])", "g");
  return String(string).replace(_FORMAT, function(match, index) {
    return index < args.length ? args[index] : match;
  });
};

function match(string, expression) {
  // Same as String.match() except that this function will return an empty
  // array if there is no match.
  return String(string).match(expression) || [];
};

function rescape(string) {
  // Make a string safe for creating a RegExp.
  return String(string).replace(_RESCAPE, "\\$1");
};

// http://blog.stevenlevithan.com/archives/faster-trim-javascript
function trim(string) {
  return String(string).replace(_LTRIM, "").replace(_RTRIM, "");
};

function K(k) {
  return function() {
    return k;
  };
};

})();
