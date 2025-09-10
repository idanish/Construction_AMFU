@extends('../master') 
@section('title', 'Notifications')
@section('content')
<div class="container py-4">
    <h3>All Notifications</h3>
    <ul class="list-unstyled">
        @foreach($notifications as $n)
            <li class="p-3 mb-2 border rounded @if(!$n->is_read) bg-light @endif">
                <div class="d-flex justify-content-between">
                    <div>
                        <div>{{ $n->message }}</div>
                        <small class="text-muted">{{ $n->created_at->diffForHumans() }}</small>
                    </div>
                    <div class="text-end">
                        <form action="{{ route('notifications.read', $n->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button class="btn btn-sm btn-outline-primary">
                                @if(!$n->is_read) Mark Read @else Read @endif
                            </button>
                        </form>

                        <form action="{{ route('notifications.destroy', $n->id) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </li>
        @endforeach
    </ul>
</div>
@endsection
