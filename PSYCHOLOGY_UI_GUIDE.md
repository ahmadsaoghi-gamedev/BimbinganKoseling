# 🧠 Psychology-Based UI Components Guide

## Overview

Sistem Bimbingan Konseling telah diperbarui dengan komponen UI yang disesuaikan dengan psikologi pengguna berdasarkan role mereka. Setiap komponen dirancang untuk memberikan pengalaman yang nyaman, aman, dan efektif.

## 🎨 Color Schemes by User Role

### Siswa (Blue Theme)

-   **Primary**: `from-blue-400 to-cyan-500`
-   **Secondary**: `from-blue-500 to-blue-600`
-   **Psychology**: Friendly, trustworthy, calming
-   **Use Case**: Membuat siswa merasa aman dan nyaman

### Guru BK (Green Theme)

-   **Primary**: `from-green-400 to-emerald-500`
-   **Secondary**: `from-green-500 to-green-600`
-   **Psychology**: Professional, growth, nurturing
-   **Use Case**: Memberikan kesan profesional dan caring

### Admin (Purple Theme)

-   **Primary**: `from-purple-400 to-indigo-500`
-   **Secondary**: `from-purple-500 to-purple-600`
-   **Psychology**: Powerful, authoritative, comprehensive
-   **Use Case**: Menunjukkan kontrol dan otoritas

### Orang Tua (Orange Theme)

-   **Primary**: `from-orange-400 to-red-500`
-   **Secondary**: `from-orange-500 to-orange-600`
-   **Psychology**: Warm, caring, protective
-   **Use Case**: Memberikan kesan hangat dan melindungi

## 📦 Available Components

### 1. User-Friendly Card (`x-user-friendly-card`)

**Props:**

-   `title`: Judul card
-   `description`: Deskripsi singkat
-   `icon`: SVG icon (optional)
-   `href`: Link tujuan
-   `userRole`: Role pengguna untuk color scheme
-   `badge`: Badge tambahan (optional)

**Usage:**

```blade
<x-user-friendly-card
    title="Curhat Rahasia"
    description="Tempat aman untuk curhat dengan Guru BK"
    :href="route('curhat_reports.index')"
    userRole="siswa"
    :icon='<svg class="w-16 h-16">...</svg>'
/>
```

### 2. Welcome Message (`x-welcome-message`)

**Props:**

-   `userRole`: Role pengguna
-   `userName`: Nama pengguna

**Features:**

-   Pesan selamat datang yang disesuaikan dengan role
-   Tips dan panduan yang relevan
-   Dapat ditutup oleh pengguna
-   Animasi smooth

**Usage:**

```blade
<x-welcome-message
    userRole="siswa"
    :userName="auth()->user()->name"
/>
```

### 3. Friendly Progress (`x-friendly-progress`)

**Props:**

-   `current`: Nilai saat ini
-   `total`: Total nilai
-   `label`: Label progress
-   `color`: Warna progress bar
-   `showPercentage`: Tampilkan persentase
-   `size`: Ukuran (sm, md, lg)

**Features:**

-   Animasi smooth
-   Pesan motivasi berdasarkan progress
-   Visual feedback yang friendly

**Usage:**

```blade
<x-friendly-progress
    :current="$completedSessions"
    :total="$totalSessions"
    label="Sesi Konseling Selesai"
    color="blue"
/>
```

### 4. Mood Indicator (`x-mood-indicator`)

**Props:**

-   `mood`: Mood saat ini (excellent, good, neutral, bad, terrible)
-   `size`: Ukuran (sm, md, lg)
-   `interactive`: Mode interaktif
-   `onMoodChange`: Callback function

**Features:**

-   Emoji-based mood selection
-   Interactive mode untuk siswa
-   Visual feedback yang friendly

**Usage:**

```blade
<x-mood-indicator
    mood="neutral"
    :interactive="true"
/>
```

### 5. Friendly Form (`x-friendly-form`)

**Props:**

-   `action`: Form action URL
-   `method`: HTTP method
-   `userRole`: Role pengguna
-   `title`: Judul form
-   `description`: Deskripsi form

**Features:**

-   Styling yang disesuaikan dengan role
-   Animasi input fields
-   Visual feedback saat focus
-   Responsive design

**Usage:**

```blade
<x-friendly-form
    :action="route('konsultasi.store')"
    method="POST"
    userRole="siswa"
    title="Buat Konsultasi Baru"
    description="Isi form di bawah untuk memulai konsultasi"
>
    <!-- Form fields here -->
</x-friendly-form>
```

### 6. Friendly Notification (`x-friendly-notification`)

**Props:**

-   `type`: Tipe notifikasi (success, error, warning, info)
-   `title`: Judul notifikasi
-   `message`: Pesan notifikasi
-   `userRole`: Role pengguna
-   `dismissible`: Dapat ditutup

**Features:**

-   Auto-dismiss setelah 5 detik
-   Progress bar visual
-   Pesan yang disesuaikan dengan role
-   Animasi smooth

**Usage:**

```blade
<x-friendly-notification
    type="success"
    title="Berhasil!"
    message="Konsultasi berhasil dibuat"
    userRole="siswa"
/>
```

## 🎯 Psychology Principles Applied

### 1. **Siswa (Students)**

-   **Safety First**: Warna biru memberikan rasa aman
-   **Encouragement**: Pesan motivasi dan dukungan
-   **Simplicity**: Interface yang mudah dipahami
-   **Privacy**: Penekanan pada kerahasiaan

### 2. **Guru BK (Counselors)**

-   **Professionalism**: Warna hijau menunjukkan profesionalisme
-   **Efficiency**: Layout yang efisien untuk kerja cepat
-   **Comprehensive**: Informasi lengkap dan terstruktur
-   **Caring**: Tone yang menunjukkan kepedulian

### 3. **Admin (Administrators)**

-   **Authority**: Warna ungu menunjukkan otoritas
-   **Control**: Interface yang memberikan kontrol penuh
-   **Analytics**: Fokus pada data dan analisis
-   **Management**: Tools untuk manajemen sistem

### 4. **Orang Tua (Parents)**

-   **Warmth**: Warna oranye memberikan kehangatan
-   **Protection**: Fokus pada perlindungan anak
-   **Information**: Akses informasi yang jelas
-   **Communication**: Saluran komunikasi yang mudah

## 🚀 Implementation Guidelines

### 1. **Consistent Usage**

-   Gunakan komponen yang sesuai dengan role pengguna
-   Jangan campur color scheme antar role
-   Pertahankan konsistensi dalam messaging

### 2. **Accessibility**

-   Semua komponen mendukung dark mode
-   Kontras warna yang memadai
-   Keyboard navigation support
-   Screen reader friendly

### 3. **Performance**

-   Komponen menggunakan CSS transitions
-   Minimal JavaScript untuk interaktivitas
-   Optimized untuk mobile devices

### 4. **Customization**

-   Color schemes dapat disesuaikan
-   Props dapat dikustomisasi
-   Styling dapat di-override jika diperlukan

## 📱 Mobile Responsiveness

Semua komponen dirancang dengan mobile-first approach:

-   Responsive grid layouts
-   Touch-friendly interactions
-   Optimized spacing untuk mobile
-   Swipe gestures support

## 🎨 Dark Mode Support

Semua komponen mendukung dark mode dengan:

-   Automatic color adaptation
-   Consistent contrast ratios
-   Smooth transitions
-   User preference respect

## 🔧 Technical Implementation

### CSS Classes

-   Utility-first approach dengan Tailwind CSS
-   Custom CSS untuk animasi dan efek khusus
-   CSS variables untuk theming

### JavaScript

-   Minimal vanilla JavaScript
-   Alpine.js untuk interaktivitas
-   No heavy frameworks

### Performance

-   Lazy loading untuk komponen besar
-   Optimized bundle size
-   Efficient re-rendering

## 📈 Future Enhancements

1. **Voice Feedback**: Audio cues untuk siswa
2. **Haptic Feedback**: Vibration untuk mobile
3. **AI-Powered Suggestions**: Rekomendasi berdasarkan behavior
4. **Accessibility Improvements**: WCAG 2.1 compliance
5. **Internationalization**: Multi-language support

---

**Note**: Komponen ini dirancang dengan mempertimbangkan aspek psikologi pengguna untuk memberikan pengalaman yang optimal dalam sistem bimbingan konseling.

