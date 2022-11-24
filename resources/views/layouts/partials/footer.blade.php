<footer class="main-footer">
    <strong>Copyright &copy; {{ date('Y') }} <a href="{{ env('APP_URL') }}" id="footer_app_name">-</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
        <b>Version</b> {{ env('APP_VERSION') }}
    </div>
</footer>

<script>
    $.get("{{ route('view.settings') }}/app_name", function(data) {
        $("#footer_app_name").text(data);
    });
</script>
