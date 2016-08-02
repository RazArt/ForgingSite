<div id="controller_title" style="display: none;">Магазин</div>

<table class="list_table">
	<tbody>
		<tr>
			<td>
				<select id='cat_id' style="width: 224px;" onChange="loadController($('#cat_id').val());" title="Выберите категорию вещей">
					<? foreach (\Engine\View::instance() -> itemsCategories as $category): ?>
						<option value="<?= \Helper\Url::getUrl('shop', 'item', ['category' => $category['id']]);?>"<?= ($category['id'] == \Engine\View::instance() -> selectedCategoryId) ? ' selected' : '';?>><a onclick=""><?=$category['name'];?></></option>
					<? endforeach; ?>
				</select>
			</td>
		</tr>
	</tbody>
</table>
<br />
<? if (!empty(\Engine\View::instance() -> items)): ?>
<table class="list_table">
	<tbody>
		<? foreach (\Engine\View::instance() -> items as $item): ?>
		<tr>
			<td>
				<?=$item['name']; ?>
				<? if ($item['stack_count'] > 1): ?>
					(<?=$item['stack_count']; ?>)
				<? endif; ?>
			</td>
			<td>
				<?=$item['cost'];?>
			</td>
			<td>
				<a onclick="loadController('<?=\Helper\Url::getUrl('shop', 'item', ['id' => $item['id']]);?>', false)">Купить</a>
			</td>
		</tr>
		<? endforeach; ?>
	</tbody>
</table>
<? else: ?>
	Вещей в данной категории нет
<? endif; ?>