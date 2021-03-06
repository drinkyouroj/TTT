<?php 
use Illuminate\Database\Eloquent\SoftDeletingTrait;
class Post extends Eloquent {
	use SoftDeletingTrait;
	//Just to be sure!
	protected $table = 'posts';
	protected $connection = 'mysql';
	protected $softDelete = true;
	protected $with = array('user');
	protected $dates = ['deleted_at'];
	
	public function __construct( ) {
		parent::__construct();
		Validator::extend('Twothousand', function($attribute, $value, $parameters) {		
		    if(!is_null($value)) {//make sure its not empty.
		    	$chars = strlen($value);
		    	// Character limits 800 <= chars <= 14000
		    	if ( $chars >= 800 && $chars <= 14000 ) {
		    		//currently only includes alphanumeric.
			    	$word_count = count(str_word_count($value, 1, '0..9'));
					//might want to add a character limit in the future also.
					if($word_count <= 2100) {
						return true;
					} else {
						return false;//more than 2100 words
					}
		    	} else {
		    		return false;//more than 11500 chars
		    	}
		    } else {
		    	return false;//no value
		    }
		});
		
		// Validator for post categories (make sure valid categories)
		Validator::extend('categories', function($attribute, $value, $parameters) {		
		    $categories = unserialize( $value );
		    $category_repo = App::make('AppStorage\Category\CategoryRepository');
		    // Make sure each category is in the categories db
		    foreach ($categories as $key => $category_id) {
		    	$category = $category_repo->findById( $category_id );
		    	if ( !($category instanceof Category) ) {
		    		return false;
		    	}
		    }
			return true;
		});
	}
	
	public function user() {
		return $this->belongsTo('User')->select('id','username','image');//maybe take out the id later.
	}

	public function useremail() {
		return $this->belongsTo('User', 'user_id')->select('id','username','image','email');//maybe take out the id later.
	}
	
	public function comments()
    {
        return $this->hasMany('MongoComment', 'post_id')->orderBy('created_at', 'DESC');  //Gotta be in chronological order.
    }
	
	public function nochildcomments()
	{
		return $this->hasMany('MongoComment', 'post_id')->where('parent_comment','=', null);
	}
	
	public function favorites()
	{
		return $this->hasMany('Favorite');
	}
	
	public function reposts()
	{
		return $this->hasMany('Repost');
	}
	
	public function likes()
	{
		return $this->hasMany('Like');
	}
	
	public function categories()
	{
		return $this->belongsToMany('Category', 'category_post');
	}
	
	/**
	 * Filters for ALL Category (This is matched against Category Model's relationship names)
	 */
	
	public function scopePostspopular() 
	{
		return $this->where('published', true)->orderBy('like_count','DESC');
	}
	
	public function scopePostsviews()
	{
		return $this->where('published', true)->orderBy('views', 'DESC');
	}

	public function scopeRecent()
	{
		return $this->where('published', true)->orderBy('published_at','DESC');
	}
	
	public function scopePostsdiscussed()
	{
		return $this->where('published', true)->orderBy('comment_count','DESC');
	}
	
	public function scopeLongest()
	{
		return $this->where('published', true)->orderBy(DB::raw('LENGTH(body)'),'DESC');
	}

	public function scopeShortest()
	{
		return $this->where('published', true)->orderBy(DB::raw('LENGTH(body)'),'ASC');
	}

	
	/**
	 * Now with existing id checker
	 */
	public function validate($input, $id = false)
	{
		// New Post
		if($id == false) {
			if ($input['draft']) {
				// Validation for new post that is a draft (only need title or body)
				$rules = array(
					'title' => 'required_without:body',
					'body' => 'required_without:title'
				);
			} else {
				// Validation for new post -> published
				$rules = array(
					'title' => 'required',
					'story_type' => 'required|in:story,advice,thought',
					'category' => 'categories',
					'tagline_1' => 'required',
					'tagline_2' => 'required',
					'tagline_3' => 'required',
					'body' => 'Twothousand',
					'image' => 'required'
				);
			}
		// Existing Post
		} else {
			if ($input['draft']) {
				// Validation for existing post -> save as draft
				$rules = array(
					'title' => 'required_without:body',
					'body' => 'required_without:title'
				);
			} else {
				// Validation for existing post -> published
				$rules = array(
					'title' => 'required',
					'story_type' => 'required|in:story,advice,thought',
					'category' => 'categories',
					'tagline_1' => 'required',
					'tagline_2' => 'required',
					'tagline_3' => 'required',
					'body' => 'Twothousand',
					'image' => 'required'
				);
			}
			
		}
		
		return Validator::make($input, $rules);
	}
	
	
}