<?php

use Livewire\Volt\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;

new class extends Component {
    public string $query = '';
    public ?array $results = null;
    public int $page = 1;
    public array $activeRecord;

    #[Rule('present|numeric|min:1|max:200')]
    public int $paging = 50;

    #[On('paging-change')]
    public function pagingChange($paging)
    {
        // calculate where we are so that the page is correct
        if ($this->page > 1) {
            $resultsCount = ($this->page - 1) * $this->paging;
            $this->page = ($resultsCount / $paging) + 1;
        }

        $this->paging = $paging;
        $this->runQuery();
    }

    #[On('new-search')]
    public function newSearch($query)
    {
        $this->resetState();

        $this->query = $query;

        $this->runQuery();
    }

    public function runQuery()
    {
        $query = $this->query;

        $query .= '&limit=' . $this->paging;

        if ($this->page > 1) {
            $query .= '&skip=' . ($this->page - 1) * $this->paging;
        }

        try {
            $response = Http::get('https://npiregistry.cms.hhs.gov/api/?version=2.1&' . $query);

            if ($response->successful() && isset($response->json()['results'])) {
                $results = $response->json()['results'];

                // reformat how addresses are stored
                foreach ($results as $resultKey => $result) {
                    $addresses = [];

                    foreach ($result['addresses'] as $address) {
                        $addresses[strtolower($address['address_purpose'])] = $address;
                    }

                    $results[$resultKey]['addresses'] = $addresses;
                }

                $this->results = $results;
            } elseif (isset($response->json()['Errors'])) {
                $errors = $response->json()['Errors'];
                $errorMsgs = [];

                // go through all of the errors and add them to the error message array
                foreach ($errors as $error) {
                    array_push($errorMsgs, $error['description']);
                }

                $this->addError('api', implode(', ', $errorMsgs));
            } else {
                $this->addError('api', 'An error occurred while searching the NPI Registry.');
            }
        } catch (\Throwable $th) {
            $this->addError('api', 'An error occurred while connecting to the NPI Registry.');
        }

        $this->dispatch('query-done');
    }

    public function next()
    {
        $this->page++;
        $this->runQuery();
    }

    public function prev()
    {
        $this->page--;
        $this->runQuery();
    }

    #[On('reset-state')]
    public function resetState()
    {
        $this->query = '';
        $this->results = [];
        $this->paging = 50;
        $this->page = 1;
    }
}; ?>

<div x-data="{ isLoading: false }" x-on:query-done="isLoading = false">
    @error('api')
        <div class="alert alert-danger" role="alert">
            <span class="icon fs-4 me-2">
                <iconify-icon icon="mdi:alert-circle-outline"></iconify-icon>
            </span>
            {{ $message }}
        </div>
    @enderror

    <div>
        <livewire:npi.search />

        @if (is_array($results))
            <livewire:npi.list :results="$results" />

            <div class="row mt-2">
                <div class="col">
                    @if ($page > 1)
                        <a href="#results" class="btn btn-outline-secondary float-start" wire:click="prev"
                            x-on:click="isLoading = true" x-bind:disabled="isLoading">
                            <span class="icon">
                                <iconify-icon icon="mdi:chevron-left"></iconify-icon>
                            </span>
                            Previous
                        </a>
                    @endif
                </div>
                <div class="col">
                    @if (count($results) == $paging && $paging * $page < 1200)
                        <a href="#results" class="btn btn-outline-secondary float-end" wire:click="next"
                            x-on:click="isLoading = true" x-bind:disabled="isLoading">
                            Next
                            <span class="icon">
                                <iconify-icon icon="mdi:chevron-right"></iconify-icon>
                            </span>
                        </a>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
