@extends('layouts.sidebar')

@section('title', 'To-do-list web')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@php $tasks = $tasks ?? collect(); @endphp

<!-- Modal Edit -->
<div id="editModal" class="modal" style="display: none;">
    <div class="modal-content p-3" style="background: #f2f2f2; border-radius: 10px;">
        <h5>Edit Subtask</h5>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <input type="text" name="title" id="editInput" class="form-control my-2" required>
            <button type="button" onclick="closeModal()" class="btn btn-outline-secondary btn-sm">Cancel</button>
            <button type="button" class="btn btn-info btn-sm text-white" onclick="submitEdit()">Ya</button>
        </form>
    </div>
</div>
<!-- End Modal Edit -->

<!-- inputan -->
<div class="d-flex justify-content-center align-items-start mt-4">
    <form action="{{ route('tasks.store') }}" method="POST" class="d-flex">
        @csrf
        <input type="text" name="title" class="form-control me-2" placeholder="Masukkan inputan" required>
        <button type="submit" class="btn btn-primary">+</button>
    </form>
</div>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-9">
            <h6 class="mb-3">Task yang belum selesai</h6>
            @foreach($tasks->where('completed', false) as $task)
                <div class="alert d-flex justify-content-between align-items-center task-item" data-task-id="{{ $task->id }}" style="background-color: #ffd966;">
                    <form action="{{ route('tasks.update', $task->id) }}" method="POST" class="me-2" onsubmit="return handleTaskCompletion(event, {{ $task->id }})">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-outline-dark rounded-circle">○</button>
                    </form>

                    <span class="flex-grow-1">
                        @if($task->priority)
                            <i class="bi bi-star-fill text-black me-1" ></i>
                        @endif
                        {{ $task->title }}
                        @if($task->jadwal)
                            <i class="bi bi-calendar-event me-1"></i>
                            <small class="text-muted">{{ $task->jadwal }}</small>
                        @endif
                    </span>

                    <div class="d-flex">
                        <button onclick="openEditModal({{ $task->id }}, '{{ addslashes($task->title) }}')" class="btn btn-sm btn-primary me-1">
                            <i class="bi bi-pencil-fill"></i>
                        </button>
                        <form action="{{ route('tasks.destroy', $task->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger"><i class="bi bi-trash-fill"></i></button>
                        </form>
                    </div>
                </div>
            @endforeach

            <!-- selesai -->

            <h6 class="mt-4 mb-3">Completed</h6>
            @foreach($tasks->where('completed', true) as $task)
                <div class="alert d-flex justify-content-between align-items-center" style="background-color: #00c853; color: white;">
                    <span class="flex-grow-1">{{ $task->title }}</span>
                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger"><i class="bi bi-trash-fill"></i></button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Context Menu -->
<div id="contextMenu" class="context-menu">
    <div onclick="openScheduleModal()">Tambahkan Jadwal</div>
    <div onclick="markAsPriority()">Tambahkan ke Prioritas</div>
</div>

<div id="scheduleModal" class="modal" style="display: none;">
    <div class="modal-content p-3" style="background: #f2f2f2; border-radius: 10px;">
        <h5>Tambahkan Jadwal</h5>
        <form id="scheduleForm" method="POST">
            @csrf
            @method('PATCH')
            <input type="datetime-local" name="jadwal" id="scheduleInput" class="form-control my-2" required>
            <button type="button" onclick="closeScheduleModal()" class="btn btn-outline-secondary btn-sm">Cancel</button>
            <button type="button" class="btn btn-info btn-sm text-white" onclick="submitSchedule()">Simpan</button>
        </form>
    </div>
</div>

<style>
    .context-menu {
        display: none;
        position: absolute;
        z-index: 9999;
        background-color: #fff;
        border: 1px solid #ccc;
        border-radius: 5px;
        padding: 5px 10px;
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }
</style>

<script>
    let currentTaskId = null;

    function openEditModal(taskId, currentTitle) {
        document.getElementById('editInput').value = currentTitle;
        document.getElementById('editForm').action = `/tasks/${taskId}`;
        document.getElementById('editModal').style.display = 'block';
    }

    function closeModal() {
        document.getElementById('editModal').style.display = 'none';
    }

    function submitEdit() {
        const form = document.getElementById('editForm');
        const input = document.getElementById('editInput');

        if (input.value.trim() === '') {
            alert('Title tidak boleh kosong');
            return;
        }

        fetch(form.action, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                _method: 'PUT',
                title: input.value
            })
        })
        .then(response => response.json())
        .then(data => {
            Swal.fire('Sukses', 'Task berhasil diperbarui!', 'success').then(() => location.reload());
        })
        .catch(error => {
            console.error(error);
            alert('Gagal memperbarui task');
        });
    }

    function handleTaskCompletion(event, taskId) {
        event.preventDefault();

        fetch(`/tasks/${taskId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                _method: 'PATCH',
                completed: true
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Gagal menyelesaikan task.');
            }
        })
        .catch(error => {
            console.error(error);
            alert('Terjadi kesalahan.');
        });

        return false;
    }

    function openScheduleModal() {
        const modal = document.getElementById('scheduleModal');
        modal.style.display = 'block';
    }

    function closeScheduleModal() {
        const modal = document.getElementById('scheduleModal');
        modal.style.display = 'none';
    }

    function submitSchedule() {
        const input = document.getElementById('scheduleInput');

        if (!input.value) {
            alert('Jadwal tidak boleh kosong');
            return;
        }

        fetch(`/tasks/${currentTaskId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                _method: 'PATCH',
                jadwal: input.value
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire('Sukses', 'Jadwal berhasil ditambahkan!', 'success').then(() => location.reload());
            } else {
                alert('Gagal menambahkan jadwal.');
            }
        })
        .catch(error => {
            console.error(error);
            alert('Terjadi kesalahan.');
        });
    }

    function markAsPriority() {
        fetch(`/tasks/${currentTaskId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                _method: 'PATCH',
                priority: true
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire('Sukses', 'Task berhasil ditandai sebagai prioritas!', 'success').then(() => location.reload());
            } else {
                alert('Gagal menandai sebagai prioritas.');
            }
        })
        .catch(error => {
            console.error(error);
            alert('Terjadi kesalahan.');
        });
    }

    document.addEventListener('contextmenu', function (e) {
        const taskElement = e.target.closest('.task-item');
        if (taskElement) {
            e.preventDefault();
            currentTaskId = taskElement.dataset.taskId;
            const menu = document.getElementById('contextMenu');
            menu.style.top = `${e.pageY}px`;
            menu.style.left = `${e.pageX}px`;
            menu.style.display = 'block';
        } else {
            document.getElementById('contextMenu').style.display = 'none';
        }
    });

    document.addEventListener('click', function () {
        document.getElementById('contextMenu').style.display = 'none';
    });
</script>
@endsection
