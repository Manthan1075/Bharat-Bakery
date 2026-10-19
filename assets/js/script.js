// Quantity +/- buttons (product details and cart)
document.querySelectorAll('.qty-btn').forEach(function (btn) {
  btn.addEventListener('click', function () {
    var input = btn.closest('.qty-box').querySelector('.qty-input');
    var value = parseInt(input.value, 10) || 1;
    var next = value + parseInt(btn.dataset.step, 10);
    input.value = Math.min(50, Math.max(1, next));
  });
});

// Hide success/info messages after a few seconds
document.querySelectorAll('.auto-dismiss').forEach(function (el) {
  setTimeout(function () {
    if (window.bootstrap) { bootstrap.Alert.getOrCreateInstance(el).close(); }
  }, 4000);
});
