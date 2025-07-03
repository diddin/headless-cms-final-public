<?php

namespace App\Livewire\Posts;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithFileUploads;

class PostForm extends Component
{
    /**
     * Class PostForm
     * 
     * This Livewire component handles the form for creating or editing posts.
     * It includes file upload functionality and manages post attributes such as title, excerpt, content, image, status, 
     * publication date, and associated categories.
     * 
     * Properties:
     * - $postId: The ID of the post being edited (if applicable).
     * - $title: The title of the post (required).
     * - $excerpt: A short summary of the post (optional).
     * - $content: The main content of the post (required).
     * - $image: The current image associated with the post.
     * - $status: The publication status of the post ('draft' or 'published', default is 'draft').
     * - $published_at: The publication date of the post (optional).
     * - $selectedCategories: An array of category IDs associated with the post.
     * - $newImage: A new image file to be uploaded (optional, must be an image file with a maximum size of 2MB).
     * 
     * Validation Rules:
     * - 'title': Required.
     * - 'excerpt': Optional.
     * - 'content': Required.
     * - 'newImage': Optional, must be an image file with a maximum size of 2048 KB.
     * - 'status': Required, must be either 'draft' or 'published'.
     * - 'published_at': Optional, must be a valid date.
     * - 'selectedCategories': Must be an array.
     * - 'selectedCategories.*': Each category ID must exist in the 'categories' table.
     * 
     * Usage:
     * This component is used in the context of a Laravel application with Livewire to manage post creation and editing.
     */
    use WithFileUploads;

    public $postId;
    
    public array $title = ['en' => '', 'id' => ''];
    public array $excerpt = ['en' => '', 'id' => ''];
    public array $content = ['en' => '', 'id' => ''];
    
    public $image, $status = 'draft', $published_at;
    public $selectedCategories = [];
    

    public $newImage;

    protected $rules = [
        'title.en' => 'required|string',
        'title.id' => 'required|string',

        'excerpt.en' => 'nullable|string',
        'excerpt.id' => 'nullable|string',

        'content.en' => 'required|string',
        'content.id' => 'required|string',
        
        'newImage' => 'nullable|image|max:2048',
        'status' => 'required|in:draft,published',
        'published_at' => 'nullable|date',
        'selectedCategories' => 'array',
        'selectedCategories.*' => 'exists:categories,id'
    ];

    /**
     * Mount the component with optional post data.
     *
     * @param int|null $postId The ID of the post to load, or null for a new post.
     *
     * If a post ID is provided, the method retrieves the post along with its associated categories
     * and initializes the component's properties with the post's data. If no post ID is provided,
     * the method sets the default published_at property to the current date and time.
     *
     * Properties initialized:
     * - $postId: The ID of the post.
     * - $title: The title of the post.
     * - $excerpt: The excerpt of the post.
     * - $content: The content of the post.
     * - $image: The image associated with the post.
     * - $status: The status of the post.
     * - $published_at: The publication date and time of the post.
     * - $selectedCategories: An array of category IDs associated with the post.
     */
    public function mount($postId = null)
    {
        if ($postId) {
            $post = Post::with('categories')->findOrFail($postId);
    
            $this->postId = $post->id;
    
            // Pastikan field JSON di-cast jadi array
            $this->title = is_string($post->title)
                ? json_decode($post->title, true)
                : ($post->title ?? ['en' => '', 'id' => '']);
            $this->excerpt = is_string($post->excerpt)
                ? json_decode($post->excerpt, true)
                : ($post->excerpt ?? ['en' => '', 'id' => '']);
            $this->content = is_string($post->content)
                ? json_decode($post->content, true)
                : ($post->content ?? ['en' => '', 'id' => '']);
            
            $this->image = $post->image;
            $this->status = $post->status;
            $this->published_at = optional($post->published_at)->format('Y-m-d\TH:i');
            $this->selectedCategories = $post->categories()->pluck('categories.id')->toArray();
        } else {
            // Default saat create
            $this->title = ['en' => '', 'id' => ''];
            $this->excerpt = ['en' => '', 'id' => ''];
            $this->content = ['en' => '', 'id' => ''];
            $this->published_at = now()->format('Y-m-d\TH:i');
        }
    }

    /**
     * Save the post data to the database.
     *
     * This method validates the input data, determines whether to create a new post
     * or update an existing one, and saves the post details including title, excerpt,
     * content, status, published date, image, and associated categories.
     *
     * Workflow:
     * - Validates the input data.
     * - Checks if a post ID exists:
     *   - If yes, retrieves the existing post.
     *   - If no, creates a new post instance.
     * - Assigns the input data to the post attributes.
     * - Handles image upload if a new image is provided.
     * - Saves the post to the database.
     * - Synchronizes the post's categories with the selected categories.
     * - Resets the component state.
     * - Dispatches a 'post-saved' event.
     *
     * @return void
     */
    public function save()
    {
        $this->validate();

        $post = $this->postId
            ? Post::findOrFail($this->postId)
            : new Post();

        $post->title = $this->title;
        $post->excerpt = $this->excerpt;
        $post->content = $this->content;
        $post->status = $this->status;
        $post->published_at = $this->published_at
        ? $this->published_at
        : null;

        if ($this->newImage) {
            $disk = config('filesystems.default');
            $path = $this->newImage->store('posts', $disk);
            $post->image = $path;
        }

        $post->save();

        $post->categories()->sync($this->selectedCategories);

        $this->mount($this->postId);

        $this->dispatch('post-saved');
    }

    /**
     * Render the Livewire component view.
     *
     * This method returns the view associated with the Livewire component.
     * It is responsible for rendering the 'livewire.posts.post-form' view.
     *
     * @return \Illuminate\View\View The rendered view instance.
     */
    public function render()
    {
        return view('livewire.posts.post-form');
    }
}
