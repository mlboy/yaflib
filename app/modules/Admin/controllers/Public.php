<?php
class PublicController extends Core\C{
    public function loginAction() {
        $this->display();
    }
    public function postLoginAction() {
        $un = $this->post('username');
        $pw = $this->post('password');
        $vc = $this->post('vcode');
        if (!$un || !$pw) {
            return $this->json(array(
                'status' => 0,
                'msg' => '用户名或密码不能为空',
            ));
        }
        if (!$vc) {
            return $this->json(array(
                'status' => 0,
                'msg' => '验证码不能为空',
            ));
        }
        if (strtolower($vc) !== $this->session['vcode']) {
            return $this->json(array(
                'status' => 0,
                'msg' => '验证码错误',
            ));
        }
        $res = AdminModel::where('username',$un)->where('password',md5($pw))->first();
        if (!$res) {
            return $this->json(array(
                'status' => 0,
                'msg' => '用户名或密码错误',
            ));
        }
        $this->session['admin'] = $res->toArray();
        return $this->json(array(
            'status' => 1,
            'msg' => '登录成功',
        ));
    }
    public function logoutAction() {
        $this->session['admin'] = null;
        $this->forward('login');
    }
    public function captchaAction() {
        $v = new \Captcha();
        $v->make();
        $this->session['vcode'] = $v->getCode();
    }
}
