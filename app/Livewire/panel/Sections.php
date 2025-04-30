<?php

namespace App\Livewire\Panel;

use App\Models\Common_attributes;
use App\Models\Images;
use App\Models\Service_sections;
use App\Models\Services;
use App\Models\Videos;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class Sections extends Component
{
    use WithFileUploads;
    // Variables de los campos que guardare en servicios
    #[Rule('required|max:50|regex:/^[^\d]+$/')]
    public $name;

    #[Rule('required|max:255')]
    public $description;

    #[Rule('required|numeric|min:0')]
    public $duration;

    public $discount;

    #[Rule('required|max:255')]
    public $benefit;

    #[Rule('required|numeric|min:0')]
    public $price;
    #[Rule('required|exists:service_sections,service_sections_id')]
    public $section_service;
    public $files, $video;
    // TERMINAN VALIDACIONES //


    public $edit_mode = false, $service_id;
    public $services = [], $sections = [];
    public $messageConfirmDelete = false;
    public $images = [];
    public $imagesView = false;
    public function showConfirmDelete($id)
    {
        $this->messageConfirmDelete = true;
        $this->service_id = $id;
    }
    public function show_images($id){
        $this->imagesView = true;
        $this->service_id = $id;
        $this->images();
    }
    public function messages()
    {
        return [
            'name.required' => 'El campo nombre es obligatorio.',
            'name.max' => 'El campo nombre no puede tener más de 50 caracteres.',
            'name.regex' => 'El campo nombre solo puede contener letras y caracteres especiales.',

            'description.required' => 'El campo descripción es obligatorio.',
            'description.max' => 'El campo descripción no puede tener más de 255 caracteres.',

            'duration.required' => 'El campo duración es obligatorio.',
            'duration.numeric' => 'El campo duración debe ser un valor numérico.',
            'duration.min' => 'El campo duración debe ser mayor o igual a 0.',

            'benefit.required' => 'El campo beneficio es obligatorio.',
            'benefit.max' => 'El campo beneficio no puede tener más de 255 caracteres.',

            'price.required' => 'El campo precio es obligatorio.',
            'price.numeric' => 'El campo precio debe ser un valor numérico.',
            'price.min' => 'El campo precio debe ser mayor o igual a 0.',

            'section_service.required' => 'El campo sección de servicio es obligatorio.',
            'section_service.exists' => 'La sección de servicio seleccionada no es válida.',

        ];
    }
    public function mount()
    {
        $this->reset('services');
        $service = Services::select('common_attributes.*', 'services.*')
            ->join('common_attributes', 'common_attributes.common_attributes_id', '=', 'services.common_attributes_id')
            ->get();

        foreach ($service as $ser) {
            $this->services[] = [
                'benefit' => $ser->benefit,
                'duration' => $ser->duration,
                'name' => $ser->name,
                'description' => $ser->description,
                'price' => $ser->price,
                'discount' => $ser->discount,
                'services_id' => $ser->services_id,
            ];
        }
        $section = Service_sections::all();
        foreach ($section as $sec) {
            $this->sections[] = [
                'name' => $sec->name,
                'id' => $sec->service_sections_id,
            ];
        }
    }
    public function save()
    {
        $this->validate();
        $com_atrb = new Common_attributes;
        $com_atrb->name = $this->name;
        $com_atrb->description = $this->description;
        $com_atrb->price = $this->price;
        $com_atrb->discount = $this->discount;
        $com_atrb->save();

        $service = new Services;
        $service->benefit = $this->benefit;
        $service->duration = $this->duration;
        $service->service_sections_id = $this->section_service;
        $service->common_attributes_id = $com_atrb->common_attributes_id;
        $service->save();

        if ($this->files) {
            foreach ($this->files as $value) {
                $destinationPath = 'images_background';
                $uniqueId = uniqid();
                $timestamp = time();
                $randomNumber = rand(1000, 9999);
                $filename = "product/{$destinationPath}_{$uniqueId}_{$timestamp}_{$randomNumber}" . '.' . $value->extension();
                $value->storeAs('public', $filename);

                $image = new Images;
                $image->path_images = $filename;
                $image->services_id = $service->services_id;
                $image->save();
            }
        }

        if ($this->video) {
            $destinationPath = 'video';
            $uniqueId = uniqid();
            $timestamp = time();
            $randomNumber = rand(1000, 9999);
            $filename = "product/{$destinationPath}_{$uniqueId}_{$timestamp}_{$randomNumber}" . '.' . $this->video->extension();
            $this->video->storeAs('public', $filename);

            $video = new Videos;
            $video->path_videos = $filename;
            $video->services_id = $service->services_id;
            $video->save();
        }

        $this->reset('name', 'description', 'duration', 'discount', 'benefit', 'price', 'section_service');
        $this->mount();
    }
    public function edit($id)
    {
        $this->service_id = $id;
        $this->edit_mode = true;
        $service = $service = Services::select('common_attributes.*', 'services.*')
            ->join('common_attributes', 'common_attributes.common_attributes_id', '=', 'services.common_attributes_id')
            ->where('services_id', $this->service_id)
            ->first();
        $this->name = $service->name;
        $this->description = $service->description;
        $this->duration = $service->duration;
        $this->discount = $service->discount;
        $this->benefit = $service->benefit;
        $this->price = $service->price;
        $this->section_service = $service->service_sections_id;
    }
    public function cancel_edit()
    {
        $this->edit_mode = false;
        $this->reset('name', 'description', 'duration', 'discount', 'benefit', 'price', 'section_service');
        $this->mount();
    }
    public function update()
    {


        $service = Services::find($this->service_id);
        $comm_atrb = Common_attributes::find($service->common_attributes_id);
        $this->validate();

        $service->duration = $this->duration;
        $service->benefit = $this->benefit;
        $service->service_sections_id = $this->section_service;
        $service->save();

        $comm_atrb->name = $this->name;
        $comm_atrb->description = $this->description;
        $comm_atrb->discount = $this->discount;
        $comm_atrb->price = $this->price;
        $comm_atrb->save();

        if ($this->files) {
            foreach ($this->files as $value) {
                $destinationPath = 'images_background';
                $uniqueId = uniqid();
                $timestamp = time();
                $randomNumber = rand(1000, 9999);
                $filename = "product/{$destinationPath}_{$uniqueId}_{$timestamp}_{$randomNumber}" . '.' . $value->extension();
                $value->storeAs('public', $filename);

                $image = new Images;
                $image->path_images = $filename;
                $image->services_id = $this->service_id;
                $image->save();
            }
        }
        if ($this->video) {
            $destinationPath = 'video';
            $uniqueId = uniqid();
            $timestamp = time();
            $randomNumber = rand(1000, 9999);
            $filename = "product/{$destinationPath}_{$uniqueId}_{$timestamp}_{$randomNumber}" . '.' . $this->video->extension();
            $this->video->storeAs('public', $filename);

            $video = new Videos;
            $video->path_videos = $filename;
            $video->services_id = $this->service_id;
            $video->save();
        }


        $this->edit_mode = false;
        $this->reset('name', 'description', 'duration', 'discount', 'benefit', 'price', 'section_service', 'service_id');
        $this->mount();
    }
    public function delete()
    {
        $service = Services::find($this->service_id);
        $service->delete();
        $this->messageConfirmDelete = false;
        $this->mount();
    }
    public function images(){
        $this->reset('images');
        $images = Images::where('services_id', $this->service_id)->get();
        foreach ($images as $img) {
            $this->images[] = [
                'path' => $img->path_images,
                'id_image' => $img->images_id,
            ];
        }
    }
    public function delete_image($id){
        $image = Images::find($id);
        $image->delete();
        $this->images();
    }
    public function close()
    {
        $this->messageConfirmDelete = false;
        $this->imagesView = false;
    }
    public function render()
    {
        return view('livewire.panel.sections');
    }
}
