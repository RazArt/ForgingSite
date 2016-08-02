<script type="text/javascript">
	$(function(){
		$("#user_login").tipTip({maxWidth: "250px", edgeOffset: 10, defaultPosition: "right"});
		$("#user_password").tipTip({maxWidth: "250px", edgeOffset: 10, defaultPosition: "right"});
		$("#user_site").tipTip({maxWidth: "250px", edgeOffset: 10, defaultPosition: "right"});
		$("#user_name_char").tipTip({maxWidth: "250px", edgeOffset: 10, defaultPosition: "right"});
		$("#name_char").tipTip({maxWidth: "250px", edgeOffset: 10, defaultPosition: "right"});
		$("#user_realm").tipTip({maxWidth: "250px", edgeOffset: 10, defaultPosition: "right"});
		$("#user_realmlist").tipTip({maxWidth: "250px", edgeOffset: 10, defaultPosition: "right"});
		$("#user_comment").tipTip({maxWidth: "250px", edgeOffset: 10, defaultPosition: "top"});
	});
</script>
<form action="<?= Config::instance() -> web['path'];user/edit" method="post" enctype="multipart/form-data">
<input type="hidden" name="token" value="<?= \Engine\View::instance() -> token;">
<input type="hidden" name="transfer_id" value="<?= \Engine\View::instance() -> transfer_info['id'];"> 
<table class="list_table" id="upload_form">
	<tbody>
		<tr>
			<td style="width: 220px;">
				<input id="user_site" style="width: 200px;" type="text" name="site" value="<?= \Engine\View::instance() -> transfer_info['old_server'];" placeholder="Сайт" title="Введите ссылку на сайт<br /> Вашего прошлого сервера">
			</td>
			<td rowspan="7" style="text-align: center;">
				<textarea  id="user_comment" name="comment" maxlength="1000" class="upload_comment" style="resize: none; height: 286px; width: 300px;" placeholder="Коментарии" title="В этом поле вы можете ввести Ваши<br /> пожелания и коментарии к переносу"><?= \Engine\View::instance() -> transfer_info['comment'];</textarea>
			</td>
		</tr>
		<tr>
			<td>
				<input id="user_realmlist" style="width: 200px;" type="text" name="realmlist" value="<?= \Engine\View::instance() -> transfer_info['old_realmlist'];" placeholder="Реалмлист" title="Введите реалмлист от<br /> Вашего прошлого сервера.<br /> Только адрес, без 'set realmlist'">
			</td>
		</tr>
		<tr>
			<td>
				<input id="user_realm" style="width: 200px;" type="text" name="realm" value="<?= \Engine\View::instance() -> transfer_info['old_realm'];" placeholder="Игровой мир" title="Введите название игрового мира на<br /> Вашем прошлом сервере">
			</td>
		</tr>
		<tr>
			<td>
				<input id="user_login" style="width: 200px;" type="text" name="login" value="<?= \Engine\View::instance() -> transfer_info['old_account'];" placeholder="Имя аккаунта" title="Введите логин c Вашего<br /> прошлого сервера">
			</td>
		</tr>
		<tr>
			<td>
				<input id="user_password" style="width: 200px;" type="password" name="password" value="<?= \Engine\View::instance() -> transfer_info['old_password'];" placeholder="Пароль" title="Введите пароль от аккаунта<br /> с Вашего прошлого сервера">
			</td>
		</tr>
		<tr>
			<td>
				<input id="user_name_char" style="width: 200px;" type="text" name="name_char" value="<?= \Engine\View::instance() -> transfer_info['old_char_name'];" placeholder="Введите имя персонажа" title="Имя переносимого персонажа">
			</td>
		</tr>
	</tbody>
</table>
<br />
<div style="width: 100%; text-align: right;">
	<a href="<?= Config::instance() -> web['path'];user" class="button_style">Вернуться назад</a>
	<input type="submit" value="Обновить данные">
</div>
</form>