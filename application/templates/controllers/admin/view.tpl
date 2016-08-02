<script type="text/javascript">
	$(function(){
		$("#user_site").tipTip({maxWidth: "250px", edgeOffset: 10, defaultPosition: "right"});
		$("#user_realmlist").tipTip({maxWidth: "250px", edgeOffset: 10, defaultPosition: "left"});
		$("#user_login").tipTip({maxWidth: "250px", edgeOffset: 10, defaultPosition: "right"});
		$("#user_password").tipTip({maxWidth: "250px", edgeOffset: 10, defaultPosition: "left"});
		$("#user_realm").tipTip({maxWidth: "250px", edgeOffset: 10, defaultPosition: "right"});
		$("#user_char_name").tipTip({maxWidth: "250px", edgeOffset: 10, defaultPosition: "left"});
		$("#user_trans_realm").tipTip({maxWidth: "250px", edgeOffset: 10, defaultPosition: "right"});
		$("#user_trans_name").tipTip({maxWidth: "250px", edgeOffset: 10, defaultPosition: "left"});
		$("#user_comment").tipTip({maxWidth: "250px", edgeOffset: 10, defaultPosition: "top"});
	});
</script>
<table class="list_table" id="upload_form">
	<tbody>
		<tr>
			<td style="text-align: center;">
				<input readonly id="user_site" style="width: 200px;" type="text" value="<?= \Engine\View::instance() -> transfer_info['old_server'];" title="Сайт сервера">
			</td>
			<td style="text-align: center;">
				<input readonly id="user_realmlist" style="width: 200px;" type="text" value="<?= \Engine\View::instance() -> transfer_info['old_realmlist'];" title="Реалмлист">
			</td>
		</tr>
		<tr>
			<td style="width: 220px; text-align: center;">
				<input readonly id="user_login" style="width: 200px;" type="text" value="<?= \Engine\View::instance() -> transfer_info['old_account'];" title="Имя аккаунта">
			</td>

			<td style="text-align: center;">
				<input readonly id="user_password" style="width: 200px;" type="text" value="<?= \Engine\View::instance() -> transfer_info['old_password'];" title="Пароль от аккаунта">
			</td>
		</tr>
		<tr>
			<td style="text-align: center;">
				<input readonly id="user_realm" style="width: 200px;" type="text" value="<?= \Engine\View::instance() -> transfer_info['old_realm'];" title="Игровой мир">
			</td>

			<td style="text-align: center;">
				<input readonly id="user_char_name" style="width: 200px;" type="text" value="<?= \Engine\View::instance() -> transfer_info['old_char_name'];" title="Имя персонажа">
			</td>
		</tr>
		<tr>
			<td style="text-align: center;">
				<input readonly id="user_trans_realm" style="width: 200px;" type="text" value="<?= Config::instance() -> realms[\Engine\View::instance() -> transfer_info['realm']]['title'];" title="Игровой мир нашего сервера">
			</td>
			<td style="text-align: center;">
				<input readonly id="user_trans_name" style="width: 200px;" type="text" value="<?= \Engine\View::instance() -> transfer_info['char_name'];" title="Временное имя персонажа">
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<textarea readonly id="user_comment" maxlength="1000"  style="resize: none; height: 75px; width: 444px;" placeholder="Коментарии отсутствуют" title="Пользовательский коментарий"><?= \Engine\View::instance() -> transfer_info['comment'];</textarea>
			</td>
		</tr>
	</tbody>
</table>
<br />
<div style="width: 100%; text-align: right;">
	<a href="<?= Config::instance() -> web['path'];admin" class="button_style">Вернуться назад</a>
</div>