<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:template match="/social">
    <html>
        <head>
            <title>Proof of Concept</title>
        </head>
        <body>
            <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
            <script src="http://static.zino.gr/revolution/global.xsl.js" type="text/javascript"></script>
            <script src="http://www.zino.gr/global.js" type="text/javascript"></script>
            <script type="text/javascript">
                _aXSLT.defaultStylesheet = 'global.xsl';
                _aXSLT.lastListIndex = 2;
                _aXSLT.unitLists[ 1 ] = [];
                var magic = {
                    responseText: XSL,
                    readyState: 4
                }
                _aXSLT.xslCache[ 'global.xsl' ] = { xhr: magic, index: 1 };
                axslt( $.get( 'http://' + document.domain ), '', function() {
                    $( document.body ).append( $( this ).find( 'body > *' ) );
                } );
            </script>
        </body>
    </html>
</xsl:template>
</xsl:stylesheet>
