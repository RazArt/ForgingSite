<?php
	class Model_Realms
	{
		public function change_info($id, $params)
		{
			$realm_config = Config::instance() -> realms[$id];
			
			if (($params['title'] == $realm_config['title']) 
				&& ($params['address'] == $realm_config['mysql']['0']) 
				&& ($params['port'] == $realm_config['mysql']['1']) 
				&& ($params['user'] == $realm_config['mysql']['2'] || strlen($params['user']) == 0) 
				&& ($params['password'] == $realm_config['mysql']['3']  || strlen($params['password']) == 0) 
				&& ($params['encoding'] == $realm_config['mysql']['4']) 
				&& ($params['db_name'] == $realm_config['mysql']['5']) 
				&& ($params['availability'] == $realm_config['config_availability']))
			{
				return true;
			}
			
			try
			{
				$db_auth = Db::get_DB('auth', Registry::instance() -> selected_realm_id);
				
				$sql = 'UPDATE `' . Config::instance() -> system['mysql']['prefix'] . 'realms` '
					 . 'SET `title` = :title, '
					 . '`address` = :address, '
					 . '`port` = :port, ';
					
				if (strlen($params['user']))
				{
					$sql .= '`user` = :user, ';
				}
				if (strlen($params['password']))
				{
					$sql .= '`password` = :password, ';
				}
				
				$sql .= '`encoding` = :encoding, '
					  . '`db_name` = :db_name, '
					  . '`availability` = :availability '
					  . 'WHERE `id` = :id';
					  
				$sth = $db_auth -> prepare($sql);
				
				$sth -> bindValue(':title', $params['title']);
				$sth -> bindValue(':address', $params['address']);
				$sth -> bindValue(':port', $params['port']);
				if (strlen($params['user']))
				{
					$sth -> bindValue(':user', $params['user']);
				}
				if (strlen($params['password']))
				{
					$sth -> bindValue(':password', $params['password']);
				}
				$sth -> bindValue(':encoding', $params['encoding']);
				$sth -> bindValue(':db_name', $params['db_name']);
				$sth -> bindValue(':availability', $params['availability']);
				$sth -> bindValue(':id', $id);
				
				$sth -> execute();
				
				if ($sth -> rowCount() > 0)
				{
					return true;
				}
				
				return false;
			}
			catch (Exception $e)
			{
				return false;
			}
		}
		
		public function add($params)
		{
			try
			{
				$db_auth = Db::get_DB('auth');
				
				$sth = $db_auth -> prepare('INSERT INTO `' . Config::instance() -> system['mysql']['prefix'] . 'realms` '
										 . 'SET `title` = :title, '
										 . '`address` = :address, '
										 . '`port` = :port, '
										 . '`user` = :user, '
										 . '`password` = :password, '
										 . '`encoding` = :encoding, '
										 . '`db_name` = :db_name, '
										 . '`availability` = :availability');
				
				$sth -> bindValue(':title', $params['title']);
				$sth -> bindValue(':address', $params['address']);
				$sth -> bindValue(':port', $params['port']);
				$sth -> bindValue(':user', $params['user']);
				$sth -> bindValue(':password', $params['password']);
				$sth -> bindValue(':encoding', $params['encoding']);
				$sth -> bindValue(':db_name', $params['db_name']);
				$sth -> bindValue(':availability', $params['availability']);
				
				$sth -> execute();
				
				if ($db_auth -> lastInsertId() > 0)
				{
					return $db_auth -> lastInsertId();
				}
				
				return false;
			}
			catch (Exception $e)
			{
				return false;
			}
		}
		
		public function delete($id)
		{
			try
			{
				$db_auth = Db::get_DB('auth');
				$db_realm = Db::get_DB('realm', $id);
				
				$db_auth -> beginTransaction();
				
				if($db_realm)
				{
					$db_realm -> beginTransaction();

					$sth = $db_auth -> prepare('SELECT guid FROM `' . Config::instance() -> system['mysql']['prefix'] . 'transfers` '
											 . 'WHERE `realm` = :id');
					
					$sth -> bindValue(':id', $id);
					
					$sth -> execute();
					
					$char_guids = [];
					
					foreach ($sth -> fetchall(PDO::FETCH_NUM) as $guid)
					{
						$char_guids[] = $guid[0];
					}
					
					if (sizeof($char_guids) > 0)
					{
						$sql = 'UPDATE `characters` '
							 . 'SET `account` = :account '
							 . 'WHERE `guid` IN (' . implode(',', $char_guids) . ')';
						
						$sth = $db_realm -> prepare($sql);
					
						$sth -> bindValue(':account', Config::instance() -> system['accounts']['temp_removed_characters']);
						
						$sth -> execute();
					}
				}
				
				$sth = $db_auth -> prepare('DELETE FROM `' . Config::instance() -> system['mysql']['prefix'] . 'transfers` '
										  . 'WHERE `realm` = :realm');
										  
				$sth -> bindValue(':realm', $id);
					
				$sth -> execute();
				
				$sth = $db_auth -> prepare('DELETE FROM `' . Config::instance() -> system['mysql']['prefix'] . 'realms` '
										  . 'WHERE `id` = :id');
										  
				$sth -> bindValue(':id', $id);
				
				$sth -> execute();
				
				$db_auth -> commit();
				if($db_realm)
				{
					$db_realm -> commit();
				}
				
				return true;
			}
			catch (Exception $e)
			{
				$db_auth -> rollBack();
				if($db_realm)
				{
					$db_realm -> rollBack();
				}
				
				return false;
			}
		}
	}
