<div class="custom-breadcrumb py-2.5 py-md-3 mb-4" style="background-color: var(--surface); border-bottom: 1px solid var(--border-color); box-shadow: var(--shadow-xs);">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small align-items-center flex-wrap" style="--bs-breadcrumb-divider: ''; font-family: 'Plus Jakarta Sans', sans-serif;">
                <li class="breadcrumb-item d-inline-flex align-items-center">
                    <a href="{{ route('home') }}" class="text-decoration-none d-inline-flex align-items-center gap-1">
                        <i class="bi bi-house-door-fill text-primary"></i>
                        <span>Beranda</span>
                    </a>
                </li>

                @if(isset($breadcrumbs) && is_array($breadcrumbs) && count($breadcrumbs) > 0)
                    @foreach($breadcrumbs as $item)
                        @if(!$loop->last && !empty($item['url']))
                            <li class="breadcrumb-item">
                                <a href="{{ $item['url'] }}" class="text-decoration-none">
                                    {{ $item['label'] }}
                                </a>
                            </li>
                        @else
                            <li class="breadcrumb-item active" aria-current="page">
                                {{ $item['label'] }}
                            </li>
                        @endif
                    @endforeach
                @else
                    @yield('breadcrumb_items')
                @endif
            </ol>
        </nav>
    </div>
</div>
