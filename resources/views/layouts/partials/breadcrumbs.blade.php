<!-- Breadcrumbs -->
@if(isset($breadcrumbs) && count($breadcrumbs) > 0)
<nav class="flex mb-6" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        @foreach($breadcrumbs as $index => $breadcrumb)
            <li class="inline-flex items-center">
                @if($index > 0)
                    <svg class="w-6 h-6 text-[#708090]" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                @endif
                
                @if($loop->last)
                    <span class="ml-1 text-sm font-medium text-[#708090] md:ml-2">{{ $breadcrumb['name'] }}</span>
                @else
                    <a href="{{ $breadcrumb['url'] }}" class="ml-1 text-sm font-medium text-[#4682B4] hover:text-[#FF6347] md:ml-2">
                        {{ $breadcrumb['name'] }}
                    </a>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
@endif