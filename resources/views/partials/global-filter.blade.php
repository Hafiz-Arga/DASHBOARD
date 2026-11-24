<form action="{{ url()->current() }}" method="GET">

    <div class="filter-container">

        <div class="filter-group">
            <label for="year">Tahun (Year)</label>

            <select id="year" name="year">
                <option value="">All Years</option>

                @foreach ($years as $year)
                    <option value="{{ $year }}"
                        {{ (string) $selectedYear === (string) $year ? 'selected' : '' }}>
                        {{ $year }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="filter-group">
            <label for="region">Wilayah (Region)</label>

            <select id="region" name="region">
                <option value="">All Regions</option>

                @foreach ($regions as $region)
                    <option value="{{ $region }}" {{ $selectedRegion == $region ? 'selected' : '' }}>
                        {{ $region }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="filter-group">
            <label for="category">Kategori (Category)</label>

            <select id="category" name="category">
                <option value="">All Categories</option>

                @foreach ($categories as $category)
                    <option value="{{ $category }}" {{ $selectedCategory == $category ? 'selected' : '' }}>
                        {{ $category }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="filter-actions">
            <button type="submit" class="filter-button">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
                Terapkan Filter
            </button>
            @if (!empty($selectedRegion) || !empty($selectedCategory))
                <a href="{{ url()->current() }}" class="filter-reset-link">Reset</a>
            @endif
        </div>

    </div>

</form>
