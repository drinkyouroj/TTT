<?php
 
class CategoryTableSeeder extends Seeder {
 
    public function run()
    {
        DB::table('categories')->delete();
 
        $category = new Category;
		
		//First post
        $category->parent_id = 0;
        $category->title = 'ADD';
        $category->alias = 'add';
		$category->description = 'What was I doing again?';
        $category->image = 1;
		$category->save();
		
		$category = new Category;
		
		//Second category
        $category->parent_id = 0;
        $category->title = 'Campfire';
        $category->alias = 'campfire';
		$category->description = 'Out in the woods...';
        $category->image = 1;
		$category->save();
		
		$category = new Category;
		
		//Third category
        $category->parent_id = 0;
        $category->title = 'Confession';
        $category->alias = 'confession';
		$category->description = 'Hmmmm....';
        $category->image = 1;
		$category->save();
		
    }
	
}