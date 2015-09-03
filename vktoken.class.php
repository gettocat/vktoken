<?php

Class vktoken {

    public static $_app_id;
    public static $_app_secret;
    public static $instance = null;
    public static $scope = 'wall,offline,photos,docs,audio,video,groups';
    public $app_id;
    public $app_secret;

    public function __construct($vk_app, $vk_secret, $scope = '') {
        $this->app_id = $vk_app;
        $this->app_secret = $vk_secret;
        if ($scope)
            static::$scope = $scope;
    }

    public function getToken($url) {
        if (preg_match("#blank\.html\#code\=(.*)$#", $url, $m)) {
            $code = $m[1];
            $sUrl = "https://api.vk.com/oauth/access_token?client_id=" . $this->app_id . "&client_secret=" . $this->app_secret . "&code=$code&redirect_uri=http://api.vk.com/blank.html";
            $oResponce = json_decode(@file_get_contents($sUrl));
            if ($oResponce->access_token)
                $result['token'] = $oResponce->access_token;
        } else {
            $result['token'] = false;
        }

        return array(
            'token' => $result['token'],
            'user' => static::getUserInfo($result['token'])
        );
    }

    public function link() {
        return "http://oauth.vk.com/authorize?client_id=$this->app_id&scope=" . static::$scope . "&redirect_uri=http://api.vk.com/blank.html&response_type=code";
    }

    /**
     * 
     * @param type $app_id
     * @param type $secret
     * @return vktoken
     */
    public static function create($app_id, $secret) {
        static::$_app_id = $app_id;
        static::$_app_secret = $secret;
        return static::get();
    }

    public static function get() {
        if (self::$instance == null)
            self::$instance = new static(static::$_app_id, static::$_app_secret);

        return self::$instance;
    }

    public static function getUserInfo($token) {
        if (!$token)
            return array();
        
        $vk = new vkapi(static::$_app_id, static::$_app_secret);
        $res = $vk->api("users.get", array(
            'access_token' => $token,
            'fields' => 'photo_100,last_seen,status'
        ));

        return $res['response'][0];
    }

}
