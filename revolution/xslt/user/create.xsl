<xsl:template match="/social[@resource='user' and @method='create']">
    <script type="text/javascript">
        <xsl:choose>
            <xsl:when test="operation/user">
                location.href = 'users/<xsl:value-of select="operation/user/subdomain" />';
            </xsl:when>
            <xsl:otherwise>
                alert( 'Το όνομα χρήστη που πληκτρολόγησες υπάρχει ήδη' );
                location.href = 'login';
            </xsl:otherwise>
        </xsl:choose>
    </script>
</xsl:template>
