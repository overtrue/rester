<?php

class HomeController extends Controller
{
    public function index()
    {
        return $this->json(['hello' => 'world!']);
    }
}