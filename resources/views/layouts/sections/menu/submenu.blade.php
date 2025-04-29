@php
use Illuminate\Support\Facades\Route;
@endphp

<ul class="menu-sub">
  @if (isset($menu))
    @foreach ($menu as $submenu)

    {{-- active menu method --}}
    @php
      $activeClass = null;
      $active = $configData["layout"] === 'vertical' ? 'active open':'active';
      $currentRouteName =  Route::currentRouteName();

      // Check if activeMenu variable is set
      if (isset($activeMenu)) {
        if ($activeMenu === $submenu->slug) {
          $activeClass = 'active';
        }
        elseif (isset($submenu->submenu)) {
          if (gettype($submenu->slug) === 'array') {
            foreach($submenu->slug as $slug){
              if ($activeMenu === $slug or (str_contains($currentRouteName,$slug) and strpos($currentRouteName,$slug) === 0)) {
                $activeClass = $active;
              }
            }
          }
          else{
            if ($activeMenu === $submenu->slug or (str_contains($currentRouteName,$submenu->slug) and strpos($currentRouteName,$submenu->slug) === 0)) {
              $activeClass = $active;
            }
          }
        }
      } else {
        // Default behavior
        if ($currentRouteName === $submenu->slug) {
            $activeClass = 'active';
        }
        elseif (isset($submenu->submenu)) {
          if (gettype($submenu->slug) === 'array') {
            foreach($submenu->slug as $slug){
              if (str_contains($currentRouteName,$slug) and strpos($currentRouteName,$slug) === 0) {
                  $activeClass = $active;
              }
            }
          }
          else{
            if (str_contains($currentRouteName,$submenu->slug) and strpos($currentRouteName,$submenu->slug) === 0) {
              $activeClass = $active;
            }
          }
        }
      }
    @endphp

      <li class="menu-item {{$activeClass}}">
        <a href="{{ isset($submenu->url) ? url($submenu->url) : 'javascript:void(0)' }}" class="{{ isset($submenu->submenu) ? 'menu-link menu-toggle' : 'menu-link' }}" @if (isset($submenu->target) and !empty($submenu->target)) target="_blank" @endif>
          @if (isset($submenu->icon))
          <i class="{{ $submenu->icon }}"></i>
          @endif
          <div>{{ isset($submenu->name) ? __($submenu->name) : '' }}</div>
          @isset($submenu->badge)
            <div class="badge bg-{{ $submenu->badge[0] }} rounded-pill ms-auto">{{ $submenu->badge[1] }}</div>
          @endisset
        </a>

        {{-- submenu --}}
        @if (isset($submenu->submenu))
          @include('layouts.sections.menu.submenu',['menu' => $submenu->submenu, 'activeMenu' => $activeMenu ?? null])
        @endif
      </li>
    @endforeach
  @endif
</ul>
