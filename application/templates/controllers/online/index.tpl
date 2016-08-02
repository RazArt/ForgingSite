<div id="controller_title" style="display: none;">Онлайн</div>

<? if (!empty(\Engine\View::instance() -> onlineList)): ?>
<table class="list_table">
	<tbody>
		<? foreach (\Engine\View::instance() -> onlineList as $character): ?>
		<tr>
			<td>
				<?=$character['guid']; ?>
			</td>
			<td>
				<?=$character['name']; ?>
			</td>
			<td>
				<?=$character['race']; ?>
			</td>
			<td>
				<?=$character['class']; ?>
			</td>
			<td>
				<?=$character['gender']; ?>
			</td>
			<td>
				<?=$character['level']; ?>
			</td>
			<td>
				<?=$character['zone']; ?>
			</td>
		</tr>
		<? endforeach; ?>
	</tbody>
</table>
<? else: ?>
	Нет персонажей в сети
<? endif; ?>