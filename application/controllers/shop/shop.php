<?php
    namespace Controller\Shop
    {
        class Shop
        {
            function __construct()
            {
                $view = \Engine\View::instance();
                
                if (!\App\Account::checkAccessLevel(\App\Account::ACCESS_LEVEL_USER)) {
                    $view -> sendResponse('accessError', null, \Engine\View::RT_ERROR);
                        
                    return;
                }
                
                $view -> setDir('controllers' . D_S . 'shop');
                $view -> route = \Engine\Request::get('shop', \Engine\Request::TYPE_STRING);
            }
            
            public function action_index($controllerArguments) 
            {
                $model = new \Model\Shop();
                $view = \Engine\View::instance();
                
                if (!isset($controllerArguments['id'])) {
                    if ($categories = $model -> getCategoriesList()) {
                        if (!isset($categories[$controllerArguments['category']])) {
                            $controllerArguments['category'] = current($categories)['id'];
                        }
                        
                        $view -> items = $model -> getItemsListFromCategory($controllerArguments['category']);
                        $view -> selectedCategoryId = (int)$controllerArguments['category'];
                        $view -> itemsCategories = $categories;
                    }
                    
                    $view -> sendResponse('item');
                    
                    return;
                } else {
                    if (!$itemInfo = $model -> getItemInfo((int)$controllerArguments['id'])) {
                        $view -> sendResponse('shopItemNotExists', null, \Engine\View::RT_ERROR);
                        
                        return;
                    }
                    
                    if ((int)$itemInfo['cost'] > \App\Account::getMoneyCount()) {
                        $view -> sendResponse('shopEnoughMoney', null, \Engine\View::RT_ERROR);
                        
                        return;
                    }
                    
                    if (!$model -> addItemToCart($itemInfo['entry'], $itemInfo['stack_count'], $itemInfo['cost'])) {
                        $view -> sendResponse('shopUnsuccessesBuy', null, \Engine\View::RT_ERROR);
                        
                        return;
                    } else {
                        $view -> sendResponse('shopSuccessesBuy', null, \Engine\View::RT_MESSAGE);
                        
                        return;
                    }
                }
            }
        }
    }