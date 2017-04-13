<?php
require dirname(__FILE__) . '/conf.php';
require FWE_Conf::ROOT() . '/pages/main.php';

class FWE_Lib
{
    private static $page = null;
    public static function getPage($uri = false)
    {
    	if (!$uri) {
    		$uri = $_SERVER['REQUEST_URI'];
    	}
        if (self::$page === null) {
        	self::$page = new FWE_PageMain(FWE_Conf::getDB());
        	self::$page->get($uri);
        	// var_dump(self::$page);
        }
        return self::$page;
    }

    public static function getTitle()
    {
        return self::getPage()->{__FUNCTION__}();
    }

    public static function getDescription()
    {
        return self::getPage()->{__FUNCTION__}();
    }

    public static function getKeywords()
    {
        return self::getPage()->{__FUNCTION__}();
    }

    public static function getTextTop()
    {
        return self::getPage()->{__FUNCTION__}();
    }

    public static function getTextBottom()
    {
        return self::getPage()->{__FUNCTION__}();
    }
}
