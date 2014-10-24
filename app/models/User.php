<?php

class User extends Model
{
    protected $table      = 'system_users';
    protected $primaryKey = 'uid';
    protected $fillable   = ['*'];
    public $timestamps    = false;

}