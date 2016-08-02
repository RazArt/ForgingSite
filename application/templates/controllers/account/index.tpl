<form id="login_form">
<input type="hidden" name="token" value="<?= \Engine\View::instance() -> token;?>"> 
<table class="list_table" id="login_form">
	<tbody>
		<tr>
			<td style="width: 200px;">
				<input id="user_login" style="width: 200px;" type="text" name="login" value=""  placeholder="Логин" title="Введите логин <br />от Вашего аккаунта">
			</td>
		</tr>
		<tr>
			<td>
				<input id="user_password" style="width: 200px;" type="password" name="password" value="" placeholder="Пароль" title="Введите Пароль <br />от Вашего аккаунта">
			</td>
		</tr>
	</tbody>
</table>
</form>
<br/>
<div>
	<input type="submit" onclick="loginCheck()" value="Войти">
</div>