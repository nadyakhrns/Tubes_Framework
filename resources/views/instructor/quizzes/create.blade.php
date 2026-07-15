<x-app-layout title="Buat Quiz Baru">
    <div class="container-fluid py-4">

        <div class="mb-3">
            <a href="{{ route('instructor.quizzes.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Kembali ke Daftar Quiz
            </a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-plus-circle"></i> Buat Quiz Baru</h5>
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('instructor.quizzes.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Course <span class="text-danger">*</span></label>
                        <select name="course_id" class="form-select @error('course_id') is-invalid @enderror" required>
                            <option value="">Pilih Course</option>
                            @foreach($courses as $c)
                                <option value="{{ $c->id }}" {{ old('course_id') == $c->id ? 'selected' : '' }}>
                                    {{ $c->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('course_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="form-text">Pilih course mana yang akan dihubungkan dengan quiz ini.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Judul Quiz <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title') }}" required placeholder="Contoh: Final Quiz — HTML Dasar">
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                                  rows="3" placeholder="Deskripsi singkat quiz ini...">{{ old('description') }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Passing Score (%) <span class="text-danger">*</span></label>
                            <input type="number" name="passing_score"
                                   class="form-control @error('passing_score') is-invalid @enderror"
                                   value="{{ old('passing_score', 70) }}" min="0" max="100" required>
                            @error('passing_score')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div class="form-text">Nilai minimum untuk lulus (0–100).</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Batas Waktu (menit)</label>
                            <input type="number" name="time_limit_minutes"
                                   class="form-control @error('time_limit_minutes') is-invalid @enderror"
                                   value="{{ old('time_limit_minutes') }}" min="1"
                                   placeholder="Kosongkan jika tidak ada batas waktu">
                            @error('time_limit_minutes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="alert alert-info d-flex align-items-center gap-2 mb-3">
                        <i class="bi bi-info-circle-fill fs-5"></i>
                        <div>
                            Quiz akan tersimpan sebagai <strong>Draft</strong>.
                            Setelah menambahkan soal-soal, kirim ke Admin untuk direview dan dipublish.
                        </div>
                    </div>

                    <hr>
                    <button class="btn btn-primary"><i class="bi bi-check-lg"></i> Simpan Draft</button>
                    <a href="{{ route('instructor.quizzes.index') }}" class="btn btn-outline-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
