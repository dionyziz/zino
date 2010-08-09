<xsl:template name="user.modal.settings">
    <div class="modal tabbed settingsmodal">
        <h2>Λογαριασμός</h2>
        <ul class="tablist">
            <li class="selected" id="view_password"><a href="">Κωδικός</a></li>
            <li id="view_email"><a href="">E-Mail</a></li>
        </ul>
        <div class="tab selected" id="tab_password">
            <form><fieldset>
                <label>Παλιός κωδικός:</label><input type="password" name="oldpassword" />
                <label>Νέος κωδικός:</label><input type="password" name="newpassword" />
                <label>Επανάληψη νέου κωδικού:</label><input type="password" name="newpassword2" />
            </fieldset></form>
            <ul class="buttons"><li><a class="save" href="">Αποθήκευση</a></li></ul>
        </div>
        <div class="tab" id="tab_email">
            <ul><li><span>Καταχωρημένο e-mail</span>
            <input type="text" value="email" /></li></ul>
            <div><input type="checkbox" checked="checked"/>Aποστολή ενημερώσεων με e-mail</div>
            <ul class="buttons"><li><a class="save" href="">Αποθήκευση</a></li></ul>
        </div>
    </div>
</xsl:template>
<xsl:template name="user.modal.aboutme">
    <div name="aboutmemodal" class="modal">
        <h2>Λίγα λόγια για μένα</h2>
        <textarea class="aboutme"></textarea>
        <ul class="buttons"><li><a class="save" href="">Αποθήκευση</a></li></ul>
        <div><a href="" class="linebutton">Να μην εμφανίζεται</a></div>
    </div>
</xsl:template>
<xsl:template name="user.modal.location">
    <div name="aboutmemodal" class="modal">
        <h2>Περιοχή</h2>
        <select class="location"></select>
        <div><a href="" class="linebutton">Να μην εμφανίζεται</a></div>
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