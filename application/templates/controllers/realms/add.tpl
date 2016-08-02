<script type="text/javascript">
	$(function(){
		$("#title").tipTip({maxWidth: "250px", edgeOffset: 10, defaultPosition: "right"});
		$("#address").tipTip({maxWidth: "250px", edgeOffset: 10, defaultPosition: "right"});
		$("#port").tipTip({maxWidth: "250px", edgeOffset: 10, defaultPosition: "left"});
		$("#user").tipTip({maxWidth: "250px", edgeOffset: 10, defaultPosition: "right"});
		$("#password").tipTip({maxWidth: "250px", edgeOffset: 10, defaultPosition: "left"});
		$("#encoding").tipTip({maxWidth: "250px", edgeOffset: 10, defaultPosition: "right"});
		$("#db_name").tipTip({maxWidth: "250px", edgeOffset: 10, defaultPosition: "left"});
	});
</script>
<form action="<?= Config::instance() -> web['path'];realms/add" method="post" enctype="multipart/form-data">
<input type="hidden" name="token" value="<?= \Engine\View::instance() -> token;">
<table class="list_table" id="upload_form">
	<tbody>
		<tr>
			<td style="width: 220px; text-align: center;">
				<input id="title" style="width: 200px;" type="text" name="title" value="" placeholder="Название игрового мира" title="Введите название<br /> игрового мира">
			</td>
			<td>
				<select id='availability' style="width: 224px;" title="Доступен ли игровой мир<br /> для переноса">
					<option value="1" selected>Доступен</option>
					<option value="0">Недоступен</option>
				</select>
				<script type="text/javascript">
					$('#availability').selectbox({TooltipePos: 'left'});
				</script>
			</td>
		</tr>
		<tr>
			<td style="width: 220px; text-align: center;">
				<input id="address" style="width: 200px;" type="text" name="address" value="" placeholder="Адрес сервера" title="Введите адрес сервера<br /> базы данных">
			</td>
			<td style="width: 220px; text-align: center;">
				<input id="port" style="width: 200px;" type="text" name="port" value="" placeholder="Используемый порт" title="Введите порт сервера<br /> базы данных">
			</td>
		</tr>
		<tr>
			<td style="width: 220px; text-align: center;">
				<input id="user" style="width: 200px;" type="text" name="user" value="" placeholder="Учетная запись" title="Введите логин сервера<br /> базы данных">
			</td>
			<td style="width: 220px; text-align: center;">
				<input id="password" style="width: 200px;" type="password" name="password" value="" placeholder="Пароль" title="Введите пароль сервера<br /> базы данных">
			</td>
		</tr>
		<tr>
			<td style="width: 220px; text-align: center;">
				<input id="encoding" style="width: 200px;" type="text" name="encoding" value="utf8" placeholder="Кодировка" title="Введите кодировку сервера<br /> базы данных">
			</td>
			<td style="width: 220px; text-align: center;">
				<input id="db_name" style="width: 200px;" type="text" name="db_name" value="" placeholder="Название базы данных" title="Введите имя базы<br /> данных персонажей">
			</td>
		</tr>
	</tbody>
</table>
<br />
<div style="width: 100%; text-align: right;">
	<a href="<?= Config::instance() -> web['path'];realms" class="button_style">Вернуться назад</a>
	<input type="submit" value="Добавить">
</div>
</form>