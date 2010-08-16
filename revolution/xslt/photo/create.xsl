<xsl:template match="/social[@resource='photo' and @method='create']">
    <xsl:choose>
        <xsl:when test="error and error/@type = 'wrongextension'">
            Αυτός ο τύπος εικόνας δεν υποστηρίζεται
        </xsl:when>
        
        <xsl:when test="error and error/@type = 'largefile'">
            H φωτογραφία σου δεν πρέπει να ξεπερνάει τα 4MB
        </xsl:when>
        
        <xsl:when test="error and error/@type = 'fileupload'">
            Παρουσιάστηκε πρόβλημα κατά τη μεταφορά της εικόνας
        </xsl:when>
        
        <xsl:when test="/social/photo/@id">
            <script type="text/javascript">
                window.location.href = 'photos/<xsl:value-of select="/social/photo/@id" />';
            </script>
        </xsl:when>
    </xsl:choose>
</xsl:template>

