<?php 
use Jenssegers\Mongodb\Model as Eloquent;

class Motification extends Eloquent {
		
	protected $connection = 'mongodb';
	protected $collection = 'notifications';
}
