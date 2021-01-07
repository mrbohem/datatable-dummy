<?php

namespace Mrbohem;

trait DataTableTrait
{
     protected $paginationTheme = 'bootstrap';
     public $sortField = 'id';
     public $sortAsc = true;
     public $search = '';
     public $perPage = 10;
     public $editableId = null;
     public $model;

     public function hydrate()
     {
          $this->resetErrorBag();
          $this->resetValidation();
     }

     public function updated($field)
     {
          $this->validateOnly($field);
     }

     public function sortBy($field)
     {
          if ($this->sortField == $field) {
               $this->sortAsc = !$this->sortAsc;
          } else {
               $this->sortAsc = true;
          }
          $this->sortField = $field;
     }

     public function clearProps()
     {
          $this->reset('model', 'editableId');
          $this->dispatchBrowserEvent('refreshSelectPicker');
     }

     public function add()
     {
          $this->clearProps();
          $this->dispatchBrowserEvent('openTableModal');
     }

     public function read($id)
     {
          $this->editableId = $id;
          $this->model = $this->modelClass::findOrFail($id);
          $this->dispatchBrowserEvent('refreshSelectPicker');
          $this->resetErrorBag();
     }

     public function submit()
     {
          $data = $this->validate();
          if ($this->editableId) {
               $this->modelClass::find($this->editableId)->update($data['model']);
               $title = 'Updated';
          } else {
               $this->modelClass::create($data['model']);
               $title = 'Created';
          }
          $this->dispatchBrowserEvent('closeModal');
          $this->dispatchBrowserEvent('swal', ['title' => $title, 'icon' => 'success']);
          $this->clearProps();
     }

     public function delete($id)
     {
          $this->modelClass::findOrFail($id)->delete();
          $this->dispatchBrowserEvent('swal', ['title' => 'Deleted', 'icon' => 'success']);
     }
}
