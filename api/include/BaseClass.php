<?php
require_once __DIR__.'/DotEnv.php';

class BaseClass {
    use DotEnv;

    protected $path;
    public $isInstalled;

    public function __construct($path) {
        $this->path = $path;
        $this->loadEnv();
        $this->isInstalled();
    }

    /**
     * @Author: Md. Russel Hussain
     * @Author Email: md.russel.hussain@gmail.com
     * checking the installation of the application
     * @return void
     */
    public function isInstalled() {
        if(getenv('INSTALLATION') === 'DONE')
            $this->isInstalled = true;
        else $this->isInstalled = false;
    }

    /**
     * @Author: Md. Russel Hussain
     * @Author Email: md.russel.hussain@gmail.com
     * Return the APP_URL
     * @return string
     */
    public function base_url(){
        return getenv('APP_URL');
    }
}
