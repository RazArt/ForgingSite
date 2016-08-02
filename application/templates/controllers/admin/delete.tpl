<script type="text/javascript">
	$(function(){
		$("#reason").tipTip({maxWidth: "250px", edgeOffset: 10, defaultPosition: "top"});
	});
</script>
<form action="<?= Config::instance() -> web['path'];admin/delete" method="post" enctype="multipart/form-data">
<input type="hidden" name="transfer_id" value="<?= \Engine\View::instance() -> transfer_id;"> 
<table class="list_table" id="upload_form">
	<tbody>
		<tr>
			<td>
				<textarea id="reason" name="reason" maxlength="600" style="resize: none; height: 75px; width: 444px;" placeholder="Причина" title="Введите причину, по которой перенос<br/> не может быть осуществлен"><?= \Engine\View::instance() -> transfer_info['comment'];</textarea>
			</td>
		</tr>
	</tbody>
</table>
<br />
<div style="width: 100%; text-align: right;">
	<a href="<?= Config::instance() -> web['path'];admin" class="button_style">Вернуться назад</a>
	<input type="submit" value="Запретить">
</div>
</form>