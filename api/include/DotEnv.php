<?php
/*
********************************************************************
* @Author		: Md. Russel Husssain					 		   *
* @Author Email	: md.russel.hussain@gmail.com					   *
* @Organization	:                       						   *
* @Purpose		: This trait is responsible to get and set env     *
********************************************************************
*/

trait DotEnv {
    /**
     * @Author: Md. Russel Hussain
     * @Author Email: md.russel.hussain@gmail.com
     * Reading the env file from path variable and load all value
     * @return void
     */
    public function loadEnv() {

        if (file_exists($this->path)) {
            $lines = file($this->path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

            foreach ($lines as $line) {

                if (strpos(trim($line), '#') === 0) {
                    continue;
                }

                list($name, $value) = explode('=', $line, 2);
                $name = trim($name);
                $value = trim($value);

                if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
                    putenv(sprintf('%s=%s', $name, $value));
                    $_ENV[$name] = $value;
                    $_SERVER[$name] = $value;
                }
            }
        }
    }

    /**
     * @Author: Md. Russel Hussain
     * @Author Email: md.russel.hussain@gmail.com
     * Get request to set a key value in the env file to update
     * @param $key
     * @param $val
     * @return void
     */
    public function setEnv($key, $val) {

        if (file_exists($this->path)) {
            $token = $key . '='.getenv($key);
            $value = $key . '=' . $val;

            file_put_contents($this->path, str_replace(
                $token, $value, file_get_contents($this->path)
            ));
        }
    }


}
