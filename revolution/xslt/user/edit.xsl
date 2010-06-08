<xsl:template name="user.settings.modal">
    <div name="settingsmodal" class="modal">
        <h2>Λογαριασμός</h2>
        <form>
            <ul>
                <li><span class="info">Παλιός κωδικός:</span><input type="password" name="oldpassword" /></li>
                <li><span class="info">Νέος κωδικός:</span><input type="password" name="newpassword" /></li>
                <li><span class="info">Επανάληψη νέου κωδικού:</span><input type="password" name="newpassword2" /></li>
            </ul>
            <input type="submit" />
        </form>
    </div>
</xsl:template>