<xsl:template name="user.modal.settings">
    <div class="modal settingsmodal">
        <h2>Λογαριασμός</h2>
        <ul>
            <li><span class="info">Παλιός κωδικός:</span><input type="password" name="oldpassword" /></li>
            <li><span class="info">Νέος κωδικός:</span><input type="password" name="newpassword" /></li>
            <li><span class="info">Επανάληψη νέου κωδικού:</span><input type="password" name="newpassword2" /></li>
        </ul>
        <ul class="buttons"><li><a class="save" href="">Αποθήκευση</a></li></ul>
    </div>
</xsl:template>
<xsl:template name="user.modal.aboutme">
    <div name="aboutmemodal" class="modal">
        <h2>Λίγα λόγια για μένα</h2>
        <textarea class="aboutme"></textarea>
        <ul class="buttons"><li><a class="save" href="">Αποθήκευση</a></li></ul>
    </div>
</xsl:template>
<xsl:template name="user.modal.location">
    <div name="aboutmemodal" class="modal">
        <h2>Περιοχή</h2>
        <select class="location"></select>
    </div>
</xsl:template>
<xsl:template name="user.modal.location.options">
    <xsl:for-each select="/social/places/place">
        <option><xsl:attribute name="value"><xsl:value-of select="id" /></xsl:attribute><xsl:value-of select="name" /></option>
    </xsl:for-each>
</xsl:template>
<xsl:template name="user.mood.edit">
    <div class="moodpicker" style="overflow: hidden">
        
    </div>
</xsl:template>