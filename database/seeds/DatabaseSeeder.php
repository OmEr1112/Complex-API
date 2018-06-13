<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Category;
use App\Product;
use App\Transaction;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{

    private $tables = [
      'users',
      'categories',
      'products',
      'transactions',
      'category_product'
    ];
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);

      $this->cleanDatabase();

      Model::unguard();

      $usersQuantity = 1000;
      $categoriesQuantity = 30;
      $productsQuantity = 1000;
      $transactionsQuantity = 1000;

      User::flushEventListeners();
      Category::flushEventListeners();
      Product::flushEventListeners();
      Transaction::flushEventListeners();

      factory(User::class, $usersQuantity)->create();
      factory(Category::class, $categoriesQuantity)->create();
      factory(Product::class, $productsQuantity)->create()->each(
        function ($product) {
          $categories = Category::all()->random(mt_rand(1, 5))->pluck('id');
          $product->categories()->attach($categories);
        });
      factory(Transaction::class, $transactionsQuantity)->create();
    }

    private function cleanDatabase() {
      DB::statement('SET FOREIGN_KEY_CHECKS=0');

      foreach ($this->tables as $tableName) {
        DB::table($tableName)->truncate();
      }
      
      DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
