<?php


/* comments
	id, creation_id, user_id, creation_date, text
 */
class Comment {
	private $id;
	private $creation_id;
	private $user_id;
	private $creation_date;
	private $text;

	public function __construct(int $id = -1) {
		global $db;
		$this->id = $id;
		if ($id != -1) {
			$r = $db->query("select * from comments where id=$id")->fetchAll()[0];
			$this->creation_id = $r['creation_id'];
			$this->creation_date = $r['creation_date'];
			$this->user_id = $r['user_id'];
			$this->text = $r['text'];
		}
	}

	public function getid() {
		return $this->id;
	}

	public function setid($value) {
		$this->id = $value;
	}

	public function getcreation_id() {
		return $this->creation_id;
	}

	public function setcreation_id($value) {
		$this->creation_id = $value;
	}

	public function getuser_id() {
		return $this->user_id;
	}

	public function setuser_id($value) {
		$this->user_id = $value;
	}

	public function setcreation_date($creation_date) {
		$this->creation_date = $creation_date;
	}

	public function getcreation_date() {
		return $this->creation_date;
	}

	public function getext() {
		return $this->text;
	}

	public function settext($value) {
		$this->text = $value;
	}

	public function save() {
		global $db;
		if ($this->id != -1) {
			$r = $db->exec("update comments set
				user_id = $this->user_id,
				creation_id = $this->creation_id,
				creation_date = '',
				text = '$this->text'
			where id = $this->id;");
		} else {
			$r = $db->exec("insert into comments values
				(NULL, $this->user_id, $this->creation_id, '', '$this->text');");
			$this->setid($db->lastInsertId());
		}
		return $r;
	}

	public function getFor($id = -1, $creation_id = -1) {
		global $db;

		if ($id != -1)
			$condition = "id = ".$id;
		else if ($creation_id != -1)
			$condition = "creation_id = ".$creation_id;
		else {
			echo "failed to get comment";
			die ;
		}
		$r = $db->query("select * from comments where $condition");
		$comments = $r->fetchAll();
		$coms = [];
		foreach ($comments as $c) {
			$nc = new Comment();
			$nc->setcreation_id($c['creation_id']);
			$nc->settext($c['text']);
			$nc->setuser_id($c['user_id']);
			$nc->setid($c['id']);
			$coms[] = $nc;
		}
		return $coms;

	}

}
