
// Once document is ready add the the following
$(document).ready(function () {
    $('#captchaform').submit(function (event) {
        event.preventDefault();
        grecaptcha.reset();
        grecaptcha.execute();
    });
});

// Manually submit captcha once response is received from Google
function onSubmitSendCaptcha(data) {
    document.getElementById("captchaform").submit();
}