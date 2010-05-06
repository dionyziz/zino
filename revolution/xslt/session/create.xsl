<xsl:template match="/social[@resource='session' and @method='create']">
    <xsl:choose>
        <xsl:when test="operation/result/text() = 'SUCCESS'">
            Γίνεται είσοδος, παρακαλώ περιμένετε!
            <script type="text/javascript">
                window.location = '<xsl:value-of select="/*[0]/@generator" />';
            </script>
        </xsl:when>
        <xsl:otherwise>
            Λάθος κωδικός, δοκίμασε ξανά!
        </xsl:otherwise>
    </xsl:choose>
</xsl:template>
