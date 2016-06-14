<?php
class TransactionLog
{
    protected $userAgent;
    protected $ip;
    protected $dateClass;
	
    public function getLocalDate()
	{
        return $this->dateClass = date("H:i:s d.m.Y");
	}
    public function getIP()
	{
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $this->ip = $_SERVER['HTTP_CLIENT_IP']; 
            }elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                return $this->ip = $_SERVER['HTTP_X_FORWARDED_FOR']; 
            }else {
                return $this->ip = $_SERVER['REMOTE_ADDR']; 
            }
    }
    public function getUserAgent()
	{
        if (strstr($_SERVER['HTTP_USER_AGENT'], 'YandexBot')) {
            return $this->userAgent='YandexBot';
            }elseif (strstr($_SERVER['HTTP_USER_AGENT'], 'Googlebot')) {
            return $this->userAgent='Googlebot';
            }
            return $this->userAgent = $_SERVER['HTTP_USER_AGENT']; 
    }
}

class LogStd extends TransactionLog 
{
    protected $stringCount = 400;
    public function writeLog($userAgent,$ip,$log,$date)
	{
        $write =  file($log);
        $url   =  $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        while(count($array)>$stringCount) array_shift($write);
        $write[] = 
        " |Дата и время собыития|" .$this->dateClass." - "
        ."|IP-адрес|"             .$ip." - "
        ."|Клиентское приложение|".$userAgent." - "
        ."|URL|"                  .$url."\r\n"; 
        file_put_contents($log ,$write);
    }
    public function makeRecord($logFile) 
	{
        $this->writeLog(
            $this->userAgent = $this->getUserAgent(),
            $this->ip        = $this->getIP(),
            $logFile, 
            $this->dateClass = $this->getLocalDate());	
    }
}

class LogMysql extends TransactionLog 
{
    protected $host;
    protected $user;
    protected $password;
    protected $dbName;
	
    public function __construct($host, $user, $password, $dbName)
	{
        $this->host     = $host;
        $this->user     = $user;
        $this->password = $password;
        $this->dbName   = $dbName;
    }
    public function writeLog($host, $user, $password, $dbName)
	{
        $mysqli = new mysqli($host, $user, $password, $dbName);
        $url    =  $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $mysqli->query("INSERT INTO log_info (user_agent, user_url, ip_user) 
                       VALUES ('{$this->getUserAgent()}', '{$url}', '{$this->getIP()}')");
	}
}
 $a       = new LogMysql("localhost","root","","log");
 $logger1 = new LogStd();
 $logger  = new LogStd();
 $a->writeLog("localhost","root","","log");
 $logger->makeRecord("error.log");
 $logger1->makeRecord("localhost.log");
 