<?php


/* comments
	id, creation_id, user_id, creation_date, content
 */
class Comment extends Data {
	private $id;
	private $creation_id;
	private $user_id;
	private $username;
	private $creation_date;
	private $content;

	public function __construct($id = -1) {
		global $db;
		$this->id = $id;
		if ($id != -1) {
			$r = $db->query("select
			   	id,creation_id,user_id,content,creation_date, b.username as username
				from comments a
				join users b on b.id = a.user_id
			   	where id=$id")->fetchAll()[0];
			$this->creation_id = $r['creation_id'];
			$this->creation_date = $r['creation_date'];
			$this->user_id = $r['user_id'];
			$this->content = $r['content'];
			$this->username = $r['username'];
		}
	}

	public function toArray() {
		return ['id' => $this->id,
		'creation_id' => $this->creation_id,
		'user_id' => $this->user_id,
		'creation_date' => $this->creation_date,
		'content' => $this->content,
		'username' => $this->username];
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

	public function setusername($value) {
		$this->username = $value;
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

	public function gecontent() {
		return $this->content;
	}

	public function setcontent($value) {
		$this->content = $value;
	}

	public function save() {
		global $db;
		if ($this->id != -1) {
			$r = $db->exec("update comments set
				user_id = $this->user_id,
				creation_id = $this->creation_id,
				creation_date = '',
				content = '$this->content'
			where id = $this->id;");
		} else {
			$r = $db->exec("insert into comments values
				(NULL, $this->user_id, $this->creation_id, '', '$this->content');");
			$this->setid($db->lastInsertId());
		}
		return $r;
	}

	public function remove(Comment $c) {
		global $db;
		$r = $db->exec("delete from comments where id={$c->getid()}");
		return $r;
	}

	public function getFor($creation_id = -1) {
		global $db;

		$r = $db->query("select
			c.id,user_id,creation_date,content,u.username as username
		from comments c
		join users u on c.user_id = u.id
		where c.creation_id = $creation_id");
		$comments = $r->fetchAll();
		$coms = [];
		foreach ($comments as $c) {
			$nc = new Comment();
			$nc->setcontent($c['content']);
			$nc->setusername($c['username']);
			$nc->setuser_id($c['user_id']);
			$nc->setid($c['id']);
			$coms[] = $nc;
		}
		return $coms;

	}
}
