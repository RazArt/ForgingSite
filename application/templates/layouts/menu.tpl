<ul>
	<li><a href="javascript://" onclick="loadController('<?= Link::get('Login');')">Авторизация</a></li>
	<li><a href="javascript://" onclick="loadController('<?= Link::get('online');')">Онлайн</a></li>
	<li><a href="javascript://" onclick="loadController('<?= Link::get('shop', 'item');')">Магазин</a></li>
	<li><a href="javascript://" onclick="loadController('<?= Link::get('shop', 'character', ['type' => 'faction']);')">Сменить фракцию</a></li>
	<li><a href="javascript://" onclick="loadController('<?= Link::get('shop', 'character', ['type' => 'race']);')">Сменить расу</a></li>
	<li><a href="javascript://" onclick="loadController('<?= Link::get('shop', 'character', ['type' => 'name']);')">Сменить имя</a></li>
	<li><a href="javascript://" onclick="loadController('<?= Link::get('shop', 'character', ['type' => 'customize']);')">Сменить внешность</a></li>
	<li><a href="javascript://" onclick="loadController('<?= Link::get('shop', 'cart');')">Рюкзак</a></li>
	<li><a href="javascript://" onclick="loadController('<?= Link::get('shop', 'unban');')">Снять блокировку</a></li>
	<li><a href="javascript://" onclick="loadController('<?= Link::get('shop');')">Пополнить баланс</a></li>
	<li><a href="javascript://" onclick="loadController('<?= Link::get('item', 'index', ['entry' => '192', 'asdf' => '666']);')">Тест ссылки</a></li>
	<li><a href="javascript://" onclick="logout();">Выход</a></li>
</ul>