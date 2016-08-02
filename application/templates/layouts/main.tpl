<!DOCTYPE html>
<html>
    <head>
        <?= \Engine\View::instance() -> getTpl('header', 'layouts');?>
    </head>
    <body>
         Id - <?= \App\Account::getId();?> |
         Nick - <?= \App\Account::getUserName();?> |
         Money - <?= \App\Account::getMoneyCount();?>
        <div id="bodyPage">
            <div id="controllerBlock">
                <script type="text/javascript">
                    loadController('<?= \Helper\Url::getUrl(\Engine\Request::get('route', \Engine\Request::TYPE_STRING));?>')
                </script>
            </div>
        </div>
    </body>
</html>