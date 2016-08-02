<script type="text/javascript">
    $(function(){
        $(".list_control_block .button").tipTip({maxWidth: "250px", edgeOffset: 8, defaultPosition: "top"});
        $(".list_block_unavailable").tipTip({maxWidth: "250px", edgeOffset: 8, defaultPosition: "right"});
    });
</script>
<?php if (sizeof(\Engine\View::instance() -> transfer_list) > 0): 
    <? $status_text = ['Ожидает проверки', 
                          '<span style="color: #611703;">Отменено пользователем</span>',
                          '<span style="color: #611703;">Отменено администратором</span>',
                          '<span style="color: #045806;">Персонаж одобрен администратором</span>',
                          '<span style="color: #045806;">Персонаж проверяется администратором</span>'];
    
    <? foreach (\Engine\View::instance() -> transfer_list as $item): 
        <?
            if($item['gm_account'] > 0 && $item['status'] == 0)
            {
                $item['status'] = 4;
            }
            
            if(isset(Config::instance() -> realms[$item['realm']]) && Config::instance() -> realms[$item['realm']]['availability'])
            {
                $realm_availability = true;
            }
            else
            {
                $realm_availability = false;
            }
        ?>
        <div class="list_block<? if($realm_availability): list_block_available<?else: list_block_unavailable<?endif;"<? if(!$realm_availability): title="Игровой мир данного персонажа не доступен"<?endif;>
            <table cellpadding="0" cellspacing="0" style="width: 100%;">
                <tr>
                    <td rowspan="2" style="width: 1px;">
                        <span style="font-size: 32px;"><?= $item['old_char_name'];</span>
                    </td>
                    <td style="vertical-align: bottom; padding-left: 7px;">
                        <span style="font-size: 10px;"><?= $item['old_server'];</span>
                    </td>
                    <? if($realm_availability):
                    <td rowspan="3" class="list_control_block" style="text-align: right;">
                    <? if($item['status'] == 0 && $item['gm_account'] == 0):
                        <a href="<?= Config::instance() -> web['path'];user/edit/id=<?= $item['id'];"><div class="button edit" title="Редактировать заявку"></div></a>
                    <?endif;
                    <? if($item['status'] == 2):
                        <div class="button info" title="<?= (strlen($item['reason']) > 0) ? 'Причина: ' . $item['reason'] : 'Причина не указана';"></div>
                    <?endif;
                    <? if($item['gm_account'] == 0):
                        <a onclick="confirm('Вы действительно хотите удалить эту заявку?', '<?= Config::instance() -> web['path'];user/delete/id=<?= $item['id']');" href="javascript:void(0)"><div class="button delete" title="Удалить заявку"></div></a>
                    <?endif;
                    </td>
                    <?endif;
                </tr>
                <tr>
                    <td style="vertical-align: top; padding-left: 7px;">
                        <span style="font-size: 10px;"><?= $item['old_realm'];</span>
                    </td>
                </tr>
                <? if($realm_availability):
                <tr>
                    <td colspan=2 style="padding-top: 5px;">
                        <?= $status_text[$item['status']];
                    </td>
                </tr>
                <?endif;
            </table>
        </div>
    <? endforeach; 
<?else:
    <div class="list_block list_block_available" style="text-align: center;">
        Заявки на перенос отсутствуют
    </div>
<? endif; 