<?php
	class Logger
	{
		public static function add($message) 
		{
			if (strlen($message) == 0) {
				return false;
			}
			
			$message = htmlspecialchars(strip_tags($message));
			
			try {
				$db_auth = Db::get_DB('auth', Registry::instance() -> selected_realm_id);
				
				$sth = $db_auth -> prepare('INSERT INTO `' . Config::instance() -> system['mysql']['prefix'] . 'logs` '
										 . 'SET `ip` = :ip, '
										 . '`account` = :account, '
										 . '`msg` = :msg, '
										 . '`date` = :date');
					
				$sth -> bindValue(':account', Account::get_id());
				$sth -> bindValue(':ip', $_SERVER['REMOTE_ADDR']);
				$sth -> bindValue(':msg', $message);
				$sth -> bindValue(':date', time());
				
				$sth -> execute();
				
				return true;
			} catch (Exception $e) {
				return false;
			}
		}
	}
