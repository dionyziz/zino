<xsl:template match="/social[@resource='session' and @method='create']">
    <xsl:choose>
        <xsl:when test="operation/result/text() = 'SUCCESS'">
            <meta http-equiv="refresh">
                <xsl:attribute name="content">
                    0;url=<xsl:value-of select="/*[1]/@generator" />
                </xsl:attribute>
            </meta>
            Γίνεται είσοδος, παρακαλώ περιμένετε!
        </xsl:when>
        <xsl:otherwise>
            Λάθος κωδικός, δοκίμασε ξανά!
        </xsl:otherwise>
    </xsl:choose>
</xsl:template>