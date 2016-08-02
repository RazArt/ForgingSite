<?php
    namespace Model
    {
        class Account
        {
            public function checkUser($login, $passwordHash) 
            {
                 try {
                    $sth = \App\Db::getConnection('auth') -> prepare(
                         'SELECT id, username, email FROM `account` '
                       . 'WHERE username = :username AND sha_pass_hash = :sha_pass_hash'
                    );
                    
                    $sth -> bindValue(':username', $login);
                    $sth -> bindValue(':sha_pass_hash', $passwordHash);
                    
                    $sth -> execute();
                    
                    return $sth -> fetch(\PDO::FETCH_ASSOC);
                } catch (Exception $e) {
                    return false;
                }
            }
            
            public function confirmedEmail() 
            {
                try {
                    $sth = \App\Db::getConnection('auth') -> 
                        prepare('SELECT id FROM `account` '
                              . 'WHERE id = :id AND checked_mail = \'1\''
                    );
                    
                    $sth -> bindValue(':id', \App\Account::getId());
                    
                    $sth -> execute();
                    
                    if ($sth -> rowCount() != 1) {
                        return false;
                    }
                    
                    return true;
                } catch (Exception $e) {
                    return false;
                }
            }
            
            public function sendCheckMail($email) 
            {
                try {
                    $confirmedCode = \Engine\Security::generatePasswlord(30);
                    
                    $sth = \App\Db::getConnection('system') -> 
                        prepare('INSERT INTO `' . \Engine\Config::instance() -> mysql['prefix'] . 'check_mail_codes` '
                              . 'SET `id` = :id, `new_email` = :newEmail, `code` = :code '
                              . 'ON DUPLICATE KEY UPDATE `new_email` = :newMail, `code` = :code');
                    
                    $sth -> bindValue(':id', \App\Account::getId());
                    $sth -> bindValue(':newEmail', $email);
                    $sth -> bindValue(':code', $confirmedCode);
                    
                    $sth -> execute();
                    
                    $mailText = \Engine\View::instance() -> getTpl('confirmEmailText', 'other');
                    
                    return true;
                } catch (Exception $e) {
                    return false;
                }
            }
        }
    }
