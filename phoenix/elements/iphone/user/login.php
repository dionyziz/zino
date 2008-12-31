<?php
    class ElementiPhoneUserLogin extends Element {
        public function Render() {
            global $page;

            $page->SetTitle( 'Είσοδος' );

            ?><div class="login">
                <form action="do/user/login" method="post">
                    <div class="input">
                        <label>Όνομα:</label>
                        <input type="text" name="username" />
                    </div>
                    <div class="input">
                        <label>Κωδικός:</label>
                        <input type="password" name="password" />
                    </div>
                    <div>
                        <input type="submit" value="Είσοδος" />
                    </div>
                </form>
            </div><?php
        }
    }
