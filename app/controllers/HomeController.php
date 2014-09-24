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
        $validator = $this->validator->make([], $rules);
        var_dump($validator->passes());
        var_dump($validator->errors());

        //return $this->json(['app' => 'Rester', 'message' => 'Hello world!']);
    }
}