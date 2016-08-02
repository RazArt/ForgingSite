<?php
	class Model_Item
	{
		private $item = [];
		
		public function get_info($entry) 
		{
			$cache_name = 'item_' . $entry . '_' . Registry::instance() -> selected_realm_id;
			
			if (!$item_info = Cache::load_cache($cache_name))
			{
				
				try
				{
					$db_system = Db::get_DB('system');
					
					if (!$db_world = Db::get_DB('world', Registry::instance() -> selected_realm_id))
					{
						
						return false;
					}
					
					if (!isset(Config::instance() -> realms[Registry::instance() -> selected_realm_id]) 
						|| !Config::instance() -> realms[Registry::instance() -> selected_realm_id]['availability'])
					{
						return false;
					}
					
					$sth = $db_world -> prepare('SELECT * FROM `item_template` '
											  . 'WHERE `entry` = :entry '
											  . 'LIMIT 1');
					
					$sth -> bindValue(':entry', $entry);
					
					$sth -> execute();

					if (!$item_info = $sth -> fetchall(PDO::FETCH_ASSOC)[0])
					{
						return false;
					}
					
					if ($item_info['socketBonus'] > 0)
					{
						$sth = $db_system -> prepare('SELECT description FROM `' . Config::instance() -> system['mysql']['prefix'] . 'item_enchantment` '
												   . 'WHERE `id` = :id '
												   . 'LIMIT 1');

						$sth -> bindValue(':id', $item_info['socketBonus']);
						
						$sth -> execute();
						
						$socket_desc = $sth -> fetchall(PDO::FETCH_NUM);
						
						$item_info['socketBonus'] = $socket_desc[0][0];
					}
					
					$sth = $db_system -> prepare('SELECT name FROM `' . Config::instance() -> system['mysql']['prefix'] . 'locales_item` '
											   . 'WHERE `entry` = :entry '
											   . 'LIMIT 1');

					$sth -> bindValue(':entry', $item_info['entry']);
					
					$sth -> execute();
					
					$item_name = $sth -> fetchall(PDO::FETCH_NUM);
					
					$item_info['name'] = $item_name[0][0];
					
					if ($item_info['RequiredSkill'] > 0)
					{
						$sth = $db_system -> prepare('SELECT name FROM `' . Config::instance() -> system['mysql']['prefix'] . 'skill_line` '
												   . 'WHERE `id` = :id '
												   . 'LIMIT 1');

						$sth -> bindValue(':id', $item_info['RequiredSkill']);
						
						$sth -> execute();
						
						$skill_desc = $sth -> fetchall(PDO::FETCH_NUM);
						
						$item_info['RequiredSkill'] = $skill_desc[0][0];
					}
					
					if ($item_info['RequiredReputationFaction'] > 0)
					{
						$sth = $db_system -> prepare('SELECT name FROM `' . Config::instance() -> system['mysql']['prefix'] . 'faction` '
												   . 'WHERE `id` = :id '
												   . 'LIMIT 1');

						$sth -> bindValue(':id', $item_info['RequiredReputationFaction']);
						
						$sth -> execute();
						
						$faction_desc = $sth -> fetchall(PDO::FETCH_NUM);
						
						$item_info['RequiredReputationFaction'] = $faction_desc[0][0];
					}
					
					foreach(range(1, 5) as $num)
					{
						if ($item_info['spellid_' . $num] > 0)
						{
							$spell = &$item_info['spells'][];
							
							$sth = $db_system -> prepare('SELECT * FROM `' . Config::instance() -> system['mysql']['prefix'] . 'spell` '
													   . 'WHERE `id` = :id '
													   . 'LIMIT 1');

							$sth -> bindValue(':id', $item_info['spellid_' . $num]);
							
							$sth -> execute();
							
							$spell = $sth -> fetchall(PDO::FETCH_ASSOC);
							
							$spell = $spell[0];
							
							$spell['Description'] = strtr($spell['Description'], array("\r" => '', "\n" => '<br />'));
							
							$spell['Description'] = str_replace(['${','}'], ['[',']'], $spell['Description']);
							
							preg_match_all("/([$][0-9]{0,10}[a-z]{1}[0-9]{0,1})/", $spell['Description'], $result);
							
							foreach($result[0] as $value)
							{
								preg_match("/^[$]([0-9]{0,10})([a-z]{1})([0-9]{0,1})/", $value, $var);
								
								$t_spell['mod'] = $var[2];
								$t_spell['num'] = $var[3];
								
								$sth = $db_system -> prepare('SELECT * FROM `' . Config::instance() -> system['mysql']['prefix'] . 'spell` '
															   . 'WHERE `id` = :id '
															   . 'LIMIT 1');

								$sth -> bindValue(':id', $var[1] > 0 ? $var[1] : $spell['id']);
								
								$sth -> execute();
								
								$sub_spell = $sth -> fetchall(PDO::FETCH_ASSOC);
								
								$sub_spell = $sub_spell[0];
							
								
								
								if ($var[2] == 's' 
									|| $var[2] == 'm') 
								{
									$r_str = abs($sub_spell['EffectBasePoints_' . $var[3]] + $sub_spell['EffectBaseDice_' . $var[3]]);
									
									if ($sub_spell['EffectDieSides_' . $var[3]] > $sub_spell['EffectBaseDice_' . $var[3]] 
										&& ($sub_spell['EffectDieSides_' . $var[3]] - $sub_spell['EffectBaseDice_' . $var[3]] != 1))
									{
										$r_str .= ' - '.abs($sub_spell['EffectBasePoints_' . $var[3]] + $sub_spell['EffectDieSides_' . $var[3]]);
									}
								}
								
								if ($var[2] == 'd') 
								{
									$sth = $db_system -> prepare('SELECT duration_1 FROM `' . Config::instance() -> system['mysql']['prefix'] . 'spell_duration` '
															   . 'WHERE `id` = :id '
															   . 'LIMIT 1');

									$sth -> bindValue(':id', $sub_spell['DurationIndex']);
									
									$sth -> execute();
									
									$spell_duration = $sth -> fetchall(PDO::FETCH_NUM);
									
									$spell_duration = $spell_duration[0][0] / 1000;
									
									if ($spell_duration >= 24*3600) {$time_text.= intval($spell_duration/(24*3600)) . ' д'; if ($spell_duration%=24*3600) $time_text.=' ';
									} elseif ($spell_duration >= 3600) {$time_text.= intval($spell_duration/3600) . ' ч'; if ($spell_duration%=3600) $time_text.=' ';
									} elseif ($spell_duration >= 60) {$time_text.= intval($spell_duration/60) . ' мин'; if ($spell_duration%=60) $time_text.=' ';
									} elseif ($spell_duration > 0) {$time_text .= $spell_duration . ' сек';}
									
									$r_str = $time_text;
								}
								
								if ($var[2] == 't') 
								{
									$r_str = $sub_spell['EffectAmplitude_'] . $var[3] ? $sub_spell['EffectAmplitude_' . $var[3]]/1000 : 5;
								}
								
								if ($var[2] == 'o')
								{
									$sth = $db_system -> prepare('SELECT duration_1 FROM `' . Config::instance() -> system['mysql']['prefix'] . 'spell_duration` '
															   . 'WHERE `id` = :id '
															   . 'LIMIT 1');

									$sth -> bindValue(':id', $sub_spell['DurationIndex']);
									
									$sth -> execute();
									
									$spell_duration = $sth -> fetchall(PDO::FETCH_NUM);
									
									$spell_duration = $spell_duration[0][0] / 1000;
									
									$spell_amplitude = $sub_spell['EffectAmplitude_1'] ? $sub_spell['EffectAmplitude_' . $var[3]]/1000 : 5;
									
									$sub_spell = @mysql_fetch_array(mysql_query("SELECT * FROM `".$func_mysql_db['cms']."`.`armory_spell` WHERE id = '$spell_id' LIMIT 1", $ConnectDB['cms']));
									
									$r_str = intval(abs($sub_spell['EffectBasePoints_' . $var[3]] + $sub_spell['EffectBaseDice_' . $var[3]]) * $spell_duration / $spell_amplitude);
									
									if ($sub_spell['EffectDieSides_' . $var[3]] > $sub_spell['EffectBaseDice_' . $var[3]] 
										&& ($sub_spell['EffectDieSides_' . $var[3]] - $sub_spell['EffectBaseDice_' . $var[3]] != 1)) 
									{
										$r_str .= intval((abs($sub_spell['EffectBasePoints_' . $var[3]] + $sub_spell['EffectDieSides_' . $var[3]])) * $spell_duration / $spell_amplitude); 
									}
								}
								
								if ($var[2] == 'r')
								{
									$sth = $db_system -> prepare('SELECT maxRange FROM `' . Config::instance() -> system['mysql']['prefix'] . 'spell_range` '
															   . 'WHERE `id` = :id '
															   . 'LIMIT 1');

									$sth -> bindValue(':id', $sub_spell['rangeIndex']);
									
									$sth -> execute();
									
									$spell_range = $sth -> fetchall(PDO::FETCH_NUM);
									
									$r_str = $spell_range[0][0] / 1000;
								}
								
								if ($var[2] == 'x')
								{
									$r_str = $sub_spell['EffectChainTarget_' . $var[3]];
								}
								
								if ($var[2] == 'v')
								{
									$r_str = $sub_spell['AffectedTargetLevel'];
								}
								
								if ($var[2] == 'b')
								{
									$r_str = $sub_spell['EffectPointsPerComboPoint_' . $var[3]];
								}
								
								if ($var[2] == 'e')
								{
									$r_str = $sub_spell['EffectMultipleValue_' . $var[3]];
								}
								
								if ($var[2] == 'i')
								{
									$r_str = $sub_spell['MaxAffectedTargets'];
								}
								
								if ($var[2] == 'f')
								{
									$r_str = $sub_spell['DmgMultiplier_' . $var[3]];
								}
								
								if ($var[2] == 'q')
								{
									$r_str = $sub_spell['EffectMiscValue_' . $var[3]];
								}
								
								if ($var[2] == 'h')
								{
									$r_str = $sub_spell['procChance'];
								}
								
								if ($var[2] == 'n')
								{
									$r_str = $sub_spell['procCharges'];
								}
								
								if ($var[2] == 'u')
								{
									$r_str = $sub_spell['StackAmount'];
								}
								
								if ($var[2] == "a")
								{
									$sth = $db_system -> prepare('SELECT * FROM `' . Config::instance() -> system['mysql']['prefix'] . 'spell_duration` '
															   . 'WHERE `id` = :id '
															   . 'LIMIT 1');

									$sth -> bindValue(':id', $sub_spell['EffectRadiusIndex_' . $var[3]]);
									
									$sth -> execute();
									
									$spell_radius = $sth -> fetchall(PDO::FETCH_ASSOC);
									
									if ($radius[0] == 0 || $radius[0] == $radius[2])
									{
										$r_str = $radius[2];
									}
									else
									{
										$r_str = $radius[0] - $radius[2]; 
									}
								}
								
								$spell['Description'] = str_replace($value, $r_str, $spell['Description']);
							}
							
							preg_match_all("/[[]([\S]{1,500})[]]/", $spell['Description'], $result);
							
							foreach($result[1] as $value)
							{
								eval('$test = abs(round(' . $value . '));');
								$spell['Description'] = str_replace('[' . $value . ']', $test, $spell['Description']);
							}
							
							$item_info['spellDesc_' . $num] = $spell['Description'];
						}
					}
					
					cache::save_cache($cache_name, $item_info);
					
					return $item_info;
				}
				catch (Exception $e)
				{
					return false;
				}
			}
			else
			{
				return $item_info;
			}
		}
	}
