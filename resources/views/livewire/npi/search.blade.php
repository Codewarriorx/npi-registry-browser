<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule as RuleValidator;

new class extends Component
{
    #[Rule('nullable|numeric|digits_between:2,10')]
    public int $number;

    #[Rule('nullable|string|min:2')]
    public string $firstName;

    #[Rule('nullable|string|min:2')]
    public string $lastName;

    #[Rule('nullable|string|min:2')]
    public string $taxonomyDescription;

    #[Rule('nullable|string|min:2')]
    public string $city;

    #[Rule('nullable|string|size:2')]
    public string $state;

    #[Rule('nullable|numeric|digits_between:2,5')]
    public int $postalCode;

    public function search()
    {
        $validatedData = $this->validate();
        $validatedData = Validator::make($validatedData, [
            'number' => ['nullable'], // npi number
            'firstName' => ['nullable'], // first name
            'lastName' => ['nullable'], // last name
            'taxonomyDescription' => ['nullable'], // taxonomy description
            'city' => ['nullable'], // city
            'postalCode' => ['nullable'], // zip code
            'state' => [RuleValidator::prohibitedIf(
                function () use ($validatedData) {
                    return count($validatedData) == 1;
                }
            )]
        ])->validate();

        $apiData = [];
        foreach ($validatedData as $key => $value) {
            $apiData[Str::snake($key)] = $value;
        }

        $this->dispatch('new-search', http_build_query($apiData));
    }

    public function resetState()
    {
        $this->number = '';
        $this->firstName = '';
        $this->lastName = '';
        $this->taxonomyDescription = '';
        $this->city = '';
        $this->state = '';
        $this->postalCode = '';

        $this->dispatch('reset-state');
    }
}; ?>

<div>
    <h2>
        <span class="icon">
            <iconify-icon icon="mdi:magnify"></iconify-icon>
        </span>
        NPI Search
    </h2>
    <form class="mb-4" wire:submit="search">
        <div class="row">
            <div class="col-sm-12 col-md-3">
                <div class="mb-2">
                    <label for="number" class="form-label">NPI Number</label>
                    <input type="number"
                        name="number"
                        class="form-control @error ('number') is-invalid @enderror"
                        id="number"
                        maxlength="10"
                        wire:model.blur="number">
                    @error ('number')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col">
                <div class="mb-2">
                    <label for="taxonomy_description" class="form-label">Taxonomy Description</label>
                    <input type="text"
                        name="taxonomy_description"
                        class="form-control @error ('taxonomyDescription') is-invalid @enderror"
                        id="taxonomy_description"
                        wire:model.blur="taxonomyDescription">
                    @error ('taxonomyDescription')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <div class="mb-2">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text"
                        name="first_name"
                        class="form-control @error ('firstName') is-invalid @enderror"
                        id="first_name"
                        wire:model.blur="firstName">
                    @error ('firstName')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col">
                <div class="mb-2">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text"
                        name="last_name"
                        class="form-control @error ('lastName') is-invalid @enderror"
                        id="last_name"
                        wire:model.blur="lastName">
                    @error ('lastName')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <div class="mb-2">
                    <label for="city" class="form-label">City</label>
                    <input type="text"
                        name="city"
                        class="form-control @error ('city') is-invalid @enderror"
                        id="city"
                        wire:model.blur="city">
                    @error ('city')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-2">
                    <label for="state" class="form-label">State</label>
                    <select name="state"
                        class="form-select capitalize @error ('state') is-invalid @enderror"
                        wire:model.live="state">
                        <option value="">any</option>
                        <option value="AL">alabama</option>
                        <option value="AK">alaska</option>
                        <option value="AS">american samoa</option>
                        <option value="AZ">arizona</option>
                        <option value="AR">arkansas</option>
                        <option value="AA">armed forces america</option>
                        <option value="AE">armed forces europe/canada/middle east/africa</option>
                        <option value="AP">armed forces pacific</option>
                        <option value="CA">california</option>
                        <option value="CO">colorado</option>
                        <option value="CT">connecticut</option>
                        <option value="DE">delaware</option>
                        <option value="DC">district of columbia</option>
                        <option value="FL">florida</option>
                        <option value="GA">georgia</option>
                        <option value="GU">guam</option>
                        <option value="HI">hawaii</option>
                        <option value="ID">idaho</option>
                        <option value="IL">illinois</option>
                        <option value="IN">indiana</option>
                        <option value="IA">iowa</option>
                        <option value="KS">kansas</option>
                        <option value="KY">kentucky</option>
                        <option value="LA">louisiana</option>
                        <option value="ME">maine</option>
                        <option value="MP">mariana islands, northern</option>
                        <option value="MH">marshall islands</option>
                        <option value="MD">maryland</option>
                        <option value="MA">massachusetts</option>
                        <option value="MI">michigan</option>
                        <option value="FM">micronesia, federated states of</option>
                        <option value="MN">minnesota</option>
                        <option value="MS">mississippi</option>
                        <option value="MO">missouri</option>
                        <option value="MT">montana</option>
                        <option value="NE">nebraska</option>
                        <option value="NV">nevada</option>
                        <option value="NH">new hampshire</option>
                        <option value="NJ">new jersey</option>
                        <option value="NM">new mexico</option>
                        <option value="NY">new york</option>
                        <option value="NC">north carolina</option>
                        <option value="ND">north dakota</option>
                        <option value="OH">ohio</option>
                        <option value="OK">oklahoma</option>
                        <option value="OR">oregon</option>
                        <option value="PA">pennsylvania</option>
                        <option value="PR">puerto rico</option>
                        <option value="RI">rhode island</option>
                        <option value="SC">south carolina</option>
                        <option value="SD">south dakota</option>
                        <option value="TN">tennessee</option>
                        <option value="TX">texas</option>
                        <option value="UT">utah</option>
                        <option value="VT">vermont</option>
                        <option value="VI">virgin islands</option>
                        <option value="VA">virginia</option>
                        <option value="WA">washington</option>
                        <option value="WV">west virginia</option>
                        <option value="WI">wisconsin</option>
                        <option value="WY">wyoming</option>
                    </select>
                    @error ('state')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-2">
                <div class="mb-2">
                    <label for="postal_code" class="form-label">Postal Code</label>
                    <input type="number"
                        name="postal_code"
                        class="form-control @error ('postalCode') is-invalid @enderror"
                        id="postal_code"
                        maxlength="5"
                        wire:model.blur="postalCode">
                    @error ('postalCode')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="mt-2">
            <button type="button"
                class="btn btn-outline-secondary"
                wire:click="resetState">Reset</button>

            <button type="submit"
                class="btn btn-primary"
                x-on:click.debounce="isLoading = true"
                x-bind:disabled="isLoading">
                <span x-show="isLoading">
                    <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                    <span role="status">Loading...</span>
                </span>
                <span x-show="!isLoading">
                    <span class="icon me-1">
                        <iconify-icon icon="mdi:account-search-outline"></iconify-icon>
                    </span>
                    Search
                </span>
            </button>
        </div>
    </form>
</div>
