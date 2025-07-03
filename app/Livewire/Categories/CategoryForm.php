<?php

namespace App\Livewire\Categories;

use Livewire\Component;
use App\DTOs\CategoryData;
use App\Services\CategoryService;

class CategoryForm extends Component
{
    /**
     * Represents the ID of the category.
     *
     * @var int|null $categoryId
     */

    /**
     * Represents the name of the category.
     *
     * @var string|null $name
     */
    public $categoryId;
    public $name;

    /**
     * Mount the component with an optional category ID.
     *
     * @param int|null $categoryId The ID of the category to load, or null to initialize without a category.
     * 
     * This method initializes the component state. If a category ID is provided, 
     * it retrieves the corresponding category from the database and sets the 
     * component's name property to the category's name.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the category ID is provided but no matching category is found.
     */
    public function mount($categoryId = null)
    {
        $this->categoryId = $categoryId;

        $service = app(CategoryService::class);

        if ($categoryId) {
            $category = $service->find($categoryId);
            $this->name = $category->name;
        }
    }

    /**
     * Save the category data.
     *
     * This method validates the input data and either updates an existing category
     * or creates a new one. After saving the category, it dispatches a 'categorySaved'
     * event for further processing.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException If validation fails.
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the category ID is invalid.
     */
    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
        ]);

        $dto = new CategoryData(name: $this->name, slug: $this->name);

        $service = app(CategoryService::class);
        if ($this->categoryId) {
            $category = $service->find($this->categoryId);
            $service->update($category, $dto);
        } else {
            $service->store($dto);
        }

        $this->mount($this->categoryId);

        $this->dispatch('categorySaved');
    }

    /**
     * Render the Livewire component view for the category form.
     *
     * @return \Illuminate\View\View The view instance for the category form.
     */
    public function render()
    {
        return view('livewire.categories.category-form');
    }
}
