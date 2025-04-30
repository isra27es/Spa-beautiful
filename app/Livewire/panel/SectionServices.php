<?php

namespace App\Livewire\Panel;

use App\Models\Images;
use App\Models\Service_sections;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class SectionServices extends Component
{
    use WithFileUploads;
    #[Rule('required|regex:/^[a-zA-Z\sáéíóúÁÉÍÓÚñÑ]+$/')]
    public $name;

    #[Rule('required|max:450|min:10')]
    public $description;
    public $path;
    public $id;
    public $addSectionOpen = false;
    public $sections = [];
    public $editMode = false, $messageConfirmDelete = false;
    public function messages()
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'name.regex' => 'El nombre solo puede contener letras.',
            'description.required' => 'La descripción es obligatoria.',
            'description.max' => 'La descripción no puede tener más de 450 caracteres.',
            'description.min' => 'La descripción debe de tener mas de 10 caracteres.',
        ];
    }
    public function openModal()
    {
        $this->addSectionOpen = true;
    }

    public function closeModal()
    {
        $this->addSectionOpen = false;
        $this->messageConfirmDelete = false;
    }
    public function mount()
    {
        $this->reset('sections', 'path');
        $section = Service_sections::all();
        foreach ($section as $key => $value) {
            $this->sections[] = [
                'id' => $value->service_sections_id,
                'name' => $value->name,
                'description' => $value->description
            ];
        }
    }
    public function saveSection()
    {
        $this->validate();

        if ($this->path) {
            $destinationPath = 'images_background';
            $uniqueId = uniqid();
            $timestamp = time();
            $randomNumber = rand(1000, 9999);
            $filename = "product/{$destinationPath}_{$uniqueId}_{$timestamp}_{$randomNumber}" . '.' . $this->path->extension();
            $this->path->storeAs('public', $filename);
        }

        if ($this->editMode) {
            $section = Service_sections::find($this->id);
            $section->name = $this->name;
            $section->description = $this->description;
            $section->save();
            $this->editMode = false;
            if ($this->path) {


                $image = Images::where('service_sections_id', $section->service_sections_id)->first();

                $fullPath = 'public/' . $image->path_images;
                if (Storage::exists($fullPath)) {
                    $deleteResult = Storage::delete($fullPath);
                    if ($deleteResult) {
                    } else {
                        dd('File could not be deleted.');
                    }
                } else {
                    dd('File does not exist.');
                }

                $image->path_images = $filename;
                $image->save();
            }
        } else {
            $section = new Service_sections;
            $section->name = $this->name;
            $section->description = $this->description;
            $section->save();

            $image = new Images;
            $image->path_images = $filename;
            $image->service_sections_id = $section->service_sections_id;
            $image->save();
        }
        $this->closeModal();
        $this->mount();
        $this->clear();
    }
    public function editSection($id)
    {
        $this->addSectionOpen = true;
        $this->reset('id');
        $this->id = $id;
        $section = Service_sections::find($this->id);
        $this->name = $section->name;
        $this->description = $section->description;
        $this->editMode = true;
    }
    public function cancelEdit()
    {
        $this->addSectionOpen = false;
        $this->editMode = false;
        $this->clear();
    }
    public function messageDelete($id)
    {
        $this->reset('id');
        $this->id = $id;
        $this->messageConfirmDelete = true;
    }
    public function deleteConfirm()
    {
        $section = Service_sections::find($this->id);
        $img = Images::where('service_sections_id', $section->service_sections_id)->first();
        $fullPath = 'public/' . $img->path_images;
        if (Storage::exists($fullPath)) {
            $deleteResult = Storage::delete($fullPath);
            if ($deleteResult) {
            } else {
                dd('File could not be deleted.');
            }
        } else {
            dd('File does not exist.');
        }
        $section->delete();
        $this->messageConfirmDelete = false;

        $this->mount();
        $this->clear();
    }
    public function deleteCancel()
    {
        $this->messageConfirmDelete = false;
    }
    public function clear()
    {
        $this->reset('name', 'description');
    }
    public function render()
    {
        return view('livewire.panel.section-services');
    }
}
