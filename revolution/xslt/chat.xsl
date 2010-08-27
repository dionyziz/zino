<xsl:template name="chat.modal.file">
    <div class="modal">
        <iframe src="http://static.zino.gr/revolution/chat-upload.html" style="border: 0"></iframe> 
    </div>
</xsl:template>

<xsl:template match="/social[@resource='session' and @method='create']">
    <html>
        <head>
            <title>File Uploaded</title>
            <script type="text/javascript">
                parent.Chat.File.OnUploaded( '<xsl:value-of select="file/media/@url" />' );
            </script>
        </head>
        <body></body>
    </html>
</xsl:template>
