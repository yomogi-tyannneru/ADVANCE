<style>
    .layout_card {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 300px;

    }

    .card {
        width: 500px;
        padding: 20px 12px 10px;
        border: 1px solid #e9eaea;
        border-radius: 12px;
        transition: 0.3s;
        justify-content: center;
    }
</style>
<div class="layout_card">
    <div class="card">
        {{ $slot }}
    </div>
</div>