<?php

namespace App\Livewire;

use App\Models\Images;
use Livewire\Component;

class Gallery extends Component
{
    public $images = [];
    public $organizedImages = [];
    public function mount()
    {
        $image = Images::select('images.*', 'services.*', 'common_attributes.*')
            ->join('services', 'services.services_id', '=', 'images.services_id')
            ->join('common_attributes', 'common_attributes.common_attributes_id', '=', 'services.common_attributes_id')
            ->get();

        foreach ($image as $img) {
            $serviceName = $img->name;

            if (!isset($this->organizedImages[$serviceName])) {
                $this->organizedImages[$serviceName] = [];
            }

            $this->organizedImages[$serviceName][] = [
                'images_id' => $img->images_id,
                'path_images' => $img->path_images,
                'services_id' => $img->services_id,
                'service_name' => $img->name,
                'products_id' => $img->products_id,
                'service_sections_id' => $img->service_sections_id,
                'created_at' => $img->created_at,
                'updated_at' => $img->updated_at,
                'benefit' => $img->benefit,
                'duration' => $img->duration,
                'common_attributes_id' => $img->common_attributes_id,
                'description' => $img->description,
                'price' => $img->price,
                'discount' => $img->discount
            ];
        }
    }
    public function render()
    {
        return view('livewire.gallery');
    }
}
