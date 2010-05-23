<xsl:template match="/social[@resource='photo' and @method='create']">
    <script type="text/javascript">
        window.location.href = 'photos/' + <xsl:value-of select="/social/entry/@id" />;
    </script>
</xsl:template>
