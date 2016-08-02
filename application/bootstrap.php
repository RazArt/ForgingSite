<?php
    /*if (!Engine\Request::isAjax()) {
        if (!$mainPage = Engine\View::instance() -> getTpl('main', 'layouts')) {
            die ('Main page not loaded');
        }
        
        echo $mainPage;
        return;
    }*/

    /*if (!Db::getConnection('system')) {
        return false;
    }

    \Engine\Registry::instance() -> dbAvailability = true;

    $sth = Db::getConnection('system') -> prepare('SELECT * FROM `' . \Engine\Config::instance() -> mysql['prefix'] . 'realms`');

    $sth -> execute();

    \Engine\Config::instance() -> realms = [];
    \Engine\Registry::instance() -> realmsAvailability = [];

    foreach ($sth -> fetchall(\PDO::FETCH_ASSOC) as $value) {
        $realmId = (int)$value['id'];
        
        \Engine\Config::instance() -> realms[$realmId]['id'] = $value['id'];
        \Engine\Config::instance() -> realms[$realmId]['title'] = $value['title'];
        \Engine\Config::instance() -> realms[$realmId]['mysql']['address'] = $value['address'];
        \Engine\Config::instance() -> realms[$realmId]['mysql']['port'] = $value['port'];
        \Engine\Config::instance() -> realms[$realmId]['mysql']['user'] = $value['user'];
        \Engine\Config::instance() -> realms[$realmId]['mysql']['password'] = $value['password'];
        \Engine\Config::instance() -> realms[$realmId]['mysql']['encoding'] = $value['encoding'];
        \Engine\Config::instance() -> realms[$realmId]['mysql']['authDbName'] = $value['auth_db_name'];
        \Engine\Config::instance() -> realms[$realmId]['mysql']['charactersDbName'] = $value['characters_db_name'];
        \Engine\Config::instance() -> realms[$realmId]['mysql']['worldDbName'] = $value['world_db_name'];
        \Engine\Config::instance() -> realms[$realmId]['transferAvailability'] = $value['transfer_availability'];
        
        if (Db::getConnection('auth', $value['id']) 
            && Db::getConnection('characters', $value['id'])
            && Db::getConnection('world', $value['id']) 
        ) {
            \Engine\Registry::instance() -> realmsAvailability[$value['id']]['db'] = true;
        } else {
            \Engine\Registry::instance() -> realmsAvailability[$value['id']]['db'] = false;
        }
    }

    if (isset($_SESSION['selectedRealmId'])
        && isset(\Engine\Config::instance() -> realms[$_SESSION['selectedRealmId']])
    ) {
        \Engine\Registry::instance() -> selectedRealmId = $_SESSION['selectedRealmId'];
    } else {
        $realmId = current(\Engine\Config::instance() -> realms)['id'];
        
        $_SESSION['selectedRealmId'] = $realmId;
        \Engine\Registry::instance() -> selectedRealmId = $realmId;
    } */
    
    Engine\Loader::loadFile(APPLICATION_PATH . 'routerRules.php');
    Engine\Router::loadController();