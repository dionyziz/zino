<xsl:template name="chat.modal.file">
    <div class="modal">
        <iframe src="static/chat-upload.html" style="border: 0"></iframe> 
    </div>
</xsl:template>

<xsl:template match="/social[@resource='file' and @method='create']">
    <html>
        <head>
            <title>File Uploaded</title>
        </head>
        <body>
            <script type="text/javascript">
                document.domain = 'zino.gr';
                parent.Chat.File.OnUploaded( '<xsl:value-of select="file/media/@url" />' );
            </script>
        </body>
    </html>
</xsl:template>
