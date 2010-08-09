<xsl:template match="/social[@resource='session' and @method='create']">
    <html>
        <head>
            <base>
                <xsl:attribute name="href"><xsl:value-of select="@generator" />/</xsl:attribute>
            </base>
            <script type="text/javascript" src="js/kamibu.js"></script>
            <script type="text/javascript">
                if ( window.parent.location == window.location ) {
                    <xsl:if test="operation/result = 'SUCCESS'">
                        Kamibu.Go( 'photos' );
                    </xsl:if>
                    <xsl:if test="operation/result = 'FAIL'">
                        alert( 'Λάθος όνομα/κωδικός πρόσβασης' );
                        Kamibu.Go( 'login' );
                    </xsl:if>
                }
                else {
                    <xsl:if test="operation/result = 'SUCCESS'">
                        parent.loginresult( true );
                    </xsl:if>
                    <xsl:if test="operation/result = 'FAIL'">
                        parent.loginresult( false );
                        Kamibu.Go( 'login.html' );
                    </xsl:if>
                }
            </script>
        </head>
        <body></body>
    </html>
</xsl:template>
