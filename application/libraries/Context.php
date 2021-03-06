<?php
class Context {
	
	function login($email, $geslo)
	{
		//Checks username and password.
		//Sets session data if correct.
		//Returns true on success.
		//Returns false on failure.
		
		$CI =& get_instance();
		$CI->load->model('ModelUporabniki');
		
		$ModelUporabniki = new ModelUporabniki();
		$user = $ModelUporabniki->getUporabnika($email, $geslo);
		
		if ($user)
		{
			$userdata = array(
					'loggedIn' => true,
					'userId' => $user->id,
					'userType' => $user->tip
			);
			
			$CI->load->library('session');
			//shranimo v sejo podatke o uporabniku
			$CI->session->set_userdata($userdata);
			
			return true;
		} else {
			return false;
		}
	}
	
	function odjavaUporabnika()
	{
		//pobrišemo sejo ko se odjavimo
		//Clears session data.
		$CI =& get_instance();
		$CI->load->library('session');
		$CI->session->unset_userdata('loggedIn');
		$CI->session->unset_userdata('userId');
		$CI->session->unset_userdata('userType');
		
		return true;
	}
	
	function isLoggedIn()
	{
		$CI =& get_instance();
		$CI->load->library('session');
		return $CI->session->userdata('loggedIn');
	}
	
	function getIdUporabnika()
	{
		$CI =& get_instance();
		$CI->load->library('session');
		if ($CI->session->userdata('loggedIn')) {
			return $CI->session->userdata('userId');
		}
		else {
			return false;
		}
	}
	
	function getTipUporabnika()
	{
		$CI =& get_instance();
		$CI->load->database();
		$CI->load->library('session');
		
		if ($CI->session->userdata('loggedIn'))
		{
			$userId = $CI->session->userdata('userId');
			
			$CI->load->model('ModelUporabniki');
			$ModelUporabniki = new ModelUporabniki();
			$tipUporabnika = $ModelUporabniki->getTipUporabnika($userId); //dobi iz modela userjev tip za trenutnega prijavljenega uporabnika
			
			
			return $tipUporabnika;
		}
		else {
			return 0;
		}
	}
}