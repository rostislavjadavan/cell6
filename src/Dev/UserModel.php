<?php

namespace Dev;

class UserModel {

	private $id;
	private $username;
	private $email;
	
	function getId() {
		return $this->id;
	}

	function getUsername() {
		return $this->username;
	}

	function getEmail() {
		return $this->email;
	}

	function setId($id) {
		$this->id = $id;
	}

	function setUsername($username) {
		$this->username = $username;
	}

	function setEmail($email) {
		$this->email = $email;
	}
}