<!DOCTYPE html>
<html>
	<head>
		<?= \Engine\View::instance() -> get_tpl('header', 'layouts');
	</head>
	<body>
		<div id="body_page">
			<div id="main_controller_block">
				<? $msg_type = ['Ошибка', 
									 'Сообщение'];
				<div class="title<?if(\Engine\View::instance() -> msg_type == 0): error_title<? endif;">
					<?= $msg_type[\Engine\View::instance() -> msg_type]
				</div>
				<div class="block" style="max-width: 400px;">
					<div class="msg_message">
						<?= \Engine\View::instance() -> content;
					</div>
					<? if(\Engine\View::instance() -> msg_back_link):
						<div style="width: 100%; text-align: right; margin-top: 10px;">
							<a href="<?= \Engine\View::instance() -> msg_back_link;" class="button_style">Вернуться назад</a>
						</div>
					<? endif;
				</div>
			</div>
		</div>
	</body>
</html>