<?php
namespace Fwlone\Core;

class User
{
    public $user;
    private $db;

    public function __construct()
    {
        $this->db = MysqliDb::getInstance();;
        if (isset($_COOKIE['user'], $_COOKIE['pass']) && $_COOKIE['user'] != NULL && $_COOKIE['pass'] != NULL) {

            $salt = Cms::guard($_COOKIE['user']);
//            $salt = $db->safe_sql($salt);

            $sess = Cms::guard($_COOKIE['pass']);
//            $sess = $db->safe_sql($sess);

//            $this->user_q = $db->query("SELECT * FROM `users` WHERE `salt` = '" . $salt . "' AND `session` = '" . $sess . "' limit 1");
//            $this->user = $db->get_row($this->user_q);

            if ($this->user['id']) {

                $ip = Cms::guard($_SERVER['REMOTE_ADDR']);
//                $ip = $db->safe_sql($ip);

                $ua = Cms::guard($_SERVER['HTTP_USER_AGENT']);
//                $ua = $db->safe_sql($ua);

                $page = Cms::guard($_SERVER['REQUEST_URI']);
//                $page = $db->safe_sql($page);

//                $db->query("UPDATE `users` SET `lasttime` = '" . TIME . "', " . ($this->user['lasttime'] > (TIME - 300) ? "`onlinetime`=`onlinetime`+'" . (TIME - $this->user['lasttime']) . "'," : "") . " `ip` = '" . $ip . "', `useragent` = '" . $ua . "', `page` = '" . $page . "' WHERE `id`='" . $this->user['id'] . "'");
                //$db->query("INSERT INTO `users_log`(`user`, `page`, `useragent`, `ip`, `time`) VALUES ('".$this->>user['id']."', '".$page."', '".$ua."', '".$ip."', '".TIME."')");

//                $notifications = $db->query("select `id` from `notification` where `user` = '" . $this->user['id'] . "' and `view` = 0 and fake = 0");
//                $tpl->set('notifications', $db->get_num_rows($notifications));

//                $messeges = $db->query("select `id` from `dialog_messages` where `user_to` = '" . $this->user['id'] . "' and `view` = 0 and `fake` = 0");
//                $tpl->set('messages', $db->get_num_rows($messeges));

            } else {
                setcookie('user', '', time() + 86400 * 31, '/');
                setcookie('pass', '', time() + 86400 * 31, '/');
            }

        } else {

            $this->user = false;

        }
    }

    public function accessSecure($level = 0)
    {
        if (!$this->user && ($_SERVER['REQUEST_URI'] != '/login' && $_SERVER['REQUEST_URI'] != '/registration')) {
            header('location: /login');
            exit();
        } elseif ($this->user && $level == 0) {
            header('location: /');
            exit();
        } elseif ($this->user != 0 && $this->user['level'] < $level) {
            header('location: /');
            exit();
        }
        return;
    }
}