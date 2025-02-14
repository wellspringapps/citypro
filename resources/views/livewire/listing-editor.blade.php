<?php
use App\Livewire\Forms\ListingForm;

use function Livewire\Volt\{
    mount,
    state,
    form,
    usesFileUploads,
    updated,
    computed
};

usesFileUploads();

state(['headerPhoto', 'listingPhoto', 'mediaUpload', 'attachmentUpload', 'attachmentTitle', 'listing', 'maxCategories', 'uploadMediaLimit', 'uploadAttachmentLimit', 'notes']);
state(['isEditingMedia' => false]);

form(ListingForm::class);

mount(function(){
    $this->form->setListing($this->listing);

    if(auth()->user()->role == 'admin'){
        $this->notes = $this->listing->notes;
    }

    $this->uploadMediaLimit = 15;
    $this->uploadAttachmentLimit = 3;
    $this->maxCategories = $this->listing->pro ? 3 : 1;
});

$save = function(){
    $this->form->save($this->listing);
    Flux::toast('Listing saved.');
};

$updateHeaderPhoto = function(){
    $this->form->headerPhoto = $this->headerPhoto->store('header-photos/' . $this->listing->public_id);
    $this->form->saveHeaderPhoto($this->listing);
    Flux::modals()->close();
    
};

$updateListingPhoto = function(){
    $this->form->listingPhoto = $this->listingPhoto->store('listing-photos/' . $this->listing->public_id);
    $this->form->saveListingPhoto($this->listing);
    Flux::modals()->close();
    
};

$uploadMedia = function(){
    $uploadedPath = $this->mediaUpload->store('listing-media/' . $this->listing->public_id );
    $this->form->media[] = $uploadedPath;

    $this->form->save($this->listing);

    Flux::toast('Media uploaded.');

    $this->mediaUpload = null;
};

$removeMedia = function($idx){
    $asset = $this->form->media[$idx];
    unset($this->form->media[$idx]);
    $this->form->media = array_values($this->form->media);
    $this->form->save($this->listing);

    Storage::delete($asset);

    Flux::toast('Media removed.');
};

$reorderMedia = function($items){

    $media = [];
    foreach($items as $item){
      $media[] = $item['value'];
    }

    $this->form->media = $media; 

    $this->form->save($this->listing);
};

$uploadAttachment = function(){
    $this->validate([
        'attachmentTitle' => 'required',
        'attachmentUpload' => 'required|file|max:10240',
    ]);

    $originalName = $this->attachmentUpload->getClientOriginalName();
    $extension = $this->attachmentUpload->getClientOriginalExtension();
    $nameWithoutExtension = pathinfo($originalName, PATHINFO_FILENAME);
    $newName = $nameWithoutExtension . '-' . generateNanoId() . '.' . $extension;
    
    $uploadedPath = $this->attachmentUpload->storeAs('listing-attachments/' . $this->listing->public_id, $newName);
    $this->form->attachments[] = [
      'path' => $uploadedPath,
      'title' => $this->attachmentTitle,
    ];

    $this->form->save($this->listing);

    Flux::toast('Attachment uploaded.');

    $this->attachmentUpload = null;
};

$removeAttachment = function($idx){
    $asset = $this->form->attachments[$idx];
    unset($this->form->attachments[$idx]);
    $this->form->attachments = array_values($this->form->attachments);
    $this->form->save($this->listing);

    Storage::delete($asset);

    Flux::toast('Attachment removed.');
};

$reorderAttachments = function($items){

    $oldAttachments = $this->form->attachments;
    $attachments = [];
    foreach($items as $item){
      $attachments[] = $oldAttachments[intval($item['value'])];
    }

    $this->form->attachments = $attachments; 

    $this->form->save($this->listing);
};

$togglePro = function(){
    
    $this->listing->pro = !$this->listing->pro;

    $this->listing->save();
    
};

$saveListingNotes = function(){
    $this->listing->notes = $this->notes;
    $this->listing->save();

    Flux::toast('Notes saved.');
}
?>

<div>
    <div class="relative">
        <div class="h-64 md:h-96 w-full bg-gray-200 relative ">
            <flux:modal.trigger name="edit-header-photo">
                @if($form->headerPhoto )
                    <img
                        src="{{ asset($form->headerPhoto) }}"
                        alt="Business cover"
                        class="w-full h-full object-cover"
                    />
                @else
                    <img
                        src="/no-pic.png"
                        alt="Business cover"
                        class="w-full h-full object-contain"
                    />

                @endif

                
            </flux:modal.trigger>
            <div class="container mx-auto px-4 mt-20">
                <div class="absolute -bottom-16 border-4 border-white rounded-xl overflow-hidden shadow-lg">
                    <flux:modal.trigger name="edit-listing-photo">
                        <img
                            src="{{ $form->listingPhoto ? asset($form->listingPhoto) : 'https://ui-avatars.com/api/?name=' . $form->title . '&color=FFFFFF&background=1B1C20' }}"
                            alt="Business logo"
                            class="w-32 h-32 object-cover"
                        />
                    </flux:modal.trigger>
                </div>
            </div>
        </div>
        
        <div class="container mx-auto px-4 mt-20">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <div x-data="{ edit: false }">
                        <h1 x-show="!edit" @dblclick="edit = true" class="text-3xl font-bold">{{ $form->title }}</h1>
                        <div x-show="edit" class="flex gap-4">
                            <flux:input wire:model.blur="form.title" type="text" class="text-3xl font-bold" value="Craft Coffee House" />
                            <flux:button variant="primary" @click="edit = false">Close</flux:button>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2 text-gray-600 mt-2">
                        
                      <flux:modal.trigger name="edit-categories">
                        <span class="text-sm">
                            @if($form->categories)
                                @foreach($form->categories as $category)
                                    <span class="bg-gray-200 px-2 py-1 rounded-full text-xs font-medium text-gray-600">{{ $category }}</span>
                                @endforeach
                            @else
                                <span class="bg-gray-200 px-2 py-1 rounded-full text-xs font-medium text-gray-600">Select categories</span>
                            @endif
                        </span>
                        </flux:modal.trigger>
                      <div class ="flex  items-center gap-2">
                      <flux:tooltip content="Avg Rating - {{ $listing->rating }} Stars">
                          <div class="flex items-center gap-1 bg-[#FBC02D] px-2 py-1 rounded-full text-sm font-medium text-black">
                              @if($listing->rating > 0)
                              @for($i = 0; $i < $listing->rating; $i++)
                                  <flux:icon.star variant="solid" class="text-black size-4" />
                              @endfor
                              @else
                                  <flux:icon.star class="text-black size-4" />
                              @endif
                          </div>
                      </flux:tooltip>
                      
                      {{ $listing->reviews()->count() ?: 'No' }} reviews
                    </div>
  
                        
                    </div>
                </div>
            
            </div>
        </div>
    </div>
    
    <!-- Business Details -->
    <div class="relative container mx-auto px-4 py-8 mb-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="md:col-span-2">
                <section class="mb-8">
                    <div x-data="{ editDesc: false }">
                        <h2 class="text-2xl font-semibold mb-4" @dblClick="editDesc = !editDesc">About</h2>
                        <div x-show="!editDesc" class="text-gray-600 leading-relaxed prose">{!! $form->description !!}</div>
                        <div x-show="editDesc" class="space-y-4">
                            <flux:editor wire:model.live="form.description" class="text-gray-600 leading-relaxed" />
                        </div>
                    </div>
                </section>
    
                @if($listing->pro)
                <section class="mb-8">
                    <h2 class="text-2xl font-semibold mb-4">Media</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4" wire:sortable="reorderMedia">
                        @foreach($form->media as $idx => $media)
                            <div wire:sortable.item="{{ $media }}" wire:key="{{ $media }}" class="aspect-square bg-black rounded-lg overflow-hidden hover-scale relative">
                                <div class="flex justify-between p-4">
                                    <flux:icon.bars-3 wire:sortable.handle class="text-white size-6" />
                                    <flux:icon.x-mark wire:click="removeMedia({{ $idx }})" class="text-white size-6 hover:text-red-500" />
                                </div>
                                @if(isImage(asset($media)))
                                    <img src="{{ asset( $media ) }}" alt="Gallery image 1" class="w-full h-full object-cover" />
                                  @else
                                    <video src="{{ asset( $media ) }}" class="w-full h-full object-contain" controls></video>
                                  @endif
                            </div>
                          @endforeach
                    </div>

                    @if(count($form->media) < $this->uploadMediaLimit)
                    <flux:card class="mt-4">
                        <div 
                            class="flex items-center gap-4"
                            x-data="{ uploading: false, progress: 0 }"
                            x-on:livewire-upload-start="uploading = true"
                            x-on:livewire-upload-finish="uploading = false"
                            x-on:livewire-upload-cancel="uploading = false"
                            x-on:livewire-upload-error="uploading = false"
                            x-on:livewire-upload-progress="progress = $event.detail.progress"
                        >
                            <flux:input type="file" wire:model="mediaUpload" accept="video/*,image/*"/>

                            <div x-show="uploading">
                                <progress max="100" x-bind:value="progress"></progress>
                            </div>

                            <flux:button wire:click="uploadMedia" variant="primary">Add Media</flux:button>
                        </div>
                    </flux:card>

                    @else
                    <flux:card class="mt-4">
                        You have hit your limit of media. Remove an attachment to add another.
                    </flux:card>

                    @endif
                </section>
                @endif
                
                @if($listing->pro)
                <section class="mb-8">
                    <h2 class="text-2xl font-semibold mb-4">Attachments</h2>
                    <div class="" wire:sortable="reorderAttachments">
                      @foreach($form->attachments as $idx => $attachment)
                        <flux:card wire:sortable.item="{{ $idx }}" class="mb-4">
                          <div class="flex justify-between p-4">
                            <div class="flex items-center gap-4">
                            <flux:icon.bars-3 wire:sortable.handle class=" size-6" />
                            <div>{{ $attachment['title'] }}</div>
                            </div>
                            <flux:icon.x-mark wire:click="removeAttachment({{ $idx }})" class=" size-6 hover:text-red-500" />
                        </div>
                        </flux:card>
                      @endforeach
                    </div>

                    
                    @if(count($form->attachments) < $this->uploadAttachmentLimit)

                    <flux:card class="mt-4">
                        <flux:input label="Title" description="Let users know what this file is." wire:model="attachmentTitle" />
                        <div class="flex items-center gap-4 mt-4">
                            <flux:input type="file" wire:model="attachmentUpload" />
                            <flux:button wire:click="uploadAttachment" variant="primary">Add Attachment</flux:button>
                        </div>
                    </flux:card>

                    @else
                    <flux:card class="mt-4">
                        You have hit your limit of attachments. Remove an attachment to add another.
                    </flux:card>

                    @endif
                </section>
                @endif
            </div>
    
            <div>
                <flux:card>
                    <h3 class="text-xl font-semibold mb-4">Business Info</h3>
                    <div class="space-y-4">
                        @if($listing->pro)
                            <div class="flex items-start gap-3">
                                <flux:icon.globe-alt />
                                <div x-data="{ editWebsite: false }">
                                    <h4 @dblClick="editWebsite = !editWebsite" class="font-medium">Website</h4>
                                    <p x-show="!editWebsite" class="text-gray-600">{{ $form->website ?? '-' }}</p>
                                    <flux:input x-show="editWebsite" wire:model.blur="form.website" type="text" class="text-3xl font-bold" />
                                </div>
                            </div>
                        @endif
            
                        <div class="flex items-start gap-3">
                            <flux:icon.map-pin />
                            <div x-data="{ editLocation: false }">
                                <h4 @dblClick="editLocation = !editLocation" class="font-medium">Location</h4>
                                <p x-show="!editLocation" class="text-gray-600">{{ $form->address }}</p>
                                <div x-show="editLocation">
                                    <gmpx-api-loader key="{{ config('services.google.maps.key')}}" solution-channel="GMP_GE_placepicker_v2"></gmpx-api-loader>
                                    <div id="place-picker-box" class="mb-4">
                                        <div id="place-picker-container">
                                            <gmpx-place-picker placeholder="Enter an address" id="place-picker" style="width:100%"></gmpx-place-picker>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
            
                        <div class="flex items-start gap-3">
                            <flux:icon.globe-americas />
                            <div x-data="{ edit: false }">
                                <h4 @dblclick="edit = !edit" class="font-medium">Areas Served</h4>
                                <p x-show="!edit" class="text-gray-600">{{ $form->areasServed ?? '-' }}</p>
                                <flux:input x-show="edit" wire:model.blur="form.areasServed" type="text" class="text-3xl font-bold" value="Craft Coffee House" />
                            </div>
                        </div>
        
                        <div class="flex items-start gap-3">
                            <flux:icon.clock />
                            <div>
                                <h4 class="font-medium">Hours</h4>
                                <div class="text-sm text-gray-600">
                                    <div class="flex justify-between py-1" x-data="{ edit: false }">
                                        <span @dblClick="edit = !edit" class="w-24">Monday</span>
                                        <span x-show="!edit">{{ $form->hours['monday'] ?? '' }}</span>
                                        <div x-show="edit" x-cloak>
                                            <flux:input wire:model.live="form.hours.monday" type="text" class="text-3xl font-bold" />
                                        </div>
                                    </div>
                                    <div class="flex justify-between py-1" x-data="{ edit: false }">
                                        <span @dblClick="edit = !edit" class="w-24">Tuesday</span>
                                        <span x-show="!edit">{{ $form->hours['tuesday'] ?? '' }}</span>
                                        <div x-show="edit" x-cloak>
                                            <flux:input wire:model.live="form.hours.tuesday" type="text" class="text-3xl font-bold" />
                                        </div>
                                    </div>
                                    <div class="flex justify-between py-1" x-data="{ edit: false }">
                                        <span @dblClick="edit = !edit" class="w-24">Wednesday</span>
                                        <span x-show="!edit">{{ $form->hours['wednesday'] ?? '' }}</span>
                                        <div x-show="edit" x-cloak>
                                            <flux:input wire:model.live="form.hours.wednesday" type="text" class="text-3xl font-bold" />
                                        </div>
                                    </div>
                                    <div class="flex justify-between py-1" x-data="{ edit: false }">
                                        <span @dblClick="edit = !edit" class="w-24">Thursday</span>
                                        <span x-show="!edit">{{ $form->hours['thursday'] ?? '' }}</span>
                                        <div x-show="edit" x-cloak>
                                            <flux:input wire:model.live="form.hours.thursday" type="text" class="text-3xl font-bold" />
                                        </div>
                                    </div>
                                    <div class="flex justify-between py-1" x-data="{ edit: false }">
                                        <span @dblClick="edit = !edit" class="w-24">Friday</span>
                                        <span x-show="!edit">{{ $form->hours['friday'] ?? '' }}</span>
                                        <div x-show="edit" x-cloak>
                                            <flux:input wire:model.live="form.hours.friday" type="text" class="text-3xl font-bold" />
                                        </div>
                                    </div>
                                    <div class="flex justify-between py-1" x-data="{ edit: false }">
                                        <span @dblClick="edit = !edit" class="w-24">Saturday</span>
                                        <span x-show="!edit">{{ $form->hours['saturday'] ?? '' }}</span>
                                        <div x-show="edit" x-cloak>
                                            <flux:input wire:model.live="form.hours.saturday" type="text" class="text-3xl font-bold" />
                                        </div>
                                    </div>
                                    <div class="flex justify-between py-1" x-data="{ edit: false }">
                                        <span @dblClick="edit = !edit" class="w-24">Sunday</span>
                                        <span x-show="!edit">{{ $form->hours['sunday'] ?? '' }}</span>
                                        <div x-show="edit" x-cloak>
                                            <flux:input wire:model.live="form.hours.sunday" type="text" class="text-3xl font-bold" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
            
                        @if($listing->pro)
                            <div class="flex items-start gap-3">
                                <flux:icon.phone />
                                <div x-data="{ editPhone: false }">
                                    <h4 @dblClick="editPhone = !editPhone" class="font-medium">Phone</h4>
                                    <p x-show="!editPhone" class="text-gray-600">{{ $form->phone ?? '-' }}</p>
                                    <flux:input x-show="editPhone" wire:model.blur="form.phone" type="text" class="text-3xl font-bold" />
                                </div>
                            </div>
                        @endif
            
                        @if($listing->pro)
                            <div class="flex items-start gap-3">
                                <flux:icon.envelope />
                                <div x-data="{ editEmail: false }">
                                    <h4 @dblClick="editEmail = !editEmail" class="font-medium">Email</h4>
                                    <p x-show="!editEmail" class="text-gray-600">{{ $form->email ?? '-' }}</p>
                                    <flux:input x-show="editEmail" wire:model.blur="form.email" type="text" class="text-3xl font-bold" />
                                </div>
                            </div>
                        @endif
                    </div>
                </flux:card>
            </div>

            
            
        </div>

        
        
        @if($listing->pro)
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" />
            <script src="https://cdn.jsdelivr.net/gh/mcstudios/glightbox/dist/js/glightbox.min.js"></script>
    
            <script type="text/javascript">
                const lightbox = GLightbox();
            </script>
        @endif

        
            @script
            <script>
                const picker = document.getElementById('place-picker');

                picker.addEventListener('gmpx-placechange', () => {
                    $wire.$set('form.address', picker.value.formattedAddress);
                });
            </script>
            @endscript
        
    </div>
    <div class="hidden lg:block absolute ml-0 top-0 right-0 w-full p-4 z-100 bg-gradient-to-b from-white to-black/0">
        <div class="flex justify-end space-x-4 ">
          @if(auth()->user()->role == 'admin')
              <flux:modal.trigger name="listing-notes">
                  <flux:button icon="pencil">Edit Notes</flux:button>
          </flux:modal.trigger>
              <flux:button wire:click="togglePro" icon="">{{ $listing->pro ? 'Remove Pro' : 'Give Pro' }}</flux:button>
          @endif
          <flux:button wire:click="save" variant="primary" icon="">Save Listing</flux:button>
        </div>
      </div>
      <div class="block lg:hidden fixed ml-0 bottom-0 right-0 w-full p-4 z-100 bg-white border-t">
        <div class="flex justify-end space-x-4 ">
          @if(auth()->user()->role == 'admin')
              <flux:modal.trigger name="listing-notes">
                  <flux:button icon="pencil">Edit Notes</flux:button>
          </flux:modal.trigger>
              <flux:button wire:click="togglePro" icon="">{{ $listing->pro ? 'Remove Pro' : 'Give Pro' }}</flux:button>
          @endif
          <flux:button wire:click="save" variant="primary" icon="">Save Listing</flux:button>
        </div>
      </div>


    
      
    <flux:modal name="edit-header-photo" variant="flyout">
        <div>
            <flux:heading size="lg">Update Header Photo</flux:heading>
            <flux:subheading>This is where you should display your best work.</flux:subheading>
            <div 
                class="mt-4"
                x-data="{ uploading: false, progress: 0 }"
                x-on:livewire-upload-start="uploading = true"
                x-on:livewire-upload-finish="uploading = false"
                x-on:livewire-upload-cancel="uploading = false"
                x-on:livewire-upload-error="uploading = false"
                x-on:livewire-upload-progress="progress = $event.detail.progress"
            >
                <flux:input wire:model="headerPhoto" type="file" />
                <div x-show="uploading">
                    <progress max="100" x-bind:value="progress"></progress>
                </div>
            
                <div class="mt-4 flex justify-end" x-show="!uploading">
                    <flux:button wire:click="updateHeaderPhoto" variant="primary">Save</flux:button>
                </div>
            </div>
        </div>
    </flux:modal>

    <flux:modal name="edit-listing-photo" variant="flyout">
        <div>
            <flux:heading size="lg">Update Listing Photo</flux:heading>
            <flux:subheading>This is where you should put your logo or something identifying.</flux:subheading>
            <div 
                class="mt-4"
                x-data="{ uploading: false, progress: 0 }"
                x-on:livewire-upload-start="uploading = true"
                x-on:livewire-upload-finish="uploading = false"
                x-on:livewire-upload-cancel="uploading = false"
                x-on:livewire-upload-error="uploading = false"
                x-on:livewire-upload-progress="progress = $event.detail.progress"
            >
                <flux:input wire:model="listingPhoto" type="file" />
                <div x-show="uploading">
                    <progress max="100" x-bind:value="progress"></progress>
                </div>
            </div>

            <div class="mt-4 flex justify-end">
                <flux:button wire:click="updateListingPhoto" variant="primary">Save</flux:button>
            </div>
        </div>
    </flux:modal>

        <flux:modal name="edit-categories" variant="flyout">
            <div>
                <flux:heading size="lg">Update Categories</flux:heading>
                <flux:subheading>Choose the categories that best describe your business.</flux:subheading>
            </div>
            <div>
                <flux:checkbox.group wire:model.live="form.categories">
                    @foreach($form->getPossibleCategories() as $category)
                        <flux:checkbox :disabled="!in_array($category, $form->categories ?? []) && count($form->categories ?? []) == $maxCategories" value="{{ $category }}" label="{{ $category }}" />
                    @endforeach
                </flux:checkbox.group>
            </div>
        </flux:modal>

    @if(auth()->user()->role == 'admin')
    <flux:modal name="listing-notes" variant="flyout">
        <div>
            <flux:heading size="lg">Listing Notes</flux:heading>
            <flux:subheading></flux:subheading>
            <div class="mt-4">
                <flux:editor wire:model="notes" label="Notes" />
            </div>
            <div class="mt-4 flex justify-end">
                <flux:button wire:click="saveListingNotes" variant="primary">Save</flux:button>
            </div>
        </div>
    </flux:modal>

    @endif
</div>
