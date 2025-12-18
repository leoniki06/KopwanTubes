@extends('layouts.app')

@section('title', 'Riwayat Membership - MoodBite')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 style="color: #FF6B8B;">
                <i class="fas fa-history me-2"></i>Riwayat Membership
            </h2>
            <a href="{{ route('membership.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-crown me-2"></i>Upgrade Premium
            </a>
        </div>
        
        @if($memberships->isEmpty())
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-crown fa-4x mb-3" style="color: #FFD166; opacity: 0.5;"></i>
                <h4 style="color: #555;">Belum ada riwayat membership</h4>
                <p class="text-muted">Mulailah pengalaman premium dengan upgrade membership Anda.</p>
                <a href="{{ route('membership.index') }}" class="btn btn-primary">
                    <i class="fas fa-crown me-2"></i>Lihat Paket Premium
                </a>
            </div>
        </div>
        @else
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Paket</th>
                                <th>Harga</th>
                                <th>Status</th>
                                <th>Berlaku Sampai</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($memberships as $membership)
                            <tr>
                                <td>{{ $membership->created_at->format('d M Y') }}</td>
                                <td>
                                    <span class="badge" style="background-color: #FFE6E6; color: #FF6B8B;">
                                        {{ $membership->type_label }}
                                    </span>
                                </td>
                                <td>Rp {{ number_format($membership->price, 0, ',', '.') }}</td>
                                <td>
                                    @if($membership->isActive())
                                        <span class="badge bg-success">Aktif</span>
                                    @elseif($membership->status == 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($membership->status == 'expired')
                                        <span class="badge bg-secondary">Kadaluarsa</span>
                                    @else
                                        <span class="badge bg-danger">{{ ucfirst($membership->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($membership->end_date)
                                        {{ $membership->end_date->format('d M Y') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" 
                                            onclick="showMembershipDetails({{ $membership->id }})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $memberships->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; border: 3px solid #FFE6E6;">
            <div class="modal-header" style="border-bottom: 2px solid #FFE6E6;">
                <h5 class="modal-title" style="color: #FF6B8B;">
                    <i class="fas fa-info-circle me-2"></i>Detail Membership
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="modalDetailsContent">
                <!-- Content will be loaded via JavaScript -->
            </div>
            <div class="modal-footer" style="border-top: 2px solid #FFE6E6;">
                <button type="button" class="btn" data-bs-dismiss="modal"
                        style="background: linear-gradient(135deg, #FF6B8B, #FFD166); color: white;">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function showMembershipDetails(membershipId) {
        // Fetch membership details via AJAX
        fetch(`/api/membership/${membershipId}`)
            .then(response => response.json())
            .then(data => {
                let featuresHtml = '';
                if (data.features && Array.isArray(data.features)) {
                    featuresHtml = data.features.map(feature => 
                        `<li class="mb-1"><i class="fas fa-check-circle me-2 text-success"></i>${feature}</li>`
                    ).join('');
                }
                
                const content = `
                    <div class="mb-3">
                        <h6>Informasi Membership</h6>
                        <p><strong>Order ID:</strong> ${data.order_id}</p>
                        <p><strong>Paket:</strong> ${data.type_label}</p>
                        <p><strong>Harga:</strong> Rp ${new Intl.NumberFormat('id-ID').format(data.price)}</p>
                        <p><strong>Status:</strong> 
                            <span class="badge ${data.status === 'active' ? 'bg-success' : 'bg-warning'}">
                                ${data.status === 'active' ? 'Aktif' : 'Tidak Aktif'}
                            </span>
                        </p>
                        <p><strong>Tanggal Mulai:</strong> ${data.start_date_formatted}</p>
                        <p><strong>Tanggal Berakhir:</strong> ${data.end_date_formatted}</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6>Fitur yang Didapatkan</h6>
                        <ul class="mb-0">
                            ${featuresHtml}
                        </ul>
                    </div>
                    
                    <div class="alert alert-info small">
                        <i class="fas fa-info-circle me-2"></i>
                        ${data.payment_status === 'paid' ? 'Pembayaran telah berhasil diproses.' : 'Menunggu pembayaran.'}
                    </div>
                `;
                
                document.getElementById('modalDetailsContent').innerHTML = content;
                
                const modal = new bootstrap.Modal(document.getElementById('detailsModal'));
                modal.show();
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('modalDetailsContent').innerHTML = 
                    '<p class="text-center text-muted">Gagal memuat data membership.</p>';
                
                const modal = new bootstrap.Modal(document.getElementById('detailsModal'));
                modal.show();
            });
    }
</script>
@endsection