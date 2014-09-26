<?php

/**
 * 演示控制器
 */
class HomeController extends Controller
{
    /**
     * demo 
     *
     * @return Slim\Http\Response
     */
    public function index()
    {
        $rules = [
            'username' => 'required',
        ];
        
        $this->validate($this->request->get(), $rules);

        //return $this->json(['app' => 'Rester', 'message' => 'Hello world!']);
    }
}