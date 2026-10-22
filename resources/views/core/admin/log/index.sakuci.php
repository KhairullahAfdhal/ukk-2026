@extends('layouts.app')

@section('content')
<div class="container">
    <h1>LOG AKTIVITAS</h1>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>User</th>
                <th>Aktivitas</th>
                <th>Waktu</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @forelse ($datap as $items)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $items->user->username ?? '-' }}</td>
                <td>{{ $items->aktifitas }}</td>
                <td>{{ $items->created_at }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center">Belum ada aktivitas tercatat.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {!! $datap->links() !!}
</div>
@endsection