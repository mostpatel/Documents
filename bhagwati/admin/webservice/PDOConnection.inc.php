<?php


class PDOConnection
{
	
	private $DBHandler;
	
	private $errorMsg;
	
	private $host,$database,$user,$password;
	
	function __construct($host,$database,$user,$password)
	{
		$this->host=$host;
		$this->database=$database;
		$this->user=$user;
		$this->password=$password;
	}
	
	function connect()
	{
		try
		{
			$this->DBHandler=new PDO('mysql:host='.$this->host.';dbname='.$this->database,$this->user,$this->password);
			$this->DBHandler->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
			return $this->DBHandler;
		}
		catch(PDOException $e)
		{
			$this->errorMsg=$e->getMessage();
			return null;
		}
			
	}
	
	function getErrorMsg()
	{
		return $this->errorMsg;
	}	
	
}


?>