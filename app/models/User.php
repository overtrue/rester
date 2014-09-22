<?php 

use Illuminate\Database\Eloquent\Model;

class User extends Model 
{   
    protected $table = 'book';
    protected $fillable   = [];
    public $timestamps = true;

}