<?php
namespace Tests\Unit;
use App\Models\Category;
use App\Models\Recipe;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_has_recipes_relation()
    {
        $category = Category::factory()->create();
        $recipe = Recipe::factory()->create();
        $category->recipes()->attach($recipe->id);
        $this->assertTrue($category->recipes->contains($recipe));
    }

    public function test_fillable_attributes()
    {
        $data = [
            'name' => 'Entrée',
            'description' => 'desc',
            'color' => '#FF0000'
        ];
        $category = Category::create($data);
        foreach ($data as $key => $value) {
            $this->assertEquals($value, $category->$key);
        }
    }
} 