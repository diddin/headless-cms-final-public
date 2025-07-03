<?php

namespace App\Livewire\Categories;

use App\Models\Category;
use Livewire\Component;
use App\Services\CategoryService;

class CategoryIndex extends Component
{
    /**
     * @var bool $showModal Indicates whether the modal is visible or not.
     * @var int|null $editId Stores the ID of the category being edited, or null if no category is being edited.
     */
    public $showModal = false;
    public $editId = null;

    /**
     * Protected property to define event listeners for the Livewire component.
     * 
     * @var array $listeners
     * 
     * Listens for the 'categorySaved' event and triggers the 'refresh' method
     * when the event is emitted.
     */
    protected $listeners = ['categorySaved' => 'refresh'];

    /**
     * Opens the modal for creating a new category.
     * 
     * This method sets the `editId` property to `null` and the `showModal` property to `true`,
     * indicating that the modal should be displayed for creating a new category.
     * 
     * @return void
     */
    public function create()
    {
        $this->editId = null;
        $this->showModal = true;
    }

    /**
     * Opens the edit modal for a specific category.
     *
     * @param int $id The ID of the category to be edited.
     * @return void
     */
    public function edit($id)
    {
        $this->editId = $id;
        $this->showModal = true;
    }

    /**
     * Refreshes the component state by hiding the modal.
     *
     * This method sets the `$showModal` property to `false`, effectively closing
     * any modal that might be displayed. It can be used to reset the UI state
     * after certain actions or events.
     *
     * @return void
     */
    public function refresh()
    {
        $this->showModal = false;
    }

    /**
     * Deletes a category by its ID.
     *
     * This method locates a category using the provided ID and deletes it
     * from the database. If the category is not found, an exception will
     * be thrown.
     *
     * @param int $id The ID of the category to delete.
     * 
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the category is not found.
     * @return void
     */
    public function delete($id)
    {
        $service = app(CategoryService::class);
        $category = $service->find($id);
        $service->delete($category);
    }

    /**
     * Render the Livewire component view for the category index.
     *
     * This method retrieves the latest categories from the database
     * and passes them to the 'livewire.categories.category-index' view.
     *
     * @return \Illuminate\View\View The rendered view for the category index.
     */
    public function render()
    {
        return view('livewire.categories.category-index', [
            'categories' => Category::latest()->get(),
        ]);
    }
}
