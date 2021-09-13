<?php
require_once __DIR__.'/BaseClass.php';
require_once __DIR__.'/Auth.php';

/*
********************************************************************
* @Author		: Md. Russel Husssain					 		   *
* @Author Email	: md.russel.hussain@gmail.com					   *
* @Organization	:                       						   *
* @Purpose		: This class is responsible for manage database    *
* All the request for insert, select, update delete will be handled*
********************************************************************
*/

class DatabaseHandler extends BaseClass {
    use Auth;

    CONST usersTable = 'users';
    CONST pagesTable = 'pages';

    protected $db_host;
    protected $db_user;
    protected $db_password;
    protected $db_name;
    protected $con = '';

    protected $systemAdmin;
    protected $adminEmail;
    protected $adminPassword;

    protected $tableName;
    protected $columns = [];
    protected $conditions = [];
    protected $limit = 0;
    protected $values = [];

    public function __construct($path) {
        parent::__construct($path);
        $this->LoadDBInfo();
        $this->connectDb();
        if(!$this->isInstalled){
            $this->createTable();
            $this->createUser();
        }

    }

    /**
     * @Author: Md. Russel Hussain
     * @Author Email: md.russel.hussain@gmail.com
     * load all information from env file
     * @return void
     */
    public function LoadDBInfo() {
        $this->db_host = getenv('DB_HOST');
        $this->db_user = getenv('DB_USERNAME');
        $this->db_password = getenv('DB_PASSWORD');
        $this->db_name = getenv('DB_DATABASE');
        $this->systemAdmin = getenv('SYSTEM_ADMIN');
        $this->adminEmail = getenv('ADMIN_EMAIL');
        $this->adminPassword = getenv('ADMIN_PASSWORD');
    }

    /**
     * @Author: Md. Russel Hussain
     * @Author Email: md.russel.hussain@gmail.com
     * connect to database
     * @return mysqli
     */
    public function connectDb() {
        $this->con = new mysqli($this->db_host, $this->db_user, $this->db_password, $this->db_name);
        if(mysqli_connect_error()) {
            die("Failed to connect to MySQL: " . mysqli_connect_error());
        }else{
            return $this->con;
        }
    }

    /**
     * @Author: Md. Russel Hussain
     * @Author Email: md.russel.hussain@gmail.com
     * create all necessary tables
     * @return array
     */
    public function createTable() {
        $usersTable = self::usersTable;
        $users = "CREATE TABLE IF NOT EXISTS `$usersTable` ( 
            `id` INT NOT NULL AUTO_INCREMENT , 
            `name` VARCHAR(50) NOT NULL , 
            `email_address` VARCHAR(100) NOT NULL , 
            `password` VARCHAR(256) NOT NULL , 
            `user_type` ENUM('Admin','User') NOT NULL DEFAULT 'User' , 
            PRIMARY KEY (`id`)) ENGINE = InnoDB AUTO_INCREMENT=1;";

        if($this->con->query($users) !== true) {
            return array(
                'status' => false,
                'message' => 'Unable to create users table'
            );
        }

        $pagesTable = self::pagesTable;
        $pages = "CREATE TABLE IF NOT EXISTS  `$pagesTable` ( 
            `id` INT NOT NULL AUTO_INCREMENT , 
            `page_title` VARCHAR(200) NOT NULL , 
            `page_content` TEXT NOT NULL , 
            `page_status` ENUM('Active','Inactive') NOT NULL DEFAULT 'Active' , 
            PRIMARY KEY (`id`)) ENGINE = InnoDB AUTO_INCREMENT=1;";

        if($this->con->query($pages) !== true){
            return array(
                'status' => false,
                'message' => 'Unable to create pages table'
            );
        }

        return array(
            'status' => false,
            'message' => 'Unable to create pages table'
        );
    }

    /**
     * @Author: Md. Russel Hussain
     * @Author Email: md.russel.hussain@gmail.com
     * Create system admin user
     * @return array
     */
    public function createUser() {

        $usersTable = self::usersTable;
        if(!$this->getUserByEmail($this->adminEmail)){
            $password = md5($this->adminPassword);

            $query = "INSERT INTO `$usersTable` (`name`, `email_address`, `password`, `user_type`) VALUES ('$this->systemAdmin', '$this->adminEmail', '$password', 'Admin')";
            $row = $this->con->query($query);

            if($row === true){
                $this->setEnv('INSTALLATION', 'DONE');
                return array(
                    'status' => true,
                    'id' => $this->con->insert_id
                );
            }else{
                return array(
                    'status' => false,
                    'message' => "Unable to insert row, try again"
                );
            }
        }

        return array(
            'status' => false,
            'message' => 'User already exist with this email'
        );
    }

    /**
     * @Author: Md. Russel Hussain
     * @Author Email: md.russel.hussain@gmail.com
     * Get a user by email address
     * @param $email
     * @return mixed
     */
    public function getUserByEmail($email) {

        $usersTable = self::usersTable;
        $result = $this->con->query("SELECT * FROM `$usersTable` where email_address = '$email' Limit 1");

        if($result->num_rows > 0){
            return $result->fetch_assoc();
        }

        return false;
    }

    /**
     * @Author: Md. Russel Hussain
     * @Author Email: md.russel.hussain@gmail.com
     * get string from array, seperated by comma
     * @return string
     */
    public function getFields() {

        return implode(', ', $this->columns);
    }

    /**
     * @Author: Md. Russel Hussain
     * @Author Email: md.russel.hussain@gmail.com
     * get string from array, seperated by comma
     * @return string
     */
    public function getFieldsWithValue() {

        $fields = '';
        foreach ($this->columns as $column => $value){
            if($fields != '')
                $fields .= ', ';

            $fields .= $column.' = '. $value;
        }

        return $fields;
    }

    /**
     * @Author: Md. Russel Hussain
     * @Author Email: md.russel.hussain@gmail.com
     * get string from array, seperated by comma
     * @return string
     */
    public function getValues() {

        return implode(', ', $this->values);
    }

    /**
     * @Author: Md. Russel Hussain
     * @Author Email: md.russel.hussain@gmail.com
     * get string from array, seperated by comma
     * @return string
     */
    public function getCondition() {

        $condition = '';

        foreach ($this->conditions as $_condition => $val){
            if($condition != '')
                $condition .= ' AND ';
            $value = is_numeric($val) ? $val: "'$val'";
            $condition .= $_condition.'='.$value ;
        }

        return $condition;
    }

    /**
     * @Author: Md. Russel Hussain
     * @Author Email: md.russel.hussain@gmail.com
     * Prepare get query to select data
     * @return string
     */
    public function prepareGetQuery() {

        $fields = $this->getFields();
        $condition = $this->getCondition();

        $query = "SELECT $fields FROM $this->tableName";

        if($condition)
            $query .=  " WHERE $condition";
        if($this->limit)
            $query .= " Limit $this->limit";
        return $query;
    }

    /**
     * @Author: Md. Russel Hussain
     * @Author Email: md.russel.hussain@gmail.com
     * Execute query and prepare data
     * @return array
     */
    public function getAll() {

        $query = $this->prepareGetQuery();
        $result = $this->con->query($query);

        if($result->num_rows > 0) {
            $data = array();
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return array(
                'status' => true,
                'data' => $data
            );
        }else{
            return array(
                'status' => false,
                'message' => 'No record found'
            );
        }
    }

    /**
     * @Author: Md. Russel Hussain
     * @Author Email: md.russel.hussain@gmail.com
     * Get row by id
     * @return array
     */
    public function getById() {

        return $this->getOne();
    }


    /**
     * @Author: Md. Russel Hussain
     * @Author Email: md.russel.hussain@gmail.com
     * Execute query and get row by conditions
     * @return array
     */
    public function getOne() {

        $this->limit = 1;
        $query = $this->prepareGetQuery();
        $result = $this->con->query($query);

        if($result->num_rows > 0) {
            return array(
                'status' => true,
                'data' => $result->fetch_assoc()
            );
        }else{
            return array(
                'status' => false,
                'message' => 'No record found'
            );
        }
    }

    /**
     * @Author: Md. Russel Hussain
     * @Author Email: md.russel.hussain@gmail.com
     * Execute query to insert record
     * @return mixed
     */
    public function insert() {

        $fields = $this->getFields();
        $values = $this->getValues();

        if(!empty($fields) && !empty($values)){
            $sql = "INSERT INTO $this->tableName ($fields) VALUES ($values)";
            if ($this->con->query($sql) === TRUE) {
                return $this->con->insert_id;
            } else {
                return false;
            }
        }
        return false;
    }

    /**
     * @Author: Md. Russel Hussain
     * @Author Email: md.russel.hussain@gmail.com
     * Execute query to update record
     * @return boolean
     */
    public function update() {

        $fields = $this->getFieldsWithValue();
        $condition = $this->getCondition();
        $sql = "UPDATE $this->tableName SET ";

        if(!empty($fields) && !empty($condition)) {
            $query = $sql . $fields . ' WHERE ' .$condition;
            return $this->con->query($query);
        }
        return false;
    }

    /**
     * @Author: Md. Russel Hussain
     * @Author Email: md.russel.hussain@gmail.com
     * Execute query to delete record
     * @return boolean
     */
    public function delete() {

        $condition = $this->getCondition();
        if(!empty($condition)) {
            $sql = "DELETE FROM $this->tableName WHERE $condition";

            return $this->con->query($sql);
        }

        return false;
    }
}

