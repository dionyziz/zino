<?php
    
    /*
        Developer: abresas
    */

    global $libs;
    $libs->Load( 'rabbit/page/controllerpage' );

    // get class of controller 
    function Controller_GetClass( $path ) {
        return 'Controller' . str_replace( '/' , '' , $path );
    }

    function Controller_Include( $path ) {
        if ( strpos( $path, ".." ) !== false ) { // maybe rabbit should check this?
            return false;
        }

        try {
            $ret = Rabbit_Include( 'controllers/' . $path ); // throws RabbitIncludeException
            return $ret;
        }
        catch ( RabbitIncludeException $e ) {
            return false;
        }
    }

    function Controller_CheckAction( $action, $method ) {
        if ( $action == 'view' ) {
            w_assert( $method == 'GET', 'View only with GET' );
        }
        else if ( $action == 'create' || $action == 'update' || $action == 'delete' ) {
            w_assert( $method == 'POST', 'Create, update and delete only with POST'  );
        }
        else {
            die( 'invalid controller action requested' );
        }
    }

    function Controller_Invalid( $method, $coala = false ) {
        Controller_Include( '404' );
        $c = New Controller404( $method, $coala );
        $c->FireAction( 'View', array() );
    }

    // this is where we decide which controller to call
    // and start action
    function Controller_Fire( $uri, $req ) {
        global $rabbit_settings;

        $coala = false; // unit handling later

        $pos = strrpos( '/', $uri );
        if ( $pos ) {
            $resourcePath = substr( $uri, 0, $pos );
            $action = substr( $uri, $pos + 1 );
            // check if the last part wasn't meant to be action e.g. for view or create?
            // maybe some other time
        }
        else {
            $resourcePath = $uri;
            $action = 'view';
        }

        Controller_CheckAction( $action, $method );
        $ret = Controller_Include( $resourcePath ); 

        if ( $ret === false ) {
            Controller_Invalid( $method, $coala );
            return;
        }

        $classname = Controller_GetClass( $resourcePath );
        w_assert( class_exists( $classname ), "controller $classname does not exist" );

        $c = New $classname( $method, $coala );
        $c->FireAction( $action, $req );
    }

    /*
        The most abstract controller class.
        Extend this class if you want to create a custom resource controller, 
        e.g. for image rendering.
        If you want statndard HTML resource management (for HTML, CSS, JS, AJAX, HTTP Redirections), 
        extend the HTMLController, that is linked to a Page class.

        HTMLController would be a child of this Controller, but for efficiency reasons
        the basic stuff are copied to it (PHP sucks at object handling).
    */
    abstract class Controller {
        protected $mMethod;

        public function __construct( $method ) {
            // instantiate variables 
            $this->mMethod = $method;
        }
        public function FireAction( $action, $req ) {
            $beforefunc = "Before$action";
            $this->$beforefunc(); // MAGIC!

            $ret = Rabbit_TypeSafe_Call( array( $this, $action ), $req );

            $afterfunc = "After$action";
            $this->$afterfunc(); // MAGIC!
        }
        public function View() {
        }
        public function Create() {
        }
        public function Update() {
        }
        public function Delete() {
        }
        protected function BeforeView() {
        }
        protected function AfterView() {
        }
        protected function BeforeCreate() {
        }
        protected function AfterCreate() {
        }
        protected function BeforeUpdate() {
        }
        protected function AfterUpdate() {
        }
        protected function BeforeDelete() {
        }
        protected function AfterDelete() {
        }
    }

    abstract class ControllerHTML { 
        protected $mMethod;
        protected $mCoala;
        protected $mPage;
        protected $mRedirected;

        public function __construct( $method, $coala = false ) {
            // instantiate variables 
            $this->mMethod = $method;
            $this->mCoala = $coala;

            if ( !$this->mCoala ) {
                global $page;

                $this->mPage = New ControllerPage();
                $page = $this->mPage; // god damn inline scripts :/
            }

            $this->mRedirected = false;
        }
        public function FireAction( $action, $req ) {
            if ( $this->mCoala ) {
                ?>while( 1 );<?php
            }
            else {
                ob_start();
            }
        
            $beforefunc = "Before$action";
            $this->$beforefunc(); // MAGIC!

            $ret = Rabbit_TypeSafe_Call( array( $this, $action ), $req );

            if ( $this->mRedirected ) {
                w_assert( !$this->mCoala );

                // leaving!
                ob_end_clean();
                return;
            }

            $afterfunc = "After$action";
            $this->$afterfunc(); // MAGIC!

            if ( !$this->mCoala ) {
                $this->mPage->Output( ob_get_clean() );
            }
        }
        protected function Redirect( $url ) {
            global $rabbit_settings;
            
            $url = $target;
            if ( !ValidURL( $target ) ) {
                $url = $rabbit_settings[ 'webaddress' ] . '/' . $target;
                if ( !ValidURL( $url ) ) {
                    $url = $rabbit_settings[ 'webaddress' ] . '/';
                }
            }
            
            $r = New HTTPRedirection( $url );
            $r->Redirect();

            $this->mRedirected = true;

            return;
        }
        // override these!
        public function View() {
        }
        public function Create() {
        }
        public function Update() {
        }
        public function Delete() {
        }
        protected function BeforeView() {
        }
        protected function AfterView() {
        }
        protected function BeforeCreate() {
        }
        protected function AfterCreate() {
        }
        protected function BeforeUpdate() {
        }
        protected function AfterUpdate() {
        }
        protected function BeforeDelete() {
        }
        protected function AfterDelete() {
        }
    }
    
    // move this to controllers/zino and define __autoload()
    abstract class ControllerZino extends ControllerHTML {
        protected function BeforeView() {
            global $rabbit_settings;
            global $xc_settings;

            // Copied from elements/main
            // TODO: do something about this shit
            // e.g. $this->mPage->AttachStylesheets( $production );

            $this->mPage->AddMeta( 'author', 'Kamibu Development Team' );
            $this->mPage->AddKeyword( array( 'greek', 'friends', 'chat', 'community', 'greece', 'meet', 'people' ) );
            $this->mPage->AddMeta( 'description', 'Το Zino είναι η παρέα σου online - είσαι μέσα;' );
            
            ob_start();
            ?>if ( typeof ExcaliburSettings == 'undefined' ) {
                ExcaliburSettings = {};
            }
            ExcaliburSettings.webaddress = '<?php
            echo $rabbit_settings[ 'webaddress' ];
            ?>';<?php
            if ( $rabbit_settings[ 'production' ] ) {
                ?>ExcaliburSettings.Production = true;<?php
            }
            else {
                ?>ExcaliburSettings.Production = false;<?php
            }
            $this->mPage->AttachInlineScript( ob_get_clean() );
            
            // attaching ALL css files
            if ( $rabbit_settings[ 'production' ] ) {
                $this->mPage->AttachStylesheet( $xc_settings[ 'staticcssurl' ] . 'global.css?' . $xc_settings[ 'cssversion' ] );
            }
            else {
                $this->mPage->AttachStylesheet( $xc_settings[ 'staticcssurl' ] . 'global-beta.css?' . $xc_settings[ 'cssversion' ] );
            }
            if ( UserBrowser() == "MSIE" ) {
                $this->mPage->AttachStylesheet( 'css/ie.css' );
            }

            //start javascript attaching
            $this->mPage->AttachScript( 'http://www.google-analytics.com/urchin.js', $language = 'javascript', false, '', 9 );
            $this->mPage->AttachInlineScript( "ExcaliburSettings.webaddress = '" . $rabbit_settings[ 'webaddress' ] . "';" );
            if ( $rabbit_settings[ 'production' ] ) {
                $globaljs = $xc_settings[ 'staticjsurl' ] . 'global.js?' . $xc_settings[ 'jsversion' ];
            }
            else {
                $globaljs = $xc_settings[ 'staticjsurl' ] . 'global-beta.js?' . $xc_settings[ 'jsversion' ];
            }
            $this->mPage->AttachScript( $globaljs, $language = 'javascript', false, '', 10 );
            
            Element( 'header' );
        }
        protected function AfterView() {
            global $rabbit_settings;
            global $water;
            global $libs;

            if ( !$this->mPage->IsTitleFinal() ) {
                if ( $this->mPage->Title() != '' ) { // If the title's page is not blank
                    $this->mPage->SetTitle( $this->mPage->Title() . ' | ' . $rabbit_settings[ 'applicationname' ] );
                }
                else {
                    $water->Notice( 'Title not defined for page' );
                    $this->mPage->SetTitle( $rabbit_settings[ 'applicationname' ] );
                }
            }

            // Element( 'statistics/log', $masterelement );
            $libs->Load( 'memoryusage' );//<collecting memory usage information
            CheckMemoryUsage();

            // move all these to an Element 
            ?></div><?php
             
            if ( !is_array( $res ) || !isset( $res[ 'tiny' ] ) ) {
                ?></div> 
                <div id="strip3">
                    <div id="strip3left" class="s1_0015">
                    </div>
                    <div id="strip3right" class="s1_0016">
                    </div>
                    <div id="strip3middle" class="sx_0003">
                    </div>
                </div>
                </div>    
                <div id="downstrip" class="sx_0002" style="position:relative"><?php
                    Element( 'footer' );
                ?></div><?php
            }
        }
    }

?>
