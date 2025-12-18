@extends('layouts.app')

@section('title', 'Hasil Rekomendasi - MoodBite')

@section('content')
<div class="result-container">
    <!-- Header Section -->
    <div class="result-header mb-5">
        <div class="header-content">
            <div class="back-button mb-4">
                <a href="{{ route('recommendations') }}" class="btn-back">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>
            
            <div class="mood-indicator">
                <div class="mood-icon">
                    @php
                        $moodIcons = [
                            'happy' => 'fa-smile-beam',
                            'sad' => 'fa-sad-tear',
                            'energetic' => 'fa-bolt',
                            'stress' => 'fa-wind',
                            'romantic' => 'fa-heart',
                        ];
                    @endphp
                    <i class="fas {{ $moodIcons[$mood] ?? 'fa-smile' }} fa-3x"></i>
                </div>
                <div class="mood-text">
                    <h1>Rekomendasi untuk Mood: <span class="mood-highlight">{{ ucfirst($mood) }}</span></h1>
                    <p class="lead">Inilah makanan yang cocok untuk suasana hatimu saat ini</p>
                </div>
            </div>
        </div>
    </div>

    @if(count($recommendations) > 0)
    <!-- Recommendations Grid -->
    <div class="recommendations-grid">
        @foreach($recommendations as $food)
        <div class="food-card-wrapper">
            <div class="food-card {{ $food->is_premium ? 'premium-card' : '' }}">
                <!-- Card Header -->
                <div class="card-header">
                    <div class="food-badges">
                        @if($food->is_premium)
                        <span class="badge premium-badge">
                            <i class="fas fa-crown me-1"></i>PREMIUM
                        </span>
                        @endif
                        
                        @if($food->rating)
                        <span class="badge rating-badge">
                            <i class="fas fa-star me-1"></i>{{ number_format($food->rating, 1) }}
                        </span>
                        @endif
                        
                        <span class="badge category-badge">
                            {{ $food->category }}
                        </span>
                    </div>
                    
                    <h3 class="food-title">{{ $food->food_name }}</h3>
                    <div class="restaurant-info">
                        <i class="fas fa-store"></i>
                        <span>{{ $food->restaurant_name }}</span>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="card-body">
                    <!-- Location -->
                    <div class="info-item location-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <div>
                            <strong>Lokasi:</strong>
                            <span>{{ $food->restaurant_location }}</span>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="description">
                        <p>{{ Str::limit($food->description, 120) }}</p>
                    </div>

                    <!-- Why it matches the mood -->
                    <div class="mood-match">
                        <h4>
                            <i class="fas fa-heart"></i>
                            Kenapa cocok untuk mood {{ $mood }}?
                        </h4>
                        <p>{{ $food->reason }}</p>
                    </div>

                    <!-- Details Grid -->
                    <div class="details-grid">
                        <div class="detail-item">
                            <i class="fas fa-tag"></i>
                            <div>
                                <small>Harga</small>
                                <strong>Rp {{ number_format($food->display_price, 0, ',', '.') }}</strong>
                            </div>
                        </div>

                        @if($food->calories)
                        <div class="detail-item">
                            <i class="fas fa-fire"></i>
                            <div>
                                <small>Kalori</small>
                                <strong>{{ $food->calories }} cal</strong>
                            </div>
                        </div>
                        @endif

                        @if($isPremium && $food->preparation_time)
                        <div class="detail-item">
                            <i class="fas fa-clock"></i>
                            <div>
                                <small>Waktu</small>
                                <strong>{{ $food->preparation_time }}</strong>
                            </div>
                        </div>
                        @endif

                        @if($isPremium && $food->location_details)
                        <div class="detail-item full-width">
                            <i class="fas fa-map-marked-alt"></i>
                            <div>
                                <small>Detail Lokasi</small>
                                <span>{{ $food->location_details }}</span>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Tags -->
                    @php
                        $tagsToShow = $isPremium ? ($food->formatted_tags ?? []) : ($food->tags ?? []);
                    @endphp
                    @if(!empty($tagsToShow))
                    <div class="tags-section">
                        <div class="tags-container">
                            @foreach($tagsToShow as $tag)
                            <span class="tag">{{ $tag }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Card Footer -->
                <div class="card-footer">
                    <div class="action-buttons">
                        <button class="btn btn-directions" onclick="showDirections('{{ addslashes($food->restaurant_location) }}')">
                            <i class="fas fa-directions"></i>
                            <span>Petunjuk</span>
                        </button>
                        
                        <button class="btn {{ $food->is_premium ? 'btn-premium-detail' : 'btn-detail' }}"
                                onclick='showFoodDetails(@json($food))'>
                            <i class="fas {{ $food->is_premium ? 'fa-star' : 'fa-info-circle' }}"></i>
                            <span>{{ $food->is_premium ? 'Detail Premium' : 'Detail' }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Other Moods Section -->
    <div class="other-moods-section">
        <div class="section-header">
            <h2>
                <i class="fas fa-random"></i>
                Coba mood lainnya
            </h2>
            <p>Temukan makanan yang sesuai dengan mood lain</p>
        </div>
        
        <div class="mood-buttons">
            @php
                $otherMoods = [
                    'happy' => ['icon' => 'fa-smile', 'color' => '#FFD166', 'label' => 'Bahagia'],
                    'sad' => ['icon' => 'fa-sad-tear', 'color' => '#87CEEB', 'label' => 'Sedih'],
                    'energetic' => ['icon' => 'fa-bolt', 'color' => '#98FF98', 'label' => 'Berenergi'],
                    'stress' => ['icon' => 'fa-wind', 'color' => '#C8A2C8', 'label' => 'Stress'],
                    'romantic' => ['icon' => 'fa-heart', 'color' => '#FF9AA2', 'label' => 'Romantis'],
                ];
            @endphp

            @foreach($otherMoods as $otherMood => $style)
                @if($otherMood != $mood)
                <a href="{{ route('recommendations') }}?mood={{ $otherMood }}" 
                   class="mood-btn" 
                   style="--mood-color: {{ $style['color'] }}">
                    <i class="fas {{ $style['icon'] }}"></i>
                    <span>{{ $style['label'] }}</span>
                </a>
                @endif
            @endforeach
        </div>
    </div>
    @else
    <!-- No Recommendations -->
    <div class="no-recommendations">
        <div class="empty-state">
            <i class="fas fa-sad-tear"></i>
            <h2>Maaf, belum ada rekomendasi untuk mood "{{ $mood }}"</h2>
            <p>Coba pilih mood lain yang sesuai dengan perasaanmu.</p>
            <a href="{{ route('recommendations') }}" class="btn-primary">
                <i class="fas fa-arrow-left me-2"></i>Pilih Mood Lain
            </a>
        </div>
    </div>
    @endif
</div>

<!-- Details Modal -->
<div class="modal fade" id="foodDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title-content">
                    <div id="modalFoodIcon"></div>
                    <h5 id="modalFoodName"></h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="modalContent"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-close-modal" data-bs-dismiss="modal">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Function to show detailed food information
function showFoodDetails(foodData) {
    console.log('Food Data:', foodData); // Debug log
    
    // Set modal title
    document.getElementById('modalFoodName').textContent = foodData.food_name || 'Detail Makanan';
    
    // Set food icon based on category
    const categoryIcons = {
        'makanan berat': 'fa-utensils',
        'minuman': 'fa-glass-martini-alt',
        'cemilan': 'fa-cookie-bite',
        'penutup': 'fa-ice-cream'
    };
    const iconClass = categoryIcons[foodData.category?.toLowerCase()] || 'fa-utensils';
    document.getElementById('modalFoodIcon').innerHTML = `<i class="fas ${iconClass}"></i>`;
    
    // Build modal content
    const isPremiumUser = <?php echo $isPremium ? 'true' : 'false'; ?>;
    const isPremiumFood = foodData.is_premium;
    
    let content = `
        <div class="modal-section">
            <div class="section-title">
                <i class="fas fa-store"></i>
                <span>Informasi Restoran</span>
            </div>
            <div class="section-content">
                <p><strong>Nama:</strong> ${foodData.restaurant_name || 'Tidak tersedia'}</p>
                <p><strong>Lokasi:</strong> ${foodData.restaurant_location || 'Tidak tersedia'}</p>
                <p><strong>Rating:</strong> ${foodData.rating ? foodData.rating + '/5.0' : 'Belum ada rating'}</p>
            </div>
        </div>
        
        <div class="modal-section">
            <div class="section-title">
                <i class="fas fa-info-circle"></i>
                <span>Detail Makanan</span>
            </div>
            <div class="section-content">
                <p><strong>Kategori:</strong> ${foodData.category || 'Tidak tersedia'}</p>
                <p><strong>Harga:</strong> Rp ${foodData.display_price ? Number(foodData.display_price).toLocaleString('id-ID') : '0'}</p>
                ${foodData.calories ? `<p><strong>Kalori:</strong> ${foodData.calories} cal</p>` : ''}
                ${foodData.preparation_time ? `<p><strong>Waktu Persiapan:</strong> ${foodData.preparation_time}</p>` : ''}
            </div>
        </div>
        
        <div class="modal-section">
            <div class="section-title">
                <i class="fas fa-heart"></i>
                <span>Alasan Rekomendasi</span>
            </div>
            <div class="section-content">
                <p>${foodData.reason || 'Tidak ada alasan spesifik'}</p>
            </div>
        </div>
        
        <div class="modal-section">
            <div class="section-title">
                <i class="fas fa-align-left"></i>
                <span>Deskripsi</span>
            </div>
            <div class="section-content">
                <p>${foodData.description || 'Tidak ada deskripsi'}</p>
            </div>
        </div>
    `;
    
    // Premium Content Section
    if (isPremiumFood && isPremiumUser) {
        content += `
            <div class="modal-section premium-section">
                <div class="section-title">
                    <i class="fas fa-crown"></i>
                    <span>Fitur Premium</span>
                </div>
                <div class="section-content">
        `;
        
        // Add premium details if they exist
        if (foodData.location_details) {
            content += `<p><strong>Detail Lokasi:</strong> ${foodData.location_details}</p>`;
        }
        
        // Add more premium features here based on your database structure
        content += `
                    <div class="premium-notice">
                        <i class="fas fa-unlock"></i>
                        <span>Anda dapat melihat semua detail premium karena Anda adalah member premium</span>
                    </div>
                </div>
            </div>
        `;
    } else if (isPremiumFood && !isPremiumUser) {
        content += `
            <div class="modal-section locked-section">
                <div class="section-title">
                    <i class="fas fa-lock"></i>
                    <span>Konten Premium</span>
                </div>
                <div class="section-content">
                    <div class="locked-content">
                        <i class="fas fa-crown fa-2x"></i>
                        <h5>Detail Premium Terkunci</h5>
                        <p>Upgrade ke premium untuk melihat detail lengkap rekomendasi ini</p>
                        <a href="/premium" class="btn-upgrade">
                            <i class="fas fa-gem me-2"></i>Upgrade Sekarang
                        </a>
                    </div>
                </div>
            </div>
        `;
    }
    
    // Tags Section
    const tags = foodData.tags || foodData.formatted_tags || [];
    if (tags.length > 0) {
        content += `
            <div class="modal-section">
                <div class="section-title">
                    <i class="fas fa-tags"></i>
                    <span>Tags</span>
                </div>
                <div class="section-content">
                    <div class="tags-container">
        `;
        
        tags.forEach(tag => {
            content += `<span class="tag">${tag}</span>`;
        });
        
        content += `
                    </div>
                </div>
            </div>
        `;
    }
    
    // Set content and show modal
    document.getElementById('modalContent').innerHTML = content;
    
    const modal = new bootstrap.Modal(document.getElementById('foodDetailsModal'));
    modal.show();
}

// Function to show directions in Google Maps
function showDirections(location) {
    const encodedLocation = encodeURIComponent(location);
    const mapsUrl = `https://www.google.com/maps/search/?api=1&query=${encodedLocation}`;
    window.open(mapsUrl, '_blank');
}
</script>

<style>
/* Base Styles */
.result-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem 1rem;
}

/* Header Styles */
.result-header {
    background: linear-gradient(135deg, #fff0f5 0%, #f0f8ff 100%);
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
}

.mood-indicator {
    display: flex;
    align-items: center;
    gap: 1.5rem;
}

.mood-icon {
    background: linear-gradient(135deg, #FF6B8B, #FFD166);
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    box-shadow: 0 4px 15px rgba(255, 107, 139, 0.3);
}

.mood-highlight {
    background: linear-gradient(135deg, #FF6B8B, #FFD166);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    font-weight: 700;
}

.btn-back {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1rem;
    background: white;
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    color: #555;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-back:hover {
    background: #f8f9fa;
    border-color: #FF6B8B;
    color: #FF6B8B;
}

/* Recommendations Grid */
.recommendations-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 1.5rem;
    margin-bottom: 3rem;
}

/* Food Card Styles */
.food-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.food-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 30px rgba(255, 107, 139, 0.15);
}

.premium-card {
    border: 2px solid #FFD166;
}

.premium-card .card-header {
    background: linear-gradient(135deg, #FFF9E6, #FFE6E6);
}

/* Card Header */
.card-header {
    padding: 1.5rem 1.5rem 1rem;
    background: #f8f9fa;
}

.food-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-bottom: 0.75rem;
}

.premium-badge {
    background: linear-gradient(135deg, #FFD166, #FFB347);
    color: #333;
    font-weight: 600;
    padding: 0.25rem 0.75rem;
}

.rating-badge {
    background: linear-gradient(135deg, #FF6B8B, #FF8E9E);
    color: white;
    font-weight: 600;
    padding: 0.25rem 0.75rem;
}

.category-badge {
    background: #e9ecef;
    color: #495057;
    font-weight: 500;
    padding: 0.25rem 0.75rem;
}

.food-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 0.5rem;
}

.restaurant-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #666;
    font-size: 0.9rem;
}

.restaurant-info i {
    color: #FF6B8B;
}

/* Card Body */
.card-body {
    padding: 1rem 1.5rem;
    flex-grow: 1;
}

.info-item {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    margin-bottom: 1rem;
    padding: 0.75rem;
    background: #f8f9fa;
    border-radius: 8px;
}

.info-item i {
    color: #FF6B8B;
    margin-top: 0.25rem;
}

.description p {
    color: #666;
    line-height: 1.6;
    margin-bottom: 1rem;
}

.mood-match {
    background: #FFF0F5;
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1rem;
}

.mood-match h4 {
    color: #FF6B8B;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.mood-match p {
    color: #666;
    font-size: 0.9rem;
    margin: 0;
}

.details-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0.75rem;
    margin-bottom: 1rem;
}

.detail-item {
    background: white;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.detail-item.full-width {
    grid-column: 1 / -1;
}

.detail-item i {
    color: #FF6B8B;
}

.detail-item div {
    display: flex;
    flex-direction: column;
}

.detail-item small {
    color: #888;
    font-size: 0.75rem;
}

.detail-item strong {
    color: #333;
    font-size: 0.9rem;
}

.tags-section {
    margin-top: auto;
}

.tags-container {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.tag {
    background: #e9ecef;
    color: #495057;
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.8rem;
}

/* Card Footer */
.card-footer {
    padding: 1rem 1.5rem;
    background: #f8f9fa;
    border-top: 1px solid #e9ecef;
}

.action-buttons {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.75rem;
}

.btn {
    padding: 0.75rem;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
    text-decoration: none;
}

.btn-directions {
    background: #e9ecef;
    color: #495057;
}

.btn-directions:hover {
    background: #dee2e6;
}

.btn-detail {
    background: #FF6B8B;
    color: white;
}

.btn-detail:hover {
    background: #ff5573;
}

.btn-premium-detail {
    background: linear-gradient(135deg, #FFD166, #FFB347);
    color: #333;
}

.btn-premium-detail:hover {
    background: linear-gradient(135deg, #FFC145, #FFA033);
}

/* Other Moods Section */
.other-moods-section {
    background: linear-gradient(135deg, #FFF0F5, #F0F8FF);
    border-radius: 20px;
    padding: 2rem;
    margin-top: 2rem;
}

.section-header {
    text-align: center;
    margin-bottom: 1.5rem;
}

.section-header h2 {
    color: #FF6B8B;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.section-header p {
    color: #666;
}

.mood-buttons {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 0.75rem;
}

.mood-btn {
    padding: 0.75rem 1.5rem;
    background: var(--mood-color);
    color: #333;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: transform 0.3s ease;
}

.mood-btn:hover {
    transform: translateY(-2px);
    color: #333;
    text-decoration: none;
}

/* No Recommendations */
.no-recommendations {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 400px;
}

.empty-state {
    text-align: center;
    max-width: 400px;
}

.empty-state i {
    font-size: 4rem;
    color: #FF6B8B;
    margin-bottom: 1rem;
}

.empty-state h2 {
    color: #333;
    margin-bottom: 0.5rem;
}

.empty-state p {
    color: #666;
    margin-bottom: 1.5rem;
}

.btn-primary {
    display: inline-flex;
    align-items: center;
    padding: 0.75rem 1.5rem;
    background: #FF6B8B;
    color: white;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    transition: background 0.3s ease;
}

.btn-primary:hover {
    background: #ff5573;
    color: white;
    text-decoration: none;
}

/* Modal Styles */
.modal-content {
    border-radius: 15px;
    border: none;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
}

.modal-header {
    background: linear-gradient(135deg, #FF6B8B, #FFD166);
    color: white;
    border-radius: 15px 15px 0 0;
    padding: 1.5rem;
}

.modal-title-content {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.modal-title-content i {
    font-size: 1.25rem;
}

.modal-body {
    padding: 1.5rem;
    max-height: 60vh;
    overflow-y: auto;
}

.modal-section {
    margin-bottom: 1.5rem;
}

.modal-section:last-child {
    margin-bottom: 0;
}

.section-title {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #FF6B8B;
    font-weight: 600;
    margin-bottom: 0.5rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #f0f0f0;
}

.section-content {
    padding-left: 1.5rem;
}

.section-content p {
    margin-bottom: 0.5rem;
    color: #555;
}

.section-content strong {
    color: #333;
}

.premium-section {
    background: #FFF9E6;
    border-radius: 10px;
    padding: 1rem;
    border-left: 4px solid #FFD166;
}

.locked-section {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 1.5rem;
    text-align: center;
}

.locked-content i {
    font-size: 2rem;
    color: #FFD166;
    margin-bottom: 1rem;
}

.locked-content h5 {
    color: #333;
    margin-bottom: 0.5rem;
}

.locked-content p {
    color: #666;
    margin-bottom: 1rem;
}

.btn-upgrade {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1rem;
    background: linear-gradient(135deg, #FFD166, #FFB347);
    color: #333;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
}

.btn-upgrade:hover {
    color: #333;
    text-decoration: none;
    opacity: 0.9;
}

.modal-footer {
    border-top: 1px solid #e9ecef;
    padding: 1rem 1.5rem;
    display: flex;
    justify-content: flex-end;
}

.btn-close-modal {
    padding: 0.5rem 1.5rem;
    background: #6c757d;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
}

.btn-close-modal:hover {
    background: #5a6268;
}

/* Responsive Design */
@media (max-width: 768px) {
    .recommendations-grid {
        grid-template-columns: 1fr;
    }
    
    .mood-indicator {
        flex-direction: column;
        text-align: center;
    }
    
    .details-grid {
        grid-template-columns: 1fr;
    }
    
    .action-buttons {
        grid-template-columns: 1fr;
    }
    
    .mood-buttons {
        justify-content: center;
    }
}
</style>
@endsection