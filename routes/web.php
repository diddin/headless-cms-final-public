<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Posts\PostIndex;
use App\Livewire\Categories\CategoryIndex;
use App\Livewire\Pages\PageIndex;
use App\Livewire\Pages\PageShow;
use Illuminate\Support\Facades\Route;
use App\Models\Page;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');

    Route::get('posts', PostIndex::class)->name('posts.index');
    Route::get('categories', CategoryIndex::class)->name('categories.index');
    Route::get('pages', PageIndex::class)->name('pages.index');
    Route::get('pages/{slug}', function ($slug) {
        $page = Page::where('slug', $slug)->firstOrFail();
        return view('pages/show', ['page' => $page]);
    })->name('pages.show');
});

require __DIR__.'/auth.php';
