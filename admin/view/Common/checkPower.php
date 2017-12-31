<script type="text/javascript">
    $(function () {
        $('.btn').hide(0);
        var power = '';
        var powers = [];
        $('.btn').each(function (i, h) {
            if (!$(this).data('power')) {
                $(this).show(0);
                return
            }
            power = $(this).data('power');
            if ($.inArray(power, powers) < 0) {
                powers.push(power);
            }
        })
        if (powers.length == 0) {
            return;
        }
        $.post('<?= U('admin/Rbac/ajaxCheckPower') ?>', {power: powers}, function (data) {
            if (!data.status) {
                return;
            }
            var powerCheck = data.data;
            $.each(powerCheck, function (i, v) {
                if (!v) {
                    return;
                }
                $('[data-power="' + i + '"]').show(0);
            });
        }, 'json')
    })
</script>