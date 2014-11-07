<?php
use Jenssegers\Mongodb\Model as Eloquent;

class WeeklyDigest extends Eloquent {
	
	protected $connection = 'mongodb';
	protected $collection = 'weekly_digest';
	protected $dates = array('created_at');

}