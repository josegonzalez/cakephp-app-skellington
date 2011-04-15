<?php
class User extends AppModel {

    const STATUS_PENDING    = 0;
    const STATUS_APPROVED   = 1;

/**
 * Array of methods that return an sql statement to be run
 * in a transaction that deletes a user's account
 *
 * @var array
 */
    private $deleteAccount = array(
        'LoginToken' => 'deleteAccount',
    );

    public function __construct($id = false, $table = null, $ds = null) {
        parent::__construct($id, $table, $ds);
        $this->order = array("`{$this->alias}`.`{$this->displayField}` asc");
        $this->_findMethods['account'] = true;
        $this->_findMethods['approve'] = true;
        $this->_findMethods['forapproval'] = true;
        $this->validate = array(
            'first_name' => array(
                'required' => array(
                    'rule' => array('notempty'),
                    'message' => __('cannot be left empty', true),
                ),
                'alphanumeric' => array(
                    'rule' => array('alphanumeric'),
                    'message' => __('must contain only letters and numbers', true),
                ),
            ),
            'last_name' => array(
                'alphanumeric' => array(
                    'rule' => array('alphanumeric'),
                    'message' => __('must contain only letters and numbers', true),
                ),
            ),
            'email_address' => array(
                'required' => array(
                    'rule' => array('notempty'),
                    'message' => __('cannot be left empty', true),
                ),
                'email' => array(
                    'rule' => array('email'),
                    'message' => __('must be a valid email address', true),
                ),
                'isUnique' => array(
                    'rule' => array('isUnique'),
                    'message' => __('this email is already registered, did you forget your password?', true),
                ),
            ),
            'password' => array(
                'required' => array(
                    'rule' => array('notempty'),
                    'message' => __('cannot be left empty', true),
                ),
                'minLength' => array(
                    'rule' => array('minLength', 6),
                    'message' => __('must be at least 6 characters long', true),
                ),
            ),
            'new_password' => array(
                'minLength' => array(
                    'rule' => array('minLength', 6),
                    'message' => __('must be at least 6 characters long', true),
                ),
            ),
            'confirm_password' => array(
                'match' => array(
                    'rule' => array('match', 'new_password'),
                    'message' => __('passwords do not match', true),
                )
            ),

        );
    }

    public function authsomeLogin($type, $credentials = array()) {
        switch ($type) {
            case 'guest':
                // You can return any non-null value here, if you don't
                // have a guest account, just return an empty array
                return array('guest' => 'guest');
            case 'user_id' :
                $conditions = array(
                    "{$this->alias}.{$this->primaryKey}" => $credentials['login'],
                );
                break;
            case 'credentials':
                // Don't even attempt to login an invalid email address
                if (!strstr($credentials['login'], '@')) {
                    return false;
                }

                // This is the logic for validating the login
                $conditions = array(
                    "{$this->alias}.email_address" => $credentials['login'],
                    "{$this->alias}.password" => Authsome::hash($credentials['credential']),
                );
                break;
            case 'cookie':
                list($token, $id) = split(':', $credentials['token']);
                $duration = $credentials['duration'];

                $loginToken = $this->LoginToken->find('first', array(
                    'conditions' => array(
                        'user_id'    => $id,
                        'token'      => $token,
                        'duration'   => $duration,
                        'used'       => false,
                        'expires <=' => date('Y-m-d H:i:s', strtotime($duration)),
                    ),
                ));

                if (!$loginToken) {
                    return false;
                }

                $loginToken['LoginToken']['used'] = true;
                $this->LoginToken->save($loginToken);

                $conditions = array(
                    $this->alias . '.' . $this->primaryKey => $loginToken['LoginToken']['user_id'],
                );
                break;
            default:
                return null;
        }

        $user = $this->find('first', compact('conditions'));
        if (!$user) {
            return false;
        }
        $user[$this->alias]['loginType'] = $type;
        return $user;
    }

    public function authsomePersist($user, $duration) {
        $token = md5(uniqid(mt_rand(), true));
        $id = $user[$this->alias][$this->primaryKey];

        $this->LoginToken->create(array(
            'user_id'  => $id,
            'token'    => $token,
            'duration' => $duration,
            'expires'  => date('Y-m-d H:i:s', strtotime($duration)),
        ));
        $this->LoginToken->save();

        return $token . ':' . $userId;
    }

    public function changeActivationKey($id) {
        $activationKey = md5(uniqid());
        $data = array(
            $this->alias => array(
                $this->primaryKey => $id,
                'activation_key' => $activationKey,
            ),
        );

        if (!$this->save($data, array('callbacks' => false))) return false;
        return $activationKey;
    }

    public function register($data = array()) {
        $fields = array('first_name', 'email_address', 'password');

        foreach ($fields as $field) {
            if (!isset($data[$this->alias][$field])) {
                return false;
            }
        }

        $this->create(array(
            'first_name'    => $data[$this->alias]['first_name'],
            'email_address' => $data[$this->alias]['email_address'],
            'password'      => Authsome::hash($data[$this->alias]['password']),
            'status'        => User::STATUS_PENDING,
        ));
        if (!$this->save()) return false;

        Authsome::login('user_id', array('login' => $this->id));
        return true;
    }

    public function approveAccount($id = null) {
        if (!$id) return false;

        $user = $this->find('approve', $id);
        if (!$user) return false;

        if ($user[$this->alias]['status'] == User::STATUS_APPROVED) {
            return true;
        }

        $user[$this->alias]['status'] = User::STATUS_APPROVED;
        return $this->save($user);
    }

    public function deleteAccount() {
        $queries = array();
        $user_id = Authsome::get('id');

        foreach ($this->deleteAccount as $modelName => $method) {
            if (strstr($modelName, '.') === false && isset($this->$modelName)) {
                if (method_exists($this->$modelName, $method)) {
                    $queries[] = $this->$modelName->$method($user_id);
                }
            } else {
                $modelObj = ClassRegistry::init($modelName);
                if (is_object($modelObj) && method_exists($this->$modelName, $method)) {
                    $queries[] = $this->$modelName->$method($user_id);
                }
            }
        }

        $queries[] = sprintf('DELETE FROM %s WHERE `%s` = %s',
            $this->useTable,
            $this->primaryKey,
            $user_id
        );

        $dataSource = $this->getDataSource();
        $dataSource->begin($this);
        foreach ($queries as $query) {
            if (!$this->query($query)) {
                $dataSource->rollback($this);
                return false;
            }
        }
        return $dataSource->commit($this);
    }

    public function updateAccount($data = array()) {
        if (empty($data)) return false;

        $fields = array('first_name', 'last_name', 'location');
        foreach ($fields as $field) {
            if (!empty($data[$this->alias][$field])) {
                $results[$field] = $data[$this->alias][$field];
            }
        }

        if (empty($results)) return false;

        $this->create($data);
        $this->id = Authsome::get('id');
        return $this->save();
    }

    public function updatePassword($data = array()) {
        if (empty($data[$this->alias]['new_password'])) return false;
        if (empty($data[$this->alias]['confirm_password'])) return false;

        $this->set(array(
            'new_password' => $data[$this->alias]['new_password'],
            'confirm_password' => $data[$this->alias]['confirm_password']
        ));
        if (!$this->validates()) return false;

        $this->create(array('password' => Authsome::hash($data[$this->alias]['new_password'])));
        $this->id = Authsome::get('id');
        return $this->save();
    }

    public function _findAccount($state, $query, $results = array()) {
        if ($state == 'before') {
            $query['conditions'] = array("{$this->alias}.{$this->primaryKey}" => Authsome::get('id'));
            $query['fields'] = array('first_name', 'last_name', 'location');
            return $query;
        } elseif ($state == 'after') {
            if (empty($results[0])) {
                return false;
            }
            return $results[0];
        }
    }

    public function _findApprove($state, $query, $results = array()) {
        if ($state == 'before') {
            if (isset($query[0])) {
                $query['conditions'] = array("{$this->alias}.{$this->primaryKey}" => $query[0]);
            } else if (isset($query['user_id'])) {
                $query['conditions'] = array("{$this->alias}.{$this->primaryKey}" => $query['user']);
            } else {
                $query['conditions'] = array("{$this->alias}.{$this->primaryKey}" => null);
            }

            $query['fields'] = array('id', 'status');
            return $query;
        } elseif ($state == 'after') {
            if (empty($results[0])) {
                return false;
            }
            return $results[0];
        }
    }

    public function _findForapproval($state, $query, $results = array()) {
        if ($state == 'before') {
            $query['conditions'] = array("{$this->alias}.status" => User::STATUS_PENDING);
            $query['fields'] = array('id', 'first_name', 'email_address', 'created', 'status');

            if (!empty($query['operation'])) {
                return $this->_findPaginatecount($state, $query, $results);
            }
            return $query;
        } elseif ($state == 'after') {
            if (!empty($query['operation'])) {
                return $this->_findPaginatecount($state, $query, $results);
            }

            return $results;
        }
    }
    function match($check, $field) {
        $value = array_values($check);
        return $value[0] == $this->data[$this->alias][$field];
    }

}