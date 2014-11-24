<?php
class uLogin {
    public $modx;
    public $config = array();
    private $_pRequest;
    private $_messParam = array();
    private $_doRedirect = false;
    public static $u_inc = 0;

    function __construct(modX &$modx,array $config = array()){
        $this->modx = & $modx;

        $basePath = $this->modx->getOption('ulogin.core_path',$config,$this->modx->getOption('core_path').'components/ulogin/');
        $assetsUrl = $this->modx->getOption('ulogin.assets_url',$config,$this->modx->getOption('assets_url').'components/ulogin/');
        $this->config = array_merge(array(
            'basePath' => $basePath,
            'corePath' => $basePath,
            'modelPath' => $basePath.'model/',
            'templatesPath' => $basePath.'templates/',
            'chunksPath' => $basePath.'elements/chunks/',
            'processorsPath' => $basePath.'processors/',
            'jsUrl' => $assetsUrl.'js/',
            'cssUrl' => $assetsUrl.'css/',
            'assetsUrl' => $assetsUrl,
            'connectorUrl' => $assetsUrl.'connector.php',
            'uGroup' => $this->modx->getOption('ulogin.group_id'),
            'uRole' => $this->modx->getOption('ulogin.role_id'),
        ),$config);

        $this->modx->addPackage('ulogin',$this->config['modelPath']);
        if ($this->modx->context->key != 'mgr') {
            $this->_pRequest = $this->_parseRequest();
        }
    }

    public function initialize($properties = array()){
        if (!$this->_pRequest) return false;
        if ($this->modx->user->isAuthenticated()){
            foreach ($properties as $key=>$value){
                $properties[$key] = trim($value, '\'\" ');
            }
            $properties['signout_url'] = !empty($properties['signout_url']) ? $properties['signout_url'] : $this->modx->makeUrl($this->modx->resource->id, '', 'ulogin_action=signout', 'full');
            $properties['signout_url'] = !empty($properties['signouturl']) ? $properties['signouturl'] : $properties['signout_url']; // для старых версий
            $properties['signout_url'] = !empty($properties['sigout_url']) ? $properties['sigout_url'] : $properties['signout_url'];
            $properties['signout_msg'] = !empty($properties['sigout_msg']) ? $properties['sigout_msg'] : $properties['signout_msg'];
//            $properties['profile_tpl'] = !empty($properties['usrpanel']) ? $properties['usrpanel'] :  $properties['profile_tpl'];
            return $this->_getChunk($properties['usrpanel'], $properties);
        }else{
            foreach ($properties as $key=>$value){
                $properties[$key] = trim($value, '\'\" ');
            }
            $template = $properties['display'] == 'window' ? 'window' : 'panel';

            $properties['ul_id'] = !empty($properties['ul_id']) ? $properties['ul_id'] : 'uLogin_' . $properties['uloginid'] . '_' . self::$u_inc;

            if (!empty($properties['callback'])) {
                $properties['redirect'] = '';
            } else {
                if (!empty( $properties['redirect'] )) {
                    $query = parse_url($properties['redirect'], PHP_URL_QUERY);
                    $url_append = $query ? '&' : '?';
                    $url_append .= 'ulogin_action=redirect&ul_id='.$properties['ul_id'];
                    $properties['redirect'] = $properties['redirect'] . $url_append ;
                } else {
                    $properties['redirect'] = $this->modx->makeUrl($this->modx->resource->id, '', array('ulogin_action'=>'redirect','ul_id'=>$properties['ul_id']), 'full');
                }
            }

            if ($this->_messParam['id'] == $properties['ul_id'])
                $properties['script'] = $this->_messParam['script'];

            self::$u_inc++;
            return $this->_getChunk($template, $properties);
        }
    }

    public function initialize_form($properties = array()){
        if (!$this->_pRequest) return false;
        if (!$this->modx->user->isAuthenticated() || $properties['display_always']) {
            foreach ( $properties as $key => $value ) {
                $properties[$key] = trim( $value, '\'\" ' );
            }

            if (!empty($properties['id']) && ($uWidget = $this->modx->getObject('uLoginWidget', $properties['id']))) {
                $properties['uloginid'] = $uWidget->uloginid;
                $template = 'panel';
            } elseif (!empty($properties['uloginid'])) {
                $template = 'panel';
            } else {
                $template = $properties['display'] == 'window' ? 'window' : 'panel';
            }

            $properties['ul_id'] = !empty($properties['ul_id']) ? $properties['ul_id'] : 'uLogin_' . $properties['uloginid'] . '_' . self::$u_inc;

            if (!empty($properties['callback'])) {
                $properties['redirect'] = '';
            } else {
                if (!empty( $properties['redirect'] )) {
                    $query = parse_url($properties['redirect'], PHP_URL_QUERY);
                    $url_append = $query ? '&' : '?';
                    $url_append .= 'ulogin_action=redirect&ul_id='.$properties['ul_id'];
                    $properties['redirect'] = urldecode( $properties['redirect'] ) . $url_append ;
                } else {
                    $properties['redirect'] = $this->modx->makeUrl($this->modx->resource->id, '', array('ulogin_action'=>'redirect','ul_id'=>$properties['ul_id']), 'full');
                }
            }

            if ($this->_messParam['id'] == $properties['ul_id'])
                $properties['script'] = $this->_messParam['script'];

            self::$u_inc++;
            return $this->_getChunk( $template, $properties );
        }
        return false;
    }

    public function initialize_profile($properties = array()){
        if (!$this->_pRequest) return false;
        if ($this->modx->user->isAuthenticated()) {
            foreach ( $properties as $key => $value ) {
                $properties[$key] = trim( $value, '\'\" ' );
            }
            $properties['signout_url'] = !empty($properties['signout_url']) ? $properties['signout_url'] : $this->modx->makeUrl($this->modx->resource->id, '', 'ulogin_action=signout', 'full');
            return $this->_getChunk($properties['tpl'], $properties);
        }
        return false;
    }

    public function initialize_networks($properties = array()){
        if (!$this->_pRequest) return false;
        $networks = $this->modx->getCollection( 'uLoginUser', array( 'user_id' => $this->modx->user->id ) );

        $ouput = '';
        foreach ($networks as $network) {
            $properties['network'] = $network->network;
            $ouput .= $this->_getChunk($properties['network_tpl'], $properties);
        }
        return $ouput;
    }

    public function initialize_message($properties = array()){
        if (!$this->_pRequest) return false;
        if (!empty($this->_messParam) && array_key_exists('id', $this->_messParam)) {
            if ($this->_messParam['id'] == $properties['id'])
                $properties = array_merge($properties, $this->_messParam);
        }
        $properties['type'] = (!empty($properties['type']) && $properties['type']=='error') ? $properties['type'] : '';
        return $this->_getChunk($properties['tpl'], $properties);
    }


    private function _parseRequest() {
        if ($_REQUEST['ulogin_action'] == 'signout'){
            $this->_signOut();
            return false;
        }

        if ($_REQUEST['ulogin_action'] == 'deleteaccount'){
            $this->_deleteAccount();
            return false;
        }

        if ($_REQUEST['ulogin_action'] == 'callback' && $_POST['token']){
            $this->_formId = $_POST['ul_id'];
            if ($user_data = $this->_checkToken()){
                $this->_proceedRegistration($user_data, 'Аккаунт успешно добавлен');
            }
            return false;
        }

        $this->modx->regClientStartupScript( 'http://ulogin.ru/js/ulogin.js' );

        if ($_REQUEST['ulogin_action'] == 'redirect' && $_POST['token']){
            $this->_formId = $_REQUEST['ul_id'];
            if ($user_data = $this->_checkToken()){
                $this->_doRedirect = true;
                $this->_proceedRegistration($user_data);
            }
        }

        $this->modx->regClientCSS( 'http://ulogin.ru/css/providers.css' );
        $this->modx->regClientCSS( $this->config['cssUrl'] . 'ulogin.css' );
        $this->modx->regClientStartupScript( $this->config['jsUrl'] . 'ulogin.js' );

        return true;
    }


    private function _getMessage($params = array()) {
        if ($this->_doRedirect){
            $this->_messParam['id'] = $this->_formId;
            $this->_messParam['title'] = isset($params['title']) ? $params['title'] : '';
            $this->_messParam['message'] = isset($params['msg']) ? $params['msg'] : '';
            $this->_messParam['type'] = isset($params['answerType']) ? $params['answerType'] : '';
            $this->_messParam['display'] = 1;
            //   $this->_messParam['tpl'] = 'message_tpl';
            $this->_messParam['script'] .= isset($params['script']) ? $params['script'] : '';
        } else {
            echo json_encode(array(
                'title' => isset($params['title']) ? $params['title'] : '',
                'msg' => isset($params['msg']) ? $params['msg'] : '',
                'answerType' => isset($params['answerType']) ? $params['answerType'] : '',
                'userId' => isset($params['userId']) ? $params['userId'] : '0',
                'existIdentity' => isset($params['existIdentity']) ? $params['existIdentity'] : '0',
            ));
            exit;
        }
    }


    private function _checkToken(){
        if (!isset($_POST['token'])){
            $this->modx->log(modX::LOG_LEVEL_ERROR,'[uLogin] ERROR: Invalid token '.$_POST['token']);
        }else{
            $request = 'http://ulogin.ru/token.php?token=' . $_POST['token'] . '&host=' . $_SERVER['HTTP_HOST'];
            if(in_array('curl', get_loaded_extensions())){
                $c = curl_init($request);
                curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
                $json_data = curl_exec($c);
                curl_close($c);

            }elseif (function_exists('file_get_contents') && ini_get('allow_url_fopen')){
                $json_data = file_get_contents($request);
            }

            $user_data = json_decode($json_data, true);
            if (isset($user_data['error'])){
                $this->modx->log(modX::LOG_LEVEL_ERROR, '[uLogin] ERROR: '.$user_data['error']);
            }else{
                return $user_data;
            }
        }
        return false;
    }

    private function _signOut(){
        $response = $this->modx->runProcessor('/security/logout');
        if ($response->isError()) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, '[uLogin] ERROR: Username: '.$this->modx->user->get('username').', uid: '.$this->modx->user->get('id').'. Message: '.$response->getMessage());
        }
        $this->modx->sendRedirect($this->modx->makeUrl($this->modx->resource->id), array('type'=>'REDIRECT_REFRESH'));
    }

    private function _deleteAccount(){

        if(! $this->modx->user->isAuthenticated()) {exit;}

        $user_id = $this->modx->user->id;
        $network = isset($_POST['network']) ? $_POST['network'] : '';

        if ($user_id > 0 && $network != '') {
            try {
                $u_users = $this->modx->getCollection('uLoginUser',array('user_id' => $user_id, 'network' => $network));
                foreach ($u_users as $u_user) {
                    $u_user->remove();
                }

                echo json_encode(array(
                    'title' => "Удаление аккаунта",
                    'msg' => "Удаление аккаунта $network успешно выполнено",
//                    'userId' => $user_id,
                    'answerType' => 'success'
                ));
                exit;
            } catch (Exception $e) {
                echo json_encode(array(
                    'title' => "Ошибка при удалении аккаунта",
                    'msg' => "Exception: " . $e->getMessage(),
                    'answerType' => 'error'
                ));
                exit;
            }
        }
        exit;
    }

    private function _proceedRegistration($data, $title = '', $msg = ''){
        try {
            $u_user_db = $this->modx->getObject( 'uLoginUser', array( 'identity' => $data['identity'] ) );

            if ( $u_user_db ) {

                $modXuser = $this->modx->getObject( 'modUser', array( 'id' => intval( $u_user_db->get( 'user_id' ) ) ) );
                $user_id = $modXuser ? $modXuser->get( 'id' ) : 0;

                if ( isset( $user_id ) && intval( $user_id ) > 0 ) {
                    if ( !$this->checkCurrentUserId( $user_id ) ) {
                        // если $user_id != ID текущего пользователя
                        return;
                    }
                } else {
                    // данные о пользователе есть в ulogin_table, но отсутствуют в modx. Необходимо переписать запись в ulogin_table и в базе modx.
                    $user_id = $this->newuLoginAccount( $data, $u_user_db );
                }
            } else {
                // пользователь НЕ обнаружен в ulogin_table. Необходимо добавить запись в ulogin_table и в базе modx.
                $user_id = $this->newuLoginAccount( $data );
            }

            // обновление данных и Вход
            if ( $user_id > 0 ) {

                $this->loginUser( $data, $user_id );

                if ( $this->_doRedirect ) {
                    $this->modx->sendRedirect( $this->modx->makeUrl( $this->modx->resource->id ), array( 'type' => 'REDIRECT_REFRESH' ) );
                } else {
                    $networksCollection = $this->modx->getCollection('uLoginUser', array('user_id' => $user_id));
                    $networks = array();
                    foreach ($networksCollection as $obj) {
                        $networks[] = $obj->network;
                    }
                    echo json_encode( array(
                        'title' => $title,
                        'msg' => $msg,
                        'networks' => $networks,
                        'answerType' => 'success'
                    ) );
                    exit;
                }

            }
            return;
        }
        catch (Exception $e) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, '[uLogin] ERROR : Was caused exclusion when registering "' . $e->getMessage() . '"');
            if ( !$this->_doRedirect ) {
                echo json_encode( array(
                    'title' => "Ошибка при работе с БД",
                    'msg' => "Exception: " . $e->getMessage(),
                    'answerType' => 'error'
                ) );
                exit;
            }
            return;
        }

    }

    /**
     * Добавление в таблицу uLogin
     * @param $u_data - данные о пользователе, полученные от uLogin
     * @param $u_user_db - при непустом значении необходимо переписать данные в таблице uLogin
     */
    private function newuLoginAccount($u_data, $u_user_db = ''){
        if ($u_user_db) {
            // данные о пользователе есть в ulogin_table, но отсутствуют в modx => удалить их
            $u_user_db->remove();
        }

        $profile = $this->modx->getObject('modUserProfile', array(
            'email' => $u_data['email'],
        ));

        // $check_m_user == true -> есть пользователь с таким email
        $user_id = 0;
        $check_m_user = false;
        if (isset($profile)) {
            $user_id = $profile->get( 'internalKey' ); // id юзера с тем же email
            $check_m_user = true;
        }

        // $isLoggedIn == true -> пользователь онлайн
        $isLoggedIn = $this->modx->user->isAuthenticated();
        $cur_user_id = $isLoggedIn? $this->modx->user->id : 0;

        if (!$check_m_user && !$isLoggedIn) {
            // отсутствует пользователь с таким email в базе -> регистрация в БД
            $user_id = $this->regUser($u_data);
            $this->adduLoginAccount($u_data, $user_id);
        } else {
            // существует пользователь с таким email или это текущий пользователь
            if (intval($u_data["verified_email"]) != 1){
                // Верификация аккаунта

                $this->_getMessage(
                    array(
                        'title' => 'Подтверждение аккаунта',
                        'msg' => 'Электронный адрес данного аккаунта совпадает с электронным адресом существующего пользователя. ' .
                                 '<br>Требуется подтверждение на владение указанным email.',
                        'script' => '<script type="text/javascript">uLogin.mergeAccounts("' . $_POST['token'] . '")</script>',
                        'answerType' => 'verify',
                    )
                );
                return false;
            }

            $user_id = $isLoggedIn ? $cur_user_id : $user_id;

            $other_u = $this->modx->getObject('uLoginUser', array(
                'user_id' => $user_id,
            ));

            if ($other_u) {
                // Синхронизация аккаунтов
                if(!$isLoggedIn && !isset($u_data['merge_account'])){
                    $this->_getMessage(
                        array(
                            'title' => 'Синхронизация аккаунтов',
                            'msg' => 'С данным аккаунтом уже связаны данные из другой социальной сети. ' .
                                     '<br>Требуется привязка новой учётной записи социальной сети к этому аккаунту.',
                            'script' => '<script type="text/javascript">uLogin.mergeAccounts("' . $_POST['token'] . '","' . $other_u->identity . '")</script>',
                            'answerType' => 'merge',
                            'existIdentity' => $other_u->identity
                        )
                    );
                    return false;
                }
            }

            $this->adduLoginAccount($u_data, $user_id);
        }

        return $user_id;
    }

    /**
     * Добавление записи в таблицу ulogin
     * @param $u_data
     * @param $user_id
     */
    private function adduLoginAccount($u_data, $user_id){
        $user = $this->modx->newObject('uLoginUser');
        $user->fromArray(array(
            'user_id' => $user_id,
            'identity' => strval($u_data['identity']),
            'network' => $u_data['network'],
        ));
        $user->save();
    }

    /**
     * Выполнение входа пользователя в систему по $user_id
     * @param $u_user
     * @param int $user_id
     */
    private function loginUser($u_data, $user_id = 0){
        $modXuser = $this->modx->getObject('modUser', array('id'=>$user_id));

        $username = '';

        if ($modXuser) {
            $username = $modXuser->get( 'username' );
        }

        $modxProfile = $modXuser->Profile;
        if (empty($modxProfile->fullname) && (isset($u_data['last_name']) || isset($u_data['first_name'])))
            $modxProfile->set('fullname', $u_data['last_name'].' '.$u_data['first_name']);
        if (empty($modxProfile->dob) && isset($u_data['sex'])) $modxProfile->set('dob', strtotime($u_data['bdate']));
        if (empty($modxProfile->gender) && $u_data['sex']>0) $modxProfile->set('gender', ($u_data['sex'] == '1' ? '2' : '1'));
        if (empty($modxProfile->country) && isset($u_data['country'])) $modxProfile->set('country', $u_data['country']);
        if (empty($modxProfile->city) && isset($u_data['city'])) $modxProfile->set('city', $u_data['city']);
        if (empty($modxProfile->website) && isset($u_data['website'])) $modxProfile->set('website', $u_data['website']);
        if (empty($modxProfile->photo) && isset($u_data['photo_big'])) $modxProfile->set('photo', $u_data['photo_big']);
        if (empty($modxProfile->mobilephone) && isset($u_data['phone'])) $modxProfile->set('phone', $u_data['phone']);
        $modxProfile->save();

        $response = $this->modx->runProcessor(
            'security/login',
            array('username' => $username,'login_context'=>'web', 'add_contexts'=>''),
            array('processors_path' => $this->config['processorsPath'])
        );

        if ($response->isError()) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, '[uLogin] ERROR : Username: '.$username.', uid: '.$user_id.'. Message: '.$response->getMessage());
        }
    }

    /**
     * Регистрация пользователя в БД modx
     * @param $u_data
     * @return mixed
     */
    private function regUser($u_data){
        $UserData['Password'] = md5($u_data['identity'].time().rand());
        $UserData['Name'] = $this->generateNickname($u_data['first_name'],$u_data['last_name'],$u_data['nickname'],$u_data['bdate']);
        $UserData['DateOfBirth'] = strtotime($u_data['bdate']);
        $UserData['Gender'] = $u_data['sex'] == '1' ? 2 : ($u_data['sex'] == '2' ? 1 : 0);

        $modXuser = $this->modx->newObject('modUser', array(
            'username' => $UserData['Name'],
            'password' => $UserData['Password'],
            'active'=>1));
        $modxProfile = $this->modx->newObject('modUserProfile', array(
            'fullname' => (isset($u_data['last_name']) || isset($u_data['first_name'])) ? $u_data['last_name'].' '.$u_data['first_name'] : '',
            'email' => $u_data['email'],
            'dob' => isset($UserData['DateOfBirth']) ? $UserData['DateOfBirth'] : 0,
            'gender' => isset($UserData['Gender']) ? $UserData['Gender'] : '',
            'country' => isset($u_data['country']) ? $u_data['country'] : '',
            'city' => isset($u_data['city']) ? $u_data['city'] : '',
            'website' => isset($u_data['identity']) ? $u_data['identity'] : '',
            'photo' => isset($u_data['photo_big']) ? $u_data['photo_big'] : '',
            'mobilephone' => isset($u_data['phone']) ? $u_data['phone'] : '',
        ));
        $modXuser->Profile = $modxProfile;

        // присвоение группы
        $uGroup = $this->modx->getOption('ulogin.group_id');
        $uRole = $this->modx->getOption('ulogin.role_id');
        if (!empty($uGroup) && !empty($uRole)) {
            $groupMember = $this->modx->newObject( 'modUserGroupMember', array(
                'user_group' => $uGroup,
                'role' => $uRole,
            ) );
            // добавляем пользователя в группы
            $modXuser->UserGroupMembers = $groupMember;
        }

        $modXuser->save();

        return $modXuser->get('id');
    }

    /**
     * Проверка текущего пользователя
     * @param $user_id
     */
    private function checkCurrentUserId($user_id){
        if($this->modx->user->isAuthenticated()) {
            $currentCustomerId = $this->modx->user->id;
            if ($currentCustomerId == $user_id) {
                return true;
            }
            $this->_getMessage(
                array(
                    'title' => '',
                    'msg' => 'Данный аккаунт привязан к другому пользователю. ' .
                             '</br>Вы не можете использовать этот аккаунт',
                    'answerType' => 'error',
                )
            );
            return false;
        }
        return true;
    }

    /**
     * Гнерация логина пользователя
     * в случае успешного выполнения возвращает уникальный логин пользователя
     * @param $first_name
     * @param string $last_name
     * @param string $nickname
     * @param string $bdate
     * @param array $delimiters
     * @return string
     */
    private function generateNickname($first_name, $last_name="", $nickname="", $bdate="", $delimiters=array('.', '_')) {
        $delim = array_shift($delimiters);

        $first_name = $this->translitIt($first_name);
        $first_name_s = substr($first_name, 0, 1);

        $variants = array();
        if (!empty($nickname))
            $variants[] = $nickname;
        $variants[] = $first_name;
        if (!empty($last_name)) {
            $last_name = $this->translitIt($last_name);
            $variants[] = $first_name.$delim.$last_name;
            $variants[] = $last_name.$delim.$first_name;
            $variants[] = $first_name_s.$delim.$last_name;
            $variants[] = $first_name_s.$last_name;
            $variants[] = $last_name.$delim.$first_name_s;
            $variants[] = $last_name.$first_name_s;
        }
        if (!empty($bdate)) {
            $date = explode('.', $bdate);
            $variants[] = $first_name.$date[2];
            $variants[] = $first_name.$delim.$date[2];
            $variants[] = $first_name.$date[0].$date[1];
            $variants[] = $first_name.$delim.$date[0].$date[1];
            $variants[] = $first_name.$delim.$last_name.$date[2];
            $variants[] = $first_name.$delim.$last_name.$delim.$date[2];
            $variants[] = $first_name.$delim.$last_name.$date[0].$date[1];
            $variants[] = $first_name.$delim.$last_name.$delim.$date[0].$date[1];
            $variants[] = $last_name.$delim.$first_name.$date[2];
            $variants[] = $last_name.$delim.$first_name.$delim.$date[2];
            $variants[] = $last_name.$delim.$first_name.$date[0].$date[1];
            $variants[] = $last_name.$delim.$first_name.$delim.$date[0].$date[1];
            $variants[] = $first_name_s.$delim.$last_name.$date[2];
            $variants[] = $first_name_s.$delim.$last_name.$delim.$date[2];
            $variants[] = $first_name_s.$delim.$last_name.$date[0].$date[1];
            $variants[] = $first_name_s.$delim.$last_name.$delim.$date[0].$date[1];
            $variants[] = $last_name.$delim.$first_name_s.$date[2];
            $variants[] = $last_name.$delim.$first_name_s.$delim.$date[2];
            $variants[] = $last_name.$delim.$first_name_s.$date[0].$date[1];
            $variants[] = $last_name.$delim.$first_name_s.$delim.$date[0].$date[1];
            $variants[] = $first_name_s.$last_name.$date[2];
            $variants[] = $first_name_s.$last_name.$delim.$date[2];
            $variants[] = $first_name_s.$last_name.$date[0].$date[1];
            $variants[] = $first_name_s.$last_name.$delim.$date[0].$date[1];
            $variants[] = $last_name.$first_name_s.$date[2];
            $variants[] = $last_name.$first_name_s.$delim.$date[2];
            $variants[] = $last_name.$first_name_s.$date[0].$date[1];
            $variants[] = $last_name.$first_name_s.$delim.$date[0].$date[1];
        }
        $i=0;

        $exist = true;
        while (true) {
            if ($exist = $this->userExist($variants[$i])) {
                foreach ($delimiters as $del) {
                    $replaced = str_replace($delim, $del, $variants[$i]);
                    if($replaced !== $variants[$i]){
                        $variants[$i] = $replaced;
                        if (!$exist = $this->userExist($variants[$i]))
                            break;
                    }
                }
            }
            if ($i >= count($variants)-1 || !$exist)
                break;
            $i++;
        }

        if ($exist) {
            while ($exist) {
                $nickname = $first_name.mt_rand(1, 100000);
                $exist = $this->userExist($nickname);
            }
            return $nickname;
        } else
            return $variants[$i];
    }

    /**
     * Проверка существует ли пользователь с заданным логином
     */
    private function userExist($login){
        if (!$this->modx->getObject('modUser', array('username'=>$login))){
            return false;
        }
        return true;
    }

    /**
     * Транслит
     */
    private function translitIt($str) {
        $tr = array(
            "А"=>"a","Б"=>"b","В"=>"v","Г"=>"g",
            "Д"=>"d","Е"=>"e","Ж"=>"j","З"=>"z","И"=>"i",
            "Й"=>"y","К"=>"k","Л"=>"l","М"=>"m","Н"=>"n",
            "О"=>"o","П"=>"p","Р"=>"r","С"=>"s","Т"=>"t",
            "У"=>"u","Ф"=>"f","Х"=>"h","Ц"=>"ts","Ч"=>"ch",
            "Ш"=>"sh","Щ"=>"sch","Ъ"=>"","Ы"=>"yi","Ь"=>"",
            "Э"=>"e","Ю"=>"yu","Я"=>"ya","а"=>"a","б"=>"b",
            "в"=>"v","г"=>"g","д"=>"d","е"=>"e","ж"=>"j",
            "з"=>"z","и"=>"i","й"=>"y","к"=>"k","л"=>"l",
            "м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
            "с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h",
            "ц"=>"ts","ч"=>"ch","ш"=>"sh","щ"=>"sch","ъ"=>"y",
            "ы"=>"y","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya"
        );
        if (preg_match('/[^A-Za-z0-9\_\-]/', $str)) {
            $str = strtr($str,$tr);
            $str = preg_replace('/[^A-Za-z0-9\_\-\.]/', '', $str);
        }
        return $str;
    }



    public function _getChunk($name,$properties = array()) {
        $chunk = null;
        if (!isset($this->chunks[$name])) {
            $chunk = $this->_getTplChunk($name);
            if (empty($chunk)) {
                $chunk = $this->modx->getObject('modChunk',array('name' => $name));
                if ($chunk == false) return false;
            }
            $this->chunks[$name] = $chunk->getContent();
        } else {
            $o = $this->chunks[$name];
            $chunk = $this->modx->newObject('modChunk');
            $chunk->setContent($o);
        }
        $chunk->setCacheable(false);
        return $chunk->process($properties);
    }

    private function _getTplChunk($name,$postfix = '.ulogin.chunk.tpl') {
        $chunk = false;
        $f = $this->config['chunksPath'].strtolower($name).$postfix;
        if (file_exists($f)) {
            $o = file_get_contents($f);
            $chunk = $this->modx->newObject('modChunk');
            $chunk->set('name',$name);
            $chunk->setContent($o);
        }
        return $chunk;
    }
}
