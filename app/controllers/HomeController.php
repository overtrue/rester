<?php

class HomeController extends Controller
{
    public function index()
    {
        return $this->json(User::first()->toArray());
    }
}