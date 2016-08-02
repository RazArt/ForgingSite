<?php
	class Transfer
	{
		public static function add($dump)
		{
			try
			{
				$db_auth = Db::get_DB('auth', Registry::instance() -> selected_realm_id);
				
				if (!isset(Config::instance() -> realms[$dump['realm_id']]) 
					|| !Config::instance() -> realms[$dump['realm_id']]['availability'])
				{
					return false;
				}
				
				$db_realm = Db::get_DB('realm', $dump['realm_id']);
				
				$max_array_size = Config::instance() -> system['mysql']['query_size'];
				
				$db_auth -> beginTransaction();
				$db_realm -> beginTransaction();
				
				$sth = $db_auth -> prepare('SELECT id FROM `' . Config::instance() -> system['mysql']['prefix'] . 'transfers` '
									     . 'WHERE `dump_hash` = :dump_hash '
									     . 'AND (`status` = \'0\' OR `status` = \'3\') '
									     . 'LIMIT 1');
				
				$sth -> bindValue(':dump_hash', $dump['sha1_hash']);
				
				$sth -> execute();
				
				if ($sth -> rowCount() > 0)
				{
					return false;
				}
				
				$char_guid = $dump['char_guid'];
				$char_name = self::generate_name();
				
				$sth = $db_auth -> prepare('INSERT INTO `' . Config::instance() -> system['mysql']['prefix'] . 'transfers` '
									     . 'SET `realm` = :realm, '
									     . '`dump_hash` = :dump_hash, '
									     . '`account` = :account, '
									     . '`guid` = :guid, '
									     . '`char_name` = :char_name, '
									     . '`old_account` = :old_account, '
									     . '`old_password` = :old_password, '
									     . '`old_realmlist` = :old_realmlist, '
									     . '`old_realm` = :old_realm, '
									     . '`old_server` = :old_server, '
									     . '`old_char_name` = :old_char_name, '
									     . '`comment` = :comment');
				
				$sth -> bindValue(':realm', $dump['realm_id']);
				$sth -> bindValue(':dump_hash', $dump['sha1_hash']);
				$sth -> bindValue(':account', Account::get_id());
				$sth -> bindValue(':guid', $char_guid);
				$sth -> bindValue(':char_name', $char_name);
				$sth -> bindValue(':old_account', $dump['old_login']);
				$sth -> bindValue(':old_password', $dump['old_password']);
				$sth -> bindValue(':old_realmlist', $dump['realmlist']);
				$sth -> bindValue(':old_realm', $dump['realm']);
				$sth -> bindValue(':old_server', $dump['old_server']);
				$sth -> bindValue(':old_char_name', $dump['name']);
				$sth -> bindValue(':comment', $dump['comment']);
				
				$sth -> execute();
				
				$transfer_id = $db_auth -> lastInsertId();
				
				$sth = $db_realm -> prepare('UPDATE `characters` '
											   . 'SET `name` = :name, '
											   . '`level` = :level, '
											   . '`gender` = :gender, '
											   . '`totalHonorPoints` = :totalHonorPoints, '
											   . '`arenaPoints` = :arenaPoints, '
											   . '`totalKills` = :totalKills, '
											   . '`money` = :money, '
											   . '`class` = :class, '
											   . '`race` = :race, '
											   . '`account` = :account, '
											   . '`speccount` = :speccount, '
											   . '`taximask` = \'0 0 0 0 0 0 0 0 0 0 0 0 0 0\', '
											   . '`position_x` = \'5741.36\', '
											   . '`position_y` = \'626.982\', '
											   . '`position_z` = \'648.354\', '
											   . '`map` = \'571\', '
											   . '`zone` = \'4395\', '
											   . '`cinematic` = \'1\', '
											   . '`at_login` = 0x08 '
											   . 'WHERE `guid` = :guid');
				
				$sth -> bindValue(':guid', $char_guid);
				$sth -> bindValue(':account', Config::instance() -> system['accounts']['temp_transfer']);
				$sth -> bindValue(':name', $char_name);
				$sth -> bindValue(':level', $dump['level']);
				$sth -> bindValue(':gender', $dump['gender']);
				$sth -> bindValue(':totalHonorPoints', $dump['honor']);
				$sth -> bindValue(':arenaPoints', $dump['arenapoints']);
				$sth -> bindValue(':totalKills', $dump['kills']);
				$sth -> bindValue(':money', $dump['money']);
				$sth -> bindValue(':class', $dump['class']);
				$sth -> bindValue(':race', $dump['race']);
				$sth -> bindValue(':speccount', $dump['specs']);
				
				$sth -> execute();
				
				if (isset($dump['spells']) && sizeof($dump['spells']))
				{
					$arrays = array_chunk(array_unique($dump['spells']), $max_array_size);
					
					$sql = 'INSERT IGNORE INTO `character_spell` '
						 . '(`guid`, `spell`) '
						 . 'VALUES ';
					
					foreach ($arrays as $array)
					{
						
						$sth = $db_realm -> prepare(self::get_sql($sql, 2, sizeof($array)));
						
						$n = 1;
						
						foreach ($array as $value)
						{
							$sth -> bindValue($n, $char_guid);
							++$n;
							
							$sth -> bindValue($n, $value);
							++$n;
							
						}
						
						$sth -> execute();
					}
				}
				
				if (isset($dump['reputations']) && sizeof($dump['reputations']))
				{
					$arrays = array_chunk($dump['reputations'], $max_array_size);
					
					$sql = 'INSERT IGNORE INTO `character_reputation` '
						 . '(`guid`, `faction`, `standing`, `flags`) '
						 . 'VALUES ';
					
					foreach ($arrays as $array)
					{
						$sth = $db_realm -> prepare(self::get_sql($sql, 4, sizeof($array)));
						
						$n = 1;
						
						foreach ($array as $value)
						{
							if ($value[2] == 1119 && $value[0] > 1)
							{
								$sons_of_hordir_quests = true;
							}
							
							$sth -> bindValue($n, $char_guid);
							++$n;
							
							$sth -> bindValue($n, $value[2]);
							++$n;
							
							$sth -> bindValue($n, $value[0]);
							++$n;
							
							$sth -> bindValue($n, $value[1] + 1);
							++$n;
						}
						
						$sth -> execute();
					}
				}
				
				if (isset($dump['achievements']) && sizeof($dump['achievements']))
				{
					$arrays = array_chunk($dump['achievements'], $max_array_size);
					
					$sql = 'INSERT IGNORE INTO `character_achievement` '
						 . '(`guid`, `achievement`, `date`) '
						 . 'VALUES ';

					foreach ($arrays as $array)
					{
						$sth = $db_realm -> prepare(self::get_sql($sql, 3, sizeof($array)));

						$n = 1;
						
						foreach ($array as $value)
						{
							$sth -> bindValue($n, $char_guid);
							++$n;
							
							$sth -> bindValue($n, $value[0]);
							++$n;
							
							$sth -> bindValue($n, $value[1]);
							++$n;
						}
						
						$sth -> execute();
					}
				}
				
				if (isset($dump['aprogress']) && sizeof($dump['aprogress']))
				{
					$arrays = array_chunk($dump['aprogress'], $max_array_size);
					
					$sql = 'INSERT IGNORE INTO `character_achievement_progress` '
						 . '(`guid`, `criteria`, `counter`, `date`) '
						 . 'VALUES ';

					foreach ($arrays as $array)
					{
						$sth = $db_realm -> prepare(self::get_sql($sql, 4, sizeof($array)));

						$n = 1;
						
						foreach ($array as $value)
						{
							$sth -> bindValue($n, $char_guid);
							++$n;
							
							$sth -> bindValue($n, $value[0]);
							++$n;
							
							$sth -> bindValue($n, $value[1]);
							++$n;
							
							$sth -> bindValue($n, time());
							++$n;
						}
						
						$sth -> execute();
					}
				}
				
				if (isset($dump['skills']) && sizeof($dump['skills']))
				{
					$arrays = array_chunk($dump['skills'], $max_array_size);
					
					$sql = 'INSERT IGNORE INTO `character_skills` '
						 . '(`guid`, `skill`, `value`, `max`) '
						 . 'VALUES ';
				
					foreach ($arrays as $array)
					{
						$sth = $db_realm -> prepare(self::get_sql($sql, 4, sizeof($array)));

						$n = 1;
						
						foreach ($array as $value)
						{
							$sth -> bindValue($n, $char_guid);
							++$n;
							
							$sth -> bindValue($n, $value[0]);
							++$n;
							
							$sth -> bindValue($n, $value[1]);
							++$n;
							
							$sth -> bindValue($n, $value[2]);
							++$n;
						}
						
						$sth -> execute();
					}
				}

				if (isset($dump['glyphs']) && sizeof($dump['glyphs']))
				{
					$sql = 'INSERT IGNORE INTO `character_glyphs` '
					     . 'SET `guid` = :guid, '
					     . '`spec` = :spec, '
					     . '`glyph1` = :glyph1, '
					     . '`glyph2` = :glyph2, '
					     . '`glyph3` = :glyph3, '
					     . '`glyph4` = :glyph4, '
					     . '`glyph5` = :glyph5, '
					     . '`glyph6` = :glyph6';
						 
					$sth = $db_realm -> prepare($sql);
																		
					$sth -> bindValue(':guid', $char_guid);
					
					foreach ($dump['glyphs'] as $key => $value)
					{
						$sth -> bindValue(':spec', $key);
						$sth -> bindValue(':glyph1', $value[1]);
						$sth -> bindValue(':glyph2', $value[2]);
						$sth -> bindValue(':glyph3', $value[3]);
						$sth -> bindValue(':glyph4', $value[4]);
						$sth -> bindValue(':glyph5', $value[5]);
						$sth -> bindValue(':glyph6', $value[6]);
						
						$sth -> execute();
					}
				}
				
				if (isset($dump['items']) && sizeof($dump['items']))
				{
					$arrays = array_chunk($dump['items'], $max_array_size);
					
					$sql = 'INSERT INTO `mail_external` '
						 . '(`receiver`, `item`, `item_count`) '
						 . 'VALUES ';

					foreach ($arrays as $array)
					{
						$sth = $db_realm -> prepare(self::get_sql($sql, 3, sizeof($array)));
						
						$n = 1;
						
						foreach ($array as $value)
						{
							$sth -> bindValue($n, $char_guid);
							$n++;
							
							$sth -> bindValue($n, $value[0]);
							$n++;
							
							$sth -> bindValue($n, $value[1]);
							$n++;
						}
						
						$sth -> execute();
					}
				}
				
				if ($dump['class'] == 6)
				{
					$sth = $db_realm -> prepare('INSERT IGNORE INTO `character_queststatus_rewarded` (`guid`,`quest`) VALUES'
												   . '(:guid, 12593), (:guid, 12641), (:guid, 12657), '
												   . '(:guid, 12670), (:guid, 12678), (:guid, 12679), '
												   . '(:guid, 12680), (:guid, 12687), (:guid, 12697), '
												   . '(:guid, 12698), (:guid, 12700), (:guid, 12701), '
												   . '(:guid, 12706), (:guid, 12711), (:guid, 12714), '
												   . '(:guid, 12715), (:guid, 12716), (:guid, 12717), '
												   . '(:guid, 12719), (:guid, 12720), (:guid, 12722), '
												   . '(:guid, 12723), (:guid, 12724), (:guid, 12725), '
												   . '(:guid, 12727), (:guid, 12733), (:guid, 12738), '
												   . '(:guid, 12747), (:guid, 12751), (:guid, 12754), '
												   . '(:guid, 12755), (:guid, 12756), (:guid, 12757), '
												   . '(:guid, 12778), (:guid, 12779), (:guid, 12800), '
												   . '(:guid, 12801), (:guid, 12842), (:guid, 12848), '
												   . '(:guid, 12849), (:guid, 12850), (:guid, 13165), '
												   . '(:guid, 13166), (:guid, 13189);');
																		
					$sth -> bindValue(':guid', $char_guid);
					
					$sth -> execute();
				}
				
				if (isset($sons_of_hordir_quests))
				{
					$sth = $db_realm -> prepare('INSERT IGNORE INTO `character_queststatus_rewarded` (`guid`,`quest`) VALUES '
												   . '(:guid, 12841), (:guid, 12843), (:guid, 12846), '
												   . '(:guid, 12851), (:guid, 12856), (:guid, 12886), '
												   . '(:guid, 12900), (:guid, 12905), (:guid, 12906), '
												   . '(:guid, 12907), (:guid, 12908), (:guid, 12915), '
												   . '(:guid, 12921), (:guid, 12924), (:guid, 12969), '
												   . '(:guid, 12970), (:guid, 12971), (:guid, 12972), '
												   . '(:guid, 12983), (:guid, 12996), (:guid, 12997), '
												   . '(:guid, 13061), (:guid, 13062), (:guid, 13063), '
												   . '(:guid, 13064);');
																		
					$sth -> bindValue(':guid', $char_guid);
					
					$sth -> execute();
				}
				
				$db_auth -> commit();
				$db_realm -> commit();
				
				return $transfer_id;
			}
			catch (Exception $e)
			{
				$db_auth -> rollBack();
				$db_realm -> rollBack();
				
				return false;
			}
		}
		
		public static function get_status($id)
		{
			try
			{
				$db_auth = Db::get_DB('auth');
				
				$sth = $db_auth -> prepare('SELECT status FROM `' . Config::instance() -> system['mysql']['prefix'] . 'transfers` '
									     . 'WHERE `id` = :id '
									     . 'LIMIT 1');
				
				$sth -> bindValue(':id', $id);
				
				$sth -> execute();
				
				if(!$status = $sth -> fetch(PDO::FETCH_ASSOC))
				{
					return false;
				}
				
				return $status['status'];
			}
			catch (Exception $e)
			{
				return false;
			}
		}
		
		public static function get_realm_id($id)
		{
			try
			{
				$db_auth = Db::get_DB('auth');
				
				$sth = $db_auth -> prepare('SELECT realm FROM `' . Config::instance() -> system['mysql']['prefix'] . 'transfers` '
										 . 'WHERE `id` = :id '
										 . 'LIMIT 1');
				
				$sth -> bindValue(':id', $id);
				
				$sth -> execute();
				
				if(!$realm = $sth -> fetch(PDO::FETCH_ASSOC))
				{
					return false;
				}
				
				return $realm['realm'];
			}
			catch (Exception $e)
			{
				return false;
			}
		}
		
		public static function get_transferable_characters_count()
		{
			try
			{
				$db_auth = Db::get_DB('auth');
				
				$sth = $db_auth -> prepare('SELECT id FROM `' . Config::instance() -> system['mysql']['prefix'] . 'transfers` '
										 . 'WHERE `account` = :account '
										 . 'AND `status` != 1 '
										 . 'AND `status` != 2 '
										 . 'LIMIT 1');
				
				$sth -> bindValue(':account', Account::get_id());
				
				$sth -> execute();
				
				return $sth -> rowCount();
			}
			catch (Exception $e)
			{
				return false;
			}
		}
		
		public static function set_status($id, $type, $abort_reason = null)
		{
			try
			{
				$db_auth = Db::get_DB('auth');
				
				$db_auth -> beginTransaction();
				
				if (!Account::can_transfer())
				{
					$sth = $db_auth -> prepare('SELECT guid, realm FROM `' . Config::instance() -> system['mysql']['prefix'] . 'transfers` '
										     . 'WHERE `id` = :id '
										     . 'AND `status` = \'0\' '
										     . 'AND `gm_account` = \'0\' '
										     . 'AND `account` = :account '
										     . 'LIMIT 1');
					
					$sth -> bindValue(':account', Account::get_id());
				}
				else
				{
					$sth = $db_auth -> prepare('SELECT guid, realm, account FROM `' . Config::instance() -> system['mysql']['prefix'] . 'transfers` '
										     . 'WHERE `id` = :id '
										     . 'AND `status` = \'0\' '
										     . 'AND `gm_account` = :gm_account '
										     . 'LIMIT 1');
					
					$sth -> bindValue(':gm_account', Account::get_id());
				}
				
				$sth -> bindValue(':id', $id);
				
				$sth -> execute();
				
				if(!$transfer_info = $sth -> fetch(PDO::FETCH_ASSOC))
				{
					return false;
				}
				
				if (!isset(Config::instance() -> realms[$transfer_info['realm']]) 
					|| !Config::instance() -> realms[$transfer_info['realm']]['availability'])
				{
					return false;
				}
				
				$db_realm = Db::get_DB('realm', $transfer_info['realm']);
				
				$db_realm -> beginTransaction();
				
				$sth = $db_realm -> prepare('UPDATE `characters` '
										  . 'SET `account` = :account, '
										  . '`at_login` = :at_login '
										  . 'WHERE `guid` = :guid');
				
				$sth -> bindValue(':guid', $transfer_info['guid']);
				
				if ($type == 0)
				{
					$sth -> bindValue(':account', Config::instance() -> system['accounts']['temp_removed_characters']);
					$sth -> bindValue(':at_login', 0);
				}
				elseif ($type == 1)
				{
					$sth -> bindValue(':account', $transfer_info['account']);
					$sth -> bindValue(':at_login', 9);
				}
				
				$sth -> execute();
				
				$sth = $db_auth -> prepare('UPDATE `' . Config::instance() -> system['mysql']['prefix'] . 'transfers` '
										 . 'SET `status` = :status, '
										 . '`active` = :active, '
										 . '`reason` = :reason '
										 . 'WHERE `id` = :id');
				
				if ($type == 0)
				{
					if (!Account::can_transfer())
					{
						$sth -> bindValue(':status', 1);
						$sth -> bindValue(':active', 0);
					}
					else
					{
						$sth -> bindValue(':status', 2);
						$sth -> bindValue(':active', 1);
					}
					
					$sth -> bindValue(':reason', $abort_reason);
				}
				elseif ($type == 1)
				{
					$sth -> bindValue(':active', 1);
					
					$sth -> bindValue(':status', 3);
					
					$sth -> bindValue(':reason', '');
				}
				
				$sth -> bindValue(':id', $id);
				
				$sth -> execute();
				
				$db_auth -> commit();
				$db_realm -> commit();
			}
			catch (Exception $e)
			{
				$db_auth -> rollBack();
				
				if (isset($db_realm))
				{
					$db_realm -> rollBack();
				}
				
				return false;
			}
			
			return true;
		}
		
		public static function get_list()
		{
			try
			{	
				$db_auth = Db::get_DB('auth');
				
				if(Account::is_admin())
				{
					$sth = $db_auth -> prepare('SELECT * FROM `' . Config::instance() -> system['mysql']['prefix'] . 'transfers` '
											 . 'WHERE `status` = \'0\' '
											 . 'ORDER BY `id` ASC LIMIT :limit');
					
					$sth -> bindValue(':limit', Config::instance() -> system['mysql']['list_limit']['worker'], PDO::PARAM_INT);
				}
				elseif(Account::can_transfer())
				{
					$sth = $db_auth -> prepare('SELECT * FROM `' . Config::instance() -> system['mysql']['prefix'] . 'transfers` '
											 . 'WHERE `status` = \'0\' '
											 . 'AND (`gm_account` = \'0\' OR `gm_account` = :gm_account)'
											 . 'ORDER BY `id` ASC LIMIT :limit');
					
					$sth -> bindValue(':gm_account', Account::get_id());
					$sth -> bindValue(':limit', Config::instance() -> system['mysql']['list_limit']['worker'], PDO::PARAM_INT);
				}
				else
				{
					$sth = $db_auth -> prepare('SELECT id, status, realm, char_name, old_char_name, old_server, old_realm, gm_account, reason FROM `' . Config::instance() -> system['mysql']['prefix'] . 'transfers` '
											 . 'WHERE `account` = :account '
											 . 'AND `active` = \'1\' '
											 . 'ORDER BY `status` ASC LIMIT :limit');
					
					$sth -> bindValue(':account', Account::get_id(), PDO::PARAM_INT);
					$sth -> bindValue(':limit', Config::instance() -> system['mysql']['list_limit']['user'], PDO::PARAM_INT);
				}
				
				
				$sth -> execute();
				
				$transfer = $sth -> fetchall(PDO::FETCH_ASSOC);
				
				if (is_array($transfer))
				{
					return $transfer;
				}
				
				return false;
			}
			catch (Exception $e)
			{
				return false;
			}
		}
		
		public static function get_info($id)
		{
			try
			{
				$db_auth = Db::get_DB('auth');
				
				if (Account::is_admin())
				{
					$sth = $db_auth -> prepare('SELECT id, realm, char_name, old_account, old_password, old_server, old_realmlist, old_realm, old_char_name, comment FROM `' . Config::instance() -> system['mysql']['prefix'] . 'transfers` '
											 . 'WHERE `id` = :id '
											 . 'AND `status` = \'0\' '
											 . 'LIMIT 1');
				}
				elseif (Account::can_transfer())
				{
					$sth = $db_auth -> prepare('SELECT id, realm, char_name, old_account, old_password, old_server, old_realmlist, old_realm, old_char_name, comment FROM `' . Config::instance() -> system['mysql']['prefix'] . 'transfers` '
											 . 'WHERE `id` = :id '
											 . 'AND `status` = \'0\' '
											 . 'AND `gm_account` = :gm_account '
											 . 'LIMIT 1');
					
					$sth -> bindValue(':gm_account', Account::get_id());
				}
				elseif (!Account::can_transfer())
				{
					$sth = $db_auth -> prepare('SELECT id, old_account, old_password, old_realmlist, old_char_name, old_realm, old_server, comment, gm_account, status, reason FROM `' . Config::instance() -> system['mysql']['prefix'] . 'transfers` '
											 . 'WHERE `id` = :id '
											 . 'AND `active` = \'1\' '
											 . 'AND `status` = \'0\' '
											 . 'AND `account` = :account '
											 . 'LIMIT 1');
					
					$sth -> bindValue(':account', Account::get_id());
				}
				
				$sth -> bindValue(':id', $id);
				
				$sth -> execute();
				
				$transfer_info = $sth -> fetch(PDO::FETCH_ASSOC);
				
				if (is_array($transfer_info))
				{
					return $transfer_info;
				}
				
				return false;
			}
			catch (Exception $e)
			{
				return false;
			}
		}
		
		public static function update_info($info)
		{
			try
			{
				$db_auth = Db::get_DB('auth');
				
				$sth = $db_auth -> prepare('UPDATE `' . Config::instance() -> system['mysql']['prefix'] . 'transfers` '
									     . 'SET `old_account` = :old_account, '
									     . '`old_password` = :old_password, '
									     . '`old_server` = :old_server, '
									     . '`old_realmlist` = :old_realmlist, '
									     . '`old_realm` = :old_realm, '
									     . '`old_char_name` = :old_char_name, '
									     . '`comment` = :comment '
									     . 'WHERE `id` = :id '
										 . 'AND `status` = \'0\' '
										 . 'AND `gm_account` = \'0\'');
				
				$sth -> bindValue(':id', $info['transfer_id']);
				$sth -> bindValue(':old_account', $info['old_login']);
				$sth -> bindValue(':old_password', $info['old_password']);
				$sth -> bindValue(':old_char_name', $info['old_char_name']);
				$sth -> bindValue(':old_server', $info['old_server']);
				$sth -> bindValue(':old_realmlist', $info['old_realmlist']);
				$sth -> bindValue(':old_realm', $info['old_realm']);
				$sth -> bindValue(':comment', $info['comment']);

				$sth -> execute();
				
				if ($sth -> rowCount() > 0)
				{
					return true;
				}
			}
			catch (Exception $e)
			{
				return false;
			}
		}
		
		public static function set_gm_account($type, $id)
		{
			try
			{
				$db_auth = Db::get_DB('auth');
				
				$db_auth -> beginTransaction();
				
				if ($type == 0)
				{
					$sth = $db_auth -> prepare('UPDATE `' . Config::instance() -> system['mysql']['prefix'] . 'transfers` '
										     . 'SET `gm_account` = :gm_account '
										     . 'WHERE `gm_account` = \'0\' '
										     . 'AND `id` = :id '
										     . 'AND `status` = \'0\'');
					
					$sth -> bindValue(':gm_account', Account::get_id());
				}
				elseif ($type == 1 && Account::is_admin())
				{
					$sth = $db_auth -> prepare('UPDATE `' . Config::instance() -> system['mysql']['prefix'] . 'transfers` '
											 . 'SET `gm_account` = \'0\' '
											 . 'WHERE `id` = :id '
											 . 'AND `status` = \'0\'');
				}
				
				$sth -> bindValue(':id', $id);
				
				$sth -> execute();
				
				if (!$sth -> rowCount())
				{
					$db_auth -> rollBack();
					
					return false;
				}
				
				$sth = $db_auth -> prepare('SELECT guid, realm FROM `' . Config::instance() -> system['mysql']['prefix'] . 'transfers` '
										 . 'WHERE `id` = :id '
										 . 'LIMIT 1');
					
				$sth -> bindValue(':id', $id);
				
				$sth -> execute();
				
				if(!$char_info = $sth -> fetch(PDO::FETCH_ASSOC))
				{
					$db_auth -> rollBack();
					
					return false;
				}
				
				if (!isset(Config::instance() -> realms[$char_info['realm']]) 
					|| !Config::instance() -> realms[$char_info['realm']]['availability'])
				{
					$db_auth -> rollBack();
					
					return false;
				}
				
				$db_realm = Db::get_DB('realm', $char_info['realm']);
				
				$db_realm -> beginTransaction();
				
				$sth = $db_realm -> prepare('UPDATE `characters` '
											   . 'SET `account` = :account '
											   . 'WHERE `guid` = :guid');	
								  
				$sth -> bindValue(':account', Account::get_id());
				$sth -> bindValue(':guid', $char_info['guid']);
				
				$sth -> execute();
				
				$db_auth -> commit();
				$db_realm -> commit();
					
				return true;
			}
			catch (Exception $e)
			{
				$db_auth -> rollBack();
				
				if (isset($db_realm))
				{
					$db_realm -> rollBack();
				}
				
				return false;
			}
			
			return false;
		}
		
		public static function hide($id)
		{
			try
			{
				$db_auth = Db::get_DB('auth');
				
				$sth = $db_auth -> prepare('UPDATE `' . Config::instance() -> system['mysql']['prefix'] . 'transfers` '
									     . 'SET `active` = \'0\' '
									     . 'WHERE `gm_account` = \'0\' '
									     . 'AND `id` = :id '
									     . 'AND `status` != \'0\'');
				
				$sth -> bindValue(':id', $id);
				
				$sth -> execute();
				
				if ($sth -> rowCount() > 0)
				{
					return true;
				}
			}
			catch (Exception $e)
			{
				return false;
			}
			
			return false;
		}
		
		private static function get_sql($sql, $params_count, $count)
		{
			for($i = 0; $i < $count; ++$i)
			{
				$inQuery[] = '(' . implode(',', array_fill(0, $params_count, '?')) . ')';
			}
			
			return $sql . implode(', ', $inQuery) . ';';
		}
		
		private static function generate_name() 
		{
			$result = '';
			
			$arr = array('a','b','c','d','e','f',
						 'g','h','i','j','k','l',
						 'm','n','o','p','r','s',
						 't','u','v','x','y','z');
			
			for($i = 0; $i < 10; $i++) 
			{
				$index = rand(0, count($arr) - 1);
				
				$result .= $arr[$index];
			}
			
			return $result;
		}
	}
