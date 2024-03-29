<div>
  <x-checkbox id="checkbox-{{ $entity->id }}" wire:model="entity_status"></x-checkbox>
  @switch($entity->status)
    @case(1)
      <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
        PUBLICADO
      </span>
    @break

    @case(0)
      <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 ">
        NO PUBLICADO
      </span>
    @break

    @default
  @endswitch
</div>
