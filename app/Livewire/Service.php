<?php

namespace App\Livewire;

use App\Models\Images;
use App\Models\Service_sections;
use App\Models\Services;
use App\Models\Videos;
use Livewire\Attributes\Title;
use Livewire\Component;

class Service extends Component
{
    #[Title('Servicios')]
    public $breadcrumb = [], $service = [];
    public function mount($id){
        $service = Services::select('services.*','common_attributes.*')
        ->join('common_attributes','common_attributes.common_attributes_id','=','services.common_attributes_id')
        ->where('services_id',$id)
        ->first();

        $serviceSection = Service_sections::where('service_sections_id', $service->service_sections_id)
        ->first();

        $image = Images::where('services_id',$service->services_id)->get();
        $images = [];
        foreach ($image as $img) {
            $images[] = [
                'path' => $img->path_images,
            ];
        }

        $video = Videos::where('services_id',$service->services_id)->first();

        if(!$video){
            $video->path_videos = false;
        }

        $this->breadcrumb = [
            'section' => $serviceSection->name,
            'service' => $service->name,
        ];
        $this->service = [
            'name' => $service->name,
            'description' => $service->description,
            'price' => $service->price,
            'duration' => $service->duration,
            'benefit' => $service->benefit,
            'images' => $images,
            'video' => $video->path_videos,
            'id_service' => $id,
        ];

    }
    public function render()
    {
        return view('livewire.service');
    }
}
