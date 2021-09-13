<?php

require_once __DIR__.'/DatabaseHandler.php';

/*
********************************************************************
* @Author		: Md. Russel Husssain					 		   *
* @Author Email	: md.russel.hussain@gmail.com					   *
* @Organization	:                       						   *
* @Purpose		: This class is responsible for all request related*
* to get all page information, create, update or delete page       *
********************************************************************
*/

class HomeClass extends DatabaseHandler {
    use Auth;

    public function __construct($path) {
        parent::__construct($path);
    }

    /**
     * @Author: Md. Russel Hussain
     * @Author Email: md.russel.hussain@gmail.com
     * prepare variables to get all active pages
     * @return array
     */
    public function getActivePages() {

        $this->tableName = self::pagesTable;
        $this->columns = array('id', 'page_title', 'page_content', 'page_status');
        $this->conditions = array('page_status' => 'Active');

        return $this->getAll();
    }

    /**
     * @Author: Md. Russel Hussain
     * @Author Email: md.russel.hussain@gmail.com
     * prepare variables to get all pages
     * @return array
     */
    public function getAllPages() {

        $this->tableName = self::pagesTable;
        $this->columns = array('id', 'page_title', 'page_content', 'page_status');

        return $this->getAll();
    }

    /**
     * @Author: Md. Russel Hussain
     * @Author Email: md.russel.hussain@gmail.com
     * prepare variables to get a single page by id
     * @param $id
     * @return array
     */
    public function getPagesById($id) {

        $this->tableName = self::pagesTable;
        $this->columns = array('id', 'page_title', 'page_content', 'page_status');
        $this->conditions = array('id'=> $id);

        return $this->getById();
    }

    /**
     * @Author: Md. Russel Hussain
     * @Author Email: md.russel.hussain@gmail.com
     * Get post data as parameter and check the validity
     * @param $post
     * @return array
     */
    public function validatePageForm($post) {
        if($post['page_title'] == ''){
            return array(
                "status" => false,
                "error" => "Page title can not be blank"
            );
        }
        if($post['page_content'] == ''){
            return array(
                "status" => false,
                "error" => "Page content can not be blank"
            );
        }

        return array(
            "status" => true,
            "error" => "No error"
        );
    }

    /**
     * @Author: Md. Russel Hussain
     * @Author Email: md.russel.hussain@gmail.com
     * Get post data as parameter and prepare variables to insert new page
     * @return array
     */
    public function createPage($post) {
        $this->tableName = self::pagesTable;
        $page_title = $this->con->real_escape_string($post['page_title']);
        $page_content = nl2br($this->con->real_escape_string($post['page_content']));

        $this->columns = array('page_title', 'page_content', 'page_status');
        $this->values = array( "'$page_title'", "'$page_content'", "'Active'");

        $id = $this->insert();

        if($id){
            return array(
                'id' => $id,
                'page_title' => $post['page_title'] ,
                'page_content' => $post['page_content'],
                'page_status' => 'Active'
            );
        }

        return array(
            "status" => false,
            "error" => ""
        );
    }

    /**
     * @Author: Md. Russel Hussain
     * @Author Email: md.russel.hussain@gmail.com
     * Get post data as parameter and prepare variables to update page information
     * @param $post
     * @return array
     */
    public function updatePage($post) {

        $this->tableName = self::pagesTable;
        $id = $this->con->real_escape_string($post['id']);
        $page_title = $this->con->real_escape_string($post['page_title']);
        $page_content = nl2br($this->con->real_escape_string($post['page_content']));
        $page_status = $this->con->real_escape_string($post['page_status']);
        $this->columns = array('page_title' => "'$page_title'", 'page_content' => "'$page_content'", 'page_status'=>"'$page_status'");
        $this->conditions = array( 'id' => $id);

        if($this->update()){
            return array(
                'id' => $id,
                'page_title' => $page_title,
                'page_content' => $page_content,
                'page_status' => $page_status
            );
        }

        return array(
            "status" => false,
            "error" => ""
        );
    }

    /**
     * @Author: Md. Russel Hussain
     * @Author Email: md.russel.hussain@gmail.com
     * Get id as parameter and prepare variables to delete a page by id
     * @param $id
     * @return boolean
     */
    public function deletePage($id) {

        $this->tableName = self::pagesTable;
        $this->conditions = array( 'id' => $id);

        return $this->delete();
    }
}
