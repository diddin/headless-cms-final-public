<?php

namespace App\Livewire\Posts;
use App\Models\Post;
use Livewire\Component;

class PostIndex extends Component
{
    /**
     * Class PostIndex
     *
     * This class represents a Livewire component for managing posts.
     *
     * @property int|null $postId The ID of the post being managed. Defaults to null.
     * @property array $listeners An array of event listeners for the component. 
     *                            'post-saved' triggers a refresh of the component.
     */
    public $postId = null;

    protected $listeners = ['post-saved' => '$refresh'];

    /**
     * Deletes a post by its ID and flashes a success message to the session.
     *
     * @param int $id The ID of the post to be deleted.
     * @return void
     */
    public function delete($id)
    {
        Post::find($id)?->delete();
        session()->flash('success', 'Post deleted.');
    }

    /**
     * Render the Livewire component view for displaying posts.
     *
     * This method retrieves the latest posts from the database using the Post model
     * and passes them to the 'livewire.posts.post-index' view for rendering.
     *
     * @return \Illuminate\View\View The rendered view for the posts index.
     */
    public function render()
    {
        return view('livewire.posts.post-index', [
            'posts' => Post::latest()->paginate(10),
        ]);
    }
}
