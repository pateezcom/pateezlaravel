<div class="dropdown">
    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="languageDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        @foreach($languages as $language)
            @if($language->code == $currentLocale)
                <i class="{{ $language->icon }}"></i> {{ $language->name }}
            @endif
        @endforeach
    </button>
    <ul class="dropdown-menu" aria-labelledby="languageDropdown">
        @foreach($languages as $language)
            <li>
                <a class="dropdown-item @if($language->code == $currentLocale) active @endif" href="{{ route('locale.set', $language->code) }}">
                    <i class="{{ $language->icon }}"></i> {{ $language->name }}
                </a>
            </li>
        @endforeach
    </ul>
</div>
