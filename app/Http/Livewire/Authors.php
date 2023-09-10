<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Nette\Utils\Random;
use Illuminate\Support\Facades\Mail;
use Livewire\WithPagination;
use Illuminate\Support\Facades\File;

class Authors extends Component
{
    use WithPagination;
    public $name, $email, $username, $author_type, $direct_publisher;
    public $search;
    public $perPage = 4;
    public $selected_author_id;
    public $blocked = 0;

    protected $listeners = [
        'resetForms',
        'deleteAuthorAction'
    ];

    public function mount(){
        $this->resetPage();
    }

    public function updatingSearch(){
        $this->resetPage();
    }

    public function resetForms()
    {
        $this->name = $this->email = $this->username = $this->author_type = $this->direct_publisher = null;
        $this->resetErrorBag();
    }

    public function addAuthor()
    {
        $this->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'username' => 'required|unique:users,username|min:6|max:20',
            'author_type' => 'required',
            'direct_publisher' => 'required',
        ],[
            'author_type.required' => 'Choose author type',
            'direct_publisher' => 'Specify author publication access'
        ]);

        if($this->isOnline()){
            
            $default_password = Random::generate(8);

            $author = new User();
            $author->name = $this->name;
            $author->email = $this->email;
            $author->username = $this->username;
            $author->password = Hash::make($default_password);
            $author->type = $this->author_type;
            $author->direct_publish = $this->direct_publisher;
            $saved = $author->save();

            $data =  array(
                'name' => $this->name,
                'username' => $this->username,
                'email' => $this->email,
                'password' => $default_password,
                'url' => route('author.profile'),
            );

            $author_email = $this->email;
            $author_name = $this->name;

            if($saved){

                // Mail::send('new-author-email-template', $data, function($message) use ($author_email, $author_name){
                //     $message->from('noreply@example.com', 'Larablog');
                //     $message->to($author_email,$author_name)
                //             ->subject('Account Creation');
                // });

                $mail_body = view('new-author-email-template', $data)->render();

                $mailConfig = array(
                    'mail_from_email' => env('EMAIL_FROM_ADDRESS'),
                    'mail_from_name' => env('EMAIL_FROM_NAME'),
                    'mail_recipient_email' => $author_email,
                    'mail_recipient_name' => $author_name,
                    'mail_subject' => 'Account Creation',
                    'mail_body' => $mail_body

                );

                sendMail($mailConfig);


                $this->name = $this->email = $this->username = $this->author_type = $this->direct_publisher = null;
                $this->dispatchBrowserEvent('success', ['message' => 'New author has been added to blog']);
                $this->emit('hideAddAuthorModal'); // Emit a custom event to hide the modal


            }else{
                $this->dispatchBrowserEvent('error', ['message' => 'Something went wrong.']);
            }

        }else{
            $this->dispatchBrowserEvent('error', ['message' => 'You are offline, check your connection and submit form again later']);
        }

    }

    public function editAuthor($author){
        // dd(['open edit author modal', $author]);
        $this->selected_author_id = $author['id'];
        $this->name = $author['name'];
        $this->email = $author['email'];
        $this->username = $author['username'];
        $this->author_type = $author['type'];
        $this->direct_publisher = $author['direct_publish'];
        $this->blocked = $author['blocked'];

        $this->dispatchBrowserEvent('showEditAuthorModal');
    }

    public function updateAuthor(){
        $this->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$this->selected_author_id,
            'username' => 'required|min:6|max:20|unique:users,username,'.$this->selected_author_id,
        ]);

        if($this->selected_author_id){
            $author = User::find($this->selected_author_id);
            $author->update([
                'name' => $this->name,
                'email' => $this->email,
                'username' => $this->username,
                'type' => $this->author_type,
                'blocked' => $this->blocked,
                'direct_publish' => $this->direct_publisher,
            ]);
        }

        
        $this->dispatchBrowserEvent('success', ['message' => 'Author details have been successfully updated']);
        $this->dispatchBrowserEvent('hide_edit_author_modal');

    }

    public function deleteAuthor($author){
        // dd(['Delete author', $author]);
        $this->dispatchBrowserEvent('deleteAuthor',[
            'title' => 'Are you sure?',
            'html' => 'You want to delete this author: <br><b>'.$author['name'].'<b>',
            'id' => $author['id'],
        ]);
    }

    public function deleteAuthorAction($id){
        // dd('yes delete');

        $author = User::find($id);
        $path = 'back/dist/img/authors/';
        $author_picture = $author->getAttributes()['picture'];
        $picture_full_path = $path.$author_picture;

        if($author_picture != null || File::exists(public_path($picture_full_path))){
            File::delete(public_path($picture_full_path));
        }

        $author->delete();
        $this->dispatchBrowserEvent('success', ['message' => 'Author has been successfull deleted']);



    }


    public function isOnline($site = 'https://youtube.com')
    {
        if(@fopen($site,'r')){
            return true;
        }else{
            return false;
        }
    }

    public function render()
    {
        return view('livewire.authors',[
            'authors' => User::search(trim($this->search))
                            ->where('id', '!=', auth()->id())->paginate($this->perPage),
            // 'authors' => User::where('id', '!=', auth()->id())->get(),
        ]);
    }
}
