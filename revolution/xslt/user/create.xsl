<xsl:template match="/social[@resource='user' and @method='create']">
    <script type="text/javascript">
        <xsl:choose>
            <xsl:when test="operation/user">
                Kamibu.Go( 'users/<xsl:value-of select="operation/user/subdomain" />' );
            </xsl:when>
            <xsl:otherwise>
                alert( 'Το όνομα χρήστη που πληκτρολόγησες υπάρχει ήδη' );
                Kamibu.Go( 'login' );
            </xsl:otherwise>
        </xsl:choose>
    </script>
</xsl:template>
