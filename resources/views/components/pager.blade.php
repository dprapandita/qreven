@props(['items'])
@if($items instanceof \Illuminate\Contracts\Pagination\Paginator && $items->hasPages())
    <div class="border-t border-line px-5 py-4">{{ $items->links() }}</div>
@endif
