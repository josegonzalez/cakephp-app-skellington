<?php
class LoginToken extends AppModel {

    public function deleteAccount($user_id) {
        return sprintf('DELETE FROM %s WHERE `%s` = %s',
            $this->useTable,
            'user_id',
            $user_id
        );
    }

}