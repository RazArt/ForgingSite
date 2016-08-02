<script type="text/javascript">
	$(function(){
		$(".list_control_block .button").tipTip({maxWidth: "250px", edgeOffset: 10, defaultPosition: "top"});
	});
</script>
<? foreach (\Engine\View::instance() -> realms_list as $id => $item): 
	<?
		if(isset(Config::instance() -> realms[$id]) && Config::instance() -> realms[$id]['availability'])
		{
			$realm_availability = true;
		}
		else
		{
			$realm_availability = false;
		}
	
	<div class="list_block <? if($item['availability']): list_block_available<?endif;">
		<table cellpadding="0" cellspacing="0" style="width: 100%;">
				<tr>
					<td rowspan="2">
						<span ><?= $item['title'];</span>
					</td>
					<td class="list_control_block" style="text-align: right; vertical-align: middle;">
						<a href="<?= Config::instance() -> web['path'];realms/edit/id=<?= $id"><span class="button edit" title="Изменить информацию"></span></a>
						<a onclick="confirm('<? if($realm_availability):Вы действительно хотите удалить этот игровой мир?<?else:База данных игрового мира не доступна, если на сервере есть не перенесенные персонажи, то они не будут удалены<br /><br />Вы действительно подтверждаете его удаление? <?endif;', '<?= Config::instance() -> web['path'];realms/delete/id=<?= $id');" href="javascript:void(0)"><span class="button delete" title="Удалить игровой мир"></span></a>
					</td>
				</tr>
			</table>
		</div>
<?endforeach;
<div style="width: 100%; text-align: right;">
	<a href="<?= Config::instance() -> web['path'];realms/add" class="button_style">Добавить игровой мир</a>
</div>