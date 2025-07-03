<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use App\Models\Page;

class PageIndex extends Component
{
    /**
     * Class PageIndex
     *
     * This class represents a Livewire component for managing page index functionality.
     *
     * Properties:
     * @property bool $showModal Indicates whether the modal is visible or not.
     * @property int|null $editId Stores the ID of the page being edited, or null if no page is being edited.
     *
     * Protected Listeners:
     * @listener pageSaved Refreshes the component when a page is saved.
     */
    public $showModal = false;
    public $editId = null;

    protected $listeners = ['pageSaved' => 'refresh'];

    /**
     * Opens the modal for creating a new page.
     *
     * This method sets the `editId` property to `null` and the `showModal` property to `true`,
     * indicating that the modal should be displayed for creating a new page.
     *
     * @return void
     */
    public function create()
    {
        $this->editId = null;
        $this->showModal = true;
    }

    /**
     * Opens the edit modal for a specific item.
     *
     * @param int $id The ID of the item to be edited.
     * @return void
     */
    public function edit($id)
    {
        $this->editId = $id;
        $this->showModal = true;
    }

    /**
     * Deletes a page record by its ID.
     *
     * This method locates a page record using the provided ID and deletes it
     * from the database. If the page record is not found, an exception will
     * be thrown.
     *
     * @param int $id The ID of the page to be deleted.
     * 
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the page with the given ID is not found.
     * @return void
     */
    public function delete($id)
    {
        Page::findOrFail($id)->delete();
    }

    /**
     * Refreshes the page state by hiding the modal.
     *
     * This method sets the `$showModal` property to `false`, effectively
     * closing or hiding the modal on the page.
     *
     * @return void
     */
    public function refresh()
    {
        $this->showModal = false;
    }

    /**
     * Render the Livewire component view.
     *
     * This method retrieves the latest pages from the database
     * and passes them to the 'livewire.pages.page-index' view.
     *
     * @return \Illuminate\View\View The rendered view for the component.
     */
    public function render()
    {
        return view('livewire.pages.page-index', [
            'pages' => Page::latest()->get(),
        ]);
    }
}
