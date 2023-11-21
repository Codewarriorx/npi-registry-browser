<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Reactive;
use Livewire\Attributes\On;

new class extends Component {
    #[Reactive]
    public array $results;
    public int $paging = 50;
    public array $activeRecord;

    public function changePaging()
    {
        $this->dispatch('paging-change', $this->paging);
    }

    public function viewRecord($resultIdx)
    {
        $this->activeRecord = $this->results[$resultIdx];
        $this->dispatch('open-modal', name: 'record-modal');
    }

    #[On('reset-state')]
    public function resetState()
    {
        $this->paging = 50;
    }
}; ?>


<div class="mt-2">
    <h2 id="results">
        <span class="icon">
            <iconify-icon icon="mdi:list-box-outline"></iconify-icon>
        </span>
        Results
        <small class="text-body-secondary">
            {{ count($results) }} Results
        </small>

        <div class="float-end">
            <select id="paging"
                name="paging"
                class="form-select @error ('paging') is-invalid @enderror"
                wire:model.live="paging"
                wire:change="changePaging"
                x-on:change="isLoading = true">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
                <option value="200">200</option>
            </select>
        </div>
    </h2>
    <table class="table table-striped table-hover caption-top align-middle table-responsive">
        <caption>
            List of NPI(s) that match your search. View more information by selecting a row.
        </caption>

        <thead>
            <tr>
                <td class="text-center d-none d-md-table-cell">NPI</td>
                <td>Name</td>
                <td class="text-center">Type</td>
                <td title="Primary Practice Address">Address</td>
                <td class="text-center d-none d-md-table-cell">Phone</td>
                <td class="text-center d-none d-md-table-cell" title="Primary Taxonomy">Taxonomy</td>
            </tr>
        </thead>
        <tbody class="table-group-divider">
            @forelse ($results as $result)
            <tr class="cursor-pointer placeholder-glow"
                wire:click="viewRecord({{ $loop->index }})">
                <td class="text-center d-none d-md-table-cell">
                    <span x-bind:class="{ 'placeholder': isLoading }">
                        {{ $result['number'] }}
                    </span>
                </td>
                <td>
                    <span x-bind:class="{ 'placeholder': isLoading }">
                        @if ($result['enumeration_type'] == 'NPI-1')
                        {{ $result['basic']['first_name'] }}
                        {{ $result['basic']['last_name'] }}
                        @else
                        {{ $result['basic']['organization_name'] }}
                        @endif
                    </span>
                </td>
                <td class="text-center">
                    <span x-bind:class="{ 'placeholder': isLoading }">
                        @if ($result['enumeration_type'] == 'NPI-1')
                        <span class="icon">
                            <iconify-icon icon="mdi:account"></iconify-icon>
                        </span>
                        @else
                        <span class="icon">
                            <iconify-icon icon="mdi:hospital-building"></iconify-icon>
                        </span>
                        @endif
                    </span>
                </td>
                <td>
                    <span x-bind:class="{ 'placeholder': isLoading }">
                        {{ $result['addresses']['location']['address_1'] }}
                        {{ $result['addresses']['location']['address_2'] ?? '' }}
                        {{ $result['addresses']['location']['city'] }},
                        {{ $result['addresses']['location']['state'] }}
                        {{ substr($result['addresses']['location']['postal_code'], 0, 5) . '-' . substr($result['addresses']['location']['postal_code'], 5) }}
                    </span>
                </td>
                <td class="text-center text-nowrap d-none d-md-table-cell">
                    <span x-bind:class="{ 'placeholder': isLoading }">
                        {{ $result['addresses']['location']['telephone_number'] }}
                    </span>
                </td>
                <td class="text-center d-none d-md-table-cell">
                    <span x-bind:class="{ 'placeholder': isLoading }">
                        {{ $result['taxonomies'][0]['desc'] }}
                    </span>
                </td>
            </tr>

            @empty

            <tr class="placeholder-glow">
                <td class="text-center" colspan="6">
                    <span x-bind:class="{ 'placeholder': isLoading }">
                        No Results Found
                    </span>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <x-modal name="record-modal">
        <x-slot name="title">
            @if ($activeRecord)
            Provider Information for {{ $activeRecord['number'] }}
            @endif
        </x-slot>
        <x-slot name="body">
            @if ($activeRecord)
            <h4>
                @if ($activeRecord['enumeration_type'] == 'NPI-1')
                <span class="icon">
                    <iconify-icon icon="mdi:account"></iconify-icon>
                </span>
                @else
                <span class="icon">
                    <iconify-icon icon="mdi:hospital-building"></iconify-icon>
                </span>
                @endif

                @if ($activeRecord['enumeration_type'] == 'NPI-1')
                {{ $activeRecord['basic']['first_name'] }}
                {{ $activeRecord['basic']['last_name'] }}

                {{ $activeRecord['basic']['credential'] ?? '' }}
                @else
                {{ $activeRecord['basic']['organization_name'] }}
                @endif

                <small class="text-body-secondary d-block">
                    Last Updated: {{ $activeRecord['basic']['last_updated'] }}
                </small>
                <small class="text-body-secondary d-block">
                    Certification Date: {{ $activeRecord['basic']['enumeration_date'] }}
                </small>
            </h4>

            <dl class="row">
                <dt class="col-sm-4 text-end text-start-sm">NPI</dt>
                <dd class="col-sm-8">{{ $activeRecord['number'] }}</dd>

                <dt class="col-sm-4 text-end text-start-sm">Enumeration Date</dt>
                <dd class="col-sm-8">{{ $activeRecord['basic']['enumeration_date'] }}</dd>

                <dt class="col-sm-4 text-end text-start-sm">NPI Type</dt>
                <dd class="col-sm-8">{{ $activeRecord['enumeration_type'] }}</dd>

                @if (isset($activeRecord['basic']['sole_proprietor']))
                <dt class="col-sm-4 text-end text-start-sm">Sole Proprietor</dt>
                <dd class="col-sm-8">{{ $activeRecord['basic']['sole_proprietor'] }}</dd>
                @endif

                <dt class="col-sm-4 text-end text-start-sm">Status</dt>
                <dd class="col-sm-8">
                    @if ($activeRecord['basic']['status'] == 'A')
                    Active
                    @else
                    Inactive
                    @endif
                </dd>

                @if ($activeRecord['enumeration_type'] == 'NPI-2')
                <dt class="col-sm-4 text-end text-start-sm">Authorized Official Information</dt>
                <dd class="col-sm-8">
                    <span class="d-block">
                        <strong>Name: </strong>
                        {{ $activeRecord['basic']['authorized_official_first_name'] }}
                        {{ $activeRecord['basic']['authorized_official_middle_name'] ?? '' }}
                        {{ $activeRecord['basic']['authorized_official_last_name'] }}
                        {{ $activeRecord['basic']['authorized_official_credential'] ?? '' }}
                    </span>
                    <span class="d-block">
                        <strong>Title: </strong>
                        {{ $activeRecord['basic']['authorized_official_title_or_position'] }}
                    </span>
                    <span class="d-block">
                        <strong>Phone: </strong>
                        {{ $activeRecord['basic']['authorized_official_telephone_number'] }}
                    </span>
                </dd>
                @endif

                <dt class="col-sm-4 text-end text-start-sm">Mailing Address</dt>
                <dd class="col-sm-8">
                    <x-address :address="$activeRecord['addresses']['mailing']" />
                </dd>

                <dt class="col-sm-4 text-end text-start-sm">Primary Practice Address</dt>
                <dd class="col-sm-8">
                    <x-address :address="$activeRecord['addresses']['location']" />
                </dd>

                @if ($activeRecord['practiceLocations'])
                <dt class="col-sm-4 text-end text-start-sm">Secondary Practice Addresses</dt>
                <dd class="col-sm-8">
                    @foreach ($activeRecord['practiceLocations'] as $practiceLocation)
                    <x-address :address="$practiceLocation" />

                    @if (!$loop->last)
                    <hr>
                    @endif
                    @endforeach
                </dd>
                @endif

                @if ($activeRecord['identifiers'])
                <dt class="col-sm-4 text-end text-start-sm">Other Identifiers</dt>
                <dd class="col-sm-8">
                    <table class="table table-striped table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Issuer</th>
                                <th>State</th>
                                <th>Number</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($activeRecord['identifiers'] as $identifier)
                            <tr>
                                <td>
                                    {{ $identifier['desc'] }}
                                </td>
                                <td>
                                    {{ $identifier['state'] }}
                                </td>
                                <td>
                                    {{ $identifier['identifier'] }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </dd>
                @endif

                <dt class="col-sm-4 text-end text-start-sm">Taxonomy</dt>
                <dd class="col-sm-8">
                    <table class="table table-striped table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Primary Taxonomy</th>
                                <th>Selected Taxonomy</th>
                                <th>State</th>
                                <th>License Number</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($activeRecord['taxonomies'] as $taxonomy)
                            <tr>
                                <td>
                                    @if ($taxonomy['primary'])
                                    Yes
                                    @else
                                    No
                                    @endif
                                </td>
                                <td>
                                    {{ $taxonomy['code'] . ' - ' . $taxonomy['desc'] }}
                                </td>
                                <td>
                                    {{ $taxonomy['state'] }}
                                </td>
                                <td>
                                    {{ $taxonomy['license'] }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </dd>

                @if ($activeRecord['endpoints'])
                <dt class="col-sm-12">Health Information Exchange</dt>
                <dd class="col-sm-12">
                    <table class="table table-striped table-hover align-middle table-responsive">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Endpoint</th>
                                <th>Description</th>
                                <th>Use</th>
                                <th>Content Type</th>
                                <th>Affiliation</th>
                                <th>Location</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($activeRecord['endpoints'] as $endpoint)
                            <tr>
                                <td>
                                    {{ $endpoint['endpointTypeDescription'] }}
                                </td>
                                <td>
                                    <a href="{{ $endpoint['endpoint'] }}" target="_blank">
                                        Endpoint
                                    </a>
                                </td>
                                <td>
                                    {{ $endpoint['endpointDescription'] }}
                                </td>
                                <td>
                                    {{ $endpoint['useOtherDescription'] }}
                                </td>
                                <td>
                                    {{ $endpoint['contentTypeDescription'] }}
                                </td>
                                <td>
                                    {{ $endpoint['affiliationName'] }}
                                </td>
                                <td>
                                    <x-address :address="$endpoint" />
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </dd>
                @endif
            </dl>
            @endif
        </x-slot>
    </x-modal>
</div>
