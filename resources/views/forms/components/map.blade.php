@php
    $statePath = $getStatePath();
    $mapId = 'map-' . uniqid();
@endphp

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.css" />

<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div
        x-data="{
            state: $wire.entangle('{{ $statePath }}'),
            map: null,
            drawnItems: null,

            init() {
                this.initMap();
            },

            initMap() {
                // انتظر تحميل مكتبات Leaflet
                if (typeof L === 'undefined') {
                    setTimeout(() => this.initMap(), 100);
                    return;
                }

                this.map = L.map('{{ $mapId }}').setView([27.1809, 31.1837], 12);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap'
                }).addTo(this.map);

                this.drawnItems = new L.FeatureGroup();
                this.map.addLayer(this.drawnItems);

                // رسم البيانات الموجودة
                if (this.state && Array.isArray(this.state) && this.state.length > 0) {
                    this.state.forEach(coords => {
                        if (coords && coords.length > 0) {
                            const polygon = L.polygon(coords);
                            this.drawnItems.addLayer(polygon);
                        }
                    });
                    // تعديل العرض ليشمل كل المناطق
                    if (this.drawnItems.getLayers().length > 0) {
                        this.map.fitBounds(this.drawnItems.getBounds());
                    }
                }

                const drawControl = new L.Control.Draw({
                    draw: {
                        polygon: true,
                        rectangle: false,
                        circle: false,
                        marker: false,
                        polyline: false,
                        circlemarker: false,
                    },
                    edit: {
                        featureGroup: this.drawnItems,
                    }
                });

                this.map.addControl(drawControl);

                // حفظ المضلعات
                const savePolygons = () => {
                    const allPolygons = [];
                    this.drawnItems.eachLayer(layer => {
                        if (layer instanceof L.Polygon) {
                            allPolygons.push(layer.getLatLngs()[0].map(p => [p.lat, p.lng]));
                        }
                    });
                    this.state = allPolygons;
                };

                this.map.on(L.Draw.Event.CREATED, (e) => {
                    this.drawnItems.addLayer(e.layer);
                    savePolygons();
                });

                this.map.on(L.Draw.Event.EDITED, () => {
                    savePolygons();
                });

                this.map.on(L.Draw.Event.DELETED, () => {
                    savePolygons();
                });

                // تعديل حجم الخريطة عند تغيير الحجم
                setTimeout(() => {
                    this.map.invalidateSize();
                }, 100);
            }
        }"
        wire:ignore
    >
        <div id="{{ $mapId }}" style="height: 500px; border-radius: 8px; border: 1px solid #e5e7eb;"></div>
    </div>
</x-dynamic-component>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.js"></script>
