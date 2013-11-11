<?php
 
class CategoryTableSeeder extends Seeder {
 
    public function run()
    {
        DB::table('categories')->delete();
 
        $category = new Category;
		
		//First post
        $category->parent_id = 0;
        $category->title = 'Donkey';
        $category->alias = 'donkey';
		$category->description = 'Donkey Teeth';
        $category->image = 1;
		$category->save();
    }
	
}