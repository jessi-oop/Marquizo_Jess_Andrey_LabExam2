
document.addEventListener('DOMContentLoaded', function () {

    // Render all data-lucide icons on the page
    lucide.createIcons();


    /* ── Password toggle ── */

    document.querySelectorAll('.eye-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var input = btn.closest('.input-wrap').querySelector('input');
            var icon  = btn.querySelector('i');
            if (!input || !icon) return;

            if (input.type === 'password') {
                input.type = 'text';
                icon.setAttribute('data-lucide', 'eye');
            } else {
                input.type = 'password';
                icon.setAttribute('data-lucide', 'eye-off');
            }

            // Re-render so Lucide picks up the new icon name
            lucide.createIcons();
        });
    });


    /* ── Live validation helpers ── */

    // Show or clear a field error
    function setError(input, msg) {
        var field = input.closest('.field');
        var span  = field && field.querySelector('.error-text');
        if (!span) return;
        span.textContent = msg;
        input.classList.toggle('is-error', msg !== '');
    }

    // Validate fullname: letters and spaces only, min 2 chars
    var fullname = document.getElementById('fullname');
    if (fullname) {
        fullname.addEventListener('input', function () {
            var v = fullname.value.trim();
            if (v === '')                          setError(fullname, '');
            else if (!/^[A-Za-z\s]+$/.test(v))    setError(fullname, 'Letters and spaces only.');
            else if (v.length < 2)                 setError(fullname, 'At least 2 characters.');
            else                                   setError(fullname, '');
        });
    }

    // Validate email: basic format
    var email = document.getElementById('email');
    if (email) {
        email.addEventListener('input', function () {
            var v = email.value.trim();
            if (v === '')                                      setError(email, '');
            else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v))  setError(email, 'Enter a valid email address.');
            else                                               setError(email, '');
        });
    }

    // Validate password strength
    var password = document.getElementById('password');
    if (password && fullname) {
        password.addEventListener('input', function () {
            var v = password.value;
            if      (v === '')              setError(password, '');
            else if (v.length < 8)          setError(password, 'At least 8 characters.');
            else if (!/[A-Z]/.test(v))      setError(password, 'Add an uppercase letter.');
            else if (!/[a-z]/.test(v))      setError(password, 'Add a lowercase letter.');
            else if (!/[0-9]/.test(v))      setError(password, 'Add a number.');
            else                            setError(password, '');

            if (confirm && confirm.value)   validateConfirm();
        });
    }

    // Validate confirm password
    var confirm = document.getElementById('confirm_password');
    function validateConfirm() {
        if (!confirm) return;
        var v = confirm.value;
        if      (v === '')                              setError(confirm, '');
        else if (password && v !== password.value)      setError(confirm, 'Passwords do not match.');
        else                                            setError(confirm, '');
    }
    if (confirm) confirm.addEventListener('input', validateConfirm);

});
