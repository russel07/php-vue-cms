<?php
session_start();
/*
********************************************************************
* @Author		: Md. Russel Husssain					 		   *
* @Author Email	: md.russel.hussain@gmail.com					   *
* @Organization	:                       						   *
* @Purpose		: This trait is responsible to authentication and  *
* get all session values and destroy session                       *
********************************************************************
*/

trait Auth {
    function validateLogin($post){
        if($post['email'] == '') {
            return array(
                "status" => false,
                "error" => "Email address can not be blank"
            );
        }elseif (!filter_var($post['email'], FILTER_VALIDATE_EMAIL)) {
            return array(
                "status" => false,
                "error" => "Provided email address is not a valid email"
            );
        }

        if($post['password'] == '') {
            return array(
                "status" => false,
                "error" => "Password can not be blank"
            );
        }

        return array(
            "status" => true,
            "error" => ''
        );
    }

    /**
     * @Author: Md. Russel Hussain
     * @Author Email: md.russel.hussain@gmail.com
     * Get request with post data and check the validity of input, validity of user
     * @param $email
     * @param $password
     * @return array
     */
    function login($email, $password){

        $hashPassword = md5($password);
        $this->tableName = 'users';
        $this->columns = array('id', 'name', 'email_address', 'user_type');
        $this->conditions = array('email_address' => $email, 'password' => $hashPassword);

        $row = $this->getOne();

        if($row['status']) {
            $_SESSION['users'] = $row['data'];
            return array(
                "status" => true,
                "user" => $_SESSION['users']
            );
        }else {
            return array(
                "status" => false,
                "error" => "Provided credential is invalid"
            );
        }
    }

    function logout() {
        unset($_SESSION['users']);
        session_unset();
        session_destroy();

        return true;
    }

    function isLoggedIn() {
        if(isset($_SESSION['users']) && !empty($_SESSION['users']))
            return true;

        return false;
    }

    function getLoggedInUserInfo() {
        if(isset($_SESSION['users']) && !empty($_SESSION['users'])){
            return $_SESSION['users'];
        }else {
            return [];
        }
    }

    function getLoggedInUserName() {
        $userInfo = $this->getLoggedInUserInfo();

        if(!empty($userInfo)){
            if(isset($userInfo['name']) && $userInfo['name'])
                return  $userInfo['name'];
            else return  "";
        }else return "";
    }
}

