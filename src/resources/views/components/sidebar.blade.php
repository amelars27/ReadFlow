@php
    $menuItems = [
        ['label' => 'Dashboard', 'icon' => 'bi-speedometer2', 'route' => 'dashboard'],
        ['label' => 'Reading Goals', 'icon' => 'bi-bullseye', 'route' => 'reading-goals.index', 'active' => 'reading-goals.*'],
        ['label' => 'Reading Materials', 'icon' => 'bi-book', 'route' => 'reading-materials.index', 'active' => 'reading-materials.*'],
        ['label' => 'Reading Sessions', 'icon' => 'bi-clock-history', 'route' => 'reading-sessions.index', 'active' => 'reading-sessions.*'],
        ['label' => 'Reading Notes', 'icon' => 'bi-journal-text', 'route' => 'reading-notes.index', 'active' => 'reading-notes.*'],
        ['label' => 'Categories', 'icon' => 'bi-tags', 'route' => 'categories.index', 'active' => 'categories.*'],
        ['label' => 'Authors', 'icon' => 'bi-pencil', 'route' => 'authors.index', 'active' => 'authors.*'],
        ['label' => 'Bookmarks', 'icon' => 'bi-bookmark', 'route' => 'bookmarks.index', 'active' => 'bookmarks.*'],
    ];
@endphp

<div class="offcanvas offcanvas-start d-md-none" tabindex="-1" id="sidebarOffcanvas" style="background-color: #EAF9FF;">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title fw-bold">ReadFlow</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body p-0">
        <nav class="py-2">
            @foreach ($menuItems as $item)
                @php
                    $activePattern = $item['active'] ?? $item['route'];
                @endphp
                <a href="{{ $item['route'] !== '#' ? route($item['route']) : '#' }}"
                   class="d-flex align-items-center gap-3 px-4 py-2 text-decoration-none
                          {{ request()->routeIs($activePattern === '#' ? '__none__' : $activePattern)
                              ? 'text-primary bg-primary bg-opacity-10 fw-semibold'
                              : 'text-muted' }}">
                    <i class="{{ $item['icon'] }} fs-5"></i>
                    <span>{{ $item['label'] }}</span>
                </a>
            @endforeach
        </nav>
    </div>
</div>

<aside class="d-none d-md-flex flex-column border-end" style="width: 260px; position: sticky; top: 0; height: 100vh; background-color: #EAF9FF;">
    <div class="p-3 border-bottom">
        <a href="{{ route('dashboard') }}" class="text-decoration-none d-flex align-items-center gap-2">
            <i class="bi bi-book-half text-primary fs-4"></i>
            <h5 class="fw-bold mb-0">ReadFlow</h5>
        </a>
    </div>

    <nav class="flex-grow-1 py-2 overflow-auto">
        @foreach ($menuItems as $item)
            @php
                $activePattern = $item['active'] ?? $item['route'];
            @endphp
            <a href="{{ $item['route'] !== '#' ? route($item['route']) : '#' }}"
               class="d-flex align-items-center gap-3 px-4 py-2 text-decoration-none
                      {{ request()->routeIs($activePattern === '#' ? '__none__' : $activePattern)
                          ? 'text-primary bg-primary bg-opacity-10 fw-semibold'
                          : 'text-muted' }}"
               style="transition: all 0.15s ease;">
                <i class="{{ $item['icon'] }} fs-5"></i>
                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach
    </nav>

    <div class="border-top p-3">
        <a href="#" class="d-flex align-items-center gap-3 text-muted text-decoration-none">
            <i class="bi bi-person-circle fs-5"></i>
            <span>Profile</span>
        </a>
    </div>
</aside>
