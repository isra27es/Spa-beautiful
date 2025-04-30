<?php

namespace App\Livewire;

use App\Models\Images;
use App\Models\Service_sections;
use Livewire\Attributes\Title;
use Livewire\Component;

class Home extends Component
{
    public $sections =[];
    public function mount()
    {
        $section = Service_sections::all();
        foreach ($section as $sec) {
            $image = Images::where('service_sections_id', $sec->service_sections_id )->first();
            $this->sections[] = [
                'id' => $sec->service_sections_id,
                'name' => $sec->name,
                'description' => $sec->description,
                'path' => $image->path_images,
            ];
        }

    }
    #[Title('Beauty Spa')]
    public function render()
    {
        return view('livewire.home');
    }
}
