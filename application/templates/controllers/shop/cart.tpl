<div id="controllerTitle" style="display: none;">Рюкзак</div>

<? if (!empty(\Engine\View::instance() -> items)): ?>
<table class="list_table">
	<tbody>
	<? foreach (\Engine\View::instance() -> items as $item): ?>
		<tr>
			<td>
				<?=$item['id']; ?>
			</td>
			<td>
				<?=$item['name'];?> (<?=$item['count'];?>)
			</td>
			<td>
				<a href="javascript://" onclick="loadController('<?=\Helper\Url::getUrl('shop', 'cart', ['itemId' => $item['id']]);?>')">Отправить персонажу</a>
			</td>
		</tr>
	<? endforeach; ?>
	</tbody>
</table>
<? else: ?> 
	Ваша карзина пуста, хотите <a href="javascript://" onclick="loadController('<?= \Helper\Url::getUrl('shop'); ?>')">купить что-нибудь</a>?
<? endif; ?>