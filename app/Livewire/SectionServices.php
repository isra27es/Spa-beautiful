<?php

namespace App\Livewire;

use App\Models\Images;
use App\Models\Service_sections;
use Livewire\Attributes\Title;
use Livewire\Component;

class SectionServices extends Component
{
    #[Title('Servicios')]
    public $sections = [], $isPair;
    public function mount(){
        $this->isPair = false;
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
        $isPair_num = count($this->sections);
        $isPair_num = ($isPair_num % 2);
        if($isPair_num == 0){
            $this->isPair = true;
        }
    }
    public function render()
    {
        return view('livewire.section-services');
    }
}
