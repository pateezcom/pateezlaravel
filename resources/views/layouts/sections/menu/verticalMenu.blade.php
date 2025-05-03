@php
use Illuminate\Support\Facades\Route;
$configData = Helper::appClasses();
@endphp

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
  @if(!isset($navbarFull))
    <div class="app-brand demo">
      <a href="{{url('/')}}" class="app-brand-link">
        <span class="app-brand-logo demo">@include('_partials.macros',["height"=>20])</span>
        <span class="app-brand-text demo menu-text fw-bold">{{config('variables.templateName')}}</span>
      </a>
      <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
        <i class="ti menu-toggle-icon d-none d-xl-block align-middle"></i>
        <i class="ti ti-x d-block d-xl-none ti-md align-middle"></i>
      </a>
    </div>
  @endif

  <div class="menu-inner-shadow"></div>

  <ul class="menu-inner py-1">
    @if(isset($menuData[0]->menu) && is_array($menuData[0]->menu))
      @foreach ($menuData[0]->menu as $menu)
        @if (isset($menu->menuHeader))
          <li class="menu-header small">
            <span class="menu-header-text">{{ __($menu->menuHeader) }}</span>
          </li>
        @elseif(auth()->check() && (auth()->user()->hasRole('admin') || !isset($menu->permission) || auth()->user()->hasAnyPermission(explode('|', $menu->permission))))
          @php
            $activeClass = null;
            $currentRouteName = Route::currentRouteName();
            if (isset($activeMenu)) {
              if ($activeMenu === $menu->slug) {
                $activeClass = 'active';
              } elseif (isset($menu->submenu)) {
                if (is_array($menu->slug)) {
                  foreach($menu->slug as $slug) {
                    if ($activeMenu === $slug || (str_contains($currentRouteName, $slug) && strpos($currentRouteName, $slug) === 0)) {
                      $activeClass = 'active open';
                    }
                  }
                } else {
                  if ($activeMenu === $menu->slug || (str_contains($currentRouteName, $menu->slug) && strpos($currentRouteName, $menu->slug) === 0)) {
                    $activeClass = 'active open';
                  }
                }
              }
            } else {
              if ($currentRouteName === $menu->slug) {
                $activeClass = 'active';
              } elseif (isset($menu->submenu)) {
                if (is_array($menu->slug)) {
                  foreach($menu->slug as $slug) {
                    if (str_contains($currentRouteName, $slug) && strpos($currentRouteName, $slug) === 0) {
                      $activeClass = 'active open';
                    }
                  }
                } else {
                  if (str_contains($currentRouteName, $menu->slug) && strpos($currentRouteName, $menu->slug) === 0) {
                    $activeClass = 'active open';
                  }
                }
              }
            }
          @endphp

          <li class="menu-item {{$activeClass}}">
            <a href="{{ isset($menu->url) ? url($menu->url) : 'javascript:void(0);' }}" class="{{ isset($menu->submenu) ? 'menu-link menu-toggle' : 'menu-link' }}" @if (isset($menu->target) && !empty($menu->target)) target="_blank" @endif>
              @isset($menu->icon)
                <i class="{{ $menu->icon }}"></i>
              @endisset
              <div>{{ isset($menu->name) ? __($menu->name) : '' }}</div>
              @isset($menu->badge)
                <div class="badge bg-{{ $menu->badge[0] }} rounded-pill ms-auto">{{ $menu->badge[1] }}</div>
              @endisset
            </a>
            @isset($menu->submenu)
              @include('layouts.sections.menu.submenu', ['menu' => $menu->submenu, 'activeMenu' => $activeMenu ?? null])
            @endisset
          </li>
        @endif
      @endforeach
    @else
      <li class="menu-item">
        <div>Menü yüklenemedi.</div>
      </li>
    @endif
  </ul>
</aside>