<?php
/**
 * GSAPI - Interface to the Grooveshark API used in the Wordpress plugin
 * 
 * PHP Version 5
 *
 * @author Roberto Sanchez <roberto.sanchez@escapemg.com>
 */

// These functions added for php versions that don't include json functions
if (!function_exists('json_decode') or !function_exists('json_encode')) {
    require_once 'GSJSON.php';
}

if ( !function_exists('json_decode') ){
    function json_decode($content){
        $json = new GS_Services_JSON(SERVICES_JSON_LOOSE_TYPE);
        return $json->decode($content);
    }
}

if ( !function_exists('json_encode') ){
    function json_encode($content){
        $json = new Services_JSON;
        return $json->encode($content);
    }
}


class GSAPI
{
    protected $sessionID = '';
    protected $userID = 0;
    protected $callCount = 0;
    private static $instance;

    public function __construct($params)
    {
        if (!empty($params)) {
            if (array_key_exists('sessionID', $params)) {
                $this->sessionID = $params['sessionID'];
            } elseif (array_key_exists('APIKey', $params)) {
                $result = self::callRemote('session.start', array('apiKey' => $params['APIKey']));
                $this->sessionID = $result['header']['sessionID'];
            }
        }
    }

    private static function callRemote($method, $params = array(), $session = '') 
    {
        $data = array('header' => array('sessionID' => $session),
                      'method' => $method,
                      'parameters' => $params);
        $data = json_encode($data);
        $header[] = 'Host: api.grooveshark.com';
        $header[] = 'Content-type: text/json';
        $header[] = 'Content-length: ' . strlen($data) . "\r\n";
        $header[] = $data;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'http://api.grooveshark.com/ws/1.0/?json');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 2);
        $result = curl_exec($curl);
        curl_close($curl);
        $decoded = json_decode($result, true);
        return $decoded;
    }

    /*
     * Gets an instance of the GSAPI object
     * 
     * @param   mixed[] Array containing either a sessionID element or an APIKey element
     * @return  GSAPI   Instance of the GSAPI object
     */
    public static function getInstance($params = array())
    {
        if (!(self::$instance instanceof GSAPI)) {
            if (!empty($params)) {
                self::$instance = new GSAPI($params);
            }
        }
        return self::$instance;
    }

    public function getUserID() {return $this->userID;}
    public function getSessionID() {return $this->sessionID;}
    public function getApiCallsCount() {return $this->callCount;}

    /**
     * Get an auth token used to login (needed for some API methods)
     *
     * @param  string  username
     * @param  string  password
     * @return  mixed[] array('userID' => 'The userID for the auth token', 'token' => 'The auth token') or array('error' => (int) Error Code)
     */
    public function createUserAuthToken($username, $password)
    {
        $hashpass = md5($password);
        $hashpass = $username . $hashpass;
        $hashpass = md5($hashpass);
        $result = self::callRemote('session.createUserAuthToken', array('username' => $username, 'hashpass' => $hashpass), $this->sessionID);
        if (isset($result['fault']['code'])) {
            return array('error' => (int)$result['fault']['code']);
        } elseif (isset($result['result']['userID']) && isset($result['result']['token'])) {
            return array('userID' => $result['result']['userID'], 'token' => $result['result']['token']);
        } else {
            return array('error' => -2);
        }
    }

    /**
     * Destroy an auth token
     *
     * @param  string  token
     * @return  bool    success?
     */
    public function destroyAuthToken($token)
    {
        $result = self::callRemote('session.destroyAuthToken', array('token' => $token), $this->sessionID);
        if (isset($result['result'])) {
            return true;
        } else {
            return false;
        }
    }

     /**
      * Logs the user in using an auth token
      *
      * @param string  token
      * @return bool    success?
      */
    public function loginViaAuthToken($token)
    {
        $result = self::callRemote('session.loginViaAuthToken', array('token' => $token), $this->sessionID);
        if (isset($result['fault']['code'])) {
            return false;
        } else {
            $this->userID = $result['result']['userID'];
            return true;
        }
    }

    /**
     * Logs the user out (just to make sure songs are not added to their account)
     *
     */
    public function logout()
    {
        self::callRemote('session.logout', array(), $this->sessionID);
    }

    /**
     * Gets the logged-in user's username, returns an empty string if no username is found
     */
    public function getUsername()
    {
        if ($this->userID != 0) {
            $result = self::callRemote('user.about', array('userID' => $this->userID), $this->sessionID);
            if (isset($result['result']['user']['username'])) {
                return $result['result']['user']['username'];
            } else {
                return '';
            }
        }
        return '';
    }

    /**
     * Performs an API search for songs
     *
     * @param   string  Search query
     * @param   int     Search limit
     * @return  mixed   Songs array or error
     */
    public function searchSongs($query, $limit)
    {
        $result = self::callRemote('search.songs', array('query' => $query, 'limit' => $limit, 'page' => 1, 'streamableOnly' => 1), $this->sessionID);
        if (isset($result['fault']['code'])) {
            return array('error' => (int)$result['fault']['code']);
        } elseif (isset($result['result']['songs'])) {
            return $result['result']['songs'];
        } else {
            return array('error' => -4);
        }
    }

    /**
     * Gets song information
     *
     * @param   int     songID
     * @return  mixed   Song information or error
     */
    public function songAbout($songID)
    {
        $result = self::callRemote('song.about', array('songID' => $songID), $this->sessionID);
        if (isset($result['fault']['code'])) {
            return array('error' => (int)$result['fault']['code']);
        } elseif (isset($result['result']['song'])) {
            return $result['result']['song'];
        } else {
            return array('error' => -8);
        }
    }
    /**
     * Gets a song's stream key (not currently used, kept if ever needed again)
     *
     * @param   int     songID
     * @return  string  Stream Key (or empty string on failure)
     */
    public function songGetStreamKey($songID)
    {
        $result = self::callRemote('song.getStreamKey', array('songID' => $songID), $this->sessionID);
        if (isset($result['fault']['code'])) {
            return ''; // Doesn't return the error code, but method is not used anyway
        } else {
            return $result['result']['streamKey'];
        }
    }

    /**
     * Gets the widget embed code for a song
     *
     * @param   int     songID
     * @param   int     width of widget in pixels
     * @return  string  Widget's embed code (or error string)
     */
    public function songGetWidgetEmbedCode($songID, $width)
    {
        $result = self::callRemote('song.getWidgetEmbedCode', array('songID' => $songID, 'pxWidth' => $width), $this->sessionID);
        if (isset($result['fault']['code'])) {
            return 'Error Code: ' . $result['fault']['code'] . ' ' . $result['fault']['message'] . '. Contact author for support.';
        } elseif (isset($result['result']['embed'])) {
            return $result['result']['embed'];
        } else {
            return 'Error Code: ' . -16 . '. Contact author for support.';
        }
    }

    /**
     * Gets widget embed code for an autoplay widget
     *
     * @param   int     songID
     * @return  string  widget's embed code or error string
     */
    public function songGetApWidgetEmbedCode($songID) {
        $result = self::callRemote('song.getWidgetEmbedCode', array('songID' => $songID, 'pxWidth' => 1, 'ap' => 1), $this->sessionID);
        if (isset($result['fault']['code'])) {
            return 'Error Code: ' . $result['fault']['code']  . '. Contact author for support.';
        } elseif (isset($result['result']['embed'])) {
            return $result['result']['embed'];
        } else {
            return 'Error Code: ' . -16 . '. Contact author for support.';
        }
    }

    /**
     * Gets the favorite songs of the logged-in user
     *
     * @return  mixed   Songs list or error
     */
    public function userGetFavoriteSongs()
    {
        if ($this->userID == 0) {
            return array('error' => 'User Not Logged In');
        }
        $result = self::callRemote('user.getFavoriteSongs', array('userID' => $this->userID, 'page' => 1), $this->sessionID);
        if (isset($result['fault']['code'])) {
            return array('error' => $result['fault']['code'] . ' ' . $result['fault']['message']);
        } elseif (isset($result['result']['songs'])) {
            return $result['result']['songs'];
        } else {
            return array('error' => -32);
        }
    }

    /**
     * Gets the playlists of the logged-in user
     *
     * @return  mixed   playlistis or error
     */
    public function userGetPlaylists()
    {
        if ($this->userID == 0) {
            return array('error' => 'User Not Logged In');
        }
        $result = self::callRemote('user.getPlaylists', array('userID' => $this->userID, 'page' => 1), $this->sessionID);
        $this->callCount++;
        if (isset($result['fault']['code'])) {
            return array('error' => $result['fault']['code'] . ' ' . $result['fault']['message']);
        } elseif (isset($result['result']['playlists'])) {
            return $result['result']['playlists'];
        } else {
            return array('error' => -64);
        }
    }

    /**
     * Gets all modified user playlists
     *
     * @param   int         unix time in seconds, such that playlists modified after this time are counted as modified
     * @return  string[]    array containing all modified playlistID's, or empty array on failure
     */
    public function playlistModified($time)
    {
        if ($this->userID == 0) {
            return array();
        }
        $result = self::callRemote('grooveshark.getUserPlaylistsModifiedSince', array('userID' => $this->userID, 'time' => $time), $this->sessionID);
        $this->callCount++;
        if (isset($result['fault']['code'])) {
            return array();
        } elseif (isset($result['result'])) {
            return $result['result'];
        } else {
            return array('error' => '-128');
        }
    }
    
    /**
     * Gets playlist information
     *
     * @param   int     playlistID
     * @return  mixed   playlist information or error
     */
    public function playlistAbout($playlistID)
    {
        $result = self::callRemote('playlist.about', array('playlistID' => $playlistID), $this->sessionID);
        $this->callCount++;
        if (isset($result['fault']['code'])) {
            return array('error' => $result['fault']['code'] . ' ' . $result['fault']['message']);
        } elseif (isset($result['result'])) {
            return $result['result'];
        } else {
            return array('error' => -128);
        }
    }

    /**
     * Creates a playlist
     *
     * @param   string      playlist name
     * @return  int[]       PlaylistID or error code
     */
    public function playlistCreate($name)
    {
        $result = self::callRemote('playlist.create', array('name' => $name), $this->sessionID);
        if (isset($result['fault']['code'])) {
            return array('error' => $result['fault']['code'] . ' ' . $result['fault']['message']);
        } elseif (isset($result['result']['playlistID'])) {
            return array('playlistID' => $result['result']['playlistID']);
        } else {
            return array('error' => -256);
        }
    }

    /**
     * Adds a song to a playlist
     *
     * @param   int     playlistID
     * @param   int     songID
     * @return  bool    success?
     */
    public function playlistAddSong($playlistID, $songID)
    {
        $result = self::callRemote('playlist.addSong', array('playlistID' => $playlistID, 'songID' => $songID), $this->sessionID);
        if (isset($result['fault']['code'])) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Gets the songs of a playlist
     *
     * @param   int     playlistID
     * @return  mixed   list of songs or error
     */
    public function playlistGetSongs($playlistID)
    {
        $result = self::callRemote('playlist.getSongs', array('playlistID' => $playlistID, 'page' => 1), $this->sessionID);
        $this->callCount++;
        if (isset($result['fault']['code'])) {
            return array('error' => $result['fault']['code'] . ' ' . $result['fault']['message']);
        } elseif (isset($result['result']['songs'])) {
            return $result['result']['songs'];
        } else {
            return array('error' => -512);
        }
    }

    /**
     * Gets the widget embed code (hex codes do not require prefix)
     *
     * @param   int     $playlistID
     * @param   int     $width in pixels
     * @param   int     $height in pixels
     * @param   string  $name: widget name
     * @param   string  $bt: body text color hex code
     * @param   string  $bth: body text hover color hex code
     * @param   string  $bbg: body background color hex code
     * @param   string  $bfg: body foreground color hex code
     * @param   string  $pbg: player background color hex code
     * @param   string  $pfg: player foreground color hex code
     * @param   string  $pbgh: player background hover color hex code
     * @param   string  $pfgh: player foreground hover color hex code
     * @param   string  $lbg: list background color hex code
     * @param   string  $lfg: list foreground color hex code
     * @param   string  $lbgh: list background hover color hex code
     * @param   string  $lfgh: list foreground hover color hex code
     * @param   string  $sb: scrollbar color hex code
     * @param   string  $sbh: scrollbar hover color hex code
     * @param   string  $secondaryIcon color hex code
     * @return  string  widget embed code
     */
    public function playlistGetWidgetEmbedCode($playlistID, $width, $height, $name, $bt, $bth, $bbg, $bfg, 
                                               $pbg, $pfg, $pbgh, $pfgh, $lbg, $lfg, $lbgh, $lfgh, $sb, $sbh, $secondaryIcon)
    {
        $result = self::callRemote('playlist.getWidgetEmbedCode', array('playlistID' => $playlistID, 'width' => $width, 'height' => $height, 'name' => $name,
                                                                        'bodyText' => $bt, 'bodyTextHover' => $bth, 'bodyBackground' => $bbg, 'bodyForeground' => $bfg,
                                                                        'playerBackground' => $pbg, 'playerForeground' => $pfg, 'playerBackgroundHover' => $pbgh,
                                                                        'playerForegroundHover' => $pfgh, 'listBackground' => $lbg, 'listForeground' => $lfg,
                                                                        'listBackgroundHover' => $lbgh, 'listForegroundHover' => $lfgh, 'scrollbar' => $sb, 
                                                                        'scrollbarHover' => $sbh, 'secondaryIcon' => $secondaryIcon), $this->sessionID);
        return $result['result']['embed'];
    }

}
