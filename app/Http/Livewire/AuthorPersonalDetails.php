<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;

class AuthorPersonalDetails extends Component
{
    public $author;
    public $name, $username, $email, $biography;

    public function mount()
    {
        $this->author = User::find(auth('web')->id());
        $this->name = $this->author->name;
        $this->username = $this->author->username;
        $this->email = $this->author->email;
        $this->biography = $this->author->biography;
    }

    public function UpdateDetails()
    {
        $this->validate([
            'name' => 'required|string',
            'username' => 'required|unique:users,username, '.auth('web')->id() // unique except the auth user [table,column,except]
        ]);

        User::where('id', auth('web')->id())->update([
            'name' => $this->name,
            'username' => $this->username,
            'biography' => $this->biography,
        ]);

        $this->emit('updateAuthorProfileHeader');
        $this->emit('updateTopHeader');

        $this->dispatchBrowserEvent('info', ['message' => 'Your Profile info have been successfully updated.']);
    }

    public function render()
    {
        return view('livewire.author-personal-details');
    }
}
