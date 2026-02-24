@extends('layouts.app')
@section('title', 'Daftar Tiket')

@section('content')

{{-- HEADER --}}
<div class="mb-4 p-4 bg-light rounded-4 shadow-sm">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2 class="fw-bold mb-1">Ticket Dashboard</h2>
            <p class="text-muted mb-0">Manage semua support ticket perusahaan</p>
        </div>
        <a href="{{ route('tickets.create') }}" class="btn btn-dark rounded-pill px-4 shadow">
            <i class="bi bi-plus-circle"></i> New Ticket
        </a>
    </div>
</div>

{{-- STAT DASHBOARD --}}
<div class="row g-3 mb-4">

    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 bg-warning bg-gradient">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fw-semibold">Open Tickets</h6>
                    <h2 class="fw-bold">
                        {{ \App\Models\Ticket::where('status','open')->count() }}
                    </h2>
                </div>
                <i class="bi bi-folder2-open fs-1 opacity-50"></i>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 bg-info bg-gradient text-white">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fw-semibold">In Progress</h6>
                    <h2 class="fw-bold">
                        {{ \App\Models\Ticket::where('status','in_progress')->count() }}
                    </h2>
                </div>
                <i class="bi bi-arrow-repeat fs-1 opacity-50"></i>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 bg-success bg-gradient text-white">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fw-semibold">Closed</h6>
                    <h2 class="fw-bold">
                        {{ \App\Models\Ticket::where('status','closed')->count() }}
                    </h2>
                </div>
                <i class="bi bi-check-circle fs-1 opacity-50"></i>
            </div>
        </div>
    </div>

</div>

{{-- TICKET GRID --}}
<div class="row g-4">

@forelse($tickets as $ticket)
<div class="col-md-6 col-lg-4">

    <div class="card border-0 shadow-sm rounded-4 h-100">
        <div class="card-body d-flex flex-column">

            {{-- HEADER CARD --}}
            <div class="d-flex justify-content-between align-items-start mb-2">
                <h5 class="fw-semibold">
                    <a href="{{ route('tickets.show', $ticket) }}" class="text-decoration-none text-dark">
                        {{ $ticket->title }}
                    </a>
                </h5>
                <div>
                    <span class="badge rounded-pill {{ $ticket->status_badge }}">
                        {{ ucfirst(str_replace('_',' ',$ticket->status)) }}
                    </span>
                </div>
            </div>

            {{-- DESC --}}
            <p class="text-muted small flex-grow-1">
                {{ Str::limit($ticket->description, 120) }}
            </p>

            {{-- FOOTER --}}
            <div class="d-flex justify-content-between align-items-center mt-auto">

                <div class="small text-muted">
                    <i class="bi bi-person"></i> {{ $ticket->user->name ?? 'Unknown' }} <br>
                    <i class="bi bi-clock"></i> {{ $ticket->created_at->diffForHumans() }}
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('tickets.edit', $ticket) }}" class="btn btn-sm btn-outline-primary rounded-circle">
                        <i class="bi bi-pencil"></i>
                    </a>

                    <form action="{{ route('tickets.destroy', $ticket) }}" method="POST"
                          onsubmit="return confirm('Yakin ingin menghapus tiket ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger rounded-circle">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>

            </div>

        </div>
    </div>

</div>
@empty
<div class="col-12 text-center py-5">
    <i class="bi bi-inbox fs-1 text-muted"></i>
    <p class="text-muted fs-5">No tickets found</p>
    <a href="{{ route('tickets.create') }}" class="btn btn-dark rounded-pill px-4">Create Ticket</a>
</div>
@endforelse

</div>

{{-- PAGINATION --}}
@if($tickets->hasPages())
<div class="mt-4 d-flex justify-content-center">
    {{ $tickets->links() }}
</div>
@endif

@endsection
