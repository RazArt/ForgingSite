<?
	$classArray[0] = [
		1 => "Воин",
		2 => "Паладин",
		3 => "Охотник",
		4 => "Разбойник",
		5 => "Жрец",
		6 => "Рыцарь смерти",
		7 => "Шаман",
		8 => "Маг",
		9 => "Чернокнижник",
		11 => "Друид"];
		
	$classArray[1] = [
		1 => "Воин",
		2 => "Паладин",
		3 => "Охотница",
		4 => "Разбойница",
		5 => "Жрица",
		6 => "Рыцарь смерти",
		7 => "Шаманка",
		8 => "Маг",
		9 => "Чернокнижница",
		11 => "Друид"];
		
	$raceArray[0] = [
		1 => "Человек",
		2 => "Орк",
		3 => "Дворф",
		4 => "Ночной эльф",
		5 => "Нежить",
		6 => "Таурен",
		7 => "Гном",
		8 => "Тролль",
		10 => "Эльф крови",
		11 => "Дреней",
		22 => "Ворген"];
		
	$raceArray[1] = [
		1 => "Человек",
		2 => "Орк",
		3 => "Дворф",
		4 => "Ночная эльфийка",
		5 => "Нежить",
		6 => "Таурен",
		7 => "Гном",
		8 => "Тролль",
		10 => "Эльфийка крови",
		11 => "Дреней",
		22 => "Ворген"]; ?>

<div id="controller_title" style="display: none;">Рюкзак</div>

<? if (isset(\Engine\View::instance() -> charactersList) && is_array(\Engine\View::instance() -> charactersList)): ?>
	<? foreach (\Engine\View::instance() -> charactersList as $character): ?>
		<div class="list_block list_block_available" onclick="loadController('<?=\Helper\Url::getUrl('shop', 'cart', ['itemId' => \Engine\View::instance() -> itemId, 'guid' => $character['guid']]); ?>')">
			<table cellpadding="0" cellspacing="0" style="width: 100%;">
				<tr>
					<td rowspan="2" style="width: 1px;">
						<span style="font-size: 32px;"><?= $character['name']; ?></span>
					</td>
					<td style="vertical-align: bottom; padding-left: 7px;">
						<span style="font-size: 12px;"><?= $raceArray[$character['gender']][$character['race']]; ?></span>
					</td>
				</tr>
				<tr>
					<td style="vertical-align: top; padding-left: 7px;">
						<span style="font-size: 16px;"><?= $classArray[$character['gender']][$character['class']]; ?></span>
					</td>
				</tr>
			</table>
		</div>
	<? endforeach; ?>
<? else: ?>
	У Вас нет игровых персонажей
<? endif; ?>