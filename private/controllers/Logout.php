<?php
/**
 * Logout controller
 */
class Logout extends Controller
{
    public function index()
    {
        Auth::logout();
        $this->redirect('login');
    }
}