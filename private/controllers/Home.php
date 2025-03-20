<?php
/**
 * Home controller
 */
class Home extends Controller
{
    public function index()
    {
        echo $this->view('home');
    }
}
