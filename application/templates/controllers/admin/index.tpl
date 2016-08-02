<script type="text/javascript">
	$(function(){
		$(".list_control_block .button").tipTip({maxWidth: "250px", edgeOffset: 10, defaultPosition: "top"});
		$(".list_block_unavailable").tipTip({maxWidth: "250px", edgeOffset: 8, defaultPosition: "right"});
	});
</script>
<?php if (sizeof(\Engine\View::instance() -> transfer_list) > 0): 
	<div id="tansfer_list" style="min-width: 300px;">
	<? foreach (\Engine\View::instance() -> transfer_list as $item): 
		<?
			if(isset(Config::instance() -> realms[$item['realm']]) && Config::instance() -> realms[$item['realm']]['availability'])
			{
				$realm_availability = true;
			}
			else
			{
				$realm_availability = false;
			}
		?>
		<div class="list_block<? if($realm_availability): list_block_available<?else: list_block_unavailable<?endif;"<? if(!$realm_availability): title="Игровой мир данного персонажа не доступен"<?endif;>
			<table cellpadding="0" cellspacing="0" style="width: 100%;">
					<tr>
						<td rowspan="2" style="width: 1px;">
							<span style="font-size: 25px;"><?= $item['char_name'];</span>
						</td>
						<td class="list_control_block" style="text-align: right; vertical-align: middle;">
						<? if($realm_availability):
							<? if(Account::is_admin()):
								<? if($item['gm_account'] == 0):
									<a onclick="confirm('Вы действительно хотите проверить данного персонажа?', '<?= Config::instance() -> web['path'];admin/check/id=<?= $item['id']');" href="javascript:void(0)"><span class="button begin_check" title="Начать рассмотрение заявки"></span></a>
								<? elseif($item['gm_account'] != Account::get_id()):
									<a onclick="confirm('Вы действительно хотите отвязать данную заявку от текущего аккаунта модератора, разрешив другим модераторам её рассмотрение?', '<?= Config::instance() -> web['path'];admin/gmclear/id=<?= $item['id']');" href="javascript:void(0)"><span class="button cancel_check" title="Отвязать от аккаунта модератора"></span></a>
								<? elseif($item['gm_account'] == Account::get_id()):
									<a onclick="confirm('Вы действительно хотите отменить проверку данного персонажа?', '<?= Config::instance() -> web['path'];admin/gmclear/id=<?= $item['id']');" href="javascript:void(0)"><span class="button cancel_check" title="Отменить рассмотрение заявки"></span></a>
								<?endif;
								
								<a href="<?= Config::instance() -> web['path'];admin/view/id=<?= $item['id']"><span class="button view_char_info" title="Просмотр информации о персонаже"></span></a>
								
								<?if($item['gm_account'] == Account::get_id()):
									<a onclick="confirm('Вы действительно подтверждаете перенос этого персонажа?', '<?= Config::instance() -> web['path'];admin/accept/id=<?= $item['id']');" href="javascript:void(0)"><span class="button accept_check" title="Разрешить перенос"></span></a>
									<a onclick="confirm('Вы действительно хотите запретить перенос этого персонажа?', '<?= Config::instance() -> web['path'];admin/delete/id=<?= $item['id']');" href="javascript:void(0)"><span class="button delete" title="Запретить перенос"></span></a>
								<?endif;
							<?else:
								<? if($item['gm_account'] == 0):
									<a onclick="confirm('Вы действительно хотите проверить данного персонажа?', '<?= Config::instance() -> web['path'];admin/check/id=<?= $item['id']');" href="javascript:void(0)"><span class="button begin_check" title="Начать рассмотрение заявки"></span></a>
								<? elseif($item['gm_account'] == Account::get_id()):
									<a href="<?= Config::instance() -> web['path'];admin/view/id=<?= $item['id']"><span class="button view_char_info" title="Просмотр информации о персонаже"></span></a>
									<a onclick="confirm('Вы действительно подтверждаете перенос этого персонажа?', '<?= Config::instance() -> web['path'];admin/accept/id=<?= $item['id']');" href="javascript:void(0)"><span class="button accept_check" title="Разрешить перенос"></span></a>
									<a onclick="confirm('Вы действительно хотите запретить перенос этого персонажа?', '<?= Config::instance() -> web['path'];admin/delete/id=<?= $item['id']');" href="javascript:void(0)"><span class="button delete" title="Запретить перенос"></span></a>
								<?endif;
							<?endif;
						<?endif;
						</td>
					</tr>
				</table>
			</div>
	<? endforeach; 
	</div>
<?else:
	<div class="list_block list_block_available" style="text-align: center;">
		Заявки на перенос отсутствуют
	</div>
<? endif; 