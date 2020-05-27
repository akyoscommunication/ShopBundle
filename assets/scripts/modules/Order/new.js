export default class OrderNew {
    constructor() {
        $(document).on('change', '#order_type_new_client', function() {
            var $form = $(this).closest('form');
            var data = {};
            data[$(this).attr('name')] = $(this).val();
            $.ajax({
                url : $form.attr('action'),
                type: $form.attr('method'),
                data : data,
                success: function(html) {
                    $form.replaceWith(
                        $(html).find('form[name=order_type_new]')
                    );
                }
            });
        });
    }
}