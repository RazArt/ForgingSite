<?php
	class Dump
	{
		static public function decode_dump($char_dump)
		{
			if (!$decodedDump = json_decode(stripslashes(base64_decode($char_dump)), true))
			{
				return false;
			}
			
			if ((int)$decodedDump['game_info']['clientbuild'] != 12340)
			{
				return false;
			}
			
			if (!in_array(strtolower($decodedDump['game_info']['locale']), array('ruru', 'engb')))
			{
				return false;
			}
			
			$charData['locale'] = strtolower($decodedDump['game_info']['locale']);
			
			$charData['realmlist'] = htmlspecialchars($decodedDump['game_info']['realmlist']);

			$charData['realm'] = htmlspecialchars($decodedDump['game_info']['realm']);
			
			if (!$charData['name'] = mb_convert_case($decodedDump['unit_info']['name'], MB_CASE_TITLE, 'UTF-8'))
			{
				return false;
			}

			if (!$charData['race'] = self::convert_race($decodedDump['unit_info']['race']))
			{
				return false;
			}
			
			if (!$charData['class'] = self::convert_class($decodedDump['unit_info']['class']))
			{
				return false;
			}
			
			$charData['level'] = max_value($decodedDump['unit_info']['level'], Config::instance() -> system['max_value']['level']);

			$charData['gender'] = (int)$decodedDump['unit_info']["gender"] - 2 == 1 ? 1 : 0;
			
			$charData['specs'] = (int)$decodedDump['unit_info']["specs"] == 2 ? 2 : 1;
			
			if ($charData['specs'] == 2)
			{
				$charData['spells'][] = 63644;
				$charData['spells'][] = 63645;
			}
			
			$charData['honor'] = max_value($decodedDump['unit_info']['honor'], Config::instance() -> system['max_value']['honor']);
			
			$charData['arenapoints'] = max_value($decodedDump['unit_info']['arenapoints'], Config::instance() -> system['max_value']['arenapoints']);
			
			$charData['kills'] = max_value($decodedDump['unit_info']['kills'], -1);
			
			$charData['money'] = max_value($decodedDump['unit_info']['money'], Config::instance() -> system['max_value']['money']);
			
			if (isset($decodedDump['items']) && sizeof($decodedDump['items']) > 0)
			{
				foreach ($decodedDump['items'] as $item)
				{
					$item_entry = (int)$item['e'];
					$item_count = (int)$item['c'];
					
					$gems[0] = (int)$item['g_1'];
					$gems[1] = (int)$item['g_2'];
					$gems[2] = (int)$item['g_3'];
					
					if ($item_entry < 1)
					{
						continue;
					}
					
					$item_data = &$charData['items'][];
					
					$item_count = $item_count < 1 ? 1 : $item_count;
					$item_count = $item_count > 1000 ? 1000 : $item_count;
					
					$item_data[0] = $item_entry;
					$item_data[1] = $item_count;
					
					foreach ($gems as $gem)
					{
						if ($gem < 1 || !$entry = self::get_id($gem, 0))
						{
							continue 2;
						}
						
						$item_data = &$charData['items'][];
						
						$item_data[0] = $entry;
						$item_data[1] = 1;
					}
				}
			}
			
			if (isset($decodedDump['currency']) && sizeof($decodedDump['currency']) > 0)
			{
				foreach ($decodedDump['currency'] as $item)
				{
					$item_entry = (int)$item['e'];
					$item_count = (int)$item['c'];
					
					if ($item_entry < 1 || $item_entry == 43308 || $item_entry == 43307)
					{
						continue;
					}
					
					$item_data = &$charData['items'][];
					
					$item_count = $item_count < 1 ? 1 : $item_count;
					$item_count = $item_count > 1000 ? 1000 : $item_count;
					
					$item_data[0] = $item_entry;
					$item_data[1] = $item_count;
				}
			}
			
			if (isset($decodedDump['spells']) && sizeof($decodedDump['spells']) > 0)
			{
				foreach ($decodedDump['spells'] as $spell_id)
				{
					$spell_id = (int)$spell_id;
					
					if ($spell_id == 0)
					{
						continue;
					}
					
					$charData['spells'][] = $spell_id;
				}
			}
			
			if (isset($decodedDump['recipes']) && sizeof($decodedDump['recipes']) > 0)
			{
				foreach ($decodedDump['recipes'] as $spell_id => $value)
				{
					$spell_id = (int)$spell_id;
					
					if ($spell_id == 0)
					{
						continue;
					}
					
					$charData['spells'][] = $spell_id;
				}
			}
			
			if (isset($decodedDump['pets']) && sizeof($decodedDump['pets']) > 0)
			{
				foreach ($decodedDump['pets'] as $spell_id)
				{
					$spell_id = (int)$spell_id;
					
					if ($spell_id < 1)
					{
						continue;
					}
					
					$charData['spells'][] = $spell_id;
				}
			}
			
			if (isset($decodedDump['reputations']) && sizeof($decodedDump['reputations']) > 0)
			{
				foreach ($decodedDump['reputations'] as $key => $value)
				{
					$reputation = (int)$value['v'];
					$flag = (int)$value['f'];
					$faction_name = (string)$value['n'];
					
					if (!$faction_id = self::get_id($faction_name, 2, $charData['locale']))
					{
						continue;
					}
					
					$rep_data = &$charData['reputations'][];
					
					$rep_data[0] = $reputation;
					$rep_data[1] = $flag;
					$rep_data[2] = $faction_id;
				}
			}
			
			if (isset($decodedDump['achievements']) && sizeof($decodedDump['achievements']) > 0)
			{
				foreach ($decodedDump['achievements'] as $value)
				{
					$achiev_id = (int)$value['i'];
					$achiev_date = (int)$value['d'];
					
					if ($achiev_id < 1)
					{
						continue;
					}
					
					$achiev_data = &$charData['achievements'][];
					
					$achiev_data[0] = $achiev_id;
					$achiev_data[1] = $achiev_date;
				}
			}
			
			if (isset($decodedDump['aprogress']) && sizeof($decodedDump['aprogress']) > 0)
			{
				foreach ($decodedDump['aprogress'] as $value)
				{
					$aprogress_id = (int)$value['i'];
					$aprogress_value = (int)$value['v'];
					
					if ($aprogress_id < 1)
					{
						continue;
					}
					
					$aprogress_data = &$charData['aprogress'][];
					
					$aprogress_data[0] = $aprogress_id;
					$aprogress_data[1] = $aprogress_value;
				}
			}
			
			if (isset($decodedDump['skills']) && sizeof($decodedDump['skills']) > 0)
			{
				foreach ($decodedDump['skills'] as $key => $value)
				{
					$skill_name = (string)$value['n'];
					$skill_value = (int)$value['c'];
					$skill_max = (int)$value['m'];
					
					
					if (!$skill_id = self::get_id($skill_name, 3, $charData['locale']))
					{
						continue;
					}
					
					$skill_data = &$charData['skills'][];
					
					$skill_data[0] = $skill_id;
					
					$skill_data[1] = max_value(self::remove_race_skill_bonus($charData['race'], $skill_data[0], $skill_value), 450);
					$skill_data[2] = max_value(self::remove_race_skill_bonus($charData['race'], $skill_data[0], $skill_max), 450);
					
					$spells = self::get_spell_id_for_skill($skill_id, $skill_data[1]);
					
					if (is_array($spells))
					{
						foreach ($spells as $spell_id)
						{
							$charData['spells'][] = $spell_id;
						}
					}
				}
			}
			
			if (isset($decodedDump['glyphs']) && sizeof($decodedDump['glyphs']) > 0)
			{
				foreach ($decodedDump['glyphs'] as $value)
				{
					$glyph_data = &$charData['glyphs'][];
					
					$glyph_data[1] = self::get_id($value[0][0], 1);
					$glyph_data[2] = self::get_id($value[0][1], 1);
					$glyph_data[3] = self::get_id($value[0][2], 1);
					$glyph_data[4] = self::get_id($value[1][0], 1);
					$glyph_data[5] = self::get_id($value[1][1], 1);
					$glyph_data[6] = self::get_id($value[1][2], 1);
				}
			}
			
			return $charData;
		}
		
			
		static public function check_dump_hash($charDump, $sha1_hash)
		{
			if ($sha1_hash != sha1(strrev(base64_decode(strrev($charDump)))))
			{
				return false;
			}
			
			return true;
		}
		
		static private function convert_race($race) 
		{
			switch (mb_strtoupper($race)) 
			{
				case 'HUMAN': return 1;
				case 'ORC': return 2;
				case 'DWARF': return 3;
				case 'NIGHTELF': return 4;
				case 'SCOURGE': return 5;
				case 'TAUREN': return 6;
				case 'GNOME': return 7;
				case 'TROLL': return 8;
				case 'BLOODELF': return 10;
				case 'DRAENEI': return 11;
			}
			
			return false;
		}
		
		static private function convert_class($class) 
		{
			switch (mb_strtoupper($class)) 
			{
				case 'WARRIOR': return 1;
				case 'PALADIN': return 2;
				case 'HUNTER': return 3;
				case 'ROGUE': return 4;
				case 'PRIEST': return 5;
				case 'DEATHKNIGHT': return 6;
				case 'SHAMAN': return 7;
				case 'MAGE': return 8;
				case 'WARLOCK': return 9;
				case 'DRUID': return 11;
			}
			
			return false;
		}
		
		static private function get_id($search_value, $type, $locale = false)
		{
			switch ($type) 
			{
				case 0:
					$file_path = REPLACER_DB_PATH . 'gem_entries';
					$search_value = (string)$search_value;
					break;
					
				case 1:
					if ($search_value == '-1') 
					{
						return 0;
					}
					
					$file_path = REPLACER_DB_PATH . 'glyphs_entries';
					$search_value = (string)$search_value;
					break;
					
				case 2:
					$file_path = REPLACER_DB_PATH . 'factions_' . $locale;
					$search_value = sha1(mb_strtoupper($search_value, 'UTF-8'));
					break;
					
				case 3:
					$file_path = REPLACER_DB_PATH . 'skills_' . $locale;
					$search_value = sha1(mb_strtoupper($search_value, 'UTF-8'));
					break;
			}
			
			if (!file_exists($file_path))
			{
				return false;
			}
			
			$file_content = file_get_contents($file_path);
			
			if (!$begin_pos = strpos($file_content, $search_value . ':'))
			{
				return false;
			}
			
			$begin_pos = $begin_pos + strlen($search_value) + 1;
			
			$required_id_len = strpos($file_content, "\n", $begin_pos) - $begin_pos;
			
			$required_id = (int)substr($file_content, $begin_pos, $required_id_len);
			
			if ($required_id == 0)
			{
				return false;
			}
			
			return $required_id;
		}
		
		static private function remove_race_skill_bonus($race, $skill_id, $value) 
		{
			if ($race == 6 && $skill_id == 182)
			{
				return $value - 5;
			}
			elseif ($race == 7 && $skill_id == 202)
			{
				return $value - 15;
			}
			elseif ($race == 10 && $skill_id == 333)
			{
				return $value - 10;
			}
			elseif ($race == 11 && $skill_id == 755)
			{
				return $value - 5;
			}
			
			return $value;
		}
		
		static private function get_spell_id_for_skill($skill_id, $max_value) 
		{
			$spells = [];
			
			switch($skill_id) 
			{
				case 43:    $spells[] = 201; break;
				case 44:    $spells[] = 196; break;
				case 45:    $spells[] = 264; break;
				case 46:    $spells[] = 266; break;
				case 54:    $spells[] = 198; break;
				case 55:    $spells[] = 202; break;
				case 118:   $spells[] = 674; break;
				case 95:    $spells[] = 204; break;
				case 226:   $spells[] = 5011; break;
				case 228:   $spells[] = 5009; break;
				case 229:   $spells[] = 200; break;
				case 293:   $spells[] = 750; break;
				case 413:   $spells[] = 8737; break;
				case 414:   $spells[] = 9077; break;
				case 415:   $spells[] = 9078; break;
				case 433:   $spells[] = 9116; break;
				case 473:   $spells[] = 15590; break;
				case 633:   $spells[] = 1804; break;
				case 172:   $spells[] = 197; break;
				case 173:   $spells[] = 1180; break;
				case 176:   $spells[] = 2567; break;
				case 136:   $spells[] = 227; break;
				case 160:   $spells[] = 199; break;
				case 162:   $spells[] = 203; break;
				case 776:   $spells[] = 53428; break;
				case 129:
					switch($max_value) {
						case 75:    $spells[] = 3273; break;
						case 150:   $spells[] = 3274; break;
						case 225:   $spells[] = 7924; break;
						case 300:   $spells[] = 10846; break;
						case 375:   $spells[] = 27028; break;
						case 450:   $spells[] = 45542; break;
					}
					break;
				case 164:
					switch($max_value) {
						case 75:    $spells[] = 2018; break;
						case 150:   $spells[] = 3100; break;
						case 225:   $spells[] = 3538; break;
						case 300:   $spells[] = 9785; break;
						case 375:   $spells[] = 29844; break;
						case 450:   $spells[] = 51300; break;
					}
					break;
				case 165:
					switch($max_value) {
						case 75:    $spells[] = 2108; break;
						case 150:   $spells[] = 3104; break;
						case 225:   $spells[] = 3811; break;
						case 300:   $spells[] = 10662; break;
						case 375:   $spells[] = 32549; break;
						case 450:   $spells[] = 51302; break;
					}
					break;
				case 171:
					switch($max_value) {
						case 75:    $spells[] = 2259; break;
						case 150:   $spells[] = 3101; break;
						case 225:   $spells[] = 3464; break;
						case 300:   $spells[] = 11611; break;
						case 375:   $spells[] = 28596; break;
						case 450:   $spells[] = 51304; break;
					}
					break;
				case 182:
					$spells[] = 2383;
				
					switch($max_value) 
					{
						case 75:    $spells[] = 55428; break;
						case 150:   $spells[] = 55480; break;
						case 225:   $spells[] = 55500; break;
						case 300:   $spells[] = 55501; break;
						case 375:   $spells[] = 55502; break;
						case 450:   $spells[] = 55503; break;
					}
				
					switch($max_value) {
						case 75:    $spells[] = 2366; break;
						case 150:   $spells[] = 2368; break;
						case 225:   $spells[] = 3570; break;
						case 300:   $spells[] = 11993; break;
						case 375:   $spells[] = 28695; break;
						case 450:   $spells[] = 50300; break;
					}
					break;
				case 185:
					$spells[] = 818;
					
					switch($max_value) 
					{
						case 75:    $spells[] = 2550; break;
						case 150:   $spells[] = 3102; break;
						case 225:   $spells[] = 3413; break;
						case 300:   $spells[] = 18260; break;
						case 375:   $spells[] = 33359; break;
						case 450:   $spells[] = 51296; break;
					}
					
					break;
				case 186:
					$spells[] = 2656;
					$spells[] = 2580;
				
					switch($max_value) 
					{
						case 75:    $spells[] = 53120; break;
						case 150:   $spells[] = 53121; break;
						case 225:   $spells[] = 53122; break;
						case 300:   $spells[] = 53123; break;
						case 375:   $spells[] = 53124; break;
						case 450:   $spells[] = 53040; break;
					}
				
					switch($max_value) 
					{
						case 75:    $spells[] = 2575; break;
						case 150:   $spells[] = 2576; break;
						case 225:   $spells[] = 3564; break;
						case 300:   $spells[] = 10248; break;
						case 375:   $spells[] = 29354; break;
						case 450:   $spells[] = 50310; break;
					}
					
					break;
				case 197:
					switch($max_value) 
					{
						case 75:    $spells[] = 3908; break;
						case 150:   $spells[] = 3909; break;
						case 225:   $spells[] = 3910; break;
						case 300:   $spells[] = 12180; break;
						case 375:   $spells[] = 26790; break;
						case 450:   $spells[] = 51309; break;
					}
					
					break;
				case 202:
					switch($max_value) 
					{
						case 75:    $spells[] = 4036; break;
						case 150:   $spells[] = 4037; break;
						case 225:   $spells[] = 4038; break;
						case 300:   $spells[] = 12656; break;
						case 375:   $spells[] = 30350; break;
						case 450:   $spells[] = 51306; break;
					}
					
					break;
				case 333:
					$spells[] = 13262;
					
					switch($max_value) 
					{
						case 75:    $spells[] = 7411; break;
						case 150:   $spells[] = 7412; break;
						case 225:   $spells[] = 7413; break;
						case 300:   $spells[] = 13920; break;
						case 375:   $spells[] = 28029; break;
						case 450:   $spells[] = 51313; break;
					}
					
					break;
				case 356:
					switch($max_value) {
						case 75:    $spells[] = 7620; break;
						case 150:   $spells[] = 7731; break;
						case 225:   $spells[] = 7732; break;
						case 300:   $spells[] = 18248; break;
						case 375:   $spells[] = 33095; break;
						case 450:   $spells[] = 51294; break;
					}
					break;
				case 393:
					switch($max_value) 
					{
						case 75:    $spells[] = 53125; break;
						case 150:   $spells[] = 53662; break;
						case 225:   $spells[] = 53663; break;
						case 300:   $spells[] = 53664; break;
						case 375:   $spells[] = 53665; break;
						case 450:   $spells[] = 53666; break;
					}
				
					switch($max_value) 
					{
						case 75:    $spells[] = 8613; break;
						case 150:   $spells[] = 8617; break;
						case 225:   $spells[] = 8618; break;
						case 300:   $spells[] = 10768; break;
						case 375:   $spells[] = 32678; break;
						case 450:   $spells[] = 50305; break;
					}
					
					break;
				case 755:
					$spells[] = 31252;
					
					switch($max_value) 
					{
						case 75:    $spells[] = 25229; break;
						case 150:   $spells[] = 25230; break;
						case 225:   $spells[] = 28894; break;
						case 300:   $spells[] = 28895; break;
						case 375:   $spells[] = 28897; break;
						case 450:   $spells[] = 51311; break;
					}
					
					break;
				case 773:
					$spells[] = 51005;
					
					switch($max_value) 
					{
						case 75:    $spells[] = 45357; break;
						case 150:   $spells[] = 45358; break;
						case 225:   $spells[] = 45359; break;
						case 300:   $spells[] = 45360; break;
						case 375:   $spells[] = 45361; break;
						case 450:   $spells[] = 45363; break;
					}
					
					break;
				case 762:
					switch($max_value) 
					{
						case 75:    $spells[] = 33388; break;
						case 150:   $spells[] = 33391; break;
						case 225:   $spells[] = 34090; break;
						case 300:   $spells[] = 34091; break;
					}
					
					break;
				default:
					return false;
			}
			
			return $spells;
		}
		
		static private function check_item_count($count) 
		{
			$count = $count < 1 ? 1 : $count;
			$count = $count > 1000 ? 1000 : $count;
			
			return $count;
		}
	}
	
