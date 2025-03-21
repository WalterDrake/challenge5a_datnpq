<?php
/**
 * Home controller
 */
class Home extends Controller
{
    public function index()
    {
        $user = new User();
        // $arr['username'] = 'test';
        // $arr['fullname'] = 'test';
        // $arr['email'] = 'test@gmail.com';
        // $arr['numbers'] = '9876534567';
        // $arr['avatar'] = 'test.jpg';
        // $arr['role'] = '2';

        // $data = $user->insert($arr);
        // $data = $user->update(1,['avatar' => 'admin.jpg']);
        $data = $user->delete(2);
        $data = $user->findAll();
        echo $this->view('home', ['rows' => $data]);
    }
}
