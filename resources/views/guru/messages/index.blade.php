{{--
  This file is intentionally a redirect stub.
  The Guru\MessageController renders admin.messages.index directly with proper variables.
  This file is only reached if something incorrectly references the view name.
--}}
@php redirect(route('guru.messages.index'))->send(); @endphp
