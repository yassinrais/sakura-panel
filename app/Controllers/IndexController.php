<?php 
namespace Sakura\Controllers;

class IndexController extends ControllerBase 
{

    public function testAction()
    {
        return 'tested! ';
    }
    public function indexAction()
    {
        return "Welcome to Sakura ! ";
    }
}