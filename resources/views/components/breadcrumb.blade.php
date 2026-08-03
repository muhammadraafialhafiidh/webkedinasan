@props(['breadcrumbs' => []])

<div class="custom-breadcrumb py-2 py-md-3 mb-4" style="background-color: var(--light, #f8f9fa); border-bottom: 1px solid #E9ECEF;">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small align-items-center flex-wrap" style="--bs-breadcrumb-divider: '>'; font-family: 'Plus Jakarta Sans', sans-serif;">
                <li class="breadcrumb-item d-inline-flex align-items-center">
                    <a href="{{ route('home') }}" class="text-decoration-none d-inline-flex align-items-center">
                        <i class="bi bi-house-door-fill text-primary me-1 fs-6"></i>
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
                    {{ $slot }}
                @endif
            </ol>
        </nav>
    </div>
</div>
