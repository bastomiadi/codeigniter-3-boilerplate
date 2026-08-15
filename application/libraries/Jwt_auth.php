<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Jwt_auth {

    protected $key;
    protected $expiration;

    public function __construct() {
        $this->key = config_item('jwt_key') ?: 'ci3-boilerplate-secret-key';
        $this->expiration = (int) (config_item('jwt_expiration') ?: 3600);
    }

    public function encode($user_id) {
        $now = time();
        return JWT::encode(array(
            'iss' => base_url(),
            'iat' => $now,
            'exp' => $now + $this->expiration,
            'user_id' => (int) $user_id
        ), $this->key, 'HS256');
    }

    public function validate($token) {
        if (empty($token) || ! is_string($token)) {
            return false;
        }

        try {
            $decoded = JWT::decode($token, new Key($this->key, 'HS256'));
            return isset($decoded->user_id) ? $decoded : false;
        } catch (Exception $e) {
            return false;
        }
    }

    public function token_from_request() {
        $ci =& get_instance();
        $auth = $ci->input->get_request_header('Authorization', TRUE);
        if ($auth && preg_match('/Bearer\s+(.+)/i', $auth, $matches)) {
            return trim($matches[1]);
        }
        return NULL;
    }
}
