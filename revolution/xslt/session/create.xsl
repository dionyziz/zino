<xsl:template match="/social[@resource='session' and @method='create']">
    <html>
        <head>
            <base>
                <xsl:attribute name="href"><xsl:value-of select="@generator" />/</xsl:attribute>
            </base>
            <script type="text/javascript">
                    <xsl:if test="operation/result = 'SUCCESS'">
                       <xsl:text>parent.loginresult( true );</xsl:text>
                    </xsl:if>
                    <xsl:if test="operation/result = 'FAIL'">
                        <xsl:text>parent.loginresult( false );</xsl:text>
                        <xsl:text>window.location.href = 'login.html';</xsl:text>
                    </xsl:if>
            </script>
        </head>
        <body></body>
    </html>
</xsl:template>
