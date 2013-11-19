<?php
class PostCategoryPivot extends Eloquent {

    protected $table = "category_post";
    protected $primaryKey = "post_id";
	
	public function post($cat_id) {
		
	}
}