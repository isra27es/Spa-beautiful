<?php

namespace App\Livewire;

use App\Models\Images;
use App\Models\Service_sections;
use App\Models\Services;
use Livewire\Attributes\Title;
use Livewire\Component;

class Section extends Component
{
    #[Title('Servicios')]
    public $services = [], $sections = [];

    public function mount($id){

        $section = Service_sections::select('service_sections.*', 'images.*')
        ->join('images','images.service_sections_id','=','service_sections.service_sections_id')
        ->where('service_sections.service_sections_id', $id)
        ->first();

        $this->sections = [
            'name' => $section->name,
            'description' => $section->description,
            'path' => $section->path_images,
        ];

        $service = Services::select('common_attributes.*', 'services.*')
        ->join('common_attributes','common_attributes.common_attributes_id','=','services.common_attributes_id')
        ->where('service_sections_id', $id)
        ->get();
        foreach ($service as $ser) {
            $service_id = $ser->services_id;
            $images_services = Images::where('services_id', $service_id)->first();
            if ($images_services && $images_services->path_images) {
                $image = $images_services->path_images;
            } else {
                $image = 'null';
            }
            $this->services[] = [
                'benefit' => $ser->benefit,
                'duration' => $ser->duration,
                'name' => $ser->name,
                'description' => $ser->description,
                'price' => $ser->price,
                'discount' => $ser->discount,
                'image' => $image,
                'services_id' => $service_id,
            ];
        }
    }
    public function render()
    {
        return view('livewire.section');
    }
}
