<xsl:template match="/social[@resource='session' and @method='create']">
    <html>
        <head>
            <script type="text/javascript">
                window.location = '<xsl:value-of select="/social/@generator" />';
            </script>
        </head>
        <body></body>
    </html>
</xsl:template>