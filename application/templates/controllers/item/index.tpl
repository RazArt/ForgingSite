<? if (is_array(\Engine\View::instance() -> item_info)): 
	<?
		$item_name_color[0] = '#9d9d9d';
		$item_name_color[1] = '#fff';
		$item_name_color[2] = '#1eff00';
		$item_name_color[3] = '#0070dd';
		$item_name_color[4] = '#a335ee';
		$item_name_color[5] = '#ff8000';
		$item_name_color[6] = '#e5cc80';
		$item_name_color[7] = '#e5cc80';
		
		$item_type_name[2][0] = 'Топор';
		$item_type_name[2][1] = 'Топор';
		$item_type_name[2][2] = 'Лук';
		$item_type_name[2][3] = 'Ружье';
		$item_type_name[2][4] = 'Дробящее';
		$item_type_name[2][5] = 'Дробящее';
		$item_type_name[2][6] = 'Древковое';
		$item_type_name[2][7] = 'Меч';
		$item_type_name[2][8] = 'Меч';
		$item_type_name[2][9] = '';
		$item_type_name[2][10] = 'Посох';
		$item_type_name[2][11] = '';
		$item_type_name[2][12] = '';
		$item_type_name[2][13] = 'Кистевое';
		$item_type_name[2][14] = 'Разное';
		$item_type_name[2][15] = 'Кинжалы';
		$item_type_name[2][16] = 'Метательное';
		$item_type_name[2][17] = 'Копье';
		$item_type_name[2][18] = 'Арбалет';
		$item_type_name[2][19] = 'Жезл';
		$item_type_name[2][20] = 'Удочка';
		
		$item_type_name[4][0] = '';
		$item_type_name[4][1] = 'Ткань';
		$item_type_name[4][2] = 'Кожа';
		$item_type_name[4][3] = 'Кольчуга';
		$item_type_name[4][4] = 'Латы';
		$item_type_name[4][5] = 'Buckler';
		$item_type_name[4][6] = 'Щит';
		$item_type_name[4][7] = 'Манускрипт';
		$item_type_name[4][8] = 'Идол';
		$item_type_name[4][9] = 'Тотем';
		$item_type_name[4][10] = 'Печать';
		
		$item_slot_name[0] = '';
		$item_slot_name[1] = 'Голова';
		$item_slot_name[2] = 'Ожерелье';
		$item_slot_name[3] = 'Плечи';
		$item_slot_name[4] = 'Рубашка';
		$item_slot_name[5] = 'Грудь';
		$item_slot_name[6] = 'Пояс';
		$item_slot_name[7] = 'Ноги';
		$item_slot_name[8] = 'Ступни';
		$item_slot_name[9] = 'Запястья';
		$item_slot_name[10] = 'Руки';
		$item_slot_name[11] = 'Кольцо';
		$item_slot_name[12] = 'Аксессуар';
		$item_slot_name[13] = 'Одноручное';
		$item_slot_name[14] = 'Щит';
		$item_slot_name[15] = 'Дальний бой';
		$item_slot_name[16] = 'Плащ';
		$item_slot_name[17] = 'Двуручное';
		$item_slot_name[18] = 'Сумка';
		$item_slot_name[19] = 'Гербовая накидка';
		$item_slot_name[20] = 'Грудь';
		$item_slot_name[21] = 'Правая рука';
		$item_slot_name[22] = 'Левая рука';
		$item_slot_name[23] = 'Левая рука';
		$item_slot_name[24] = 'Боеприпасы';
		$item_slot_name[25] = 'Метательное';
		$item_slot_name[26] = 'Дальний бой';
		$item_slot_name[27] = 'Колчан';
		$item_slot_name[28] = 'Реликвия';
		
		$item_bonding_text[1] = 'Становится персональным при получении';
		$item_bonding_text[2] = 'Становится персональным при надевании';
		$item_bonding_text[3] = 'Становится персональным при использовании';
		$item_bonding_text[4] = 'Предмет, необходимый для задания';
		
		$item_st_text[3] = 'к ловкости';
		$item_st_text[4] = 'к силе';
		$item_st_text[5] = 'к интелекту';
		$item_st_text[6] = 'к духу';
		$item_st_text[7] = 'к выносликости';
		$item_st_text[12] = 'Рейтинг защиты';
		$item_st_text[13] = 'Рейтинг уклонения';
		$item_st_text[14] = 'Рейтинг парирования';
		$item_st_text[15] = 'Рейтинг блокирования';
		$item_st_text[16] = 'Рейтинг меткости';
		$item_st_text[17] = 'Рейтинг меткости';
		$item_st_text[18] = 'Рейтинг меткости';
		$item_st_text[19] = 'Рейтинг критического удара';
		$item_st_text[20] = 'Рейтинг критического удара';
		$item_st_text[21] = 'Рейтинг критического удара';
		$item_st_text[28] = 'Рейтинг скорости';
		$item_st_text[29] = 'Рейтинг скорости';
		$item_st_text[30] = 'Рейтинг скорости';
		$item_st_text[31] = 'Рейтинг меткости';
		$item_st_text[32] = 'Рейтинг критического удара';
		$item_st_text[35] = 'Рейтинг устойчивости';
		$item_st_text[36] = 'Рейтинг скорости';
		$item_st_text[37] = 'Рейтинг мастерства';
		$item_st_text[38] = 'Сила атаки';
		$item_st_text[39] = 'Сила атаки дальнего боя';
		$item_st_text[40] = 'Сила атаки зверя';
		$item_st_text[43] = 'Скорость восстановления маны';
		$item_st_text[44] = 'Рейтинг пробивания брони';
		$item_st_text[45] = 'Сила заклинаний';
		
		$item_res_name[0] = 'holy_res';
		$item_res_name[1] = 'fire_res';
		$item_res_name[2] = 'nature_res';
		$item_res_name[3] = 'frost_res';
		$item_res_name[4] = 'shadow_res';
		$item_res_name[5] = 'arcane_res';
		
		$item_res_text[0] = 'к сопротивлению магии света';
		$item_res_text[1] = 'к сопротивлению магии огня';
		$item_res_text[2] = 'к сопротивлению сил природы';
		$item_res_text[3] = 'к сопротивлению магии льда';
		$item_res_text[4] = 'к сопротивлению темной магии';
		$item_res_text[5] = 'к сопротивлению тайной магии';
		
		$item_socket_text[1] = '<div style="background: url(\'./style/images/socket-meta.gif\') no-repeat 1px; color: #9d9d9d; height: 16px; display: display inline; padding-left: 23px;">особое гнездо</div>';
		$item_socket_text[2] = '<div style="background: url(\'./style/images/socket-red.gif\') no-repeat 1px; color: #9d9d9d; height:16px; display: display inline; padding-left: 23px;">красное гнездо</div>';
		$item_socket_text[4] = '<div style="background: url(\'./style/images/socket-yellow.gif\') no-repeat 1px; color: #9d9d9d; height: 16px; display: display inline; padding-left: 23px;">желтое гнездо</div>';
		$item_socket_text[8] = '<div style="background: url(\'./style/images/socket-blue.gif\') no-repeat 1px; color: #9d9d9d; height:16px; display: display inline; padding-left: 23px;">синее гнездо</div>';
		
		$faction_rep_rank[0] = 'Ненависть';
		$faction_rep_rank[1] = 'Враждебность';
		$faction_rep_rank[2] = 'Неприязнь';
		$faction_rep_rank[3] = 'Равнодушие';
		$faction_rep_rank[4] = 'Дружелюбие';
		$faction_rep_rank[5] = 'Уважение';
		$faction_rep_rank[6] = 'Почтение';
		$faction_rep_rank[7] = 'Превознесение';
		
		$spell_trigger_text[0] = 'Использование';
		$spell_trigger_text[1] = 'Если на персонаже';
		$spell_trigger_text[2] = 'Если на персонаже';
		$spell_trigger_text[6] = 'Использование';
	
	
	<table width="300px">
		<tr>
			<td>
				<div style="color: <?=$item_name_color[\Engine\View::instance() -> item_info['Quality']];; text-align: left;">
					<?=\Engine\View::instance() -> item_info['name'];
				</div>
			</td>
		</tr>
		
		<? if (\Engine\View::instance() -> item_info['bonding']): 
		<tr>
			<td>
				<div style="text-align: left;">
					<?=$item_bonding_text[\Engine\View::instance() -> item_info['bonding']];
				</div>
			</td>
		</tr>
		<? endif; 
		
		<? if (\Engine\View::instance() -> item_info['maxcount'] == 1): 
		<tr>
			<td>
				<div style="text-align: left;">
					Уникальный
				</div>
			</td>
		</tr>
		<? elseif (\Engine\View::instance() -> item_info['maxcount'] > 1):
		<tr>
			<td>
				<div style="text-align: left;">
					Уникальный (<?=$item_bonding_text[\Engine\View::instance() -> item_info['maxcount']];)
				</div>
			</td>
		</tr>
		<? endif; 
		
		<? if (\Engine\View::instance() -> item_info['InventoryType'] > 0 && \Engine\View::instance() -> item_info['ContainerSlots'] == 0): 
		<tr>
			<td>
				<div style="float: left; width: 50%; text-align: left; ">
					<?=$item_slot_name[\Engine\View::instance() -> item_info['InventoryType']];
				</div>
				<div style="float: left; width: 50%; text-align: right;">
					<?=$item_type_name[\Engine\View::instance() -> item_info['class']][\Engine\View::instance() -> item_info['subclass']];
				</div>
			</td>
		</tr>
		<? endif; 
		<? if (\Engine\View::instance() -> item_info['InventoryType'] > 0 && \Engine\View::instance() -> item_info['ContainerSlots'] > 0): 
		<tr>
			<td>
				<div style="float: left; width: 50%; text-align: left;">
					<?=$item_slot_name[\Engine\View::instance() -> item_info['InventoryType']]; (<?=\Engine\View::instance() -> item_info['ContainerSlots']; <?=declOfNum(\Engine\View::instance() -> item_info['ContainerSlots'], array('слот', 'слота', 'слотов'));)
				</div>
			</td>
		</tr>
		<? endif; 
		
		<? if (\Engine\View::instance() -> item_info['dmg_min1'] > 0): 
		<tr>
			<td>
				<div style="float: left; width: 50%; text-align: left;">
					Урон: <?=ceil(\Engine\View::instance() -> item_info['dmg_min1']); - <?=ceil(\Engine\View::instance() -> item_info['dmg_max1']);
				</div>
				<div style="float: left; width: 50%; text-align: right;">
					Скорость <?=\Engine\View::instance() -> item_info['delay']/1000;
				</div>
			</td>
		</tr>
		<tr>
			<td>
				<div style="text-align: left;">
					(<?=round((\Engine\View::instance() -> item_info['dmg_min1'] + \Engine\View::instance() -> item_info['dmg_max1']) / (\Engine\View::instance() -> item_info['delay'] / 1000) / 2, 1); ед. урона в секунду)
				</div>
			</td>
		</tr>
		<? endif; 
		
		<? if (\Engine\View::instance() -> item_info['armor'] > 0): 
			<tr>
				<td>
					<div style="text-align: left;">
						Броня: <?=\Engine\View::instance() -> item_info['armor'];
					</div>
				</td>
			</tr>
		<? endif; 
		
		<? foreach (range(1, 10) as $num): 
			<? if (in_array(\Engine\View::instance() -> item_info['stat_type' . $num], range(3, 7))): 
			<tr>
				<td>
					<div style="text-align: left;">
						+<?=\Engine\View::instance() -> item_info['stat_value' . $num]; <?=$item_st_text[\Engine\View::instance() -> item_info['stat_type' . $num]];
					</div>
				</td>
			</tr>
			<? endif; 
		<? endforeach; 
		
		<? foreach (range(0, 5) as $num): 
			<? if (\Engine\View::instance() -> item_info[$item_res_name[$num]] > 0): 
				<tr>
					<td>
						<div style="text-align: left;">
							+<?=\Engine\View::instance() -> item_info[$item_res_name[$num]]; <?=$item_res_text[$num];
						</div>
					</td>
				</tr>
			<? endif; 
		<? endforeach; 
		
		<? foreach (range(1, 3) as $num): 
			<? if (\Engine\View::instance() -> item_info['socketColor_' . $num] > 0): 
				<tr>
					<td>
						<div style="text-align: left;">
							<?=$item_socket_text[\Engine\View::instance() -> item_info['socketColor_' . $num]];
						</div>
					</td>
				</tr>
			<? endif; 
		<? endforeach; 
		<? if (\Engine\View::instance() -> item_info['socketBonus']): 
			<tr>
				<td>
					<div style="color: #9d9d9d; text-align: left;">
						При соответствии цвета: <?=\Engine\View::instance() -> item_info['socketBonus'];
					</div>
				</td>
			</tr>
		<? endif; 
		
		<? if (\Engine\View::instance() -> item_info['MaxDurability'] > 0): 
			<tr>
				<td>
					<div style="text-align: left;">
						Прочность: <?=\Engine\View::instance() -> item_info['MaxDurability']; / <?=\Engine\View::instance() -> item_info['MaxDurability'];
					</div>
				</td>
			</tr>
		<? endif; 
		
		<? if ((\Engine\View::instance() -> item_info['AllowableClass']&1) == 1): 
			<?$allowable_class[] = 'Воин';
		<? endif; 
		<? if ((\Engine\View::instance() -> item_info['AllowableClass']&2) == 2): 
			<?$allowable_class[] = 'Паладин';
		<? endif; 
		<? if ((\Engine\View::instance() -> item_info['AllowableClass']&4) == 4): 
			<?$allowable_class[] = 'Охотник';
		<? endif; 
		<? if ((\Engine\View::instance() -> item_info['AllowableClass']&8) == 8): 
			<?$allowable_class[] = 'Разбойник';
		<? endif; 
		<? if ((\Engine\View::instance() -> item_info['AllowableClass']&16) == 16): 
			<?$allowable_class[] = 'Жрец';
		<? endif; 
		<? if ((\Engine\View::instance() -> item_info['AllowableClass']&32) == 32): 
			<?$allowable_class[] = 'Рыцарь смерти';
		<? endif; 
		<? if ((\Engine\View::instance() -> item_info['AllowableClass']&64) == 64): 
			<?$allowable_class[] = 'Шаман';
		<? endif; 
		<? if ((\Engine\View::instance() -> item_info['AllowableClass']&128) == 128): 
			<?$allowable_class[] = 'Маг';
		<? endif; 
		<? if ((\Engine\View::instance() -> item_info['AllowableClass']&256) == 256): 
			<?$allowable_class[] = 'Чернокнижник';
		<? endif; 
		<? if ((\Engine\View::instance() -> item_info['AllowableClass']&1024) == 1024): 
			<?$allowable_class[] = 'Друид';
		<? endif; 
		<? if (isset($allowable_class) && sizeof($allowable_class) < 10): 
		<tr>
			<td>
				<div style="text-align: left;">
					Классы: <?=implode($allowable_class, ', ');
				</div>
			</td>
		</tr>
		<? endif; 
		
		<? if ((\Engine\View::instance() -> item_info['AllowableRace']&1) == 1): 
			<?$allowable_race[] = 'Человек';
		<? endif; 
		<? if ((\Engine\View::instance() -> item_info['AllowableRace']&2) == 2): 
			<?$allowable_race[] = 'Орк';
		<? endif; 
		<? if ((\Engine\View::instance() -> item_info['AllowableRace']&4) == 4): 
			<?$allowable_race[] = 'Дворф';
		<? endif; 
		<? if ((\Engine\View::instance() -> item_info['AllowableRace']&8) == 8): 
			<?$allowable_race[] = 'Ночной эльф';
		<? endif; 
		<? if ((\Engine\View::instance() -> item_info['AllowableRace']&16) == 16): 
			<?$allowable_race[] = 'Нежить';
		<? endif; 
		<? if ((\Engine\View::instance() -> item_info['AllowableRace']&32) == 32): 
			<?$allowable_race[] = 'Таурен';
		<? endif; 
		<? if ((\Engine\View::instance() -> item_info['AllowableRace']&64) == 64): 
			<?$allowable_race[] = 'Гном';
		<? endif; 
		<? if ((\Engine\View::instance() -> item_info['AllowableRace']&128) == 128): 
			<?$allowable_race[] = 'Тролль';
		<? endif; 
		<? if ((\Engine\View::instance() -> item_info['AllowableRace']&512) == 512): 
			<?$allowable_race[] = 'Кровавый эльф';
		<? endif; 
		<? if ((\Engine\View::instance() -> item_info['AllowableRace']&1024) == 1024): 
			<?$allowable_race[] = 'Дреней';
		<? endif; 
		<? if (isset($allowable_race) && sizeof($allowable_race) < 10): 
		<tr>
			<td>
				<div style="text-align: left;">
					Расы: <?=implode($allowable_race, ', ');
				</div>
			</td>
		</tr>
		<? endif; 
		
		<? if (\Engine\View::instance() -> item_info['RequiredLevel'] > 0): 
		<tr>
			<td>
				<div style="text-align: left;">
					Требуется уровень: <?=\Engine\View::instance() -> item_info['RequiredLevel'];
				</div>
			</td>
		</tr>
		<? endif; 
		
		<? if (\Engine\View::instance() -> item_info['RequiredSkill']): 
		<tr>
			<td>
				<div style="text-align: left;">
					Требуется: <?=\Engine\View::instance() -> item_info['RequiredSkill']; (<?=\Engine\View::instance() -> item_info['RequiredSkillRank'];)
				</div>
			</td>
		</tr>
		<? endif; 
		
		<? if (\Engine\View::instance() -> item_info['RequiredReputationFaction']): 
		<tr>
			<td>
				<div style="text-align: left;">
					Требуется: <?=$faction_rep_rank[\Engine\View::instance() -> item_info['RequiredReputationRank']]; (<?=\Engine\View::instance() -> item_info['RequiredReputationFaction'];)
				</div>
			</td>
		</tr>
		<? endif; 
		
		<? if (\Engine\View::instance() -> item_info['ItemLevel']): 
		<tr>
			<td>
				<div style="color: #ffd100; text-align: left;">
					Уровень предмета: <?=\Engine\View::instance() -> item_info['ItemLevel'];
				</div>
			</td>
		</tr>
		<? endif; 
		
		<? foreach (range(1, 10) as $num): 
			<? if (\Engine\View::instance() -> item_info['stat_type' . $num] == 46): 
			<tr>
				<td>
					<div style="color: #1eff00; text-align: left;">
						Если на персонаже: Восстанавливает <?=\Engine\View::instance() -> item_info['stat_value' . $num]; ед. здоровья раз в 5 сек.
					</div>
				</td>
			</tr>
			<? endif; 
			<? if (\Engine\View::instance() -> item_info['stat_type' . $num] == 47): 
			<tr>
				<td>
					<div style="color: #1eff00; text-align: left;">
						Если на персонаже: Восстанавливает <?=\Engine\View::instance() -> item_info['stat_value' . $num]; ед. маны раз в 5 сек.
					</div>
				</td>
			</tr>
			<? endif; 
		<? endforeach; 
		
		<? foreach (range(1, 10) as $num): 
			<? if (in_array(\Engine\View::instance() -> item_info['stat_type' . $num], range(12, 45))): 
			<tr>
				<td>
					<div style="color: #1eff00; text-align: left;">
						Если на персонаже: <?=$item_st_text[\Engine\View::instance() -> item_info['stat_type' . $num]]; +<?=\Engine\View::instance() -> item_info['stat_value' . $num];
					</div>
				</td>
			</tr>
			<? endif; 
		<? endforeach; 
		
		<? foreach (range(1, 5) as $num): 
			<? if (\Engine\View::instance() -> item_info['spellid_' . $num] > 0 && \Engine\View::instance() -> item_info['spellDesc_' . $num]): 
			<tr>
				<td>
					<div style="color: #1eff00; text-align: left;">
						<?=$spell_trigger_text[\Engine\View::instance() -> item_info['spelltrigger_' . $num]];: <?=\Engine\View::instance() -> item_info['spellDesc_' . $num];
					</div>
				</td>
			</tr>
			<? endif; 
		<? endforeach; 
<? else: 
    error
<? endif; 