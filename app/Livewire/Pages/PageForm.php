<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use App\Models\Page;
use Illuminate\Support\Str;


class PageForm extends Component
{
    /**
     * Class PageForm
     *
     * Represents the form for managing pages in the application.
     *
     * @property int|null $pageId The ID of the page being edited or null for a new page.
     * @property string $title The title of the page.
     * @property array  $body The content/body of the page.
     * @property string $status The status of the page, default is 'draft'.
     */
    public $pageId;
    public $title;
    public $body = [];
    public $status = 'draft';

    /**
     * Mount method to initialize the component with optional page data.
     *
     * @param int|null $pageId The ID of the page to load. If null, the component is initialized without page data.
     * 
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the page with the given ID is not found.
     * 
     * This method sets the component's properties based on the page data if a valid page ID is provided.
     * It initializes the following properties:
     * - $title: The title of the page.
     * - $body: The body content of the page.
     * - $status: The status of the page.
     */
    public function mount($pageId = null)
    {
        $this->pageId = $pageId;

        if ($pageId) {
            $page = Page::findOrFail($pageId);
            $this->title = $page->title;
            $this->body = $page->body ?? [];
            $this->status = $page->status;
        }
    }

    public function addBlock($type)
    {
        $data = [];

        if ($type === 'heading') {
            $data = ['text' => '', 'level' => '2'];
        } elseif ($type === 'paragraph') {
            $data = ['text' => ''];
        } elseif ($type === 'image') {
            $data = ['url' => '', 'alt' => '', 'align' => 'center'];
        }

        $this->body[] = [
            'type' => $type,
            'id' => (string) Str::uuid(),
            'data' => $data,
        ];
    }

    public function removeBlock($index)
    {
        unset($this->body[$index]);
        $this->body = array_values($this->body);
    }

    /**
     * Save the page data to the database.
     *
     * This method validates the input data, checks if a page ID exists to determine
     * whether to update an existing page or create a new one, and saves the page
     * data to the database. It also dispatches a 'pageSaved' event upon successful
     * save.
     *
     * Validation rules:
     * - 'title': Required, string, maximum length of 255 characters.
     * - 'body': Required, string.
     * - 'status': Required, must be either 'draft' or 'published'.
     *
     * @return void
     * @throws \Illuminate\Validation\ValidationException If validation fails.
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the page ID is invalid.
     */
    public function save()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|array|min:1',
            'body.*.type' => 'required|string|in:heading,paragraph,image',
            'body.*.data' => 'required|array',
            'status' => 'required|in:draft,published',
        ]);

        $page = $this->pageId
            ? Page::findOrFail($this->pageId)
            : new Page();

        $page->title = $this->title;
        $page->body = $this->body;
        $page->status = $this->status;
        $page->save(); // slug generated in model

        $this->mount($this->pageId);

        $this->dispatch('pageSaved');
    }

    /**
     * Render the Livewire page form view.
     *
     * This method returns the view associated with the Livewire component
     * for the page form. It is responsible for rendering the UI defined
     * in the 'livewire.pages.page-form' Blade template.
     *
     * @return \Illuminate\View\View The rendered view for the page form.
     */
    public function render()
    {
        return view('livewire.pages.page-form');
    }
}
