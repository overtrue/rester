<?php 

use Illuminate\Database\Eloquent\Model;

class User extends Model 
{   
    protected $table = 'user';
    protected $fillable = ['*'];
    public $timestamps = true;

}