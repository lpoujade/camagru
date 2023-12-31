<?php

class Creation extends Data {
	private $id;
	private $image;
	private $likes;
	private $likes_count;
	private $user_id;
	private $creation_date;

	public function __construct($id=-1, $image="", $user_id=-1, $creation_date="") {
		global $db;
		$this->id = $id;
		if ($id != -1 && $user_id == -1) {
			$r = $db->query("select * from creations where id=$id")->fetchAll();
			if (count($r) != 1)
				return (NULL);
			$r = $r[0];
			$this->image = $r['img_path'];
			$this->user_id = $r['user_id'];
			$this->creation_date = $r['creation_date'];

			$r = $db->query("select user_id from likes where creation_id = ".$this->id)->fetchAll();
			$this->likes_count = count($r);
			foreach ($r as $like) {
				$this->likes[] = $like['user_id'];
			}
		}
	   	else {
			$this->image = $image;
			$this->user_id = $user_id;
			$this->creation_date = $creation_date;
		}
	}

	public function addLike(int $user_id) {
		global $db;
		if (!$this->likes || !in_array($user_id, $this->likes)) {
			$this->likes[] = $user_id;
			$this->likes_count++;
			$r = $db->exec("insert into likes values($user_id, $this->id)");
			return true;
		}
		return false;
	}

	public function toArray() {
		return ['id' => $this->id,
			'user_id' => $this->user_id,
			'name' => $this->image,
			'image' => $this->image,
			'likes_count' => $this->likes_count,
			'likes' => $this->likes];
	}

	public function getid() {
		return $this->id;
	}

	public function setid($value) {
		$this->id = $value;
	}

	public function getcreation_date() {
		return $this->creation_date;
	}

	public function setcreation_date($value) {
		$this->creation_date = $value;
	}

	public function getuserid() {
		return $this->user_id;
	}

	public function setuserid($value) {
		$this->user_id = $value;
	}

	public function getimage() {
		return $this->image;
	}

	public function setimage($value) {
		$this->image = $value;
	}

	/*
	 * return @array of Creations
	*/
	static function getAll($offset=0, $items=5) {
		global $db;

		$pdo_statement = $db->query("select * from creations order by creation_date desc limit $items offset $offset");
		$res = $pdo_statement->fetchAll();
		$creations = [];
		foreach($res as $c)
			$creations[] = new Creation($c['id']);//, $c['img_path'], $c['user_id'], $c['creation_date']);
		return ($creations);
	}

	static function create($image) {
		$c = new Creation(-1);
		$c->setimage($image);
		$c->setuserid($_SESSION['user']->getid());
		$c->setcreation_date(getdate()[0]);
		Creation::save($c);
		return $c;
	}

	static function save(Creation $c) {
		global $db;
		if ($c->id > -1) {
			$r = $db->exec("update creations set
				id = {$c->getid()},
				creation_date = {$c->getcreation_date()},
				img_path = '{$c->getimage()}', ''
			where id= {$c->getid()};");
		} else {
			$r = $db->exec("insert into creations values
				(NULL, '{$c->getuserid()}', '{$c->getimage()}', '{$c->getcreation_date()}');");
			$c->setid($db->lastInsertId());
		}
		return $r;
	}

	static function remove(Creation $c) {
		global $db;
		global $DATAS_DIR;

		$fname = $DATAS_DIR."/".$c->id.".png";
		if (file_exists($fname))
			unlink($fname);
		$r = $db->exec("delete from likes where creation_id = ".$c->getid());
		$r = $db->exec("delete from comments where creation_id = ".$c->getid());
		$r = $db->exec("delete from creations where id=".$c->getid());
		return $r;
	}
}
