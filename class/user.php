<?php

class User {
	public $id;
	public $nick;
	public $group;
	public $grouptitle;
	public $perm;
	
	function hasPerm($p)
	{
		return isset($this->perm[$p]);
	}
	
	public function __construct() {
        $this->perm = array();
    }
	
}

?>