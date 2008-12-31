<?php
    class ElementiPhoneUserLogin extends Element {
        public function Render() {
            ?><div class="login">
                <form action="do/user/login" method="post">
                    <h2>Είσοδος στο zino</h2>
                    <div>
                        <label>Όνομα:</label> <input type="text" name="username" />
                    </div>
                    <div>
                        <label>Κωδικός:</label> <input type="password" name="password" />
                    </div>
                    <div>
                        <input type="submit" value="Είσοδος &raquo;" />
                    </div>
                </form>
            </div><?php
        }
    }
