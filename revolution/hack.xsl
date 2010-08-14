<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:template match="/social">
    <html>
        <head>
            <base href="http://zino.gr/" />
            <link type="text/css" href="global.css" rel="stylesheet" />
            <link type="text/css" href="http://static.zino.gr/css/emoticons.css" rel="stylesheet" />
            <link type="text/css" href="http://static.zino.gr/css/spriting/sprite1.css" rel="stylesheet" />
            <meta http-equiv="X-UA-Compatible" content="IE=8,chrome=1" />
            <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
			<script type="text/javascript"><xsl:attribute name="src"><xsl:value-of select="/social[1]/@generator" />/global.js</xsl:attribute></script>
            <script src="http://static.zino.gr/revolution/global.xsl.js" type="text/javascript"></script>
            <script type="text/javascript">
                <xsl:if test="/social/@for">
                    var User = '<xsl:value-of select="/social/@for" />';
                </xsl:if>
                var Now = '<xsl:value-of select="/social/@generated" />';
                var NowDate = stringToDate( Now );
                setInterval( function(){
                    NowDate.setSeconds( NowDate.getSeconds() + 60 );
                    Now = dateToString( NowDate );
                }, 60000 );
                
                var XMLData = {
                    author: '<xsl:value-of select="/social/*/author/name" />'
                }

                _aXSLT.defaultStylesheet = 'global.xsl';
                _aXSLT.lastListIndex = 2;
                _aXSLT.unitLists[ 1 ] = [];
                var magic = {
                    responseText: XSL,
                    readyState: 4
                }
                _aXSLT.xslCache[ 'global.xsl' ] = { xhr: magic, index: 1 };
                axslt( $.get( window.location ), '', function() {
                    $( document.body ).append( $( this ).find( 'body > *' ) );
                    $( document.head ).append( $( this ).find( 'title' ) );
                } );
            </script>
        </head>
        <body>
        </body>
    </html>
</xsl:template>
</xsl:stylesheet>
