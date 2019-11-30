var btn = document.getElementById('order_by');

btn.addEventListener('change', function() {
    document.myform.submit();
}, false);

$("#order_by").val(items_order);