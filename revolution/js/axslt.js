/**
 * axslt.js
 *
 * Copyright (c) 2010 Tzortzidis Alexandros ( chorvus@gmail.com )
 * <http://chorvus.com>
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a
 * copy of this software and associated documentation files (the "Software"),
 * to deal in the Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included
 * in all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
 * OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN
 * NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
 * DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR
 * OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE
 * USE OR OTHER DEALINGS IN THE SOFTWARE.
 * 
 */

XMLHttpRequest.prototype.transform = function( template, callbackfn, xslPath, templateMode ) {
    // Compact parameter mode.
    // Example call: xhr.transform( { 'name': templateName, 'mode': templateMode }, callback );
    if ( template instanceof Array ) {
        templateName = template['name'];//TODO
        if ( template[ 'type' ] == 'call' ) {
            templateMode == 'call';
        }
        else if ( template[ 'type' ] == 'apply' ) {
            templateMode == 'apply';
        }
        else {
            templateMode == _aXSLT.defaultMode;
        }
    }
    // Normal parameter mode.
    // Example call: xhr.transform( templateName, callback, templateMode );
    else {
        templateName = template;//TODO
        if ( templateMode != 'call' ) templateMode = 'apply'
    }
    _aXSLT.init();
    this._aXSLTCallback = callbackfn;
    this._aXSLTtemplateMode = templateMode;
    this._aXSLTtemplateName = templateName;
    if ( !xslPath ) {
        xslPath = _aXSLT.defaultStylesheet;
    }
    var stylesheet = _aXSLT.getXSL( _aXSLT.defaultStylesheet );
    _aXSLT.attachAsyncTransform( this, stylesheet );
}

var _aXSLT = {
    initialized: false,
    defaultStylesheet: 'global.xsl',
    defaultMode: 'apply',
    init: function() {
        if ( this.initialized ) {
            return;
        }
        this.initialized = true;
        if ( this.defaultStylesheet ) {
            this.getXSL( this.defaultStylesheet );
        }
    },
    stylesheets: {},
    lastIndex: 0,
    getXSL: function( path ) {
        if ( this.stylesheets[ path ] ) {
            return this.stylesheets[ path ];
        }
        var stylesheet = {};
        if ( window.XMLHttpRequest ) {
            stylesheet.xhr = new XMLHttpRequest();
        }
        else {
            //TODO
        }
        stylesheet.async = [];
        
        var iterateXML = function() {
            if ( stylesheet.xhr.readyState != 4 ) {
                return;
            }
            for ( var i in stylesheet.async ) {
                var xml = stylesheet.async[ i ];
                if ( xml.readyState == 4 ) {
                    _aXSLT.transform( xml , stylesheet, xml._aXSLTCallback, xml._aXSLTtemplateName, xml._aXSLTtemplateMode );
                }
            }
        }
        
        if ( document.addEventListener ) {
            stylesheet.xhr.addEventListener( 'readystatechange', iterateXML, false );
        }
        else if ( document.attachEvent ) {
            stylesheet.xhr.attachEvent( 'onreadystatechange', iterateXML );
        }
        stylesheet.xhr.open( 'GET', path, false );
        stylesheet.xhr.send();
        this.stylesheets[ path ] = stylesheet;
        return this.stylesheets[ path ];
    },
    attachAsyncTransform: function( xml, xsl ) {
        if ( xml.readyState != 4 && xsl.xhr.readyState != 4 ) {
            //alert('preemptive');
            this.transform( xml, xsl, xml._aXSLTCallback, xml._aXSLTtemplateName, xml._aXSLTtemplateMode );
            return;
        }
        var event = ( function( xml, xsl, callback ) {
            return function() {
                xsl.async.splice( xml._aXSLTindex, 1 );
                _aXSLT.transform( xml, xsl.xhr, xml._aXSLTCallback, xml._aXSLTtemplateName, xml._aXSLTtemplateMode );
            } } )( xml, xsl, xml._aXSLTCallback );
        if ( document.addEventListener ) {
            xml.addEventListener( 'readystatechange', event, false );
        }
        else if ( document.attachEvent ) {
            xml.attachEvent( 'readystatechange', event );
        }
        if ( !xml._aXSLTindex ) {
            xml._aXSLTindex = this.lastIndex;
            xsl.async[ this.lastIndex ] =  xml;
            this.lastIndex++;
            for ( var i = 0; i < xsl.async.length; ++i ) {
                //alert( 'pre index:' + xml._aXSLTindex + ' ' +xsl.async[ xml._aXSLTindex ] );
                xsl.async.splice( xml._aXSLTindex, 1 );
                //alert( 'after index:' + xml._aXSLTindex + ' ' +xsl.async[ xml._aXSLTindex ] );
                _aXSLT.transform( xml, xsl.xhr, xml._aXSLTCallback, xml._aXSLTtemplateName, xml._aXSLTtemplateMode );
            }
        }
        else {
            xsl.async[ xml._aXSLTindex ] = xml;
        }
    },
    addTemplate: function( basicStylesheet, templateName, templateMode ) {
        if ( !templateName || templateName == '/' ) {
            return basicStylesheet;
        }
        var templateString =
        '<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">' +
            '<xsl:template match="/" priority="500000">' +
                ( templateMode == 'call' ?
                    '<xsl:call-template name="' + templateName + '" />'
                :
                    '<xsl:apply-templates select="' + templateName + '" />'
                ) +
            '</xsl:template>'+
        '</xsl:stylesheet>';
    
        var templateDOM = new DOMParser().parseFromString( templateString, 'text/xml' ).childNodes[0].childNodes[0];
        basicStylesheet.childNodes[0].appendChild( basicStylesheet.importNode( templateDOM, true ) );
        return basicStylesheet;
    },
    transform: function( xml, xsl, callback, templateName, templateMode ) {
        if ( xml.readyState != 4 || xsl.readyState != 4 ) {
            return false;
        }
        var result;
        var processor;
        var stylesheet;
        //alert( new XMLSerializer().serializeToString( xml.responseXML ) );
        stylesheet = _aXSLT.addTemplate( xsl.responseXML, templateName, templateMode );
        if ( !stylesheet ) {
            console.warn( 'aXSLT: Error in master template transmutation' );
            return;
        }
        //alert( new XMLSerializer().serializeToString( stylesheet ) );
        if ( window.ActiveXObject ) {
            
        }
        else if ( window.XSLTProcessor ) {
            processor = new XSLTProcessor();
            processor.importStylesheet( stylesheet );
            result = processor.transformToFragment( xml.responseXML, document);
        }
        if ( typeof( result ) == 'undefined' ) {
            console.warn( 'aXSLT: Null transformed document' );
            return;
        }
        
        callback.call( result.childNodes );
    }
}