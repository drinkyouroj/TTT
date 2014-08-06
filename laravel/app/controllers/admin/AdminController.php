<?php
class AdminController extends Controller {
	
	public function __construct(PostRepository $post) {
		$this->post = $post;
	}

	/**
	 * Landing Page for Admin.  Needs tons of work.
	 */
	public function getIndex() {
		
		//provide currently featured articles.
		$featured = $this->post->allFeatured();
		$post_count = $this->post->countPublished();//All published posts.
		
		$user_count = User::where('banned', false)
					->count();
		
		return View::make('admin.index')
				->with('featured', $featured)
				->with('post_count', $post_count)
				->with('user_count', $user_count);
	}
	
	/******************************************************************
	 * Message Everyone system. 
	 */
	public function postMessageall() {
		$message = self::message_object_input_filter();
		$message->save();
		
		return Redirect::to('admin');
	}
	
		private function message_object_input_filter() {
			$message = new Message;
			$message->from_uid = Auth::user()->id;
			$message->to_uid = 0;
			$message->body = Request::get('body');
			return $message;
		}
	
	

	/******************************************************************
	 * Category Systems
	 */

	//Add a new Category
	public function postAddcategory() {
		$category = self::cat_object_input_filter();
		$validator = $category->validate($category->toArray());
		
		if($validator->passes()) {
			$category->save();
			return Redirect::to('admin');
		} else {
			return Redirect::to('admin')
							->withErrors($validator)
							->withInput();
		}
		
	}
	
		private function cat_object_input_filter() {
			
			$category = new Category;
			$category->title = Request::get('title');
			$category->alias = preg_replace('/[^A-Za-z0-9]/', '', Request::get('title') );
			$category->description = Request::get('description');
			
			return $category;
		}
	
	//Delete category
	public function postDelcategory() {
		$categories = Request::get('category');
		
		Category::whereIn('id', $categories)->delete();
		
		return Redirect::to('admin');
	}


	/**************************************************************
	 * RESTFUL stuff
	 */

	//Below sets a certain 
	public function getFeature() {
		$feature_id = Request::segment(3);
		
		$width = Request::get('width',1);
		$height = Request::get('height',1);
		
		$order = Request::get('order','last');//last
		
		$post = $this->post->findById($feature_id);
		
		//flip the featured
		if($post->featured) {
			$post->featured = false;
			$post->featured_date = date('Y-m-d');
			$order = $post->order;
			Featured::where('post_id', $post->id)->delete();
			
			$reorders = Featured::orderBy('order', 'ASC');
			$total = $reorders->count();
			foreach($reorders->get() as $k => $reorder) {
				Featured::where('id', $reorder->id)
						->update(array('order'=>$k+1));
			}
			
		} else {
			$post->featured = true;
			$post->featured_date = date('Y-m-d');
			
			//lets just be sure we didnt' already post this as new.
			if(!Featured::where('post_id', $post->id)->count()) {
				
				if($order == 'first') {
					$order_pos = 1;
					//Now gotta reset the ordering for rest of the featured items.
					Featured::increment('order',1);
				} else {
					$order_pos = Featured::count()+1;
				}
				
				//newly featured.
				$featured = new Featured;			
				$featured->user_id = $post->user->id;
				$featured->post_id = $post->id;
				$featured->width = $width;
				$featured->height = $height;
				$featured->order = $order_pos;
				$featured->save();
			}
		}
		
		
		$post->save();
		
		return Response::json(
			array(
				'status' => $post->featured 
			),
			200//response is OK!
		);
		
	}
	
	public function getModassign() {
		$id = Request::segment(3);
		$user = User::where('id', $id)->first();
		
		$mod = Role::where('name', 'Moderator')->first();
		
		if($user->hasRole('Moderator')) {
			$user->detachRole($mod);
			return Response::json(
				array(
					'status' => 'detached'
				),
				200//response is OK!
			);
		} else {
			$user->attachRole($mod);
			return Response::json(
				array(
					'status' => 'attached'
				),
				200//response is OK!
			);
		}
	}
	
	//Danger Danger! NOPE! just another soft hard delete.
	public function getHarddeletepost() {
		$id = Request::segment(3);
		if($id != 0) {
			$post = $this->post->findById($id,'trashed');//gets trashed too.
			if($post->published == true) {
				//Unpublish				
				$this->post->delete($id);
				
				return Response::json(
					array('result'=>'deleted'),
					200//response is OK!
				);
			} else {
				//Publish
				$this->post->undelete($id);
				
				//return the body so that we can display it.
				return Response::json(
					array('result'=>'restored'),
					200//response is OK!
				);
			}
		} else {
			return Response::json(
				array('result'=>'fail'),
				200//response is OK!
			);
		}
	}
	
	//Resets the solr users database from the SQL database.
	public function getResetSolr() {
		$users = User::get();
		foreach($users as $user) {
			SolariumHelper::updateUser($user);
		}
		return'Good'; 
	}
	
}