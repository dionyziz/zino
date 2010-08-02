<xsl:template name="user.modal.settings">
    <div class="modal tabbed settingsmodal">
        <h2>Λογαριασμός</h2>
        <ul class="tabs">
            <li class="selected"><a href="">Κωδικός</a></li>
            <li><a href="">Ειδοποιήσεις</a></li>
        </ul>
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
        <a href="" class="link">Να μην εμφανίζεται</a>
    </div>
</xsl:template>
<xsl:template name="user.modal.location.options">
    <xsl:for-each select="/social/places/place">
        <option><xsl:attribute name="value"><xsl:value-of select="id" /></xsl:attribute><xsl:value-of select="name" /></option>
    </xsl:for-each>
</xsl:template>
<xsl:template name="user.mood.edit">
    <xsl:param name="gender" />
    <div class="moodpicker">
        <span class="modalclose" />
        <ul style="overflow: hidden">
            <xsl:for-each select="/social/moodlist/mood">
                <li><div class="moodtile">
                    <xsl:attribute name="style">background-image:url(<xsl:value-of select="media[1]/@url" />)</xsl:attribute>
                    <xsl:choose>
                        <xsl:when test="$gender = 'f'">
                            <xsl:attribute name="alt"><xsl:value-of select="text[@gender='f']" /></xsl:attribute>
                            <xsl:attribute name="title"><xsl:value-of select="text[@gender='f']" /></xsl:attribute>
                        </xsl:when>
                        <xsl:otherwise>
                            <xsl:attribute name="alt"><xsl:value-of select="text[@gender='m']" /></xsl:attribute>
                            <xsl:attribute name="title"><xsl:value-of select="text[@gender='m']" /></xsl:attribute>
                        </xsl:otherwise>
                    </xsl:choose>
                    <xsl:attribute name="id">mood_<xsl:value-of select="@id" /></xsl:attribute>
                </div></li>
            </xsl:for-each>
        </ul>
    </div>
</xsl:template>