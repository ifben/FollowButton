<?php

class Posts extends MulletMapper {
	
	function __construct() {
    /*
	      validates_presence_of   :firstname, :lastname
		attr_accessible :login, :email, :name, :password, :password_confirmation,
      :firstname, :lastname, :fullname, :is_admin
      */
	}
	
	function get() {
	}
	
	function put() {
	}
	
	
	function comment() {
    $arr = json_decode(file_get_contents('php://input'));
  	$conn = new Mullet('guest','guest');
  	$coll = $conn->user->posts;
  	$result = $coll->insert(array(
  	  'title' => $arr->title
  	));
  	

    session_start();
  	require 'lib/facebook.php';
  	$return = 'http://'.$_SESSION['current_user'].'.followbutton.com/profiles/facebook';


    $coll = $conn->user->profiles;
    $cursor = $coll->find(array(
      'username' => $_SESSION['current_user']
    ));
    $user = $cursor->getNext();
  	$f = new Facebook( FB_SEC, FB_AID, $return,	$user->facebook_token );
	
    $result = $f->comment($arr->title,$arr->objid);

  	
  	return array(
  		'ok'=>$result,'msg'=>$result
  	);
	}
	
	function post() {
    $arr = json_decode(file_get_contents('php://input'));
  	$conn = new Mullet('guest','guest');
  	$coll = $conn->user->posts;
  	$result = $coll->insert(array(
  	  'title' => $arr->title
  	));
  	
  	if ($arr->sendfb == 1) {
      session_start();
    	require 'lib/facebook.php';
    	$return = 'http://'.$_SESSION['current_user'].'.followbutton.com/profiles/facebook';


      $coll = $conn->user->profiles;
      $cursor = $coll->find(array(
        'username' => $_SESSION['current_user']
      ));
      $user = $cursor->getNext();
    	$f = new Facebook( FB_SEC, FB_AID, $return,	$user->facebook_token );
  	
      $result = $f->publish($arr->title,$user->facebook_uid);
    }
  	
  	return array(
  		'ok'=>$result,'msg'=>$result
  	);
	}
	
	function delete() {
	}
	
}

/*
def self.is_indexable_by(accessing_user, parent = nil)
    accessing_user.is_admin?
  end

  def self.is_creatable_by(user, parent = nil)
    user == nil or user.is_admin?
  end

  def is_updatable_by(accessing_user, parent = nil)
    id == accessing_user.id or accessing_user.is_admin?
  end

  def is_deletable_by(accessing_user, parent = nil)
    false
  end

  def is_readable_by(user, parent = nil)
    id.eql?(user.id) or user.is_admin?
  end
  
  */
  
  
