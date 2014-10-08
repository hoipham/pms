<?php

class Customer_Info {

    /**
     * ログイン者情報を取得
     * @return MUser
     */
    public static function getUser() {
        return Customer_User::getInstance()->getUser();
    }

    /**
     * ログイン時間を取得
     * @return MUser
     */
    public static function getLoginTime() {
        return Customer_User::getInstance()->getLoginTime();
    }

    /**
     * ログイン状態確認
     * @return boolean
     */
    public static function isLoggedUser() {
        return Customer_User::getInstance()->isLogged();
    }

}

?>