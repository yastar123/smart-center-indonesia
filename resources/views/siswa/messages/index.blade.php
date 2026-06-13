{{--
  This file is intentionally a redirect stub.
  The Siswa\MessageController renders admin.messages.index directly with proper variables.
  This file is only reached if something incorrectly references the view name.
--}}
@php redirect(route('siswa.messages.index'))->send(); @endphp
