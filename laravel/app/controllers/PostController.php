<?php
class PostController extends BaseController {

	public function getIndex() {
		return View::make('generic.post');
	}

    /**
     * Get Post
     */
    public function getPost($alias)
    {	
        $post = Post::where('alias', $alias)->first();
        return View::make('generic.post')
						->with('post', $post);
    }


	/**
	 * New Post
	 */
	public function getNew() {
		return View::make('posts/form');
	}
	
	public function getForm() {
		return $this->getNew();
	}

}