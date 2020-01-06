let order_by = document.getElementById('order_by');

order_by.addEventListener('change', function() {
    let my_form = document.getElementById('my_form');
    my_form.submit();
}, false);
