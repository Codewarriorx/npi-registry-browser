@props(['title', 'name'])

<div x-data="{ isOpen: false }"
    x-on:open-modal.window="isOpen = ($event.detail.name === '{{ $name }}')"
    x-on:close-modal.window="isOpen = false"
    x-on:keydown.escape.window="isOpen = false"
    x-show="isOpen"
    x-transition>

    <!-- Modal -->
    <div class="modal fade d-block modal-lg"
        x-bind:class="{ 'show': isOpen }"
        tabindex="-1">
        <div class="modal-dialog"
            x-on:click.outside="isOpen = false">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">
                        {{ $title ?? 'NPI Record'}}
                    </h1>
                    <button type="button"
                        class="btn-close"
                        aria-label="Close"
                        x-on:click="isOpen = false"></button>
                </div>
                <div class="modal-body">
                    {{ $body }}
                </div>
            </div>
        </div>
    </div>
</div>
